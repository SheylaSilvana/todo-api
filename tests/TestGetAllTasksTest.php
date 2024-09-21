<?php

namespace Tests;

class TestGetAllTasksTest extends TestCase
{
    // Testar a listagem de todas as tarefas
    public function testGetAllTasks()
    {
        // Faz a requisição GET para listar todas as tarefas
        $response = $this->get('/api/tasks');

        // Verifica se o status HTTP da resposta é 200
        $this->seeStatusCode(200);

        // Captura o conteúdo da resposta
        $content = $response->response->getContent();

        // Exibe o conteúdo da resposta para que você possa vê-lo
        var_dump($content);
    }
}
