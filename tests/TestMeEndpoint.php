<?php

namespace Tests;

class TestMeEndpoint extends TestCase
{
    public function testMe()
    {
        // Autentica o usuário
        $this->authenticate();

        // Faz a requisição GET para o endpoint /api/v1/me
        $response = $this->get('/api/v1/me', $this->withToken());

        // Verifica se o status HTTP da resposta é 200
        $this->seeStatusCode(200);

        // Exibe o conteúdo da resposta com var_dump
        var_dump($response->response->getContent());

        // Verifica se o JSON retornado contém os dados do usuário autenticado
        $this->seeJsonStructure(['id', 'name', 'email']);
    }
}
