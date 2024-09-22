<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', ['A Fazer', 'Em Progresso', 'Pausada', 'Cancelada', 'Feitas'])->default('A Fazer');
            $table->timestamp('completed_at')->nullable(); // Campo para armazenar o timestamp de conclusão
            $table->timestamps();

            // Adiciona a coluna 'user_id' como chave estrangeira
            $table->unsignedBigInteger('user_id')->nullable();

            // Define a chave estrangeira
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['user_id']); // Remove a chave estrangeira
        });

        Schema::dropIfExists('tasks');
    }
};