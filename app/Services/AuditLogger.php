<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;

class AuditLogger
{
    public function record(Model $entity, string $action, ?array $oldValues = null, ?array $newValues = null): void
    {
        $this->insert($action, class_basename($entity), $entity->getKey(), $oldValues, $newValues);
    }

    public function recordAction(string $action): void
    {
        $this->insert($action, 'system', null, null, null);
    }

    private function insert(string $action, string $entityType, mixed $entityId, ?array $oldValues, ?array $newValues): void
    {
        $user = auth()->user();

        AuditLog::create([
            'user_id' => $user?->getAuthIdentifier(),
            'actor_name' => $user?->name ?? 'Guest',
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'created_at' => now(),
        ]);
    }
}
