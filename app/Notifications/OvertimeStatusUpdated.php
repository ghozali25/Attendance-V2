<?php

namespace App\Notifications;

use App\Models\Overtime;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OvertimeStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    public Overtime $overtime;

    public function __construct(Overtime $overtime)
    {
        $this->overtime = $overtime;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $status = ucfirst($this->overtime->status);
        $emoji = $this->overtime->status === 'approved' ? '✅' : '❌';

        $mail = (new MailMessage)
            ->from(config('mail.from.address'), \App\Models\Setting::getValue('mail.from_name', config('app.name')))
            ->replyTo(
                \App\Models\Setting::getValue('mail.reply_to_address', config('mail.from.address')),
                \App\Models\Setting::getValue('mail.reply_to_name', config('app.name'))
            )
            ->subject("{$emoji} Overtime Request {$status}")
            ->greeting("Hello, {$notifiable->name}!")
            ->line("Your overtime request for **{$this->overtime->date->format('d M Y')}** has been **{$status}**.")
            ->line("Duration: {$this->overtime->duration_text}");

        if ($this->overtime->status === 'rejected' && $this->overtime->rejection_reason) {
            $mail->line("Reason: " . $this->overtime->rejection_reason);
        }

        return $mail
            ->action('View Overtime', route('overtime'))
            ->line('Thank you for using our application!');
    }

    public function toArray(object $notifiable): array
    {
        $status = ucfirst($this->overtime->status);
        $emoji = $this->overtime->status === 'approved' ? '✅' : '❌';
        
        return [
            'type' => 'overtime_status',
            'title' => 'Overtime Request ' . $status,
            'overtime_id' => $this->overtime->id,
            'status' => $this->overtime->status,
            'date' => $this->overtime->date->format('Y-m-d'),
            'duration' => $this->overtime->duration_text,

            'message' => __('Your overtime for :date has been :status', [
                'date' => $this->overtime->date->format('d M'),
                'status' => $status
            ]) . " " . $emoji,
            'url' => route('overtime'),
        ];
    }
}
