<?php
include 'verificar_sesion.php';
include 'firebase_functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_proyecto = $_POST['id_proyecto'];

    if (!empty($id_proyecto)) {
        $firebase_url = "https://ingeweb1-2eb3d-default-rtdb.firebaseio.com/proyectos/$id_proyecto.json";
        eliminarDeFirebase($firebase_url);
        header("Location: crear_proyectos.php?mensaje=Proyecto eliminado correctamente");
        exit();
    } else {
        echo "ID del proyecto no válido.";
    }
}
?>
