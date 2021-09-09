<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ForgotPasswordEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    protected $url;
    protected $token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, String $token, String $url)
    {
        $this->user = $user;
        $this->token = $token;
        $this->url = $url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('noreply@cashexs.com', 'Cashexs')
        ->view('emails.account.forgot-password')
        ->subject("Forgot Password")
        ->with([
            "name" => $this->user->firstname,
            "token" => $this->token,
            "url" => $this->url
        ]);
    }
}
