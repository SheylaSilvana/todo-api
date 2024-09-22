<?php

namespace Tests;

class TestGetRecentTasksWithTotalCountTest extends TestCase
{
    public function testGetRecentTasksWithTotalCount()
    {
        // Autentica o usuário e obtém o token JWT
        $this->authenticate();

        // Faz a requisição GET para listar as tarefas recentes dos últimos 7 dias
        $response = $this->get('/api/v1/tasks/recent', $this->withToken());

        // Verifica se o status HTTP da resposta é 200
        $this->seeStatusCode(200);

        // Captura o conteúdo da resposta e exibe com var_dump
        $content = $response->response->getContent();
        var_dump($content);

        // Verifica se o JSON retornado contém a estrutura esperada
        $this->seeJsonStructure([
            '*' => ['id', 'title', 'description', 'status', 'completed_at', 'created_at', 'updated_at', 'user_id']
        ]);
    }
}
