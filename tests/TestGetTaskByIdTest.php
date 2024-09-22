<?php

namespace Tests;

use App\Models\Task;

class TestGetTaskByIdTest extends TestCase
{
    public function testGetTaskById()
    {
        // Autentica o usuário e obtém o token JWT
        $this->authenticate();

        // Recupera a primeira tarefa existente no banco de dados
        $task = Task::where('user_id', auth()->id())->first();

        // Verifica se a tarefa existe
        $this->assertNotNull($task, 'Nenhuma tarefa encontrada no banco de dados');

        // Faz a requisição GET para exibir a tarefa pelo ID
        $response = $this->get('/api/v1/tasks/' . $task->id, $this->withToken());

        // Verifica se o status HTTP da resposta é 200
        $this->seeStatusCode(200);

        // Captura o conteúdo da resposta e exibe com var_dump
        $content = $response->response->getContent();
        var_dump($content);

        // Verifica se o JSON retornado contém os dados da tarefa
        $this->seeJson([
            'title' => $task->title,
            'description' => $task->description,
            'status' => $task->status
        ]);
    }
}
