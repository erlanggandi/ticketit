<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\Ticket;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $openCount = Ticket::query()->where('status', Ticket::STATUS_OPEN)->count();
        $inProgressCount = Ticket::query()->where('status', Ticket::STATUS_IN_PROGRESS)->count();
        $overdueCount = Ticket::query()->overdue()->count();
        $resolvedThisWeek = Ticket::query()
            ->where('status', Ticket::STATUS_RESOLVED)
            ->where('resolved_at', '>=', now()->startOfWeek())
            ->count();

        $recentTickets = Ticket::query()
            ->with('category')
            ->whereIn('status', [Ticket::STATUS_OPEN, Ticket::STATUS_IN_PROGRESS, Ticket::STATUS_RESOLVED])
            ->latest('created_at')
            ->limit(8)
            ->get();

        $unreadCount = AdminNotification::query()->unread()->count();
        $notifications = AdminNotification::query()
            ->latest()
            ->limit(6)
            ->get();

        return view('admin.dashboard', [
            'openCount' => $openCount,
            'inProgressCount' => $inProgressCount,
            'overdueCount' => $overdueCount,
            'resolvedThisWeek' => $resolvedThisWeek,
            'recentTickets' => $recentTickets,
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ]);
    }
}
