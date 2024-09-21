<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    // 1. Listar todas as tarefas (GET /tasks)
    public function index()
    {
        $tasks = Task::all(); // Pega todas as tarefas
        return response()->json($tasks); // Retorna em formato JSON
    }

    // 2. Criar nova tarefa (POST /tasks)
    public function store(Request $request)
    {
        // Validação dos campos
        $this->validate($request, [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'in:A Fazer,Feitas'
        ]);

        // Criação da tarefa
        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status ?? 'A Fazer' // Se não for informado, o status será "A Fazer"
        ]);

        return response()->json($task, 201); // Retorna a tarefa criada com status 201 (Created)
    }

    // 3. Exibir uma tarefa específica (GET /tasks/{id})
    public function show($id)
    {
        $task = Task::findOrFail($id); // Encontra a tarefa pelo ID ou retorna 404
        return response()->json($task); // Retorna a tarefa encontrada
    }

    // 4. Atualizar uma tarefa existente (PUT /tasks/{id})
    public function update(Request $request, $id)
    {
        // Validação dos campos
        $this->validate($request, [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'in:A Fazer,Feitas'
        ]);

        $task = Task::findOrFail($id); // Encontra a tarefa pelo ID ou retorna 404

        // Atualiza os campos da tarefa
        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status
        ]);

        return response()->json($task); // Retorna a tarefa atualizada
    }

    // 5. Excluir uma tarefa (DELETE /tasks/{id})
    public function destroy($id)
    {
        $task = Task::findOrFail($id); // Encontra a tarefa pelo ID ou retorna 404
        $task->delete(); // Exclui a tarefa

        return response()->json(['message' => 'Tarefa excluída com sucesso']); // Retorna uma mensagem de sucesso
    }
}
