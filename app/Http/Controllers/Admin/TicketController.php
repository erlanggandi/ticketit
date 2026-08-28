<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\InteractsWithCsv;
use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Category;
use App\Models\Technician;
use App\Models\Ticket;
use App\Models\TicketAssignment;
use App\Services\AuditLogger;
use App\Services\SlaService;
use App\Services\TicketAssignmentService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TicketController extends Controller
{
    use InteractsWithCsv;

    /** Kolom ekspor terkini (dengan department). */
    private const EXPORT_HEADERS = [
        'ticket_number',
        'created_at',
        'requester_name',
        'department',
        'category_name',
        'priority',
        'status',
        'technician_name',
        'sla_due_at',
        'resolved_at',
        'closed_at',
        'description',
    ];

    /** Varian ekspor lama (sebelum ada department) — tetap didukung saat impor. */
    private const EXPORT_HEADERS_V1 = [
        'ticket_number',
        'created_at',
        'requester_name',
        'category_name',
        'priority',
        'status',
        'technician_name',
        'sla_due_at',
        'resolved_at',
        'closed_at',
        'description',
    ];

    public function index(Request $request): View
    {
        $tickets = $this->filteredTicketQuery($request)
            ->orderByDesc('ticket_number')
            ->paginate(15)
            ->withQueryString();

        return view('admin.tickets.index', [
            'tickets' => $tickets,
            'filters' => $this->filters($request),
            'statuses' => Ticket::STATUSES,
            'priorities' => Ticket::PRIORITIES,
            'categories' => Category::query()->orderBy('category_name')->get(),
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        ['mode' => $mode] = $this->resolvePeriod($request);

        $query = $this->filteredTicketQuery($request)->orderByDesc('ticket_number');

        $filename = 'tiket-ekspor-'.$mode.'-'.now()->format('Ymd_His').'.csv';

        return response()->streamDownload(function () use ($query): void {
            $out = fopen('php://output', 'w');

            // BOM agar Excel mengenali UTF-8.
            fwrite($out, "\xEF\xBB\xBF");

            fputcsv($out, self::EXPORT_HEADERS);

            $query->chunk(500, function ($tickets) use ($out): void {
                foreach ($tickets as $ticket) {
                    fputcsv($out, [
                        $ticket->ticket_number,
                        optional($ticket->created_at)->format('Y-m-d H:i:s'),
                        $ticket->requester_name,
                        $ticket->department,
                        $ticket->category?->category_name,
                        $ticket->priority,
                        $ticket->status,
                        $ticket->assignedTechnician?->technician_name,
                        optional($ticket->sla_due_at)->format('Y-m-d H:i:s'),
                        optional($ticket->resolved_at)->format('Y-m-d H:i:s'),
                        optional($ticket->closed_at)->format('Y-m-d H:i:s'),
                        $ticket->description,
                    ]);
                }
            });

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /** @var list<string> */
    private const LEGACY_HEADERS = [
        'TicketID', 'Tanggal', 'NamaLengkap', 'Departemen', 'Kategori',
        'Kendala', 'Priority', 'Status', 'Teknisi', 'Catatan',
    ];

    /**
     * Kunci = nama ternormalisasi (huruf kecil, titik diganti spasi).
     *
     * @var array<string, string>
     */
    private const TECHNICIAN_ALIASES = [
        'abdullah tajuddin' => 'Abdullah Tajuddin',
        'abdullah tauddin' => 'Abdullah Tajuddin',
        'abdullah tajjuddin' => 'Abdullah Tajuddin',
        'a erlangga' => 'Andi Erlangga',
        'andi erlangga' => 'Andi Erlangga',
        'mirza' => 'Mirza Zulham',
        'mirza zulham' => 'Mirza Zulham',
        'mirza zulham ahmad' => 'Mirza Zulham',
        'farhan' => 'Farhan',
        'muh farhan' => 'Farhan',
        'rahmat' => 'Rahmatullah',
        'rahmatullah' => 'Rahmatullah',
        'vendor' => 'Abdullah Tajuddin',
    ];

    /** @var array<string, string> */
    private const PRIORITY_ALIASES = [
        'low' => Ticket::PRIORITY_LOW,
        'medium' => Ticket::PRIORITY_MEDIUM,
        'high' => Ticket::PRIORITY_HIGH,
        'critical' => Ticket::PRIORITY_CRITICAL,
        'urgent' => Ticket::PRIORITY_CRITICAL,
    ];

    public function import(Request $request, AuditLogger $audit, SlaService $slaService): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:2048'],
        ], [
            'file.required' => 'Pilih file CSV terlebih dahulu.',
            'file.mimes' => 'File harus berformat CSV.',
            'file.max' => 'Ukuran file maksimal 2 MB.',
        ]);

        $handle = fopen($request->file('file')->getRealPath(), 'r');

        if ($handle === false) {
            return back()->withErrors(['file' => 'File tidak dapat dibaca.']);
        }

        // Baris pertama dibaca mentah agar BOM Excel bisa dibuang dan delimiter terdeteksi.
        $firstLine = fgets($handle);

        if ($firstLine === false) {
            fclose($handle);

            return back()->withErrors(['file' => 'File CSV kosong atau tidak dapat dibaca.']);
        }

        $firstLine = $this->stripUtf8Bom($firstLine);
        $delimiter = $this->detectDelimiter($firstLine);
        $header = str_getcsv($firstLine, $delimiter);

        $headerKeys = array_map(fn ($column) => $this->normalizeKey((string) $column), $header);

        // Peta posisi kolom berdasarkan nama header (urutan bebas, department opsional).
        $mapFor = function (array $headers) use ($headerKeys): ?array {
            if ($headerKeys !== array_map(fn ($column) => $this->normalizeKey($column), $headers)) {
                return null;
            }

            $index = [];

            foreach ($headers as $position => $name) {
                $index[$this->normalizeKey($name)] = $position;
            }

            return $index;
        };

        $fieldIndex = $mapFor(self::EXPORT_HEADERS)
            ?? $mapFor(self::EXPORT_HEADERS_V1)
            ?? $mapFor(self::LEGACY_HEADERS);

        if ($fieldIndex === null) {
            fclose($handle);

            $received = array_slice(array_map(fn ($column) => trim((string) $column), $header), 0, 10);
            $receivedLabel = $received === [] ? '(kosong)' : implode(' | ', $received);

            return back()->withErrors([
                'file' => 'Header CSV tidak dikenali. '
                    .'Kolom terbaca dari file Anda: ['.$receivedLabel.']. '
                    .'Format didukung — Ekspor aplikasi: ticket_number, created_at, requester_name, [department], category_name, priority, status, technician_name, sla_due_at, resolved_at, closed_at, description; '
                    .'atau Helpdesk lama: TicketID, Tanggal, NamaLengkap, Departemen, Kategori, Kendala, Priority, Status, Teknisi, Catatan.',
            ]);
        }

        $isLegacyFormat = isset($fieldIndex['ticketid']);
        $source = $isLegacyFormat ? 'import_helpdesk_lama' : 'import';

        $created = 0;
        /** @var list<string> $skipped */
        $skipped = [];
        /** @var array<string, true> $seen */
        $seen = [];

        while (($row = fgetcsv($handle, null, $delimiter)) !== false) {
            if (count($row) === 1 && trim((string) $row[0]) === '') {
                continue;
            }

            $data = $this->rowPayload($row, $fieldIndex, $isLegacyFormat);

            $number = $data['number'];
            $requesterName = $data['requester_name'];
            $categoryName = $data['category_name'];

            if ($number === '') {
                $skipped[] = 'baris tanpa no. tiket';

                continue;
            }

            if ($seen[$number] ?? false || Ticket::query()->where('ticket_number', $number)->exists()) {
                $skipped[] = "{$number} (duplikat)";

                continue;
            }

            if ($requesterName === '') {
                $skipped[] = "{$number} (pelapor kosong)";

                continue;
            }

            $priority = $this->resolvePriority($data['priority']);

            if ($priority === null) {
                $skipped[] = "{$number} (prioritas tidak dikenal)";

                continue;
            }

            $status = $this->resolveStatus($data['status']);

            if ($status === null) {
                $skipped[] = "{$number} (status tidak dikenal)";

                continue;
            }

            $createdAt = $this->parseFlexibleDate($data['created_at_raw']);

            if ($data['created_at_raw'] !== '' && $createdAt === null) {
                $skipped[] = "{$number} (tanggal tidak valid: ".mb_substr($data['created_at_raw'], 0, 19).')';

                continue;
            }

            // SLA dari file bila sah; selain itu dihitung dari created_at/prioritas — tidak pernah kosong.
            $slaDueAt = $this->parseFlexibleDate($data['sla_due_at_raw'])
                ?? $slaService->calculateDueDate($priority, $createdAt);

            // Tanggal resolusi/penutupan disimpan apa adanya; string rusak dikosongkan tanpa menolak baris.
            $resolvedAt = $this->parseFlexibleDate($data['resolved_at_raw']);
            $closedAt = $this->parseFlexibleDate($data['closed_at_raw']);

            if ($categoryName === '') {
                $skipped[] = "{$number} (kategori kosong)";

                continue;
            }

            $category = Category::query()->firstOrCreate(['category_name' => $categoryName]);

            $ticket = Ticket::query()->create([
                'ticket_number' => $number,
                'requester_name' => $requesterName,
                'department' => $data['department'] !== '' ? $data['department'] : null,
                'category_id' => $category->id,
                'priority' => $priority,
                'description' => $data['description'],
                'status' => $status,
                'sla_due_at' => $slaDueAt,
                'resolved_at' => $resolvedAt,
                'closed_at' => $closedAt,
            ]);

            if ($createdAt !== null) {
                $ticket->forceFill(['created_at' => $createdAt])->save();
            }

            $technicianName = $this->resolveTechnicianName($data['technician_name']);

            if ($technicianName !== null) {
                $technician = Technician::query()->firstOrCreate(['technician_name' => $technicianName]);

                TicketAssignment::query()->create([
                    'ticket_id' => $ticket->id,
                    'technician_id' => $technician->id,
                    'assigned_at' => $createdAt ?? now(),
                ]);

                $ticket->forceFill(['assigned_technician_id' => $technician->id])->save();
            }

            $audit->record($ticket, 'created', null, ['source' => $source]);

            $seen[$number] = true;
            $created++;
        }

        fclose($handle);

        $message = "Impor selesai: {$created} tiket dibuat";

        if ($skipped !== []) {
            $preview = implode('; ', array_slice($skipped, 0, 5));
            $more = count($skipped) > 5 ? ', dan '.(count($skipped) - 5).' lainnya' : '';
            $message .= ', '.count($skipped)." dilewati ({$preview}{$more})";
        }

        return redirect()
            ->route('admin.tickets.index')
            ->with('success', $message.'.');
    }

    public function show(Ticket $ticket): View
    {
        $trail = AuditLog::query()
            ->where('entity_type', 'Ticket')
            ->where('entity_id', $ticket->id)
            ->latest('created_at')
            ->limit(30)
            ->get();

        return view('admin.tickets.show', [
            'ticket' => $ticket->load(['category', 'requester', 'assignedTechnician', 'assignments.technician']),
            'trail' => $trail,
            'nextStatuses' => $ticket->nextAllowedStatuses(),
            'priorities' => Ticket::PRIORITIES,
            'categories' => Category::query()->orderBy('category_name')->get(),
            'technicians' => Technician::query()->where('is_active', true)->orderBy('technician_name')->get(),
        ]);
    }

    public function updateStatus(Request $request, Ticket $ticket, AuditLogger $audit): RedirectResponse
    {
        $allowed = $ticket->nextAllowedStatuses();

        if (empty($allowed)) {
            abort(403, 'Tiket berstatus Closed tidak dapat diubah lagi.');
        }

        $validated = $request->validate([
            'status' => ['required', 'string', 'in:'.implode(',', $allowed)],
        ], [
            'status.in' => 'Perubahan status tidak sesuai alur kerja.',
        ]);

        $old = ['status' => $ticket->status];
        $new = ['status' => $validated['status']];

        $ticket->status = $validated['status'];

        if ($validated['status'] === Ticket::STATUS_RESOLVED && ! $ticket->resolved_at) {
            $ticket->resolved_at = now();
            $new['resolved_at'] = $ticket->resolved_at->toDateTimeString();
        }

        if ($validated['status'] === Ticket::STATUS_CLOSED && ! $ticket->closed_at) {
            $ticket->closed_at = now();
            $new['closed_at'] = $ticket->closed_at->toDateTimeString();
        }

        $ticket->save();

        $audit->record($ticket, 'status_changed', $old, $new);

        return back()->with('success', 'Status tiket diperbarui menjadi '.$validated['status'].'.');
    }

    public function assign(Request $request, Ticket $ticket, AuditLogger $audit, TicketAssignmentService $assignmentService): RedirectResponse
    {
        if ($ticket->isClosed()) {
            abort(403, 'Tiket berstatus Closed tidak dapat diubah lagi.');
        }

        $validated = $request->validate([
            'technician_id' => [
                'required',
                'integer',
                Rule::exists('technicians', 'id')->where('is_active', true),
            ],
        ], [
            'technician_id.required' => 'Pilih teknisi terlebih dahulu.',
            'technician_id.exists' => 'Teknisi tidak ditemukan atau sedang nonaktif.',
        ]);

        $technician = Technician::query()->findOrFail($validated['technician_id']);

        $previous = $ticket->assignments()->active()->with('technician')->first();
        $old = ['assigned_technician_id' => $ticket->assigned_technician_id];

        $assignmentService->assign($ticket, $technician);

        $action = $previous !== null ? 'escalated' : 'assigned';
        $audit->record($ticket, $action, $old, [
            'assigned_technician_id' => $technician->id,
            'technician_name' => $technician->technician_name,
        ]);

        return back()->with(
            'success',
            ($previous !== null ? 'Tiket dieskalasi ke ' : 'Tiket ditugaskan ke ').$technician->technician_name.'.'
        );
    }

    public function updateDetails(Request $request, Ticket $ticket, AuditLogger $audit, SlaService $slaService): RedirectResponse
    {
        if ($ticket->isClosed()) {
            abort(403, 'Tiket berstatus Closed tidak dapat diubah lagi.');
        }

        $validated = $request->validate([
            'priority' => ['required', 'string', 'in:'.implode(',', Ticket::PRIORITIES)],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
        ]);

        $old = [
            'priority' => $ticket->priority,
            'category_id' => $ticket->category_id,
        ];

        $priorityChanged = $validated['priority'] !== $ticket->priority;

        $ticket->fill([
            'priority' => $validated['priority'],
            'category_id' => $validated['category_id'],
        ]);

        if ($priorityChanged) {
            $ticket->sla_due_at = $slaService->calculateDueDate($validated['priority'], $ticket->created_at);
            $ticket->sla_alert_approaching_sent_at = null;
            $ticket->sla_alert_breach_sent_at = null;
        }

        $ticket->save();

        $audit->record($ticket, 'details_updated', $old, [
            'priority' => $ticket->priority,
            'category_id' => $ticket->category_id,
            'sla_due_at' => optional($ticket->sla_due_at)->toDateTimeString(),
        ]);

        return back()->with('success', 'Detail tiket berhasil diperbarui.');
    }

    /**
     * Query tiket dengan filter teks + rentang periode aktif (dipakai index & ekspor).
     */
    private function filteredTicketQuery(Request $request): Builder
    {
        $filters = $this->filters($request);
        ['from' => $from, 'to' => $to] = $this->resolvePeriod($request);

        return Ticket::query()
            ->with(['category', 'assignedTechnician'])
            ->when($filters['q'] ?? null, function ($query, $q) {
                $term = '%'.strtolower($q).'%';

                $query->where(function ($inner) use ($term) {
                    $inner->whereRaw('lower(ticket_number) like ?', [$term])
                        ->orWhereRaw('lower(requester_name) like ?', [$term]);
                });
            })
            ->when(($filters['status'] ?? '') !== '' && ($filters['status'] ?? null), fn ($q) => $q->where('status', $filters['status']))
            ->when(($filters['priority'] ?? '') !== '' && ($filters['priority'] ?? null), fn ($q) => $q->where('priority', $filters['priority']))
            ->when($filters['category_id'] ?? null, fn ($q) => $q->where('category_id', $filters['category_id']))
            ->when($from !== null, fn ($q) => $q->whereDate('created_at', '>=', $from))
            ->when($to !== null, fn ($q) => $q->whereDate('created_at', '<=', $to));
    }

    /**
     * @return array{q: ?string, status: ?string, priority: ?string, category_id: ?string, date_from: ?string, date_to: ?string, period_mode: string, period_date: ?string, period_month: ?string, period_year: ?string}
     */
    private function filters(Request $request): array
    {
        return array_merge(
            $request->only(['q', 'status', 'priority', 'category_id', 'date_from', 'date_to']),
            [
                'period_mode' => $request->input('period_mode', 'custom'),
                'period_date' => $request->input('period_date'),
                'period_month' => $request->input('period_month'),
                'period_year' => $request->input('period_year'),
            ]
        );
    }

    /**
     * Terjemahkan mode periode menjadi rentang tanggal Y-m-d.
     *
     * @return array{from: ?string, to: ?string, mode: string}
     */
    private function resolvePeriod(Request $request): array
    {
        $mode = (string) $request->input('period_mode', 'custom');

        switch ($mode) {
            case 'day':
                $date = $request->input('period_date') ?: now()->toDateString();

                return ['from' => $date, 'to' => $date, 'mode' => 'day'];

            case 'month':
                $raw = (string) ($request->input('period_month') ?: now()->format('Y-m'));

                try {
                    $start = preg_match('/^\d{4}-\d{2}$/', $raw)
                        ? Carbon::createFromFormat('Y-m', $raw)->startOfMonth()
                        : now()->startOfMonth();
                } catch (\Throwable) {
                    $start = now()->startOfMonth();
                }

                return ['from' => $start->toDateString(), 'to' => $start->copy()->endOfMonth()->toDateString(), 'mode' => 'month'];

            case 'year':
                $year = (int) ($request->input('period_year') ?: now()->year);

                if ($year < 2000 || $year > 2100) {
                    $year = (int) now()->year;
                }

                return ['from' => "{$year}-01-01", 'to' => "{$year}-12-31", 'mode' => 'year'];
        }

        return ['from' => $request->input('date_from'), 'to' => $request->input('date_to'), 'mode' => 'custom'];
    }

    /**
     * Petakan satu baris CSV ke payload terpadu memakai posisi header yang terdeteksi.
     *
     * @param  list<mixed>  $row
     * @param  array<string, int>  $fieldIndex
     * @return array{number: string, requester_name: string, department: string, category_name: string, priority: string, status: string, technician_name: ?string, description: string, created_at_raw: string, sla_due_at_raw: string, resolved_at_raw: string, closed_at_raw: string}
     */
    private function rowPayload(array $row, array $fieldIndex, bool $isLegacyFormat): array
    {
        // Format legacy memakai nama kolom sendiri (TicketID, Tanggal, …).
        if ($isLegacyFormat) {
            return [
                'number' => trim((string) ($row[$fieldIndex['ticketid']] ?? '')),
                'requester_name' => trim((string) ($row[$fieldIndex['namalengkap']] ?? '')),
                'department' => trim((string) ($row[$fieldIndex['departemen']] ?? '')),
                'category_name' => trim((string) ($row[$fieldIndex['kategori']] ?? '')),
                'priority' => trim((string) ($row[$fieldIndex['priority']] ?? '')),
                'status' => trim((string) ($row[$fieldIndex['status']] ?? '')),
                'technician_name' => isset($row[$fieldIndex['teknisi']]) ? trim((string) $row[$fieldIndex['teknisi']]) : null,
                'description' => trim((string) ($row[$fieldIndex['kendala']] ?? '')),
                'created_at_raw' => trim((string) ($row[$fieldIndex['tanggal']] ?? '')),
                'sla_due_at_raw' => '',
                'resolved_at_raw' => '',
                'closed_at_raw' => '',
            ];
        }

        $get = function (string $field) use ($row, $fieldIndex): string {
            $key = $this->normalizeKey($field);

            return isset($fieldIndex[$key]) ? trim((string) ($row[$fieldIndex[$key]] ?? '')) : '';
        };

        return [
            'number' => $get('ticket_number'),
            'requester_name' => $get('requester_name'),
            'department' => $get('department'),
            'category_name' => $get('category_name'),
            'priority' => $get('priority'),
            'status' => $get('status'),
            'technician_name' => $get('technician_name') !== '' ? $get('technician_name') : null,
            'description' => $get('description'),
            'created_at_raw' => $get('created_at'),
            'sla_due_at_raw' => $get('sla_due_at'),
            'resolved_at_raw' => $get('resolved_at'),
            'closed_at_raw' => $get('closed_at'),
        ];
    }

    /**
     * Kembalikan prioritas kanonik; null bila tidak dikenali.
     */
    private function resolvePriority(string $raw): ?string
    {
        $key = $this->normalizeKey($raw);

        if ($key === '') {
            return Ticket::PRIORITY_MEDIUM;
        }

        return self::PRIORITY_ALIASES[$key] ?? null;
    }

    /**
     * Kembalikan status kanonik untuk impor legacy; null bila tidak dikenali.
     */
    private function resolveStatus(string $raw): ?string
    {
        $key = $this->normalizeKey($raw);

        if ($key === '') {
            return Ticket::STATUS_OPEN;
        }

        foreach (Ticket::STATUSES as $status) {
            if ($this->normalizeKey($status) === $key) {
                return $status;
            }
        }

        return null;
    }

    /**
     * Parse tanggal fleksibel: ISO (hasil ekspor) maupun dd/mm/yyyy (warisan Excel).
     * Kosong/tak cocok/tahun mustahil -> null.
     */
    private function parseFlexibleDate(string $raw): ?Carbon
    {
        $value = trim($raw);

        if ($value === '') {
            return null;
        }

        foreach (['Y-m-d H:i:s', 'Y-m-d H:i', 'd/m/Y H:i:s', 'd/m/Y G:i:s', 'd/m/Y H:i'] as $format) {
            try {
                $date = Carbon::createFromFormat($format, $value);
            } catch (\Throwable) {
                continue;
            }

            // Tolak pencocokan ambigu (mis. tahun 2 digit "28" menjadi 0028).
            if ($date !== false && $date->year >= 2000 && $date->year <= 2100) {
                return $date;
            }
        }

        return null;
    }

    /**
     * Normalisasi nama teknisi: peta alias, gabungan "&" ambil nama utama.
     * Null berarti tanpa penugasan.
     */
    private function resolveTechnicianName(?string $raw): ?string
    {
        $name = trim((string) $raw);

        if ($name === '') {
            return null;
        }

        if (str_contains($name, '&')) {
            $name = trim(explode('&', $name)[0]);
        }

        if ($name === '') {
            return null;
        }

        return self::TECHNICIAN_ALIASES[$this->normalizeKey($name)] ?? $name;
    }
}
