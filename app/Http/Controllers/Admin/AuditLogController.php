<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->only(['action', 'entity_type', 'date_from', 'date_to']);

        $logs = AuditLog::query()
            ->when(($filters['action'] ?? '') !== '' && ($filters['action'] ?? null), fn ($q) => $q->where('action', $filters['action']))
            ->when(($filters['entity_type'] ?? '') !== '' && ($filters['entity_type'] ?? null), fn ($q) => $q->where('entity_type', $filters['entity_type']))
            ->when($filters['date_from'] ?? null, fn ($q, $d) => $q->whereDate('created_at', '>=', $d))
            ->when($filters['date_to'] ?? null, fn ($q, $d) => $q->whereDate('created_at', '<=', $d))
            ->latest('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.audit-logs.index', [
            'logs' => $logs,
            'filters' => $filters,
            'actions' => ['created', 'updated', 'deleted', 'status_changed', 'details_updated', 'toggled', 'login', 'logout'],
            'entityTypes' => ['Ticket', 'Category', 'Employee', 'TelegramSetting', 'system'],
        ]);
    }
}
