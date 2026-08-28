<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TechnicianRequest;
use App\Models\Technician;
use App\Models\Ticket;
use App\Models\TicketAssignment;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TechnicianController extends Controller
{
    public function index(): View
    {
        return view('admin.technicians.index', [
            'technicians' => Technician::query()
                ->orderBy('technician_name')
                ->get(),
        ]);
    }

    public function store(TechnicianRequest $request, AuditLogger $audit): RedirectResponse
    {
        $technician = Technician::query()->create([
            'technician_name' => trim((string) $request->validated('technician_name')),
            'expertise' => $request->validated('expertise') ?: null,
            'contact' => $request->validated('contact') ?: null,
            'is_active' => true,
        ]);

        $audit->record($technician, 'created', null, ['technician_name' => $technician->technician_name]);

        return back()->with('success', 'Data teknisi berhasil ditambahkan.');
    }

    public function update(TechnicianRequest $request, Technician $technician, AuditLogger $audit): RedirectResponse
    {
        $old = $technician->only(['technician_name', 'expertise', 'contact', 'is_active']);

        $data = [
            'technician_name' => trim((string) $request->validated('technician_name')),
            'expertise' => $request->validated('expertise') ?: null,
            'contact' => $request->validated('contact') ?: null,
            'is_active' => $request->boolean('is_active'),
        ];

        $technician->update($data);
        $audit->record($technician, 'updated', $old, $data);

        return back()->with('success', 'Data teknisi berhasil diperbarui.');
    }

    public function toggle(Technician $technician, AuditLogger $audit): RedirectResponse
    {
        $old = ['is_active' => $technician->is_active];
        $technician->forceFill(['is_active' => ! $technician->is_active])->save();

        $audit->record($technician, 'toggled', $old, ['is_active' => $technician->is_active]);

        return back()->with(
            'success',
            $technician->is_active ? 'Teknisi diaktifkan.' : 'Teknisi dinonaktifkan.'
        );
    }

    public function destroy(Technician $technician, AuditLogger $audit): RedirectResponse
    {
        DB::transaction(function () use ($technician): void {
            // Lepaskan keterikatan: riwayat penugasan dihapus dan tiket aktif
            // kehilangan penanggung jawab (nama tetap tercatat di audit trail).
            TicketAssignment::query()->where('technician_id', $technician->id)->delete();
            Ticket::query()->where('assigned_technician_id', $technician->id)
                ->update(['assigned_technician_id' => null]);

            $technician->delete();
        });

        $audit->record($technician, 'deleted', ['technician_name' => $technician->technician_name], null);

        return back()->with('success', 'Data teknisi berhasil dihapus. Tiket terkait kehilangan penanggung jawab.');
    }
}
