<?php

namespace Tests;

use App\Models\Task;

class TestCreateTaskTest extends TestCase
{
    public function testCreateTask()
    {
        $this->authenticate();

        $data = [
            'title' => 'Tarefa Teste',
            'description' => 'Descrição da tarefa teste',
            'start_date_time' => '24/09/2024 01:05',
            'end_date_time' => '24/09/2024 01:14'
        ];

        // Faz a requisição POST para criar a tarefa
        $response = $this->post('/api/v1/tasks', $data, $this->withToken());

        // Verifica se o status HTTP da resposta é 201
        $this->seeStatusCode(201);

        // Verifica se os dados retornados contêm os dados esperados
        $this->seeJson([
            'title' => $data['title'],
            'description' => $data['description']
        ]);

        // Verifica se os dados foram inseridos no banco de dados
        $this->seeInDatabase('tasks', [
            'title' => $data['title'],
            'description' => $data['description'],
            'status' => 'A Fazer',
            'user_id' => auth()->id()
        ]);

        // Captura o conteúdo da resposta e exibe para verificação
        $content = $response->response->getContent();
        var_dump($content);
    }
}
