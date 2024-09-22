<?php

namespace Tests;

class TestGetAllTasksTest extends TestCase
{
    public function testGetAllTasks()
    {
        // Autentica o usuário e obtém o token JWT
        $this->authenticate();

        // Faz a requisição GET para listar todas as tarefas
        $response = $this->get('/api/v1/tasks', $this->withToken());

        // Verifica se o status HTTP da resposta é 200
        $this->seeStatusCode(200);

        // Captura o conteúdo da resposta e exibe com var_dump
        $content = $response->response->getContent();
        var_dump($content);

        // Verifica se o JSON de resposta tem a estrutura esperada
        $this->seeJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'description',
                    'status',
                    'completed_at',
                    'created_at',
                    'updated_at',
                    'user_id'
                ]
            ]
        ]);
    }
}
