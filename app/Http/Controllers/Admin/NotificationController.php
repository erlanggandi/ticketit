<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    public function poll(): JsonResponse
    {
        $notifications = AdminNotification::query()
            ->latest()
            ->limit(6)
            ->get();

        return response()->json([
            'unread_count' => AdminNotification::query()->unread()->count(),
            'items' => $notifications->map(fn ($n) => [
                'id' => $n->id,
                'type' => $n->type,
                'title' => $n->title,
                'body' => $n->body,
                'url' => data_get($n->data, 'url'),
                'read_at' => optional($n->read_at)?->toISOString(),
                'time_ago' => $n->timeAgo(),
                'is_unread' => $n->read_at === null,
            ]),
        ]);
    }

    public function markAllRead(): JsonResponse
    {
        AdminNotification::query()->unread()->update(['read_at' => now()]);

        return response()->json(['ok' => true]);
    }
}
