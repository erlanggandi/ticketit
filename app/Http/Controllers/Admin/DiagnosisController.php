<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Services\TicketDiagnosisService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DiagnosisController extends Controller
{
    public function index(Request $request, TicketDiagnosisService $diagnosisService): View
    {
        $openTickets = Ticket::query()
            ->open()
            ->with('category')
            ->orderBy('ticket_number')
            ->get();

        $ticket = $this->resolveTicket(
            (string) ($request->input('ticket_id') ?? $request->input('ticket') ?? $request->input('ticket_number') ?? $request->input('id') ?? '')
        );

        $diagnosis = $ticket === null
            ? null
            : $diagnosisService->analyze($ticket);

        return view('admin.diagnosis.index', [
            'openTickets' => $openTickets,
            'ticket' => $ticket,
            'diagnosis' => $diagnosis,
        ]);
    }

    public function show(Ticket $ticket, TicketDiagnosisService $diagnosisService): View
    {
        $diagnosis = $diagnosisService->analyze($ticket);

        return view('admin.diagnosis.index', [
            'openTickets' => Ticket::query()->open()->with('category')->orderBy('ticket_number')->get(),
            'ticket' => $ticket,
            'diagnosis' => $diagnosis,
        ]);
    }

    public function analyze(Request $request, TicketDiagnosisService $diagnosisService): View
    {
        return $this->index($request, $diagnosisService);
    }

    private function resolveTicket(?string $value): ?Ticket
    {
        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        if (ctype_digit($value)) {
            return Ticket::query()->find((int) $value);
        }

        return Ticket::query()->where('ticket_number', $value)->first();
    }
}
