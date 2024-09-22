<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Support\Facades\Mail;

class SendEmailJob extends Job
{
    protected $user;
    protected $content;
    protected $subject;

    public function __construct(User $user, $subject, $content)
    {
        $this->user = $user;
        $this->subject = $subject;
        $this->content = $content;
    }

    public function handle()
    {
        Mail::raw($this->content, function ($message) {
            $message->to($this->user->email)
                    ->subject($this->subject);
        });
    }
}
