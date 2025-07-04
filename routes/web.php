<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

// Page d'accueil (liste des tâches)
Route::get('/', [TaskController::class, 'index'])->name('tasks.index');

// Groupe de routes pour les tâches
Route::prefix('tasks')->name('tasks.')->group(function () {

    // Ajouter une tâche
    Route::post('/', [TaskController::class, 'store'])->name('store');

    // Marquer toutes les tâches comme terminées / non terminées
    Route::post('/toggle-all', [TaskController::class, 'toggleAll'])->name('toggleAll');

    // Tâches individuelles avec contrainte numérique
    Route::put('/{task}', [TaskController::class, 'update'])->whereNumber('task')->name('update');
    Route::delete('/{task}', [TaskController::class, 'destroy'])->whereNumber('task')->name('destroy');
    Route::patch('/{task}/toggle', [TaskController::class, 'toggle'])->whereNumber('task')->name('toggle');

});
