<?php

namespace App\Livewire;

use App\Models\Attendance;
use App\Models\Overtime;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class HomeAttendanceStatus extends Component
{
    public $hasCheckedIn = false;
    public $hasCheckedOut = false;
    public $attendance = null;

    public $approvedAbsence = null;
    public $requiresFaceEnrollment = false;
    public $overtime = null;

    public function mount()
    {
        $this->checkAttendanceStatus();
    }

    public function checkAttendanceStatus()
    {
        $user = Auth::user();
        $today = now()->format('Y-m-d');

        // Check for mandatory face enrollment (Open Core Logic)
        $service = app(\App\Contracts\AttendanceServiceInterface::class);
        $requirePhoto = $service->shouldEnforceFaceEnrollment();
        
        if ($requirePhoto && !$user->hasFaceRegistered()) {
            $this->requiresFaceEnrollment = true;
        }
        
        $this->attendance = Attendance::with(['shift', 'barcode'])
            ->where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        if ($this->attendance) {
            $this->hasCheckedIn = !is_null($this->attendance->time_in);
            $this->hasCheckedOut = !is_null($this->attendance->time_out);

            // Check for approved absence
            if (in_array($this->attendance->status, ['sick', 'excused', 'permission', 'leave']) &&
                $this->attendance->approval_status === Attendance::STATUS_APPROVED
            ) {
                $this->approvedAbsence = $this->attendance;
            }
        }

        // Check for Overtime Request
        $this->overtime = Overtime::where('user_id', $user->id)
            ->where('date', $today)
            ->first();
    }

    public function render()
    {
        return view('livewire.home-attendance-status');
    }
}
