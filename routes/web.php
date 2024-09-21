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

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('tasks', 'TaskController@store'); // Criar tarefa
    $router->get('tasks', 'TaskController@index'); // Listar tarefas
    $router->get('tasks/{id}', 'TaskController@show'); // Exibir uma tarefa
    $router->put('tasks/{id}', 'TaskController@update'); // Atualizar tarefa
    $router->delete('tasks/{id}', 'TaskController@destroy'); // Excluir tarefa
});
