<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeaveRequested extends Notification
{
    public $attendance;
    public $fromDate;
    public $toDate;
    public $totalDays;

    public function __construct($attendance, $fromDate = null, $toDate = null)
    {
        $this->attendance = $attendance;
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
        
        // Calculate total days
        if ($fromDate && $toDate) {
            $this->totalDays = $fromDate->diffInDays($toDate) + 1;
        } else {
            $this->totalDays = 1;
        }
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        // Format date range for database notification
        if ($this->fromDate && $this->toDate && $this->totalDays > 1) {
            $dateDisplay = $this->fromDate->format('d M') . ' - ' . $this->toDate->format('d M Y');
            $message = "Pengajuan {$this->attendance->status} dari {$this->attendance->user->name} ({$this->totalDays} hari)";
        } else {
            $dateDisplay = $this->attendance->date->format('Y-m-d');
            $message = "Pengajuan {$this->attendance->status} dari {$this->attendance->user->name}";
        }
        
        return [
            'type' => 'leave_request',
            'title' => 'New Leave Request',
            'user_id' => $this->attendance->user_id,
            'user_name' => $this->attendance->user->name,
            'leave_type' => $this->attendance->status,
            'date' => $dateDisplay,
            'total_days' => $this->totalDays,
            'message' => $message,
            'url' => route('admin.leaves'),
        ];
    }
}
