<?php
include 'verificar_sesion.php';
include 'firebase_functions.php';

$firebase_base_url_tareas = "https://ingeweb1-2eb3d-default-rtdb.firebaseio.com/tareas";
$firebase_base_url_miembros = "https://ingeweb1-2eb3d-default-rtdb.firebaseio.com/miembros";

// Cargar miembros desde Firebase
$miembros = json_decode(file_get_contents($firebase_base_url_miembros . ".json"), true);

// Verificar si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $estado = $_POST['estado'];
    $tarea_id = $_POST['tarea_id'];
    $miembro_id = $_POST['miembro_id'];  // Obtener el miembro asignado

    if (!empty($nombre) && !empty($descripcion) && !empty($estado) && !empty($tarea_id) && !empty($miembro_id)) {
        $data = [
            "nombre" => $nombre,
            "descripcion" => $descripcion,
            "estado" => $estado,
            "miembro_id" => $miembro_id,  // Guardar miembro asignado
        ];
        $respuesta = guardarEnFirebase($firebase_base_url_tareas . "/$tarea_id/subtareas.json", $data);

        // Redirigir con mensaje de éxito
        header("Location: crear_tarea.php?id=$tarea_id&mensaje=Subtarea creada correctamente");
        exit();
    } else {
        $mensaje = "Por favor, completa todos los campos.";
    }
}

$tarea_id = $_GET['tarea_id'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Subtarea</title>
    <!-- Cargar CSS de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
</head>
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

<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-end">
            <a href="logout.php" class="btn btn-danger">Cerrar Sesión</a>
        </div>

        <h1 class="text-center mb-4">Crear Subtarea</h1>

        <?php if (isset($mensaje)): ?>
            <div class="alert alert-warning"><?php echo $mensaje; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="hidden" name="tarea_id" value="<?php echo $tarea_id; ?>">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre de la Subtarea</label>
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
            <button type="submit" class="btn btn-primary">Crear Subtarea</button>
            <a href="crear_tarea.php?id=<?php echo $tarea_id; ?>" class="btn btn-secondary">Volver</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>

</html>