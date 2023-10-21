<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Str;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->status ?? false;
        $user_id = Auth::user()->id;
        if ($status) {
            $tasks = Task::with('todos')
                ->where('user_id', $user_id)
                ->where('status', $status)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            $tasks = Task::with('todos')
                ->where('user_id', $user_id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }
        return Inertia::render('Tasks/Index', ['tasks' => $tasks, 'status' => $status]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Tasks/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|unique:tasks',
            'notes' => 'nullable|string',
            'tags' => 'nullable|string',
            'status' => 'required|in:OPEN,IN_PROGRESS,DONE',
            'due_date' => 'nullable|date',
        ]);

        $data['slug'] = Str::slug($request->input('title'));
        $data['user_id'] = Auth::user()->id;

        $task = Task::create($data);
        return redirect()->to(route('tasks.index'))->with('success', "The $task->title task has been stored successfully.");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $task = Task::with('todos')->where('user_id',Auth::user()->id)->findOrFail($id);
        return Inertia::render('Tasks/Show', ['task' => $task]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $task = $this->getUserTask($id);
        return Inertia::render('Tasks/Edit', ['task' => $task]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'title' => 'required|string|unique:tasks,title,' . $id,
            'notes' => 'nullable|string',
            'tags' => 'nullable|string',
            'status' => 'required|in:OPEN,IN_PROGRESS,DONE',
            'due_date' => 'nullable|date',
        ]);

        $data['slug'] = Str::slug($request->input('title'));
        $task = $this->getUserTask($id);

        $task->update($data);
        return redirect()->to(route('tasks.index'))->with('success', "The $task->title task has been updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = $this->getUserTask($id);
        $task->delete();
        return redirect()->to(route('tasks.index'))->with('success', "The $task->title task has been deleted successfully.");
    }

    /**
     * Get a user-specific task.
     */
    private function getUserTask($id)
    {
        $user_id = Auth::user()->id;
        return Task::where('user_id', $user_id)->findOrFail($id);
    }
}
