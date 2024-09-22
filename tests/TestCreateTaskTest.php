<?php

namespace Tests;

use App\Models\Task;

class TestCreateTaskTest extends TestCase
{
    public function testCreateTask()
    {
        $this->authenticate();

        $data = [
            'title' => 'Tarefa Teste 2 user 1',
            'description' => 'Descrição da tarefa teste',
            'status' => 'A Fazer'
        ];

        // Faz a requisição POST para criar a tarefa
        $response = $this->post('/api/v1/tasks', $data, $this->withToken());

        // Verifica se o status HTTP da resposta é 201
        $this->seeStatusCode(201);

        // Captura o conteúdo da resposta e exibe com var_dump
        $content = $response->response->getContent();
        var_dump($content);

        // Verifica se o JSON retornado contém os dados esperados
        $this->seeJson($data);

        // Verifica se os dados foram inseridos no banco de dados
        $this->seeInDatabase('tasks', [
            'title' => $data['title'],
            'description' => $data['description'],
            'status' => $data['status'],
            'user_id' => auth()->id()
        ]);
    }
}
