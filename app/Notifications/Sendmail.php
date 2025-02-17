<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Mail;

class Sendmail extends Notification
{
    use Queueable;
    public $data;

    /**
     * Create a new notification instance.
     */
    public function __construct($data)
    {
        //
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
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
        $imgpath = storage_path('app/public/' . $this->data['image']);
        return (new MailMessage)
            ->subject('Notifical mail from' . $this->data['name'])
            // ->line('The introduction to the notification.')
            // ->line('Name: '.$this->data['name'])
            // ->line('Email: '.$this->data['email'])
            // ->line('Phone: '.$this->data['phone'])
            // ->line('Message: '.$this->data['message'])
            ->subject('Notification mail from ' . $this->data['name'])
            ->markdown('emailtemplate.emailt', ['data' => $this->data])
            ->attach($imgpath)
            ->action('Active Now', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
