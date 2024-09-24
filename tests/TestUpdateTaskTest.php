<?php

namespace Tests;

use App\Models\Task;

class TestUpdateTaskTest extends TestCase
{
    public function testUpdateTask()
    {
        // Autentica o usuário e obtém o token JWT
        $this->authenticate();

        // Recupera a primeira tarefa existente no banco de dados
        $task = Task::first();

        // Verifica se a tarefa existe
        $this->assertNotNull($task, 'Nenhuma tarefa encontrada no banco de dados');

        // Dados atualizados
        $updatedData = [
            'title' => 'Tarefa Atualizada',
            'description' => 'Descrição atualizada',
            'start_date_time' => '24/09/2024 03:00',
            'end_date_time' => '25/09/2024 16:00',
            'status' => 'Feitas'
        ];

        // Faz a requisição PUT para atualizar a tarefa
        $response = $this->put('/api/v1/tasks/' . $task->id, $updatedData, $this->withToken());

        // Verifica se o status HTTP da resposta é 200
        $this->seeStatusCode(200);

        // Verifica se o JSON retornado contém os dados atualizados
        $this->seeJson([
            'title' => $updatedData['title'],  // Alterado de $data para $updatedData
            'description' => $updatedData['description']
        ]);

        // Verifica se os dados atualizados estão no banco de dados
        $this->seeInDatabase('tasks', [
            'id' => $task->id,
            'title' => $updatedData['title'],
            'description' => $updatedData['description'],
            'status' => $updatedData['status'],
            'user_id' => auth()->id()
        ]);

        // Captura o conteúdo da resposta e exibe com var_dump
        $content = $response->response->getContent();
        var_dump($content);
    }
}
