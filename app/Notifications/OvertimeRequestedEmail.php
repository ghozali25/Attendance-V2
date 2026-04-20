<?php

namespace App\Notifications;

use App\Models\Overtime;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OvertimeRequestedEmail extends Notification implements ShouldQueue
{
    use Queueable;

    public $overtime;

    public function __construct(Overtime $overtime)
    {
        $this->overtime = $overtime;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $userName = $this->overtime->user->name ?? 'Unknown';
        
        // Get app name and support contact from settings
        $appName = \App\Models\Setting::getValue('app.company_name', config('app.name', 'Ali'));
        $supportEmail = \App\Models\Setting::getValue('app.support_contact', config('mail.from.address'));
        
        return (new MailMessage)
            ->from(config('mail.from.address'), $appName)
            ->replyTo(
                \App\Models\Setting::getValue('mail.reply_to_address', $supportEmail),
                $appName
            )
            ->subject($appName . " - " . __('New Overtime Request') . ": $userName")
            ->view('emails.aligned-request', [
                'greeting' => __('Hello, Admin!'),
                'introLines' => [
                    __('A new overtime request has been submitted by') . ' ' . $userName
                ],
                'details' => [
                    'Staff' => $userName,
                    'Date' => $this->overtime->date->format('d M Y'),
                    'Time' => $this->overtime->start_time->format('H:i') . ' - ' . $this->overtime->end_time->format('H:i'),
                    'Duration' => $this->overtime->duration_text,
                    'Reason' => $this->overtime->reason,
                ],
                'actionText' => __('View Request'),
                'actionUrl' => route('admin.overtime'),
                'outroLines' => [
                    __('Please review this request in the dashboard.')
                ]
            ]);
    }
}
