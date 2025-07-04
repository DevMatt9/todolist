<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

/**
 * Class TaskController
 *
 * Handles the CRUD operations for tasks.
 */
class TaskController extends Controller
{
    /**
     * Display a listing of the tasks.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $tasks = Task::all()->sortBy('due_date');
        $tasksCount = Task::where('completed', false)->count();
        return view('tasks.index', compact('tasks', 'tasksCount'));
    }

    /**
     * Store a newly created task in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|min:3',
            'due_date' => 'nullable|date',
        ]);

        Task::create([
            'title' => $request->title,
            'due_date' => $request->due_date,
        ]);

        return redirect()->back()->with('success', 'Tâche ajoutée !');
    }

    /**
     * Remove the specified task from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\RedirectResponse
     */

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->back()->with('success', 'Tâche supprimée.');
    }

    /**
     * Toggle the completion status of a task.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggle(Task $task)
    {
        $task->completed = !$task->completed;
        $task->save();

        return redirect()->back()->with('success', 'État de la tâche mis à jour.');
    }

    /**
     * Update the specified task in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'due_date' => 'nullable|date',
        ]);

        $task->update([
            'title' => $request->title,
            'due_date' => $request->due_date,
        ]);

        return redirect()->back()->with('success', 'Tâche mise à jour avec succès.');
    }

    /**
     * Toggle the completion status of all tasks.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleAll()
    {
        $incompleteCount = Task::where('completed', false)->count();

        if ($incompleteCount > 0) {
            Task::where('completed', false)->update(['completed' => true]);
            $message = 'Toutes les tâches ont été marquées comme terminées.';
        } else {
            Task::where('completed', true)->update(['completed' => false]);
            $message = 'Toutes les tâches ont été remises à non terminées.';
        }

        return redirect()->back()->with('success', $message);
    }
}
