<?php

namespace Tests;

use App\Models\Task;

class TestCreateTaskTest extends TestCase
{
    // Testar a criação de uma tarefa
    public function testCreateTask()
    {
        $data = [
            'title' => 'Tarefa Teste',
            'description' => 'Descrição da tarefa teste',
            'status' => 'A Fazer'
        ];

        // Faz a requisição POST para criar a tarefa
        $response = $this->post('/api/tasks', $data);

        // Verifica se o status HTTP da resposta é 201
        $this->seeStatusCode(201);

        // Captura o conteúdo da resposta
        $content = $response->response->getContent();

        // Exibe o conteúdo retornado pela API
        var_dump($content);

        // Verifica se o JSON retornado contém os dados esperados
        $this->seeJson($data);

        // Verifica se os dados foram inseridos no banco de dados
        $this->seeInDatabase('tasks', $data);
    }
}
