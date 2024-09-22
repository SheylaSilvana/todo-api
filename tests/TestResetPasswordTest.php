<?php

namespace Tests;

use App\Models\User;

class TestResetPasswordTest extends TestCase
{
    public function testResetPassword()
    {
        // Autentica o usuário e obtém o token JWT
        $this->authenticate();

        // Recupera um usuário existente no banco de dados
        $user = User::first();

        // Verifica se o usuário existe
        $this->assertNotNull($user, 'Nenhum usuário encontrado no banco de dados');

        // Faz a requisição POST para redefinir a senha do usuário
        $response = $this->post('/api/v1/users/' . $user->id . '/reset-password', [], $this->withToken());

        // Verifica se o status HTTP da resposta é 200
        $this->seeStatusCode(200);

        // Captura o conteúdo da resposta e exibe com var_dump
        $content = $response->response->getContent();
        var_dump($content);

        // Verifica se a mensagem de sucesso foi retornada
        $this->seeJson(['message' => 'Senha redefinida com sucesso e enviada por e-mail']);
    }
}
