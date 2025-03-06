<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        return response()->json(Auth::user()->tasks()->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $task = Auth::user()->tasks()->create($request->all());

        return response()->json($task, 201);
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);
        return response()->json($task);
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);
        $request->validate([
            'completed' => 'boolean',
        ]);

        $task->update($request->all());

        return response()->json($task);
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $task->delete();

        return response()->json(null, 204);
    }
}


