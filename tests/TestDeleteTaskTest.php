<?php

namespace Tests;

use App\Models\Task;

class TestDeleteTaskTest extends TestCase
{
    // Testar a exclusão de uma tarefa existente
    public function testDeleteTask()
    {
        // Recupera uma tarefa existente no banco de dados
        $task = Task::first(); // Pega a primeira tarefa existente

        // Verifica se a tarefa existe
        $this->assertNotNull($task, 'Nenhuma tarefa encontrada no banco de dados');

        // Faz a requisição DELETE para excluir a tarefa
        $response = $this->delete('/api/tasks/' . $task->id);

        // Verifica se o status HTTP da resposta é 200
        $this->seeStatusCode(200);

        // Captura o conteúdo da resposta
        $content = $response->response->getContent();

        // Exibe o conteúdo retornado pela API
        var_dump($content);

        // Verifica se a mensagem de sucesso foi retornada
        $this->seeJson(['message' => 'Tarefa excluída com sucesso']);

        // Verifica se a tarefa foi removida do banco de dados
        $this->notSeeInDatabase('tasks', ['id' => $task->id]);
    }
}
