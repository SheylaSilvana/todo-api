<?php

namespace Tests;

class TestUpdateUserTest extends TestCase
{
    public function testUpdateUser()
    {
        // Autentica o usuário
        $this->authenticate();
        $user = auth()->user(); // Obter o usuário autenticado

        // Dados de atualização
        $data = [
            'name' => 'Updated Name'
        ];

        // Faz a requisição PUT para o endpoint /api/v1/users/{id}
        $response = $this->put('/api/v1/users/' . $user->id, $data, $this->withToken());

        // Verifica se o status HTTP da resposta é 200
        $this->seeStatusCode(200);

        // Exibe o conteúdo da resposta com var_dump
        var_dump($response->response->getContent());

        // Verifica se o JSON retornado contém os dados atualizados
        $this->seeJson($data);

        // Verifica se os dados foram atualizados no banco de dados
        $this->seeInDatabase('users', ['id' => $user->id, 'name' => $data['name']]);
    }
}
