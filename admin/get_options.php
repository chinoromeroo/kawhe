<?php
require_once('check_auth.php');
require_once('../conexion.php');

if (isset($_GET['type'])) {
    $type = $_GET['type'];
    $options = [];
    
    if ($type === 'categoria') {
        // Secciones para el select de categorías
        $query = "SELECT id_seccion as id, nombre FROM secciones WHERE activo = 1";
    } elseif ($type === 'producto') {
        // Categorías para el select de productos
        $query = "SELECT id_categoria as id, nombre FROM categorias WHERE activo = 1";
    }
    
    $result = mysqli_query($conexion, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $options[] = $row;
    }
    
    header('Content-Type: application/json');
    echo json_encode($options);
    exit;
} 