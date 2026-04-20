<?php

namespace App\Contracts;

interface AuditServiceInterface
{
    /**
     * Record an activity log.
     * 
     * @param string $action
     * @param string|null $description
     * @return mixed
     */
    public function record(string $action, ?string $description = null);
}
