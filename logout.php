<?php
session_start(); // Inicia la sesión

// Destruir todas las variables de sesión
session_unset();

// Destruir la sesión
session_destroy();

// Redirigir al usuario a la página de inicio de sesión
header("Location: iniciar-sesion.php");
exit();
?>
