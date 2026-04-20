<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use App\Exports\AttendancesExport;
use App\Exports\ActivityLogsExport;
use App\Models\Division;
use App\Models\JobTitle;
use App\Models\Education;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Models\Attendance;

class ImportExportController extends Controller
{
    public function users()
    {
        return view('admin.import-export.users');
    }

    public function attendances()
    {
        return view('admin.import-export.attendances');
    }

    public function exportUsers(Request $request)
    {
        $groups = $request->input('groups', ['user']); 
        if (is_string($groups)) {
            $groups = explode(',', $groups);
        }

        $service = app(\App\Contracts\ReportingServiceInterface::class);
        $response = $service->exportUsers($groups);

        return $this->handleServiceResponse($response);
    }

    public function exportAttendances(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');
        $division = $request->input('division');
        $job_title = $request->input('job_title');
        $education = $request->input('education');

        $service = app(\App\Contracts\ReportingServiceInterface::class);
        $response = $service->exportAttendances($month, $year, $division, $job_title, $education);

        return $this->handleServiceResponse($response);
    }

    public function exportActivityLogs()
    {
        $service = app(\App\Contracts\ReportingServiceInterface::class);
        $response = $service->exportActivityLogs();

        return $this->handleServiceResponse($response);
    }

    public function exportReportPdf(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $service = app(\App\Contracts\ReportingServiceInterface::class);
        $response = $service->exportMonthlyReportPdf($month, $year);
        
        return $this->handleServiceResponse($response);
    }

    protected function handleServiceResponse($response)
    {
        if ($response === null) {
            // Community Edition Lock
            return back()->with('flash.banner', 'Advanced Reporting is an Enterprise Feature 🔒. Please Upgrade.')->with('flash.bannerStyle', 'danger');
        }
        return $response;
    }

    public function importUsers(Request $request)
    {
        abort(404);
    }

    public function importAttendances(Request $request)
    {
        abort(404);
    }
}
