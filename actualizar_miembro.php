<?php
include 'verificar_sesion.php';
include 'firebase_functions.php';

$firebase_base_url = "https://ingeweb1-2eb3d-default-rtdb.firebaseio.com/miembros";

// Obtener el ID del miembro desde POST
$id = $_POST['id_miembro'] ?? '';

if (empty($id)) {
    header("Location: crear_miembro.php?mensaje=ID de miembro no válido");
    exit();
}

// Obtener los datos del miembro desde Firebase
$miembro = obtenerDeFirebase("$firebase_base_url/$id.json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $rol = $_POST['rol'] ?? '';

    if (!empty($nombre) && !empty($rol)) {
        $data = [
            "nombre" => $nombre,
            "rol" => $rol,
        ];
        $firebase_url = "$firebase_base_url/$id.json";
        actualizarEnFirebase($firebase_url, $data);

        // Redirigir con mensaje de éxito
        header("Location: crear_miembro.php?mensaje=Miembro actualizado correctamente");
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
    <title>Actualizar Miembro</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
        <!-- Botón de Cerrar Sesión -->
        <div class="d-flex justify-content-end">
            <a href="logout.php" class="btn btn-danger">Cerrar Sesión</a>
        </div>

        <h1 class="text-center mb-4">Actualizar Miembro</h1>

        <!-- Mensaje -->
        <?php if (isset($mensaje)): ?>
            <div class="alert alert-warning"><?php echo $mensaje; ?></div>
        <?php endif; ?>

        <!-- Formulario para actualizar el miembro -->
        <form method="POST" action="">
            <!-- Campo oculto para el ID del miembro -->
            <input type="hidden" name="id_miembro" value="<?php echo htmlspecialchars($id); ?>">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del Miembro</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($miembro['nombre'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label for="rol" class="form-label">Rol</label>
                <input type="text" class="form-control" id="rol" name="rol" value="<?php echo htmlspecialchars($miembro['rol'] ?? ''); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar Miembro</button>
            <a href="crear_miembro.php" class="btn btn-secondary">Volver</a>
        </form>
    </div>

    <!-- Bootstrap JS y Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>

</html>