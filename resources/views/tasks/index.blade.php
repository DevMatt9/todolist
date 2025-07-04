<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>ToDo List</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light py-5">

    <div class="container">
        <h1 class="mb-4 text-center">📝 To Do List</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row g-4">
            <!-- Formulaire -->
            <div class="col-12 col-md-4">
                <button id="toggleDarkMode" class="btn btn-secondary mb-3 w-100">🌙 Mode Sombre</button>

                <form action="{{ route('tasks.store') }}" method="POST" class="d-grid gap-2">
                    @csrf

                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                        placeholder="Nouvelle tâche..." required>

                    <input type="date" name="due_date" class="form-control" placeholder="Date limite">

                    <button type="submit" class="btn btn-success">Ajouter</button>

                    @error('title')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </form>
            </div>

            <!-- Liste des tâches -->
            <div class="col-12 col-md-8">
                <div class="table-responsive">
                    <p>{{ $tasksCount }} tâche(s) en cours</p>

                    <form method="POST" action="{{ route('tasks.toggleAll') }}" class="mb-3">
                        @csrf
                        <button type="submit" class="btn btn-warning">
                            @if ($tasks->where('completed', false)->count() > 0)
                                ✅ Tout marquer comme terminé
                            @else
                                🔄 Tout décocher
                            @endif
                        </button>
                    </form>

                    <table class="table table-hover align-middle bg-white rounded shadow-sm">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">✅</th>
                                <th scope="col">Tâche</th>
                                <th scope="col">Date</th>
                                <th scope="col" class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tasks as $task)
                                <tr class="{{ $task->completed ? 'table-success' : '' }}">
                                    <td>
                                        <form action="{{ route('tasks.toggle', $task) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="checkbox" onchange="this.form.submit()" {{ $task->completed ? 'checked' : '' }}>
                                        </form>
                                    </td>
                                    <td>
                                        <span
                                            class="{{ $task->completed ? 'text-decoration-line-through text-muted' : '' }}">
                                            {{ $task->title }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($task->due_date)
                                            <span
                                                class="{{ \Carbon\Carbon::parse($task->due_date)->isPast() && !$task->completed ? 'text-danger fw-bold' : '' }}">
                                                📅 {{ \Carbon\Carbon::parse($task->due_date)->format('d/m/Y') }}
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <button type="button" class="btn btn-outline-primary btn-sm"
                                                data-bs-toggle="modal" data-bs-target="#editModal-{{ $task->id }}">
                                                ✏️
                                            </button>

                                            <form method="POST" action="{{ route('tasks.destroy', $task->id) }}"
                                                onsubmit="return confirm('Voulez-vous vraiment supprimer cette tâche ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                                    🗑️
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Aucune tâche pour le moment 🙌</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @foreach ($tasks as $task)
        <div class="modal fade" id="editModal-{{ $task->id }}" tabindex="-1"
            aria-labelledby="editModalLabel-{{ $task->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content rounded shadow-sm">
                    <form action="{{ route('tasks.update', $task) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel-{{ $task->id }}">Modifier la tâche</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                        </div>

                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="title-{{ $task->id }}" class="form-label">Titre</label>
                                <input type="text" class="form-control" id="title-{{ $task->id }}" name="title"
                                    value="{{ $task->title }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="due_date-{{ $task->id }}" class="form-label">Date limite</label>
                                <input type="date" class="form-control" id="due_date-{{ $task->id }}" name="due_date"
                                    value="{{ $task->due_date }}">
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>