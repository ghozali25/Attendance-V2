<?php

namespace App\Services\Reporting;

use App\Contracts\ReportingServiceInterface;

class CommunityReportingService implements ReportingServiceInterface
{
    public function exportUsers(array $groups)
    {
        // Community: Feature Locked 🔒
        return null;
    }

    public function exportAttendances($month, $year, $division, $jobTitle, $education)
    {
        // Community: Feature Locked 🔒
        return null;
    }

    public function exportActivityLogs()
    {
        // Community: Feature Locked 🔒
        return null;
    }

    public function exportMonthlyReportPdf($month, $year)
    {
        // Community: Feature Locked 🔒
        return null;
    }
}
