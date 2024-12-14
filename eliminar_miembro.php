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

// Eliminar miembro de Firebase
$firebase_url = "$firebase_base_url/$id.json";
eliminarDeFirebase($firebase_url);

// Redirigir con mensaje de éxito
header("Location: crear_miembro.php?mensaje=Miembro eliminado correctamente");
exit();
?>
