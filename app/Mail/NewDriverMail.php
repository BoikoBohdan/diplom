<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewDriverMail extends Mailable
{
    use Queueable, SerializesModels;

    public $userFullName;
    public $userEmail;
    public $userPassword;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($userFullName, $userEmail, $userPassword)
    {
        $this->userFullName = $userFullName;
        $this->userEmail = $userEmail;
        $this->userPassword = $userPassword;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.new_driver')->subject('Registration Information');
    }
}
