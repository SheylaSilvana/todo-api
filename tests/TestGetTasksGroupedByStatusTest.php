<?php

namespace Tests;

class TestGetTasksGroupedByStatusTest extends TestCase
{
    public function testGetTasksGroupedByStatus()
    {
        // Autentica o usuário e obtém o token JWT
        $this->authenticate();

        // Faz a requisição GET para contar o número de tarefas por status
        $response = $this->get('/api/v1/tasks/status-count', $this->withToken());

        // Verifica se o status HTTP da resposta é 200
        $this->seeStatusCode(200);

        // Captura o conteúdo da resposta e exibe com var_dump
        $content = $response->response->getContent();
        var_dump($content);

        // Verifica se o JSON retornado contém a estrutura esperada
        $this->seeJsonStructure([
            '*' => ['status', 'total']
        ]);
    }
}
