<?php

namespace App\Notifications;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InviteMember extends Notification
{
    use Queueable;

    private $member;

    private $from;

    /**
     * Create a new notification instance.
     *
     * @param User $member
     * @param User $from
     * @return void
     */
    public function __construct (User $member, User $from)
    {
        $this->member = $member;
        $this->from = $from;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via ($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail ($notifiable)
    {
        return (new MailMessage())
            ->view('mail.invite_member', ['member' => $this->member])
            ->from($this->from->email)
            ->subject('Invite');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray ($notifiable)
    {
        return [
            //
        ];
    }
}
