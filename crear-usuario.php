<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Usuario</title>
    <!-- Enlaces a Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Enlace a la fuente Roboto -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #121212;
            /* Fondo oscuro */
            color: #e0e0e0;
            /* Texto claro */
            font-family: 'Roboto', sans-serif;
            /* Fuente Roboto */
            text-align: center;
            margin-top: 50px;
        }

        h2 {
            color: #ff9800;
            /* Título naranja */
        }

        .form-container {
            max-width: 400px;
            margin: auto;
            padding: 20px;
            background-color: #1e1e1e;
            /* Fondo del formulario oscuro */
            border-radius: 8px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.3);
        }

        .form-label {
            color: #e0e0e0;
            /* Etiquetas en color claro */
        }

        .form-control {
            background-color: #333;
            /* Fondo oscuro para los campos de texto */
            border-color: #555;
            /* Bordes oscuros */
            color: #e0e0e0;
            /* Texto claro en los campos de texto */
        }

        .form-control:focus {
            background-color: #444;
            /* Fondo más oscuro al hacer foco */
            border-color: #ff9800;
            /* Bordes en naranja cuando está enfocado */
        }

        .btn-primary {
            background-color: #6200ea;
            /* Botón morado */
            border-color: #3700b3;
        }

        .btn-primary:hover {
            background-color: #3700b3;
            /* Hover morado más oscuro */
            border-color: #6200ea;
        }

        /* Estilo adaptativo */
        @media (max-width: 768px) {
            .form-container {
                padding: 15px;
                max-width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h2>Crear Usuario</h2>
        <form action="procesar-registro.php" method="POST">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="mb-3">
                <label for="apellido" class="form-label">Apellido</label>
                <input type="text" class="form-control" id="apellido" name="apellido" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="celular" class="form-label">Celular</label>
                <input type="text" class="form-control" id="celular" name="celular" required>
            </div>
            <div class="mb-3">
                <label for="usuario" class="form-label">Usuario</label>
                <input type="text" class="form-control" id="usuario" name="usuario" required>
            </div>
            <div class="mb-3">
                <label for="pass" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="pass" name="pass" required>
            </div>
            <button type="submit" class="btn btn-primary">Registrar</button>
        </form>
    </div>

    <!-- Enlace a Bootstrap JS y dependencias (popper) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>