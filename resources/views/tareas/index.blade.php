<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Tareas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Lista de Tareas</h1>
            @auth
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger">Cerrar Sesión</button>
                </form>
            @else
                <div>
                    <a href="{{ route('login') }}" class="btn btn-primary">Iniciar Sesión</a>
                    <a href="{{ route('register') }}" class="btn btn-outline-primary">Registrarse</a>
                </div>
            @endauth
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @auth
            <div class="card mb-4">
                <div class="card-body">
                    <form action="{{ route('tareas.store') }}" method="POST" class="d-flex gap-2">
                        @csrf
                        <div class="flex-grow-1">
                            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" 
                                   placeholder="Nueva tarea" value="{{ old('nombre') }}" required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Agregar</button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    @if($tareas->isEmpty())
                        <p class="text-center text-muted">No hay tareas pendientes</p>
                    @else
                        <ul class="list-group">
                            @foreach($tareas as $tarea)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-2">
                                        <form action="{{ route('tareas.update', $tarea->id) }}" method="POST" class="me-2">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="completada" value="{{ $tarea->completada ? 0 : 1 }}">
                                            <button type="submit" class="btn btn-sm {{ $tarea->completada ? 'btn-success' : 'btn-outline-success' }}">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </form>
                                        <span class="{{ $tarea->completada ? 'text-decoration-line-through text-muted' : '' }}">
                                            {{ $tarea->nombre }}
                                        </span>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('tareas.edit', $tarea->id) }}" class="btn btn-sm btn-warning">Editar</a>
                                        <form action="{{ route('tareas.destroy', $tarea->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar esta tarea?')">Eliminar</button>
                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        @else
            <div class="alert alert-info">
                <h4 class="alert-heading">¡Bienvenido!</h4>
                <p>Para gestionar tus tareas, por favor inicia sesión o regístrate.</p>
            </div>
        @endauth
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
</body>
</html>