<?php
include 'verificar_sesion.php';
include 'firebase_functions.php';

$firebase_base_url_tareas = "https://ingeweb1-2eb3d-default-rtdb.firebaseio.com/tareas";

// Verificar que los parámetros tarea_id y subtarea_id están presentes en la URL
if (isset($_GET['tarea_id']) && isset($_GET['subtarea_id'])) {
    $tarea_id = $_GET['tarea_id'];
    $subtarea_id = $_GET['subtarea_id'];

    // Obtener la tarea específica
    $tarea_ref = "$firebase_base_url_tareas/$tarea_id.json";
    $tarea = obtenerDeFirebase($tarea_ref);

    if ($tarea && isset($tarea['subtareas']) && isset($tarea['subtareas'][$subtarea_id])) {
        // Eliminar la subtarea de la tarea
        unset($tarea['subtareas'][$subtarea_id]);

        // Actualizar la tarea en Firebase
        actualizarEnFirebase($tarea_ref, $tarea);

        // Redirigir con mensaje de éxito
        header("Location: crear_tarea.php?id=$tarea_id&mensaje=Subtarea eliminada correctamente");
        exit();
    } else {
        // Si no se encuentra la subtarea, redirigir con mensaje de error
        header("Location: crear_tarea.php?id=$tarea_id&mensaje=Subtarea no encontrada");
        exit();
    }
} else {
    // Si no se pasan los parámetros correctamente
    header("Location: dashboard.php?mensaje=Parámetros inválidos");
    exit();
}
?>
