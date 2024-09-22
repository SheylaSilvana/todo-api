<?php

namespace Tests;

use Laravel\Lumen\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected $token;
    protected $loginData;

    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    public function setUp(): void
    {
        parent::setUp();

        // Defina os dados de login em um único lugar
        $this->loginData = [
            'email' => 'sheylasilvana19@gmail.com',
            'password' => 'JNumX7ZdUD' // Certifique-se de que esta senha é válida para o usuário
        ];
    }

    public function authenticate()
    {
        // Faz a requisição POST para a rota de login com os dados definidos
        $response = $this->post('/api/v1/login', $this->loginData);

        // Verifica se o status HTTP da resposta é 200
        $this->seeStatusCode(200);

        // Captura o conteúdo da resposta
        $content = json_decode($response->response->getContent(), true);

        // Armazena o token JWT para uso posterior nos testes
        $this->token = $content['token'];
    }

    public function withToken()
    {
        return ['Authorization' => "Bearer {$this->token}"];
    }
}
