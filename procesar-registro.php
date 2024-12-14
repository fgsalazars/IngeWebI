<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Información en Firebase</title>
    <!-- Enlaces a Bootstrap CSS y JS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <style>
        body {
            background-color: #121212; /* Fondo oscuro */
            color: #e0e0e0; /* Texto claro */
            font-family: Arial, sans-serif;
            text-align: center;
        }

        h4 {
            color: #ff9800; /* Título naranja */
        }

        .container {
            max-width: 1200px;
            margin-top: 50px;
        }

        .card {
            background-color: #1e1e1e; /* Fondo del contenedor */
            border-radius: 8px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.3);
        }

        .btn-primary {
            background-color: #0288d1; /* Azul más oscuro */
            border-color: #0277bd;
        }

        .btn-primary:hover {
            background-color: #0277bd; /* Hover azul más oscuro */
            border-color: #01579b;
        }

        .text-success {
            color: #4caf50; /* Verde de éxito */
        }

        .text-danger {
            color: #f44336; /* Rojo de error */
        }

        /* Estilo adaptativo */
        @media (max-width: 768px) {
            .container {
                padding-left: 15px;
                padding-right: 15px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="card-header text-center">
                <h4>Registro a Base de Datos Firebase</h4>
            </div>
            <?php
            // Recolectar información del formulario
            $nombre = $_POST["nombre"];
            $apellido = $_POST["apellido"];
            $email = $_POST["email"];
            $celular = $_POST["celular"];
            $usuario = $_POST["usuario"];
            $pass = $_POST["pass"];

            // Crear vector de almacenamiento en Firebase
            $data = json_encode([
                "Usuario" => $usuario,
                "Password" => $pass,
                "Nombre" => $nombre,
                "Apellido" => $apellido,
                "Email" => $email,
                "Celular" => $celular
            ]);

            // URL de Firebase Realtime Database
            $url = 'https://ingeweb1-2eb3d-default-rtdb.firebaseio.com/usuario.json';

            // Inicio de comunicación por cURL
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

            // Ejecutar la solicitud
            $response = curl_exec($ch);

            // Validar si ocurrió algún error
            if (curl_errno($ch)) {
                echo '<div class="text-center text-danger">Error: ' . curl_error($ch) . '</div>';
            } else {
                echo '
        <div class="text-center text-success">
            Usuario registrado correctamente.
            <br><br>
            <a href="iniciar-sesion.php" class="btn btn-primary">Iniciar Sesión</a>
        </div>
    ';
            }

            // Cerrar conexión cURL
            curl_close($ch);
            ?>
        </div>
    </div>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>

</body>

</html>
