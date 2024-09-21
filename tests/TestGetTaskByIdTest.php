<?php

namespace Tests;

use App\Models\Task;

class TestGetTaskByIdTest extends TestCase
{
    // Testar a exibição de uma tarefa específica
    public function testGetTaskById()
    {
        // Supondo que você já tenha uma tarefa existente no banco de dados
        $task = Task::first(); // Pega a primeira tarefa existente

        // Verifica se a tarefa existe
        $this->assertNotNull($task, 'Nenhuma tarefa encontrada no banco de dados');

        // Faz a requisição GET para exibir a tarefa pelo ID
        $response = $this->get('/api/tasks/' . $task->id);

        // Verifica se o status HTTP da resposta é 200
        $this->seeStatusCode(200);

        // Captura o conteúdo da resposta
        $content = $response->response->getContent();

        // Exibe o conteúdo retornado
        var_dump($content);

        // Verifica se o JSON retornado contém os dados da tarefa
        $this->seeJson([
            'title' => $task->title,
            'description' => $task->description,
            'status' => $task->status
        ]);
    }
}
