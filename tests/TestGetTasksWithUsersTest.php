<?php

namespace Tests;

class TestGetTasksWithUsersTest extends TestCase
{
    public function testGetTasksWithUsers()
    {
        // Autentica o usuário e obtém o token JWT
        $this->authenticate();

        // Faz a requisição GET para listar as tarefas com informações dos usuários
        $response = $this->get('/api/v1/tasks/with-users', $this->withToken());

        // Verifica se o status HTTP da resposta é 200
        $this->seeStatusCode(200);

        // Captura o conteúdo da resposta e exibe com var_dump
        $content = $response->response->getContent();
        var_dump($content);

        // Verifica se o JSON retornado contém a estrutura esperada
        $this->seeJsonStructure([
            '*' => ['id', 'title', 'description', 'status', 'user_name', 'user_email']
        ]);
    }
}
