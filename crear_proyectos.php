<?php
include 'verificar_sesion.php';
include 'firebase_functions.php';

// URL Base de Firebase
$firebase_url = "https://ingeweb1-2eb3d-default-rtdb.firebaseio.com/proyectos";

// Manejo del formulario para crear un proyecto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear_proyecto'])) {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];

    // Validación básica
    if (!empty($nombre) && !empty($descripcion)) {
        $data = [
            "nombre" => $nombre,
            "descripcion" => $descripcion,
        ];
        guardarEnFirebase("$firebase_url.json", $data);
        $mensaje = "Proyecto creado exitosamente.";
    } else {
        $mensaje = "Por favor, completa todos los campos.";
    }
}

// Obtener la lista de proyectos
$proyectos = obtenerDeFirebase("$firebase_url.json");
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Proyectos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-color: #181818;
            color: #f5f5f5;
            font-family: 'Arial', sans-serif;
        }

        h1,
        h2 {
            color: #ffffff;
            text-shadow: 1px 1px 3px #000;
        }

        .table-dark {
            background-color: #212121;
            color: #f5f5f5;
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        .table-dark th,
        .table-dark td {
            vertical-align: middle;
            text-align: center;
            border-bottom: 1px solid #343a40;
            padding: 15px;
        }

        .table-dark tbody tr:hover {
            background-color: #2c2c2c;
        }

        .alert-info {
            background-color: #3a3a3a;
            color: #ffffff;
            border: 1px solid #5e5e5e;
        }

        .btn {
            border-radius: 50px;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            box-shadow: 0px 4px 6px rgba(0, 123, 255, 0.3);
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
            box-shadow: 0px 4px 6px rgba(220, 53, 69, 0.3);
        }

        .btn-danger:hover {
            background-color: #b21f2d;
            border-color: #b21f2d;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .btn-secondary:hover {
            background-color: #565e64;
            border-color: #545b62;
        }

        .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #212529;
        }

        .btn-warning:hover {
            background-color: #e0a800;
            border-color: #d39e00;
        }

        form {
            background-color: #212121;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.5);
        }

        input,
        textarea {
            background-color: #343a40;
            color: #ffffff;
            border: 1px solid #5e5e5e;
            width: 100%;
        }

        input:focus,
        textarea:focus {
            background-color: #495057;
            color: #ffffff;
            border-color: #007bff;
            outline: none;
            box-shadow: 0px 0px 5px rgba(0, 123, 255, 0.5);
        }

        table {
            margin-top: 20px;
            border-radius: 10px;
            overflow: hidden;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
        }

        a {
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Ajuste para los botones en fila */
        .acciones {
            display: flex;
            gap: 10px;
        }

        .acciones button {
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <!-- Botón de Cerrar Sesión -->
        <div class="d-flex justify-content-end">
            <a href="logout.php" class="btn btn-danger">Cerrar Sesión</a>
        </div>

        <h1 class="text-center mb-4">Crear Proyecto</h1>

        <!-- Mensaje -->
        <?php if (isset($mensaje)): ?>
            <div class="alert alert-info"><?php echo htmlspecialchars($mensaje); ?></div>
        <?php endif; ?>

        <!-- Formulario para crear un proyecto -->
        <form method="POST" action="">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del Proyecto</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" id="descripcion" name="descripcion" required></textarea>
            </div>
            <button type="submit" name="crear_proyecto" class="btn btn-primary">Crear Proyecto</button>
            <a href="dashboard.php" class="btn btn-secondary">Volver</a>
        </form>

        <!-- Listado de proyectos -->
        <h2 class="text-center my-4">Lista de Proyectos</h2>
        <table class="table table-bordered table-striped table-dark">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($proyectos): ?>
                    <?php foreach ($proyectos as $id => $proyecto): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($id); ?></td>
                            <td><?php echo htmlspecialchars($proyecto['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($proyecto['descripcion']); ?></td>
                            <td>
                                <!-- Contenedor de las acciones en fila -->
                                <div class="acciones">
                                    <!-- Botón Eliminar -->
                                    <form action="eliminar_proyecto.php" method="POST" class="d-inline">
                                        <input type="hidden" name="id_proyecto" value="<?php echo htmlspecialchars($id); ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                    </form>
                                    <!-- Botón Actualizar -->
                                    <form action="actualizar_proyecto.php" method="GET" class="d-inline">
                                        <input type="hidden" name="id_proyecto" value="<?php echo htmlspecialchars($id); ?>">
                                        <button type="submit" class="btn btn-warning btn-sm">Actualizar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">No hay proyectos disponibles</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>

</html>
