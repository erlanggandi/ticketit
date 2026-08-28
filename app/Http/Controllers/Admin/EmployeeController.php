<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\InteractsWithCsv;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeRequest;
use App\Models\Employee;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EmployeeController extends Controller
{
    use InteractsWithCsv;

    /** @var list<string> */
    private const CSV_HEADERS = ['employee_name', 'department', 'contact', 'is_active'];

    public function index(): View
    {
        return view('admin.employees.index', [
            'employees' => Employee::query()
                ->orderBy('employee_name')
                ->paginate(15)
                ->withQueryString(),
        ]);
    }

    public function export(): StreamedResponse
    {
        $filename = 'karyawan-ekspor-'.now()->format('Ymd_His').'.csv';

        $query = Employee::query()->orderBy('employee_name');

        return response()->streamDownload(function () use ($query): void {
            $out = fopen('php://output', 'w');

            // BOM agar Excel mengenali UTF-8.
            fwrite($out, "\xEF\xBB\xBF");

            fputcsv($out, self::CSV_HEADERS);

            $query->chunk(500, function ($employees) use ($out): void {
                foreach ($employees as $employee) {
                    fputcsv($out, [
                        $employee->employee_name,
                        $employee->department,
                        $employee->contact,
                        $employee->is_active ? 'Aktif' : 'Nonaktif',
                    ]);
                }
            });

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function import(Request $request, AuditLogger $audit): RedirectResponse
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

        $firstLine = fgets($handle);

        if ($firstLine === false) {
            fclose($handle);

            return back()->withErrors(['file' => 'File CSV kosong atau tidak dapat dibaca.']);
        }

        $firstLine = $this->stripUtf8Bom($firstLine);
        $delimiter = $this->detectDelimiter($firstLine);
        $header = str_getcsv($firstLine, $delimiter);

        if (array_map(fn ($column) => $this->normalizeKey((string) $column), $header)
            !== array_map(fn ($column) => $this->normalizeKey($column), self::CSV_HEADERS)) {
            fclose($handle);

            $received = array_slice(array_map(fn ($column) => trim((string) $column), $header), 0, 10);
            $receivedLabel = $received === [] ? '(kosong)' : implode(' | ', $received);

            return back()->withErrors([
                'file' => 'Header CSV tidak dikenali. '
                    .'Kolom terbaca dari file Anda: ['.$receivedLabel.']. '
                    .'Format yang didukung: employee_name, department, contact, is_active.',
            ]);
        }

        $created = 0;
        $updated = 0;
        /** @var list<string> $skipped */
        $skipped = [];

        while (($row = fgetcsv($handle, null, $delimiter)) !== false) {
            if (count($row) === 1 && trim((string) $row[0]) === '') {
                continue;
            }

            $name = trim((string) ($row[0] ?? ''));
            $department = trim((string) ($row[1] ?? ''));
            $contact = trim((string) ($row[2] ?? ''));
            $activeRaw = trim((string) ($row[3] ?? ''));

            if ($name === '') {
                $skipped[] = '(tanpa nama)';

                continue;
            }

            $isActive = $this->resolveActiveFlag($activeRaw);

            if ($isActive === null && $activeRaw !== '') {
                $skipped[] = "{$name} (status tidak valid)";

                continue;
            }

            // Upsert per nama (tanpa membedakan huruf besar/kecil).
            $employee = Employee::query()
                ->whereRaw('lower(employee_name) = ?', [mb_strtolower($name)])
                ->first();

            if ($employee !== null) {
                $old = $employee->only(['department', 'contact', 'is_active']);

                $data = [
                    'department' => $department !== '' ? $department : null,
                    'contact' => $contact !== '' ? $contact : null,
                    'is_active' => $isActive ?? $employee->is_active,
                ];

                $employee->update($data);
                $audit->record($employee, 'updated', $old, ['source' => 'import'] + $data);
                $updated++;

                continue;
            }

            $employee = Employee::query()->create([
                'employee_name' => $name,
                'department' => $department !== '' ? $department : null,
                'contact' => $contact !== '' ? $contact : null,
                'is_active' => $isActive ?? true,
            ]);

            $audit->record($employee, 'created', null, ['source' => 'import']);
            $created++;
        }

        fclose($handle);

        $message = "Impor selesai: {$created} karyawan dibuat, {$updated} diperbarui";

        if ($skipped !== []) {
            $preview = implode('; ', array_slice($skipped, 0, 5));
            $more = count($skipped) > 5 ? ', dan '.(count($skipped) - 5).' lainnya' : '';
            $message .= ', '.count($skipped)." dilewati ({$preview}{$more})";
        }

        return redirect()
            ->route('admin.employees.index')
            ->with('success', $message.'.');
    }

    /**
     * Terjemahkan kolom status; null berarti tidak disebutkan di file.
     */
    private function resolveActiveFlag(string $raw): ?bool
    {
        $key = mb_strtolower($raw);

        if ($key === '') {
            return null;
        }

        if (in_array($key, ['aktif', '1', 'true', 'ya', 'yes'], true)) {
            return true;
        }

        if (in_array($key, ['nonaktif', '0', 'false', 'no', 'tidak'], true)) {
            return false;
        }

        return null;
    }

    public function store(EmployeeRequest $request, AuditLogger $audit): RedirectResponse
    {
        $employee = Employee::query()->create([
            'employee_name' => trim((string) $request->validated('employee_name')),
            'department' => $request->validated('department') ?: null,
            'contact' => $request->validated('contact') ?: null,
            'is_active' => true,
        ]);

        $audit->record($employee, 'created', null, ['employee_name' => $employee->employee_name]);

        return back()->with('success', 'Data karyawan berhasil ditambahkan.');
    }

    public function update(EmployeeRequest $request, Employee $employee, AuditLogger $audit): RedirectResponse
    {
        $old = $employee->only(['employee_name', 'department', 'contact', 'is_active']);

        $data = [
            'employee_name' => trim((string) $request->validated('employee_name')),
            'department' => $request->validated('department') ?: null,
            'contact' => $request->validated('contact') ?: null,
            'is_active' => $request->boolean('is_active'),
        ];

        $employee->update($data);
        $audit->record($employee, 'updated', $old, $data);

        return back()->with('success', 'Data karyawan berhasil diperbarui.');
    }

    public function toggle(Employee $employee, AuditLogger $audit): RedirectResponse
    {
        $old = ['is_active' => $employee->is_active];
        $employee->forceFill(['is_active' => ! $employee->is_active])->save();

        $audit->record($employee, 'toggled', $old, ['is_active' => $employee->is_active]);

        return back()->with(
            'success',
            $employee->is_active ? 'Karyawan diaktifkan.' : 'Karyawan dinonaktifkan.'
        );
    }

    public function destroy(Employee $employee, AuditLogger $audit): RedirectResponse
    {
        if ($employee->tickets()->exists()) {
            return back()->withErrors([
                'delete' => 'Karyawan sudah memiliki tiket terkait. Nonaktifkan saja datanya.',
            ]);
        }

        $audit->record($employee, 'deleted', ['employee_name' => $employee->employee_name], null);
        $employee->delete();

        return back()->with('success', 'Data karyawan berhasil dihapus.');
    }
}
