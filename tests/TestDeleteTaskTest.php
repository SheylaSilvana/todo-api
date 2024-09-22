<?php

namespace Tests;

use App\Models\Task;

class TestDeleteTaskTest extends TestCase
{
    public function testDeleteTask()
    {
        // Autentica o usuário e obtém o token JWT
        $this->authenticate();

        // Recupera a primeira tarefa existente no banco de dados
        $task = Task::where('user_id', auth()->id())->first();

        // Verifica se a tarefa existe
        $this->assertNotNull($task, 'Nenhuma tarefa encontrada no banco de dados');

        // Faz a requisição DELETE para excluir a tarefa
        $response = $this->delete('/api/v1/tasks/' . $task->id, [], $this->withToken());
        // Verifica se o status HTTP da resposta é 200
        $this->seeStatusCode(200);

        // Captura o conteúdo da resposta e exibe com var_dump
        $content = $response->response->getContent();
        var_dump($content);

        // Verifica se a mensagem de sucesso foi retornada
        $this->seeJson(['message' => 'Tarefa excluída com sucesso']);

        // Verifica se a tarefa foi removida do banco de dados
        $this->notSeeInDatabase('tasks', ['id' => $task->id]);
    }
}
