<?php

namespace App\Livewire\Admin;

use App\Livewire\Traits\AttendanceDetailTrait;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class DashboardComponent extends Component
{
    use AttendanceDetailTrait;

    public $showStatModal = false;
    public $selectedStatType = '';
    public $detailList = [];

    // Pending Counts
    public $pendingLeavesCount = 0;
    public $pendingReimbursementsCount = 0;
    public $pendingOvertimesCount = 0;
    public $pendingKasbonCount = 0;

    // Overview Counts
    public $missingFaceDataCount = 0;
    public $activeHolidaysCount = 0;

    // Filter Properties
    public $search = '';
    public $chartFilter = 'week'; // 'week' | 'month'

    public function showStatDetail($type)
    {
        $this->selectedStatType = $type;
        $this->showStatModal = true;
        $today = date('Y-m-d');

        if ($type === 'absent') {
            // Users who have NO attendance record for today (and are users, not admins)
            $this->detailList = User::where('group', 'user')
                ->whereDoesntHave('attendances', fn($q) => $q->where('date', $today))
                ->get();
        } else {
            $query = Attendance::managedBy(auth()->user())->with(['user', 'shift'])->where('date', $today);

            if ($type === 'early_checkout') {
                $this->detailList = $query->get()->filter(function ($attendance) {
                    if (!$attendance->time_out || !$attendance->shift) return false;
                    return $attendance->time_out->format('H:i:s') < $attendance->shift->end_time;
                });
            } else {
                // present, late, excused, sick
                $this->detailList = $query->where('status', $type)->get();
            }
        }
    }

    public function closeStatModal()
    {
        $this->showStatModal = false;
        $this->detailList = [];
    }



    public function updatedChartFilter()
    {
        $this->dispatch('chart-updated', $this->calculateChartData());
    }

    private function calculateChartData()
    {
        try {
            $chartLabels = [];
            $chartPresent = [];
            $chartLate = [];
            $chartAbsent = [];

            if ($this->chartFilter === 'month') {
                // Last 30 Days
                $startDate = now()->subDays(29);
                $endDate = now();
                $period = \Carbon\CarbonPeriod::create($startDate, $endDate);

                // Optimize: Fetch strict range
                $periodAttendances = collect();
                try {
                    $periodAttendances = Attendance::managedBy(auth()->user())
                        ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                        // Only approved leaves OR present/late statuses (which don't need approval usually, but if they do, add here)
                        // Assuming 'present'/'late' are auto-approved or don't need it. 'sick'/'excused' need approval.
                        ->get();
                } catch (\Exception $e) {
                    $periodAttendances = collect();
                }

                foreach ($period as $date) {
                    $chartLabels[] = $date->format('d M');
                    $dayAttendances = $periodAttendances->where('date', '>=', $date->startOfDay())->where('date', '<=', $date->endOfDay());
                    $chartPresent[] = $dayAttendances->where('status', 'present')->count();
                    $chartLate[] = $dayAttendances->where('status', 'late')->count();
                    $chartAbsent[] = $dayAttendances->whereIn('status', ['sick', 'excused'])
                        ->where('approval_status', 'approved') // Only approved
                        ->count();
                }
            } else {
                // Default: Last 7 Days (Week)
                for ($i = 6; $i >= 0; $i--) {
                    $date = now()->subDays($i);
                    $chartLabels[] = $date->format('d M');

                    $startDate = now()->subDays(6);
                    $endDate = now();
                    $weeklyAttendances = collect();
                    try {
                        $weeklyAttendances = Attendance::managedBy(auth()->user())
                            ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])->get();
                    } catch (\Exception $e) {
                        $weeklyAttendances = collect();
                    }

                    $dayAttendances = $weeklyAttendances->where('date', '>=', $date->startOfDay())->where('date', '<=', $date->endOfDay());

                    $chartPresent[] = $dayAttendances->where('status', 'present')->count();
                    $chartLate[] = $dayAttendances->where('status', 'late')->count();
                    $chartAbsent[] = $dayAttendances->whereIn('status', ['sick', 'excused'])
                        ->where('approval_status', 'approved') // Only approved
                        ->count();
                }
            }

            return [
                'labels' => $chartLabels,
                'present' => $chartPresent,
                'late' => $chartLate,
                'other' => $chartAbsent
            ];
        } catch (\Exception $e) {
            \Log::error('calculateChartData error: ' . $e->getMessage());
            return [
                'labels' => [],
                'present' => [],
                'late' => [],
                'other' => []
            ];
        }
    }

    public function render()
    {
        try {
            // Minimal render for debugging
            return view('livewire.admin.dashboard', [
                'employees' => collect(),
                'employeesCount' => 0,
                'presentCount' => 0,
                'lateCount' => 0,
                'earlyCheckoutCount' => 0,
                'excusedCount' => 0,
                'sickCount' => 0,
                'absentCount' => 0,
                'recentLogs' => collect(),
                'chartData' => ['labels' => [], 'present' => [], 'late' => [], 'other' => []],
                'overdueUsers' => collect(),
                'calendarLeaves' => collect(),
                'pendingOvertimesCount' => 0,
                'pendingKasbonCount' => 0,
                'missingFaceDataCount' => 0,
                'activeHolidaysCount' => 0,
            ]);
        } catch (\Exception $e) {
            \Log::error('Dashboard render error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
            ]);
            throw $e;
        }
    }

    public function notifyUser($attendanceId)
    {
        $attendance = Attendance::find($attendanceId);
        if ($attendance && $attendance->user && $attendance->user->email) {
            \Illuminate\Support\Facades\Mail::to($attendance->user->email)->send(new \App\Mail\CheckoutReminderMail($attendance->user));

            // Log it
            \App\Models\ActivityLog::record('Notification Sent', 'Sent checkout reminder to ' . $attendance->user->name);
        }
    }

    private function formatLeaveGroup($leaves)
    {
        $first = $leaves[0];
        $last = end($leaves);
        $count = count($leaves);

        $dateDisplay = $first->date->format('d M');
        if ($count > 1) {
            if ($first->date->format('M') == $last->date->format('M')) {
                $dateDisplay .= ' - ' . $last->date->format('d M Y');
            } else {
                $dateDisplay .= ' - ' . $last->date->format('d M Y');
            }
            $dateDisplay .= ' (' . $count . ' days)';
        } else {
            $dateDisplay = $first->date->format('d M Y');
        }

        return [
            'title' => $first->user->name,
            'date_display' => $dateDisplay,
            'start_date' => $first->date, // Raw date for parsing
            'status' => $first->status
        ];
    }
}
