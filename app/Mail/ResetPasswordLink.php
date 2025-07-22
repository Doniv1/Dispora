<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordLink extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;
    public $token;

    public function __construct($name, $email, $token)
    {
        $this->name = $name;
        $this->email = $email;
        $this->token = $token;
    }

    public function build()
    {
        return $this->subject('Permintaan Reset Password')
                    ->markdown('emails.reset_password')
                    ->with([
                        'name' => $this->name,
                        'email' => $this->email,
                        'token' => $this->token,
                    ]);
    }
}

