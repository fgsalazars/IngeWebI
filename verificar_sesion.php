<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    // Si no hay sesión activa, redirigir al login
    header("Location: iniciar-sesion.php");
    exit();
}
?>
