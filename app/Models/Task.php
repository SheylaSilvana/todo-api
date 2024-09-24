<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    // Permite a inserção de dados em massa (mass assignment)
    protected $fillable = ['title', 'description', 'status', 'completed_at', 'user_id', 'google_event_id', 'start_date_time', 'end_date_time'];

    // Definição do status como enum
    protected $casts = [
        'status' => 'string',
        'completed_at' => 'datetime',
        'start_date_time' => 'datetime',
        'end_date_time' => 'datetime',
    ];

    // Definição dos estados possíveis para a tarefa
    const STATUS_OPTIONS = [
        'A Fazer',
        'Feitas',
    ];

    // Método para marcar uma tarefa como concluída
    public function markAsCompleted()
    {
        $this->status = 'Feitas';
        $this->completed_at = now(); // Armazena a data de conclusão
        $this->save();
    }

    // Relacionamento com o modelo User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
