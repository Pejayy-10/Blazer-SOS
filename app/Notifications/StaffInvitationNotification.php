<?php

namespace App\Notifications;

use App\Models\StaffInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class StaffInvitationNotification extends Notification
{
    use Queueable;

    public StaffInvitation $invitation;

    /**
     * Create a new notification instance.
     */
    public function __construct(StaffInvitation $invitation)
    {
        $this->invitation = $invitation;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $registrationUrl = URL::temporarySignedRoute(
            'admin.register.form',
            $this->invitation->expires_at,
            ['token' => $this->invitation->token]
        );

        return (new MailMessage)
                    ->subject('Invitation to Join Blazer SOS Staff')
                    ->greeting('Hello!')
                    ->line('You have been invited to join the **' . config('app.name', 'Blazer SOS') . '** staff team as a **' . $this->invitation->role_name . '**.')
                    ->line('Please click the button below to create your account and accept the invitation. This link is valid for 7 days and can only be used once.')
                    ->action('Accept Invitation', $registrationUrl)
                    ->line('Regards,')
                    ->salutation(config('app.name', 'Blazer SOS') . ' Team')
                    // *** Replace ->subcopy() with ->line() ***
                    // You can use Markdown for formatting (e.g., italics or just plain)
                    ->line('_If you\'re having trouble clicking the "Accept Invitation" button, copy and paste the URL below into your web browser:_') // Example with italics
                    ->line($registrationUrl); // Display the URL on its own line
                    // Or combine them:
                    // ->line('_If you\'re having trouble... browser:_ ' . $registrationUrl)
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            // Data for database notifications if needed later
        ];
    }
}