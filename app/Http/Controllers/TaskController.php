<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Exception;

class TaskController extends Controller
{
    public function index()
    { 
        try {
            $tasks = Task::paginate(10);
            return response()->json($tasks, 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Failed to retrieve tasks.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(TaskRequest $request)
    {
        try {
            $task = Task::create($request->validated());
            return response()->json($task, 201);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Failed to create task.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(Task $task)
    {
        try {
            return response()->json($task, 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Failed to retrieve task.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(TaskRequest $request, Task $task)
    {
        try {
            $task->update($request->validated());
            return response()->json($task, 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Failed to update task.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Task $task)
    {
        try {
            $task->delete();
            return response()->json(['message' => 'Task deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Failed to delete task.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
