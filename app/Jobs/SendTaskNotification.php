<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Task;
use Illuminate\Support\Facades\Mail;

class SendTaskNotification extends Job
{
    protected $user;
    protected $task;
    protected $action;

    public function __construct(User $user, Task $task, $action)
    {
        $this->user = $user;
        $this->task = $task;
        $this->action = $action;
    }

    public function handle()
    {
        $message = "A tarefa '{$this->task->title}' foi {$this->action}.";

        Mail::raw($message, function ($mail) {
            $mail->to($this->user->email)
                ->subject("Notificação de Tarefa");
        });
    }
}
