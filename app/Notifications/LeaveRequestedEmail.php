<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeaveRequestedEmail extends Notification implements ShouldQueue
{
    use Queueable;

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
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $userName = $this->attendance->user->name ?? 'Unknown';
        $leaveType = $this->attendance->status === 'sick' ? 'Sakit' : 'Izin';
        
        // Get app name and support contact from settings
        $appName = \App\Models\Setting::getValue('app.company_name', config('app.name', 'Ali'));
        $supportEmail = \App\Models\Setting::getValue('app.support_contact', config('mail.from.address'));
        
        // Format date range
        if ($this->fromDate && $this->toDate && $this->totalDays > 1) {
            $dateDisplay = $this->fromDate->format('d M Y') . ' s/d ' . $this->toDate->format('d M Y');
            $daysInfo = "({$this->totalDays} hari)";
        } else {
            $dateDisplay = $this->attendance->date?->format('d M Y') ?? 'Unknown';
            $daysInfo = "(1 hari)";
        }
        
        return (new MailMessage)
            ->from(config('mail.from.address'), $appName)
            ->replyTo(
                \App\Models\Setting::getValue('mail.reply_to_address', $supportEmail),
                $appName
            )
            ->subject($appName . " - " . __('New Leave Request') . ": $userName")
            ->view('emails.aligned-request', [
                'greeting' => __('Hello, Admin!'),
                'introLines' => [
                    __('There is a new leave request that requires your attention.')
                ],
                'details' => [
                    'Employee' => $userName,
                    'Type' => $leaveType,
                    'Date' => $dateDisplay . ' ' . $daysInfo,
                    'Reason' => $this->attendance->note ?? '-',
                ],
                'actionText' => __('View Request'),
                'actionUrl' => route('admin.leaves'),
                'outroLines' => [
                    __('Please login to approve or reject this request.')
                ]
            ]);
    }
}
