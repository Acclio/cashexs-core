<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ConfirmationEmail extends Mailable
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
    public function __construct(User $user, String $url, String $token)
    {
        $this->user = $user;
        $this->url = $url;
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('noreply@cashexs.com', 'Cashexs')
        ->view('emails.account.confirmation')
        ->subject("Email Confirmation")
        ->with([
            "name" => $this->user->firstname,
            "url" => $this->url,
            "token" => $this->token
        ]);
    }
}
