<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTicketRequest;
use App\Models\Category;
use App\Models\Employee;
use App\Models\Ticket;
use App\Services\AdminNotificationService;
use App\Services\AuditLogger;
use App\Services\SlaService;
use App\Services\TicketNumberGenerator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function create(): View
    {
        return view('public.tickets.create', [
            'categories' => Category::query()->where('is_active', true)->orderBy('category_name')->get(),
        ]);
    }

    public function store(
        StoreTicketRequest $request,
        TicketNumberGenerator $numberGenerator,
        SlaService $slaService,
        AdminNotificationService $notifier,
        AuditLogger $audit,
    ): RedirectResponse {
        $validated = $request->validated();

        $department = null;
        if (! empty($validated['employee_id'])) {
            $department = Employee::query()->find($validated['employee_id'])?->department;
        }

        $ticket = Ticket::query()->create([
            'ticket_number' => $numberGenerator->generate(),
            'requester_name' => $validated['requester_name'],
            'requester_id' => $validated['employee_id'] ?? null,
            'department' => $department,
            'category_id' => $validated['category_id'],
            'priority' => $validated['priority'],
            'description' => $validated['description'],
            'status' => Ticket::STATUS_OPEN,
            'sla_due_at' => $slaService->calculateDueDate($validated['priority']),
        ]);

        $audit->record($ticket, 'created', null, ['ticket_number' => $ticket->ticket_number]);
        $notifier->notifyNewTicket($ticket);

        return redirect()
            ->route('tickets.success', ['ticket' => $ticket->id])
            ->with('just_created_ticket_number', $ticket->ticket_number);
    }

    public function success(Request $request): View
    {
        abort_unless($request->session()->has('just_created_ticket_number'), 404);

        $ticketNumber = (string) $request->session()->get('just_created_ticket_number');
        $ticket = Ticket::query()->where('ticket_number', $ticketNumber)->firstOrFail();

        return view('public.tickets.success', [
            'ticket' => $ticket,
        ]);
    }

    public function check(Request $request): View
    {
        $number = trim((string) $request->query('number', ''));
        $ticket = null;

        if ($number !== '') {
            $normalized = strtoupper($number);
            $ticket = Ticket::query()
                ->where('ticket_number', $normalized)
                ->first();

            if ($ticket === null) {
                // Toleransi input tanpa prefix/format: coba cari yang mengandung angka tahun+urut
                $digits = preg_replace('/[^0-9]/', '', $normalized);

                if ($digits !== null && strlen($digits) >= 4) {
                    // Pencocokan digit dilakukan di PHP agar portabel lintas driver database
                    $ticket = Ticket::query()
                        ->where('ticket_number', 'like', "%{$digits}%")
                        ->get()
                        ->first(fn (Ticket $candidate): bool => preg_replace('/[^0-9]/', '', $candidate->ticket_number) === $digits);
                }
            }
        }

        return view('public.tickets.check', [
            'number' => $number,
            'ticket' => $ticket,
            'searched' => $number !== '',
        ]);
    }
}
