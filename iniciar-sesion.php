<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <!-- Enlaces a Bootstrap CSS y JS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-color: #121212;
            /* Fondo oscuro */
            color: #ffffff;
            /* Texto claro */
            font-family: 'Roboto', sans-serif;
            /* Fuente moderna */
        }

        .container {
            background-color: #1e1e1e;
            /* Fondo del formulario */
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.4);
            /* Sombra más sutil */
        }

        h3 {
            color: #bb86fc;
            /* Título con tono púrpura */
            font-weight: bold;
            margin-bottom: 20px;
        }

        .form-label {
            color: #e0e0e0;
            /* Etiquetas claras */
        }

        input.form-control {
            background-color: #2a2a2a;
            /* Fondo de inputs */
            color: #ffffff;
            /* Texto de inputs */
            border: 1px solid #444444;
            /* Bordes definidos */
            border-radius: 5px;
        }

        input.form-control:focus {
            border-color: #bb86fc;
            /* Resaltado al enfocar */
            box-shadow: 0 0 5px rgba(187, 134, 252, 0.5);
            /* Brillo suave */
        }

        .btn-primary {
            background-color: #6200ea;
            border-color: #6200ea;
            font-size: 1rem;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.2s;
        }

        .btn-primary:hover {
            background-color: #3700b3;
            transform: scale(1.05);
            /* Efecto hover */
        }

        .btn-primary:active {
            background-color: #1a0080;
            /* Color al presionar */
        }

        .alert-danger {
            background-color: #f44336;
            /* Fondo rojo vibrante */
            color: #ffffff;
            border-radius: 5px;
            padding: 10px;
            margin-top: 20px;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        @media (max-width: 576px) {
            h3 {
                font-size: 1.5rem;
            }

            .btn-primary {
                font-size: 0.9rem;
            }
        }
    </style>

</head>

<body>
    <div class="container mt-5">
        <h3 class="text-center">Inicio de Sesión</h3>
        <?php
        session_start(); // Inicia la sesión

        // Verificar si ya hay una sesión activa
        if (isset($_SESSION['usuario'])) {
            header("Location: dashboard.php"); // Redirige al dashboard
            exit();
        }

        // Procesar el formulario de inicio de sesión
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Recolectar datos del formulario
            $usuario = $_POST["usuario"];
            $pass = $_POST["pass"];

            // URL de Firebase Realtime Database
            $url = 'https://ingeweb1-2eb3d-default-rtdb.firebaseio.com/usuario.json';

            // Comunicación por cURL
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Ejecutar la solicitud GET
            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                echo '<div class="alert alert-danger text-center">Error al conectar con Firebase: ' . curl_error($ch) . '</div>';
            } else {
                // Decodificar la respuesta JSON
                $users = json_decode($response, true);

                // Verificar si la decodificación fue exitosa y si hay datos
                if (is_array($users)) {
                    $found = false;

                    // Verificar las credenciales
                    foreach ($users as $user) {
                        if ($user['Usuario'] == $usuario && $user['Password'] == $pass) {
                            $found = true;
                            $_SESSION['usuario'] = $usuario; // Guardar usuario en la sesión
                            header("Location: dashboard.php"); // Redirigir al dashboard
                            exit();
                        }
                    }

                    if (!$found) {
                        echo '<div class="alert alert-danger text-center">Usuario o contraseña incorrectos.</div>';
                    }
                } else {
                    echo '<div class="alert alert-danger text-center">No se encontraron usuarios o la respuesta de Firebase es inválida.</div>';
                }
            }

            // Cerrar cURL
            curl_close($ch);
        }
        ?>
        <form action="iniciar-sesion.php" method="POST" class="mt-4">
            <div class="mb-3">
                <label for="usuario" class="form-label">Usuario</label>
                <input type="text" name="usuario" id="usuario" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="pass" class="form-label">Contraseña</label>
                <input type="password" name="pass" id="pass" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>

</html>