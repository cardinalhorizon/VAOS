<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PirepFiled extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
      if ($notifiable->status == 0){
        return (new MailMessage)
          ->subject('Notification Subject')
          ->greeting('Hello!')
          ->line('Your pilot report has been saved to the system!')
          ->action('Notification Action', 'https://laravel.com')
          ->line('Thank you for using our application!');
      }elseif ($notifiable->status == 1){
        return (new MailMessage)
          ->subject('Notification Subject')
          ->greeting('Hello!')
          ->line('Your pilot report has been accepted!')
          ->action('Notification Action', 'https://laravel.com')
          ->line('Thank you for using our application!');
      }else{
        return (new MailMessage)
          ->subject('Notification Subject')
          ->greeting('Hello!')
          ->line('Your pilot report has been rejected!')
          ->action('Notification Action', 'https://laravel.com')
          ->line('Thank you for using our application!');
      }
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
