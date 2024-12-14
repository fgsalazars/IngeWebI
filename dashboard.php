<?php include 'verificar_sesion.php'; ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Proyectos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* Estilo oscuro */
        body {
            background-color: #121212;
            color: #ffffff;
        }

        .navbar {
            background-color: #1e1e1e;
        }

        .navbar-brand {
            color: #ffffff;
            transition: color 0.3s ease;
        }

        .navbar-brand:hover {
            color: #6200ea;
        }

        .navbar-nav .nav-link {
            color: #ffffff;
        }

        .navbar-nav .nav-link:hover {
            color: #6200ea;
        }

        .container {
            background-color: #1e1e1e;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
        }

        h1 {
            color: #ffffff;
            text-align: center;
            margin-bottom: 30px;
        }

        .btn-lg {
            transition: background-color 0.3s ease;
        }

        .btn-primary {
            background-color: #6200ea;
            border-color: #6200ea;
        }

        .btn-primary:hover {
            background-color: #3700b3;
            border-color: #3700b3;
        }

        .btn-secondary {
            background-color: #03a9f4;
            border-color: #03a9f4;
        }

        .btn-secondary:hover {
            background-color: #0288d1;
            border-color: #0288d1;
        }

        .btn-success {
            background-color: #4caf50;
            border-color: #4caf50;
        }

        .btn-success:hover {
            background-color: #388e3c;
            border-color: #388e3c;
        }

        .btn-danger {
            background-color: #f44336;
            border-color: #f44336;
        }

        .btn-danger:hover {
            background-color: #d32f2f;
            border-color: #d32f2f;
        }

        .spinner {
            display: none;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .spinner.show {
            display: flex;
        }
    </style>
</head>

<body>
    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Gestión de Proyectos</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link btn btn-danger text-white" href="logout.php" onclick="return confirm('¿Estás seguro de que deseas cerrar sesión?')">Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1>Gestión de Proyectos</h1>

        <!-- Botones para las acciones principales -->
        <div class="d-grid gap-3 col-6 mx-auto">
            <a href="crear_proyectos.php" class="btn btn-primary btn-lg">
                <i class="bi bi-folder-plus"></i> Crear Proyecto
            </a>
            <a href="crear_miembro.php" class="btn btn-secondary btn-lg">
                <i class="bi bi-person-plus"></i> Crear Miembro del Equipo
            </a>
            <a href="crear_tarea.php" class="btn btn-success btn-lg">
                <i class="bi bi-list-check"></i> Crear Tarea
            </a>
            <a href="tablero_kanban.php" class="btn btn-danger btn-lg">
                <i class="bi bi-kanban"></i> Tablero Kanban
            </a>
        </div>
    </div>

    <!-- Spinner para carga -->
    <div class="spinner" id="spinner">
        <div class="spinner-border text-light" role="status">
            <span class="visually-hidden">Cargando...</span>
        </div>
    </div>

    <!-- Agregar Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>

    <!-- Script para mostrar el spinner -->
    <script>
        document.querySelectorAll('.btn').forEach(button => {
            button.addEventListener('click', () => {
                document.getElementById('spinner').classList.add('show');
            });
        });
    </script>
</body>

</html>