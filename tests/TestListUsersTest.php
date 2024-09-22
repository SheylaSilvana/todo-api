<?php

namespace Tests;

class TestListUsersTest extends TestCase
{
    public function testListUsers()
    {
        // Autentica o usuário
        $this->authenticate();

        // Faz a requisição GET para o endpoint /api/v1/users com paginação
        $response = $this->get('/api/v1/users?per_page=5', $this->withToken());

        // Verifica se o status HTTP da resposta é 200
        $this->seeStatusCode(200);

        // Exibe o conteúdo da resposta com var_dump
        var_dump($response->response->getContent());

        // Verifica se o JSON retornado contém os dados paginados
        $this->seeJsonStructure([
            'current_page',
            'data' => [
                '*' => ['id', 'name', 'email']
            ],
            'first_page_url',
            'from',
            'last_page',
            'last_page_url',
            'next_page_url',
            'path',
            'per_page',
            'prev_page_url',
            'to',
            'total'
        ]);
    }
}
