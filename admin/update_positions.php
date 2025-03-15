<?php
require_once('check_auth.php');
require_once('../conexion.php');

header('Content-Type: application/json');

try {
    // Obtener datos del request
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['type']) || !isset($data['oldIndex']) || !isset($data['newIndex']) || !isset($data['id'])) {
        throw new Exception('Faltan parámetros requeridos');
    }
    
    $type = $data['type'];
    $oldIndex = $data['oldIndex'];
    $newIndex = $data['newIndex'];
    $id = intval($data['id']);
    
    // Iniciar transacción
    mysqli_begin_transaction($conexion);
    
    // Determinar la tabla y columna ID basado en el tipo
    switch ($type) {
        case 'seccion':
            $table = 'secciones';
            $id_column = 'id_seccion';
            $where_clause = "activo = 1";
            break;
        case 'categoria':
            $table = 'categorias';
            $id_column = 'id_categoria';
            
            // Obtener la sección de la categoría
            $query = "SELECT id_seccion FROM categorias WHERE id_categoria = ?";
            $stmt = mysqli_prepare($conexion, $query);
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $categoria = mysqli_fetch_assoc($result);
            
            if (!$categoria) {
                throw new Exception('Categoría no encontrada');
            }
            
            // Obtener todas las categorías de la sección en su orden actual
            $query = "SELECT $id_column FROM $table 
                     WHERE id_seccion = ? AND activo = 1 
                     ORDER BY position, $id_column";
            $stmt = mysqli_prepare($conexion, $query);
            mysqli_stmt_bind_param($stmt, "i", $categoria['id_seccion']);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            $items = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $items[] = $row[$id_column];
            }
            
            // Remover el elemento movido y reinsertarlo en la nueva posición
            $moving_item = array_splice($items, $oldIndex, 1)[0];
            array_splice($items, $newIndex, 0, [$moving_item]);
            
            // Actualizar posiciones
            foreach ($items as $index => $item_id) {
                $position = $index + 1;
                $update_query = "UPDATE $table SET position = ? WHERE $id_column = ?";
                $stmt = mysqli_prepare($conexion, $update_query);
                mysqli_stmt_bind_param($stmt, "ii", $position, $item_id);
                
                if (!mysqli_stmt_execute($stmt)) {
                    throw new Exception("Error actualizando posición para $id_column = $item_id");
                }
            }
            break;
        case 'producto':
            $table = 'productos';
            $id_column = 'id_producto';
            
            // Obtener la categoría del producto
            $query = "SELECT id_categoria FROM productos WHERE id_producto = ?";
            $stmt = mysqli_prepare($conexion, $query);
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $producto = mysqli_fetch_assoc($result);
            
            if (!$producto) {
                throw new Exception('Producto no encontrado');
            }
            
            $where_clause = "id_categoria = {$producto['id_categoria']} AND activo = 1";
            break;
        default:
            throw new Exception('Tipo no válido');
    }
    
    // Confirmar transacción
    mysqli_commit($conexion);
    
    echo json_encode(['success' => true]);
    
} catch (Exception $e) {
    // Revertir transacción en caso de error
    mysqli_rollback($conexion);
    
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?> 