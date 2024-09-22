<?php

namespace Tests;

class TestDeleteUserTest extends TestCase
{
    public function testDeleteUser()
    {
        // Autentica o usuário
        $this->authenticate();
        $user = auth()->user(); // Obter o usuário autenticado

        // Faz a requisição DELETE para o endpoint /api/v1/users/{id}
        $response = $this->delete('/api/v1/users/' . $user->id, [], $this->withToken());

        // Verifica se o status HTTP da resposta é 200
        $this->seeStatusCode(200);

        // Exibe o conteúdo da resposta com var_dump
        var_dump($response->response->getContent());

        // Verifica se a resposta contém a mensagem de sucesso
        $this->seeJson(['message' => 'Usuário excluído com sucesso']);

        // Verifica se o usuário foi removido do banco de dados
        $this->notSeeInDatabase('users', ['id' => $user->id]);
    }
}
