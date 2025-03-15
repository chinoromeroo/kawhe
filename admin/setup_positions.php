<?php
require_once('../conexion.php');

try {
    mysqli_begin_transaction($conexion);

    // Reiniciar posiciones de secciones
    $query = "SELECT id_seccion FROM secciones WHERE activo = 1 ORDER BY id_seccion";
    $result = mysqli_query($conexion, $query);
    $position = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        $query = "UPDATE secciones SET position = ? WHERE id_seccion = ?";
        $stmt = mysqli_prepare($conexion, $query);
        mysqli_stmt_bind_param($stmt, "ii", $position, $row['id_seccion']);
        mysqli_stmt_execute($stmt);
        $position++;
    }

    // Reiniciar posiciones de categorías por sección
    $query = "SELECT id_seccion FROM secciones WHERE activo = 1";
    $result = mysqli_query($conexion, $query);
    while ($seccion = mysqli_fetch_assoc($result)) {
        // Obtener todas las categorías de la sección
        $query = "SELECT id_categoria FROM categorias 
                  WHERE id_seccion = ? AND activo = 1 
                  ORDER BY id_categoria";
        $stmt = mysqli_prepare($conexion, $query);
        mysqli_stmt_bind_param($stmt, "i", $seccion['id_seccion']);
        mysqli_stmt_execute($stmt);
        $result_cat = mysqli_stmt_get_result($stmt);
        
        $position = 1;
        while ($categoria = mysqli_fetch_assoc($result_cat)) {
            $update = "UPDATE categorias SET position = ? WHERE id_categoria = ?";
            $stmt_update = mysqli_prepare($conexion, $update);
            mysqli_stmt_bind_param($stmt_update, "ii", $position, $categoria['id_categoria']);
            mysqli_stmt_execute($stmt_update);
            $position++;
        }
    }

    // Reiniciar posiciones de productos por categoría
    $query = "SELECT id_categoria FROM categorias WHERE activo = 1";
    $result = mysqli_query($conexion, $query);
    while ($categoria = mysqli_fetch_assoc($result)) {
        $query = "SELECT id_producto FROM productos 
                  WHERE id_categoria = ? AND activo = 1 
                  ORDER BY id_producto";
        $stmt = mysqli_prepare($conexion, $query);
        mysqli_stmt_bind_param($stmt, "i", $categoria['id_categoria']);
        mysqli_stmt_execute($stmt);
        $result_prod = mysqli_stmt_get_result($stmt);
        
        $position = 1;
        while ($producto = mysqli_fetch_assoc($result_prod)) {
            $update = "UPDATE productos SET position = ? WHERE id_producto = ?";
            $stmt_update = mysqli_prepare($conexion, $update);
            mysqli_stmt_bind_param($stmt_update, "ii", $position, $producto['id_producto']);
            mysqli_stmt_execute($stmt_update);
            $position++;
        }
    }

    mysqli_commit($conexion);
    echo "¡Posiciones reiniciadas correctamente!";
    
} catch (Exception $e) {
    mysqli_rollback($conexion);
    echo "Error: " . $e->getMessage();
}
?> 