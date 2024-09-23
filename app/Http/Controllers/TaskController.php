<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    // 1. Listar todas as tarefas com paginação (GET /tasks)
    public function index(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 10);
            $query = Task::query();

            $user = auth()->user();

            $query = Task::where('user_id', $user->id);

            // Filtros opcionais
            if ($request->has('status')) {
                $query->where('status', $request->get('status'));
            }

            if ($request->has('today') && $request->get('today') == 'true') {
                $query->whereDate('created_at', Carbon::today());
            }

            if ($request->has('title')) {
                $query->whereRaw('LOWER(title) LIKE ?', ['%' . strtolower($request->get('title')) . '%']);
            }

            $tasks = $query->paginate($perPage);

            if ($tasks->isEmpty()) {
                return response()->json(['message' => 'Nenhuma tarefa encontrada.'], 404);
            }

            return response()->json($tasks);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao listar tarefas: ' . $e->getMessage()], 500);
        }
    }

    // 2. Criar nova tarefa (POST /tasks)
    public function store(Request $request)
    {
        $messages = [
            'title.required' => 'O título da tarefa é obrigatório.',
            'status.in' => 'O status da tarefa deve ser um dos seguintes: A Fazer, Em Progresso, Pausada, Cancelada, Feitas.'
        ];

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'in:A Fazer,Em Progresso,Pausada,Cancelada,Feitas'
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $user = auth()->user();

            $task = Task::create([
                'title' => $request->title,
                'description' => $request->description,
                'status' => $request->status ?? 'A Fazer',
                'user_id' => $user->id
            ]);

            return response()->json($task, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao criar tarefa: ' . $e->getMessage()], 500);
        }
    }

    // 3. Exibir uma tarefa específica (GET /tasks/{id})
    public function show($id)
    {
        try {
            $user = auth()->user();

            $task = Task::where('id', $id)->where('user_id', $user->id)->first();

            if (!$task) {
                return response()->json(['message' => 'Tarefa não encontrada.'], 404);
            }

            return response()->json($task);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao buscar tarefa: ' . $e->getMessage()], 500);
        }
    }

    // 4. Atualizar uma tarefa existente (PUT /tasks/{id})
    public function update(Request $request, $id)
    {
        $messages = [
            'title.required' => 'O título da tarefa é obrigatório.',
            'status.in' => 'O status da tarefa deve ser um dos seguintes: A Fazer, Em Progresso, Pausada, Cancelada, Feitas.'
        ];

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'in:A Fazer,Em Progresso,Pausada,Cancelada,Feitas'
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $user = auth()->user();
            $task = Task::where('id', $id)->where('user_id', $user->id)->first();

            if (!$task) {
                return response()->json(['message' => 'Tarefa não encontrada.'], 404);
            }

            if ($request->status === 'Feitas' && $task->status !== 'Feitas') {
                $task->completed_at = Carbon::now();
            }

            $task->update([
                'title' => $request->title,
                'description' => $request->description,
                'status' => $request->status,
            ]);

            return response()->json($task);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao atualizar tarefa: ' . $e->getMessage()], 500);
        }
    }

    // 5. Excluir uma tarefa (DELETE /tasks/{id})
    public function destroy($id)
    {
        try {
            $user = auth()->user();
            $task = Task::where('id', $id)->where('user_id', $user->id)->first();

            if (!$task) {
                return response()->json(['message' => 'Tarefa não encontrada.'], 404);
            }

            $task->delete();
            return response()->json(['message' => 'Tarefa excluída com sucesso']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao excluir tarefa: ' . $e->getMessage()], 500);
        }
    }

    // 6. Contar o número de tarefas por status (GET /tasks/status-count)
    public function getTasksGroupedByStatus()
    {
        try {
            $user = auth()->user();

            $tasksByStatus = DB::table('tasks')
                ->select(DB::raw('status, COUNT(*) as total'))
                ->where('user_id', $user->id)
                ->groupBy('status')
                ->get();

            if ($tasksByStatus->isEmpty()) {
                return response()->json(['message' => 'Nenhuma tarefa encontrada.'], 404);
            }

            return response()->json($tasksByStatus);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao agrupar tarefas: ' . $e->getMessage()], 500);
        }
    }

    // 7. Buscar tarefas criadas em um intervalo de datas (GET /tasks/date-range)
    public function getTasksByDateRange(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $user = auth()->user();

        if (!$startDate && !$endDate) {
            return response()->json(['message' => 'É necessário fornecer pelo menos uma das datas: start_date ou end_date.'], 400);
        }

        try {
            $query = Task::where('user_id', $user->id);

            if ($startDate && !$endDate) {
                $endDate = Carbon::now()->toDateString();
            }

            if (!$startDate && $endDate) {
                $startDate = Carbon::createFromTimestamp(0)->toDateString();
            }

            if ($startDate === $endDate) {
                $tasks = $query->whereDate('created_at', $startDate)->get();
            } else {
                $tasks = $query->whereBetween('created_at', [$startDate, $endDate])->get();
            }

            if ($tasks->isEmpty()) {
                return response()->json(['message' => 'Nenhuma tarefa encontrada no intervalo de datas especificado.'], 404);
            }

            return response()->json($tasks);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao buscar tarefas por intervalo de datas: ' . $e->getMessage()], 500);
        }
    }

    // 8. Buscar tarefas com informações de usuários (GET /tasks/with-users)
    public function getTasksWithUsers()
    {
        try {
            $user = auth()->user();

            $tasksWithUsers = DB::table('tasks')
                ->join('users', 'tasks.user_id', '=', 'users.id')
                ->select('tasks.*', 'users.name as user_name', 'users.email as user_email')
                ->where('tasks.user_id', $user->id)
                ->get();

            if ($tasksWithUsers->isEmpty()) {
                return response()->json(['message' => 'Nenhuma tarefa com informações de usuários encontrada.'], 404);
            }

            return response()->json($tasksWithUsers);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao buscar tarefas com usuários: ' . $e->getMessage()], 500);
        }
    }

    // 9. Subconsulta para buscar tarefas recentes (últimos 7 dias) (GET /tasks/recent)
    public function getRecentTasksWithTotalCount()
    {
        try {
            $user = auth()->user();

            $recentTasks = DB::table('tasks')
                ->where('user_id', $user->id)
                ->where('created_at', '>=', DB::raw('NOW() - INTERVAL \'7 DAYS\''))
                ->get();

            if ($recentTasks->isEmpty()) {
                return response()->json(['message' => 'Nenhuma tarefa recente encontrada.'], 404);
            }

            return response()->json($recentTasks);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao buscar tarefas recentes: ' . $e->getMessage()], 500);
        }
    }
}
