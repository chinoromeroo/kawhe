<?php
require_once('check_auth.php');
require_once('../conexion.php');

// Ensure no output before headers
ob_start();

header('Content-Type: application/json');

try {
    if (!isset($_GET['type'])) {
        throw new Exception('Tipo no especificado');
    }

    $type = $_GET['type'];
    $options = [];
    
    if ($type === 'secciones') {
        // Secciones para el select de categorías
        $query = "SELECT id_seccion as id, nombre FROM secciones WHERE activo = 1";
    } elseif ($type === 'categorias') {
        // Categorías para el select de productos
        $query = "SELECT id_categoria as id, nombre FROM categorias WHERE activo = 1";
    } else {
        throw new Exception('Tipo no válido');
    }
    
    $result = mysqli_query($conexion, $query);
    if (!$result) {
        throw new Exception(mysqli_error($conexion));
    }
    
    while ($row = mysqli_fetch_assoc($result)) {
        $options[] = $row;
    }
    
    // Clear any previous output
    ob_clean();
    
    echo json_encode(['success' => true, 'data' => $options]);
} catch (Exception $e) {
    ob_clean();
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}