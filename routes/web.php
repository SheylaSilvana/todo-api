<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->group(['prefix' => 'api/v1'], function () use ($router) {
    $router->post('register', 'AuthController@register'); // Rota de registro
    $router->post('login', 'AuthController@login'); // Rota de login (gera token)

    // Rotas protegidas por autenticação JWT
    $router->group(['middleware' => 'jwt.auth'], function () use ($router) {
        // Rotas estáticas para tarefas
        $router->get('tasks/status-count', 'TaskController@getTasksGroupedByStatus'); // Contar tarefas por status
        $router->get('tasks/date-range', 'TaskController@getTasksByDateRange'); // Buscar tarefas por intervalo de datas
        $router->get('tasks/with-users', 'TaskController@getTasksWithUsers'); // Buscar tarefas com informações de usuários
        $router->get('tasks/recent', 'TaskController@getRecentTasksWithTotalCount'); // Buscar tarefas recentes (últimos 7 dias)

        // Rotas CRUD para tarefas
        $router->post('tasks', 'TaskController@store'); // Criar tarefa
        $router->get('tasks', 'TaskController@index'); // Listar tarefas com paginação e filtros
        $router->get('tasks/{id}', 'TaskController@show'); // Exibir uma tarefa específica
        $router->put('tasks/{id}', 'TaskController@update'); // Atualizar tarefa
        $router->delete('tasks/{id}', 'TaskController@destroy'); // Excluir tarefa

        // Rotas para gerenciamento de usuários
        $router->get('me', 'AuthController@me'); // Obter o usuário autenticado
        $router->post('logout', 'AuthController@logout'); // Fazer logout
        $router->get('users', 'AuthController@index'); // Listar usuários com paginação
        $router->get('users/{id}', 'AuthController@show'); // Exibir detalhes de um usuário
        $router->put('users/{id}', 'AuthController@update'); // Atualizar dados do usuário
        $router->delete('users/{id}', 'AuthController@destroy'); // Excluir usuário
        $router->post('users/{id}/reset-password', 'AuthController@resetPassword'); // Redefinir senha e enviar por e-mail
    });
});
