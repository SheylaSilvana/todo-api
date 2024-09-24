<?php

namespace Tests;

class TestGetAllTasksTest extends TestCase
{
    public function testGetAllTasks()
    {
        // Autentica o usuário e obtém o token JWT
        $this->authenticate();

        // Define os parâmetros opcionais para o teste
        $params = [
            'per_page' => 10, // Define o número de resultados por página
            'status' => 'A Fazer', // Filtra por status "A Fazer"
        ];

        // Faz a requisição GET para listar todas as tarefas com parâmetros
        $response = $this->get('/api/v1/tasks?' . http_build_query($params), $this->withToken());

        // Verifica se o status HTTP da resposta é 200
        $this->seeStatusCode(200);

        // Captura o conteúdo da resposta e exibe com var_dump (para debug)
        $content = $response->response->getContent();
        var_dump($content);

        // Verifica se o JSON de resposta tem a estrutura esperada
        $this->seeJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'description',
                    'status',
                    'created_at',
                    'completed_at',
                    'user_id'
                ]
            ],
            'current_page',
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
