<?php

namespace Tests;

class TestGetTasksByDateRangeTest extends TestCase
{
    public function testGetTasksByDateRange()
    {
        // Autentica o usuário e obtém o token JWT
        $this->authenticate();

        $startDate = '2024-09-20';
        $endDate = '2024-09-23';

        // Faz a requisição GET para listar tarefas em um intervalo de datas
        $response = $this->get("/api/v1/tasks/date-range?start_date=$startDate&end_date=$endDate", $this->withToken());

        // Verifica se o status HTTP da resposta é 200
        $this->seeStatusCode(200);

        // Captura o conteúdo da resposta e exibe com var_dump
        $content = $response->response->getContent();
        var_dump($content);

        // Captura o conteúdo da resposta e exibe com var_dump
        $content = $response->response->getContent();
        var_dump($content);

        // Verifica se o JSON retornado contém a estrutura esperada
        $this->seeJsonStructure([
            '*' => ['id', 'title', 'description', 'status', 'completed_at', 'created_at', 'updated_at', 'user_id']
        ]);
    }
}
