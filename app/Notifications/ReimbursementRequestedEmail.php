<?php

namespace App\Notifications;

use App\Models\Reimbursement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReimbursementRequestedEmail extends Notification implements ShouldQueue
{
    use Queueable;

    public $reimbursement;

    public function __construct(Reimbursement $reimbursement)
    {
        $this->reimbursement = $reimbursement;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $userName = $this->reimbursement->user->name ?? 'Unknown';
        $amount = number_format($this->reimbursement->amount ?? 0, 0, ',', '.');
        
        // Get app name from settings
        $appName = \App\Models\Setting::getValue('app.company_name', config('app.name', 'Ali'));
        $supportEmail = \App\Models\Setting::getValue('app.support_contact', config('mail.from.address'));
        
        // Safe date parsing
        $date = $this->reimbursement->date;
        $dateFormatted = $date ? \Carbon\Carbon::parse($date)->format('d M Y') : '-';

        $details = [
            'Staff' => $userName,
            'Type' => $this->reimbursement->type,
            'Amount' => 'Rp ' . $amount,
            'Description' => $this->reimbursement->description ?? '-',
            'Date' => $dateFormatted,
        ];
        
        return (new MailMessage)
            ->from(config('mail.from.address'), $appName)
            ->replyTo(
                \App\Models\Setting::getValue('mail.reply_to_address', config('mail.from.address')),
                $appName
            )
            ->subject($appName . " - " . __('New Reimbursement Request') . ": $userName")
            ->view('emails.aligned-request', [
                'greeting' => __('Hello, Admin!'),
                'introLines' => [
                    __('User') . " **{$userName}** " . __('has submitted a new reimbursement request.')
                ],
                'details' => $details,
                'actionText' => __('Review Request'),
                'actionUrl' => route('admin.reimbursements'),
                'outroLines' => [
                    __('Please review this request at your earliest convenience.')
                ]
            ]);
    }
}
