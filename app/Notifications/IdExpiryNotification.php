<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class IdExpiryNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $expiringPersonnel;

    /**
     * Create a new notification instance.
     */
    public function __construct($expiringPersonnel)
    {
        $this->expiringPersonnel = $expiringPersonnel;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->from('it@digitalstationz.com', 'Digital Station')
            ->subject('Personnel ID Expiry Notification')
            ->greeting('Hello ' . ($notifiable->name ?? 'Agency') . ',');

        $expiryDate = optional($this->expiringPersonnel->first())->id_expiry;

        if ($expiryDate) {
            $mail->line('The following personnel under your agency have IDs expiring on ' . \Carbon\Carbon::parse($expiryDate)->toFormattedDateString() . ':');
        } else {
            $mail->line('Some personnel under your agency have IDs expiring soon:');
        }

        if ($this->expiringPersonnel->isNotEmpty()) {
            foreach ($this->expiringPersonnel as $personnel) {
                $mail->line("- {$personnel->first_name}{$personnel->first_name} ({$personnel->email})");
            }
        } else {
            $mail->line('No personnel found.');
        }

        $mail->line('')
            ->action('Update IDs', url('https://technostation.org/'))
            ->line('Please ensure that ID documents are updated in time to avoid service interruption.')
            ->line('Thank you for using our system.');

        return $mail;
    }



    /**
     * Format list of personnel for email.
     */
    protected function formatPersonnelList()
    {
        return $this->expiringPersonnel->map(function ($personnel) {
            return "- {$personnel->name} ({$personnel->email})";
        })->toArray();
    }

    /**
     * Optional: for database notifications (not needed here)
     */
    public function toArray($notifiable)
    {
        return [];
    }
}
