<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // Показать список задач
    public function index()
    {
        $tasks = Task::with('user')->get();
        return response()->json($tasks);
    }

    // Создать новую задачу
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed',
            'priority' => 'required|integer|between:1,3',
            'user_id' => 'required|exists:users,id'
        ]);

        $task = Task::create($validated);

        return response()->json($task, 201);
    }

    // Показать конкретную задачу
    public function show($id)
    {
        $task = Task::with('user')->findOrFail($id);
        return response()->json($task);
    }

    // Обновить задачу
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed',
            'priority' => 'required|integer|between:1,3',
            'user_id' => 'required|exists:users,id'
        ]);

        $task->update($validated);

        return response()->json($task);
    }

    // Удалить задачу
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return response()->json(['message' => 'Task deleted successfully']);
    }
}
