<?php

namespace Tests;

class TestRegisterUserTest extends TestCase
{
    public function testRegisterUser()
    {
        $data = [
            'name' => 'Teste Usuário',
            'email' => 'sheylasilvana19@gmail.com'
        ];

        // Faz a requisição POST para registrar um novo usuário
        $response = $this->post('/api/v1/register', $data);

        // Verifica se o status HTTP da resposta é 201
        $this->seeStatusCode(201);

        // Captura o conteúdo da resposta e exibe com var_dump
        $content = $response->response->getContent();
        var_dump($content);

        // Verifica se o JSON retornado contém os dados esperados
        $this->seeJson([
            'name' => $data['name'],
            'email' => $data['email']
        ]);

        // Verifica se o usuário foi inserido no banco de dados
        $this->seeInDatabase('users', ['email' => $data['email']]);
    }
}
