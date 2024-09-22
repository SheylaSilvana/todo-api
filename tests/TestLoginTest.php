<?php

namespace Tests;

use App\Models\User;

class TestLoginTest extends TestCase
{
    public function testLogin()
    {
        // Faz a requisição POST para a rota de login usando os dados de login da classe TestCase
        $response = $this->post('/api/v1/login', $this->loginData);

        // Verifica se o status HTTP da resposta é 200
        $this->seeStatusCode(200);

        // Captura o conteúdo da resposta
        $content = json_decode($response->response->getContent(), true);

        // Exibe o conteúdo da resposta com var_dump
        var_dump($content);

        // Verifica se o token JWT foi retornado na resposta
        $this->assertArrayHasKey('token', $content, 'O token JWT não foi retornado na resposta');

        // Armazena o token JWT para uso posterior nos testes
        $this->token = $content['token'];
    }
}
