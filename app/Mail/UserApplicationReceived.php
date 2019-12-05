<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserApplicationReceived extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $admin;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, User $admin)
    {
        $this->user  = $user;
        $this->admin = $admin;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject('New User Application: '.$this->user->username)->markdown('mail.account.admin_pending');
    }
}
