<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    // Permite a inserção de dados em massa (mass assignment)
    protected $fillable = ['title', 'description', 'status'];

    // Definição do status como enum
    protected $casts = [
        'status' => 'string',
    ];
}
