<?php

namespace Tests;

class TestLogoutTest extends TestCase
{
    public function testLogout()
    {
        // Autentica o usuário
        $this->authenticate();

        // Faz a requisição POST para o endpoint de logout
        $response = $this->post('/api/v1/logout', [], $this->withToken());

        // Verifica se o status HTTP da resposta é 200
        $this->seeStatusCode(200);

        // Exibe o conteúdo da resposta com var_dump
        var_dump($response->response->getContent());

        // Verifica se a resposta contém a mensagem de sucesso
        $this->seeJson(['message' => 'Logout realizado com sucesso']);
    }
}
