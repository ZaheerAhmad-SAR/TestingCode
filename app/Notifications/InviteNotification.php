<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InviteNotification extends Notification
{
    use Queueable;
    protected $notification_url;
    /**
     * Create a new notification_url instance.
     *
     * @param $notification_url
     */
    public function __construct($notification_url)
    {
        $this->notification_url = $notification_url;
    }
    /**
     * Get the notification_url's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }
    /**
     * Get the mail representation of the notification_url.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Invitation from OCAP, OIRRC')
            ->greeting('Greetings!')
            ->from('infor@oirrc.net')
            ->replyTo('infor@oirrc.net')
            ->line('This is to invite you to join OCAP, OIRRC Team ' . config('OCAP'))
            ->action('Join Team',$this->notification_url)
            ->line('Thank you for using our data capturing system!');
    }
    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
