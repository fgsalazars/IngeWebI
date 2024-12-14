<?php
include 'verificar_sesion.php';
include 'firebase_functions.php';

$firebase_base_url_proyectos = "https://ingeweb1-2eb3d-default-rtdb.firebaseio.com/proyectos";
$firebase_base_url_tareas = "https://ingeweb1-2eb3d-default-rtdb.firebaseio.com/tareas";
$firebase_base_url_miembros = "https://ingeweb1-2eb3d-default-rtdb.firebaseio.com/miembros"; // Nuevo: URL de miembros

// Obtener los proyectos existentes en Firebase
$proyectos = obtenerDeFirebase("$firebase_base_url_proyectos.json");

// Obtener las tareas existentes
$tareas = obtenerDeFirebase("$firebase_base_url_tareas.json");

// Obtener los miembros existentes en Firebase
$miembros = obtenerDeFirebase("$firebase_base_url_miembros.json");

function agregarTareaAProyecto($proyecto_ref, $tarea_id)
{
    // Obtener los datos actuales del proyecto
    $proyecto_actual = obtenerDeFirebase($proyecto_ref);

    // Verificar si ya existe el campo "tareas", si no, inicializarlo como un array
    if (!isset($proyecto_actual['tareas'])) {
        $proyecto_actual['tareas'] = [];
    }

    // Agregar la tarea al array de tareas del proyecto
    array_push($proyecto_actual['tareas'], $tarea_id);

    // Actualizar el proyecto con la nueva lista de tareas
    actualizarEnFirebase($proyecto_ref, $proyecto_actual);
}

function obtenerSubtareasPorTarea($tarea_id)
{
    global $firebase_base_url_tareas;

    // Obtener las subtareas de Firebase
    $subtareas = obtenerDeFirebase("$firebase_base_url_tareas/$tarea_id/subtareas.json");

    // Si no hay subtareas, devolver un array vacío
    if (is_null($subtareas)) {
        return [];
    }

    return $subtareas;
}

