<?php

namespace App\Services\Audit;

use App\Contracts\AuditServiceInterface;

class CommunityAuditService implements AuditServiceInterface
{
    public function record(string $action, ?string $description = null)
    {
        // Community Edition: Audit Trail Locked 🔒
        // No logs are recorded in the database.
        return null;
    }
}
