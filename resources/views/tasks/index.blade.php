<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>ToDo List</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="bg-light py-5">

    <div class="container">
        <h1 class="mb-4 text-center">📝 To Do List</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('tasks.store') }}" method="POST" class="mb-4 d-flex gap-2">
            @csrf
            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                placeholder="Nouvelle tâche..." required>
            <button type="submit" class="btn btn-success">Ajouter</button>
            @error('title')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </form>

        <ul class="list-group">
            @forelse ($tasks as $task)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $task->title }}
                    <form action="{{ route('tasks.destroy', $task) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                    </form>
                </li>
            @empty
                <li class="list-group-item text-center text-muted">Aucune tâche pour le moment 🙌</li>
            @endforelse
        </ul>
    </div>

</body>

</html>