<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FlightCreated extends Notification
{
    use Queueable;

    public $flight;
    public $autocreate;
    public $source;

    /**
     * FlightCreated constructor.
     *
     * @param $flight
     * @param bool $autocreated
     * @param null $source
     */
    public function __construct($flight, $autocreated = false, $source = null)
    {
        $this->flight     = $flight;
        $this->autocreate = $autocreated;
        $this->source     = $source;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     *
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
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if ($this->autocreate) {
            return (new MailMessage)
                ->greeting('Hi, '.$this->flight->user->first_name)
                ->line($this->source.' detected a valid condition to create a new flight in the system')
                ->action('Notification Action', url('/'))
                ->line('Thank you for using our application!');
        }

        return (new MailMessage)
            ->greeting('Hi, '.$this->flight->user->first_name)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
