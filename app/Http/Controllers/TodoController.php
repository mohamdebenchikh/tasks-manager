<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'todo'=> 'required|string|max:255',
            'task_id'=>'required|numeric'
        ]);

        Todo::create($data);
        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(string $id)
    {
        $todo = Todo::findOrFail($id);
        $todo->update([
            'checked' => $todo->checked ? false : true
        ]);
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $todo = Todo::findOrFail($id);
        $todo->delete();
        return redirect()->back();
    }
}
