<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $newPassword;
    public $userFullName;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct ($newPassword, $userFullName)
    {
        $this->newPassword = $newPassword;
        $this->userFullName = $userFullName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build ()
    {
        return $this->view('mail.new_password')->subject('New Password');
    }
}