// Manejo del formulario para crear tarea
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $estado = $_POST['estado'];
    $proyecto_id = $_POST['proyecto_id'];
    $miembro_id = $_POST['miembro_id']; // Nuevo: miembro asignado

    if (!empty($nombre) && !empty($descripcion) && !empty($estado) && !empty($proyecto_id) && !empty($miembro_id)) {
        $data = [
            "nombre" => $nombre,
            "descripcion" => $descripcion,
            "estado" => $estado,
            "miembro_asignado" => $miembro_id, // Guardar el miembro asignado
        ];
        $respuesta = guardarEnFirebase($firebase_base_url_tareas . ".json", $data);

        // Asociar la tarea al proyecto
        $tarea_id = $respuesta['name'];  // Firebase devuelve el ID de la tarea creada
        $proyecto_ref = $firebase_base_url_proyectos . "/$proyecto_id/tareas.json";
        agregarTareaAProyecto($proyecto_ref, $tarea_id);

        // Redirigir con mensaje de éxito
        header("Location: crear_tarea.php?mensaje=Tarea creada correctamente");
        exit();
    } else {
        $mensaje = "Por favor, completa todos los campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Tarea</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
            max-width: 800px;
        }

        a {
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-end">
            <a href="logout.php" class="btn btn-danger">Cerrar Sesión</a>
        </div>

        <h1 class="text-center mb-4">Crear Tarea</h1>

        <?php if (isset($mensaje)): ?>
            <div class="alert alert-warning"><?php echo $mensaje; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre de la Tarea</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" id="descripcion" name="descripcion" required></textarea>
            </div>
            <div class="mb-3">
                <label for="estado" class="form-label">Estado</label>
                <select class="form-control" id="estado" name="estado" required>
                    <option value="Pendiente">Pendiente</option>
                    <option value="En progreso">En progreso</option>
                    <option value="Completada">Completada</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="proyecto_id" class="form-label">Seleccionar Proyecto</label>
                <select class="form-control" id="proyecto_id" name="proyecto_id" required>
                    <?php foreach ($proyectos as $id => $proyecto): ?>
                        <option value="<?php echo $id; ?>"><?php echo $proyecto['nombre']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Nuevo: Campo para seleccionar miembro -->
            <div class="mb-3">
                <label for="miembro_id" class="form-label">Miembro Asignado</label>
                <select class="form-control" id="miembro_id" name="miembro_id" required>
                    <option value="">Selecciona un miembro</option>
                    <?php if ($miembros): ?>
                        <?php foreach ($miembros as $id => $miembro): ?>
                            <option value="<?php echo $id; ?>"><?php echo $miembro['nombre']; ?></option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="">No hay miembros disponibles</option>
                    <?php endif; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Crear Tarea</button>
            <a href="dashboard.php" class="btn btn-secondary">Volver</a>
        </form>

        <h2 class="mt-5">Listado de Tareas</h2>
        <?php if (empty($tareas)): ?>
            <div class="alert alert-info">No hay tareas creadas aún.</div>
        <?php else: ?>
            <ul class="list-group">
                <?php foreach ($tareas as $tarea_id => $tarea): ?>
                    <li class="list-group-item">
                        <strong><?php echo $tarea['nombre']; ?></strong> - Estado: <?php echo $tarea['estado']; ?>
                        <p><?php echo $tarea['descripcion']; ?></p>
                        <p><strong>Asignada a: </strong><?php echo isset($miembros[$tarea['miembro_asignado']]) ? $miembros[$tarea['miembro_asignado']]['nombre'] : 'Sin asignar'; ?></p>

                        <!-- Botones de acción -->
                        <a href="editar_tarea.php?id=<?php echo $tarea_id; ?>" class="btn btn-warning btn-sm">Editar</a>
                        <a href="eliminar_tarea.php?id=<?php echo $tarea_id; ?>" class="btn btn-danger btn-sm">Eliminar</a>
                        <a href="crear_subtarea.php?tarea_id=<?php echo $tarea_id; ?>" class="btn btn-success btn-sm">Crear Subtarea</a>

                        <!-- Mostrar subtareas asociadas a la tarea -->
                        <div class="mt-3">
                            <h5>Subtareas:</h5>
                            <?php
                            // Consulta para obtener las subtareas asociadas
                            $subtareas = obtenerSubtareasPorTarea($tarea_id);

                            if (empty($subtareas)): ?>
                                <p>No hay subtareas para esta tarea.</p>
                            <?php else: ?>
                                <ul class="list-group">
                                    <?php foreach ($subtareas as $subtarea_id => $subtarea): ?>
                                        <li class="list-group-item">
                                            <strong><?php echo $subtarea['nombre']; ?></strong> - Estado: <?php echo $subtarea['estado']; ?>
                                            <p><?php echo $subtarea['descripcion']; ?></p>
                                            <p><strong>Asignada a: </strong>
                                                <?php
                                                // Verifica si el miembro_id existe y está en la lista de miembros
                                                if (isset($subtarea['miembro_id']) && isset($miembros[$subtarea['miembro_id']])) {
                                                    echo $miembros[$subtarea['miembro_id']]['nombre'];
                                                } else {
                                                    echo 'Sin asignar';
                                                }
                                                ?>
                                            </p>
                                            <a href="editar_subtarea.php?tarea_id=<?php echo $tarea_id; ?>&subtarea_id=<?php echo $subtarea_id; ?>" class="btn btn-warning btn-sm">Editar</a>
                                            <a href="eliminar_subtarea.php?tarea_id=<?php echo $tarea_id; ?>&subtarea_id=<?php echo $subtarea_id; ?>" class="btn btn-danger btn-sm">Eliminar</a>
                                        </li>
                                    <?php endforeach; ?>

                                </ul>
                            <?php endif; ?>
                        </div>

                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</body>

</html>