<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    // Permite a inserção de dados em massa (mass assignment)
    protected $fillable = ['title', 'description', 'status', 'completed_at', 'user_id'];

    // Definição do status como enum
    protected $casts = [
        'status' => 'string',
        'completed_at' => 'datetime',
    ];

    // Definição dos estados possíveis para a tarefa
    const STATUS_OPTIONS = [
        'A Fazer',
        'Em Progresso',
        'Pausada',
        'Cancelada',
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
