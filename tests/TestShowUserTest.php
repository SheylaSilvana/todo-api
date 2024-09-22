<?php

namespace Tests;

class TestShowUserTest extends TestCase
{
    public function testShowUser()
    {
        // Autentica o usuário e obtém o usuário autenticado
        $this->authenticate();
        $user = auth()->user(); // Obter o usuário autenticado

        // Faz a requisição GET para o endpoint /api/v1/users/{id}
        $response = $this->get('/api/v1/users/' . $user->id, $this->withToken());

        // Verifica se o status HTTP da resposta é 200
        $this->seeStatusCode(200);

        // Exibe o conteúdo da resposta com var_dump
        var_dump($response->response->getContent());

        // Verifica se o JSON retornado contém os dados do usuário
        $this->seeJson([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email
        ]);
    }
}
