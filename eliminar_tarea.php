<?php
include 'verificar_sesion.php';
include 'firebase_functions.php';

$firebase_base_url_tareas = "https://ingeweb1-2eb3d-default-rtdb.firebaseio.com/tareas";

// Obtener el ID de la tarea desde la URL
$tarea_id = $_GET['id'];

// Eliminar la tarea de Firebase
eliminarDeFirebase("$firebase_base_url_tareas/$tarea_id.json");

// Redirigir con mensaje de éxito
header("Location: crear_tarea.php?mensaje=Tarea eliminada correctamente");
exit();
?>
