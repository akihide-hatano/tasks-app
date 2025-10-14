<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\User;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;   // ← 追加

class TaskController extends Controller
{
    public function index()
    {
        $user = Auth::user();           // ← 型が User|null と分かる
        if (!$user) abort(401);

        $tasks = Task::whereBelongsTo($user)->latest()->paginate(10);
        return view('tasks.index', compact('tasks'));
    }

    public function store(StoreTaskRequest $request)
    {
        /** @var User $user */        // ← ここで User 型だと明示
        $user = Auth::user();
        if (!$user) abort(401);

        $user->tasks()->create($request->validated());
        return redirect()->route('tasks.index')->with('status','Task created.');
    }

    public function show(Task $task)
    {
        $this->abortIfNotOwner($task);
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $this->abortIfNotOwner($task);
        return view('tasks.edit', compact('task'));
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $this->abortIfNotOwner($task);
        $task->update($request->validated());
        return redirect()->route('tasks.index')->with('status','Task updated.');
    }

    public function destroy(Task $task)
    {
        $this->abortIfNotOwner($task);
        $task->delete();
        return redirect()->route('tasks.index')->with('status','Task deleted.');
    }

    private function abortIfNotOwner(Task $task): void
    {
        $uid = Auth::id();              // ← これも Facade 経由に
        if (!$uid || $task->user_id !== $uid) {
            abort(403);
        }
    }
}
