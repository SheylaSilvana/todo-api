<?php

namespace Tests;

use App\Models\Task;

class TestUpdateTaskTest extends TestCase
{
    // Testar a atualização de uma tarefa existente
    public function testUpdateTask()
    {
        // Recupera uma tarefa existente no banco de dados
        $task = Task::first(); // Pega a primeira tarefa existente no banco

        // Verifica se a tarefa existe
        $this->assertNotNull($task, 'Nenhuma tarefa encontrada no banco de dados');

        // Dados atualizados
        $updatedData = [
            'title' => 'Tarefa Atualizada',
            'description' => 'Descrição atualizada',
            'status' => 'Feitas'
        ];

        // Faz a requisição PUT para atualizar a tarefa
        $response = $this->put('/api/tasks/' . $task->id, $updatedData);

        // Verifica se o status HTTP da resposta é 200
        $this->seeStatusCode(200);

        // Captura o conteúdo da resposta
        $content = $response->response->getContent();

        // Exibe o conteúdo retornado pela API
        var_dump($content);

        // Verifica se o JSON retornado contém os dados atualizados
        $this->seeJson($updatedData);

        // Verifica se os dados atualizados estão no banco de dados
        $this->seeInDatabase('tasks', $updatedData);
    }
}
