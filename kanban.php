<?php
include 'verificar_sesion.php';
include 'firebase_functions.php';


// URL base de la colección de tareas en Firebase
$firebase_base_url_tareas = "https://ingeweb1-2eb3d-default-rtdb.firebaseio.com/tareas";

// Obtener las tareas desde Firebase
$tareas = obtenerDeFirebase("$firebase_base_url_tareas.json");

// Filtrar tareas según su estado
$tareasPendientes = array_filter($tareas, function ($tarea) {
    return $tarea['estado'] == 'Pendiente';
});
$tareasEnProgreso = array_filter($tareas, function ($tarea) {
    return $tarea['estado'] == 'En progreso';
});
$tareasCompletadas = array_filter($tareas, function ($tarea) {
    return $tarea['estado'] == 'Completada';
});

// Manejo de formulario para agregar tarea
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $estado = $_POST['estado'];

    if (!empty($nombre) && !empty($descripcion) && !empty($estado)) {
        $data = [
            "nombre" => $nombre,
            "descripcion" => $descripcion,
            "estado" => $estado,
        ];
        // Llamada a la función para guardar la tarea en Firebase
        guardarEnFirebase("$firebase_base_url_tareas.json", $data);
        header("Location: kanban.php?mensaje=Tarea creada correctamente");
        exit();
    } else {
        $mensaje = "Por favor, completa todos los campos.";
    }
}

// Función para actualizar el estado de la tarea
function actualizarEstadoTarea($tarea_id, $nuevo_estado)
{
    global $firebase_base_url_tareas;
    // Obtener la tarea actual
    $tarea = obtenerDeFirebase("$firebase_base_url_tareas/$tarea_id.json");
    $tarea['estado'] = $nuevo_estado;
    // Actualizar la tarea con el nuevo estado
    actualizarEnFirebase("$firebase_base_url_tareas/$tarea_id.json", $tarea);
}

// Manejo de actualización del estado de la tarea
if (isset($_GET['cambiar_estado']) && isset($_GET['tarea_id']) && isset($_GET['nuevo_estado'])) {
    actualizarEstadoTarea($_GET['tarea_id'], $_GET['nuevo_estado']);
    header("Location: kanban.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tablero Kanban</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
    body {
        background-color: #181818;
        color: #f5f5f5;
        font-family: 'Arial', sans-serif;
    }

    h1,
    h3 {
        color: #FFD700;
        /* Color dorado brillante */
        text-shadow: 1px 1px 3px #000;
    }

    h1 {
        font-size: 3rem;
        text-align: center;
        margin-bottom: 20px;
    }

    h3 {
        color: #FF6347;
        /* Color rojo anaranjado para los subtítulos */
        font-size: 2rem;
        margin-bottom: 10px;
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
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.5);
    }

    input,
    textarea {
        background-color: #343a40;
        color: #ffffff;
        border: 1px solid #5e5e5e;
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
    }

    a {
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }

    .column {
        background-color: #2c2c2c;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 20px;
        height: 400px;
        overflow-y: auto;
    }

    .list-group-item {
        background-color: #3a3a3a;
        border: none;
        margin-bottom: 10px;
        color: white;
    }
</style>

<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Tablero Kanban</h1>
        <div class="d-flex justify-content-end">
            <a href="logout.php" class="btn btn-danger">Cerrar Sesión</a>
        </div>

        <?php if (isset($mensaje)): ?>
            <div class="alert alert-warning"><?php echo $mensaje; ?></div>
        <?php endif; ?>

        <div class="row">
            <!-- Columna de Tareas Pendientes -->
            <div class="col-md-4">
                <h3>Pendientes</h3>
                <div class="list-group">
                    <?php foreach ($tareasPendientes as $tarea_id => $tarea): ?>
                        <div class="list-group-item">
                            <h5><?php echo $tarea['nombre']; ?></h5>
                            <p><?php echo $tarea['descripcion']; ?></p>
                            <a href="kanban.php?cambiar_estado=1&tarea_id=<?php echo $tarea_id; ?>&nuevo_estado=En progreso" class="btn btn-primary btn-sm">Mover a En Progreso</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Columna de Tareas En Progreso -->
            <div class="col-md-4">
                <h3>En Progreso</h3>
                <div class="list-group">
                    <?php foreach ($tareasEnProgreso as $tarea_id => $tarea): ?>
                        <div class="list-group-item">
                            <h5><?php echo $tarea['nombre']; ?></h5>
                            <p><?php echo $tarea['descripcion']; ?></p>
                            <a href="kanban.php?cambiar_estado=1&tarea_id=<?php echo $tarea_id; ?>&nuevo_estado=Completada" class="btn btn-success btn-sm">Mover a Completada</a>
                            <a href="kanban.php?cambiar_estado=1&tarea_id=<?php echo $tarea_id; ?>&nuevo_estado=Pendiente" class="btn btn-warning btn-sm">Mover a Pendiente</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Columna de Tareas Completadas -->
            <div class="col-md-4">
                <h3>Completadas</h3>
                <div class="list-group">
                    <?php foreach ($tareasCompletadas as $tarea_id => $tarea): ?>
                        <div class="list-group-item">
                            <h5><?php echo $tarea['nombre']; ?></h5>
                            <p><?php echo $tarea['descripcion']; ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>