<?php
require_once('check_auth.php');
require_once('../conexion.php');

// Procesar actualizaciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Limpiar cualquier salida anterior
    ob_clean();
    
    // Establecer headers
    header('Content-Type: application/json');
    
    $action = $_POST['action'] ?? '';
    
    try {
        if (empty($action)) {
            throw new Exception('Acción no especificada');
        }
        
        switch($action) {
            case 'add_item':
                $table = $_POST['table'] ?? '';
                if (empty($table)) {
                    throw new Exception('Tabla no especificada');
                }
                
                $nombre = mysqli_real_escape_string($conexion, $_POST['nombre'] ?? '');
                if (empty($nombre)) {
                    throw new Exception('Nombre es requerido');
                }
                
                switch($table) {
                    case 'secciones':
                        $query = "INSERT INTO secciones (nombre, activo) VALUES (?, 1)";
                        $stmt = mysqli_prepare($conexion, $query);
                        mysqli_stmt_bind_param($stmt, "s", $nombre);
                        break;
                        
                    case 'categorias':
                        $id_seccion = intval($_POST['id_seccion'] ?? 0);
                        if ($id_seccion <= 0) {
                            throw new Exception('Sección inválida');
                        }
                        
                        $query = "INSERT INTO categorias (nombre, id_seccion, activo) VALUES (?, ?, 1)";
                        $stmt = mysqli_prepare($conexion, $query);
                        mysqli_stmt_bind_param($stmt, "si", $nombre, $id_seccion);
                        break;
                        
                    case 'productos':
                        $id_categoria = intval($_POST['id_categoria'] ?? 0);
                        if ($id_categoria <= 0) {
                            throw new Exception('Categoría inválida');
                        }
                        
                        $descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion'] ?? '');
                        
                        // Verificar si es categoría de café
                        $check_query = "SELECT nombre FROM categorias WHERE id_categoria = ?";
                        $check_stmt = mysqli_prepare($conexion, $check_query);
                        mysqli_stmt_bind_param($check_stmt, "i", $id_categoria);
                        mysqli_stmt_execute($check_stmt);
                        $result_check = mysqli_stmt_get_result($check_stmt);
                        $categoria = mysqli_fetch_assoc($result_check);
                        
                        if ($categoria['nombre'] === 'Cafés') {
                            $precio_chico = floatval($_POST['precio_chico'] ?? 0);
                            $precio_mediano = floatval($_POST['precio_mediano'] ?? 0);
                            $precio_grande = floatval($_POST['precio_grande'] ?? 0);
                            $precio_extra_grande = floatval($_POST['precio_extra_grande'] ?? 0);
                            
                            $query = "INSERT INTO productos (nombre, descripcion, id_categoria, precio_chico, precio_mediano, precio_grande, precio_extra_grande, activo) 
                                     VALUES (?, ?, ?, ?, ?, ?, ?, 1)";
                            $stmt = mysqli_prepare($conexion, $query);
                            mysqli_stmt_bind_param($stmt, "ssidddd", 
                                $nombre,
                                $descripcion,
                                $id_categoria,
                                $precio_chico,
                                $precio_mediano,
                                $precio_grande,
                                $precio_extra_grande
                            );
                        } else {
                            $precio = floatval($_POST['precio'] ?? 0);
                            
                            $query = "INSERT INTO productos (nombre, descripcion, id_categoria, precio, activo) 
                                     VALUES (?, ?, ?, ?, 1)";
                            $stmt = mysqli_prepare($conexion, $query);
                            mysqli_stmt_bind_param($stmt, "ssid", 
                                $nombre,
                                $descripcion,
                                $id_categoria,
                                $precio
                            );
                        }
                        break;
                        
                    default:
                        throw new Exception('Tipo de tabla no válido');
                }
                
                if (!mysqli_stmt_execute($stmt)) {
                    throw new Exception(mysqli_stmt_error($stmt));
                }
                
                echo json_encode(['success' => true]);
                break;
                
            case 'update_item':
                $table = $_POST['table'];
                $id = intval($_POST['id']);
                
                if ($table === 'productos') {
                    // Verificar si es un café
                    $check_query = "SELECT c.nombre FROM productos p 
                                  JOIN categorias c ON p.id_categoria = c.id_categoria 
                                  WHERE p.id_producto = ?";
                    $stmt_check = mysqli_prepare($conexion, $check_query);
                    mysqli_stmt_bind_param($stmt_check, "i", $id);
                    mysqli_stmt_execute($stmt_check);
                    $result_check = mysqli_stmt_get_result($stmt_check);
                    $categoria = mysqli_fetch_assoc($result_check);
                    
                    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
                    $descripcion = isset($_POST['descripcion']) ? mysqli_real_escape_string($conexion, $_POST['descripcion']) : '';
                    
                    if ($categoria['nombre'] === 'Cafés') {
                        $precio_chico = isset($_POST['precio_chico']) ? floatval($_POST['precio_chico']) : 0;
                        $precio_mediano = isset($_POST['precio_mediano']) ? floatval($_POST['precio_mediano']) : 0;
                        $precio_grande = isset($_POST['precio_grande']) ? floatval($_POST['precio_grande']) : 0;
                        $precio_extra_grande = isset($_POST['precio_extra_grande']) ? floatval($_POST['precio_extra_grande']) : 0;
                        
                        $query = "UPDATE productos SET 
                                 nombre = ?,
                                 descripcion = ?,
                                 precio_chico = ?,
                                 precio_mediano = ?,
                                 precio_grande = ?,
                                 precio_extra_grande = ?
                                 WHERE id_producto = ?";
                                 
                        $stmt = mysqli_prepare($conexion, $query);
                        mysqli_stmt_bind_param($stmt, "ssddddi", 
                            $nombre,
                            $descripcion,
                            $precio_chico,
                            $precio_mediano,
                            $precio_grande,
                            $precio_extra_grande,
                            $id
                        );
                    } else {
                        $precio = isset($_POST['precio']) ? floatval($_POST['precio']) : 0;
                        
                        $query = "UPDATE productos SET 
                                 nombre = ?,
                                 descripcion = ?,
                                 precio = ?
                                 WHERE id_producto = ?";
                                 
                        $stmt = mysqli_prepare($conexion, $query);
                        mysqli_stmt_bind_param($stmt, "ssdi", 
                            $nombre,
                            $descripcion,
                            $precio,
                            $id
                        );
                    }
                } else if ($table === 'secciones') {
                    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
                    $query = "UPDATE secciones SET nombre = ? WHERE id_seccion = ?";
                    $stmt = mysqli_prepare($conexion, $query);
                    mysqli_stmt_bind_param($stmt, "si", $nombre, $id);
                } else if ($table === 'categorias') {
                    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
                    $query = "UPDATE categorias SET nombre = ? WHERE id_categoria = ?";
                    $stmt = mysqli_prepare($conexion, $query);
                    mysqli_stmt_bind_param($stmt, "si", $nombre, $id);
                }
                
                if (!mysqli_stmt_execute($stmt)) {
                    throw new Exception(mysqli_stmt_error($stmt));
                }
                
                echo json_encode(['success' => true]);
                break;
                
            case 'delete_item':
                try {
                    $table = mysqli_real_escape_string($conexion, $_POST['table']);
                    $id = (int)$_POST['id'];
                    
                    // Columna ID
                    $id_column = '';
                    switch ($table) {
                        case 'secciones':
                            $id_column = 'id_seccion';
                            // Verificar categorías
                            $check_query = "SELECT COUNT(*) as count FROM categorias WHERE id_seccion = ? AND activo = 1";
                            break;
                        case 'categorias':
                            $id_column = 'id_categoria';
                            // Verificar productos
                            $check_query = "SELECT COUNT(*) as count FROM productos WHERE id_categoria = ? AND activo = 1";
                            break;
                        case 'productos':
                            $id_column = 'id_producto';
                            $check_query = null;
                            break;
                        default:
                            throw new Exception('Tipo de tabla no válido');
                    }
                    
                    // Verificar dependencias
                    if ($check_query) {
                        $check_stmt = mysqli_prepare($conexion, $check_query);
                        mysqli_stmt_bind_param($check_stmt, "i", $id);
                        mysqli_stmt_execute($check_stmt);
                        $check_result = mysqli_stmt_get_result($check_stmt);
                        $row = mysqli_fetch_assoc($check_result);
                        
                        if ($row['count'] > 0) {
                            throw new Exception('No se puede eliminar porque tiene elementos asociados. Elimina primero los elementos dependientes.');
                        }
                    }
                    
                    // Eliminación lógica
                    $query = "UPDATE $table SET activo = 0 WHERE $id_column = ?";
                    $stmt = mysqli_prepare($conexion, $query);
                    
                    if ($stmt === false) {
                        throw new Exception('Error en la preparación de la consulta');
                    }
                    
                    mysqli_stmt_bind_param($stmt, "i", $id);
                    
                    if (mysqli_stmt_execute($stmt)) {
                        echo json_encode(['success' => true]);
                    } else {
                        throw new Exception('Error al ejecutar la consulta: ' . mysqli_stmt_error($stmt));
                    }
                    
                    mysqli_stmt_close($stmt);
                    
                } catch (Exception $e) {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
                }
                break;
                
            default:
                throw new Exception('Acción no válida');
        }
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrativo - Kawhe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg nav-panel">
        <div class="container">
            <img src="../images/LOGOTIPO-KAWHE.png" alt="Logo Kawhe" class="nav-logo">
            
            <!-- Botón hamburguesa -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarPanel">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse justify-content-end" id="navbarPanel">
                <div class="d-flex gap-2">
                    <a href="../index.php" class="btn btn-volver">Volver al Menú</a>
                    <a href="logout.php" class="btn btn-danger">Cerrar Sesión</a>
                </div>
            </div>
        </div>
    </nav>
    
    <main>
        <section class="menu-section">
            <div class="container">
                <h1 class="text-center panel-title">Gestión del Menú</h1>
                <p class="text-center mb-4 panel-subtitle">Haz clic en cualquier elemento para editarlo</p>
                
                <!-- Botones de acción -->
                <div class="d-flex justify-content-center gap-3 mb-4">
                    <button class="btn btn-success" id="btnAddSeccion">
                        <i class="fas fa-plus"></i> Agregar Sección
                    </button>
                    <button class="btn btn-success" id="btnAddCategoria">
                        <i class="fas fa-plus"></i> Agregar Categoría
                    </button>
                    <button class="btn btn-success" id="btnAddProducto">
                        <i class="fas fa-plus"></i> Agregar Producto
                    </button>
                </div>
                
                <?php
                // Obtener secciones
                $query_secciones = "SELECT * FROM secciones WHERE activo = 1";
                $result_secciones = mysqli_query($conexion, $query_secciones);
                
                echo '<div class="row">';
                
                while($seccion = mysqli_fetch_assoc($result_secciones)) {
                    $columnClass = ($seccion['nombre'] == 'Deli') ? 'col-12' : 'col-md-6';
                    
                    echo '<div class="' . $columnClass . ' mb-5">';
                    echo '<h2 class="menu-title text-center mb-4 editable" 
                             data-type="seccion" 
                             data-id="' . $seccion['id_seccion'] . '">' . $seccion['nombre'] . '</h2>';
                    
                    if($seccion['nombre'] == 'Deli') {
                        echo '<div class="row">';
                    }
                    
                    // Obtener categorías
                    $query_categorias = "SELECT * FROM categorias WHERE id_seccion = {$seccion['id_seccion']} AND activo = 1";
                    $result_categorias = mysqli_query($conexion, $query_categorias);
                    
                    while($categoria = mysqli_fetch_assoc($result_categorias)) {
                        if($seccion['nombre'] == 'Deli') {
                            echo '<div class="col-md-6">';
                        }
                        
                        echo '<div class="menu-category mb-4">';
                        
                        // Mostrar header de categoría
                        if($categoria['nombre'] === 'Cafés') {
                            echo '<div class="categoria-header d-flex justify-content-between align-items-center">';
                            echo '<h3 class="categoria-title mb-0 editable" data-type="categoria" data-id="' . $categoria['id_categoria'] . '">' . $categoria['nombre'] . '</h3>';
                            echo '<div class="coffee-sizes">';
                            echo '<i class="fas fa-coffee coffee-icon size-s"></i>';
                            echo '<i class="fas fa-coffee coffee-icon size-m"></i>';
                            echo '<i class="fas fa-coffee coffee-icon size-l"></i>';
                            echo '<i class="fas fa-coffee coffee-icon size-xl"></i>';
                            echo '</div>';
                            echo '</div>';
                        } else {
                            echo '<h3 class="categoria-title mb-3 editable" data-type="categoria" data-id="' . $categoria['id_categoria'] . '">' . $categoria['nombre'] . '</h3>';
                        }
                        
                        // Obtener productos
                        $query_productos = "SELECT * FROM productos WHERE id_categoria = {$categoria['id_categoria']} AND activo = 1";
                        $result_productos = mysqli_query($conexion, $query_productos);
                        
                        while($producto = mysqli_fetch_assoc($result_productos)) {
                            echo '<div class="menu-item editable" data-type="producto" data-id="' . $producto['id_producto'] . '">';
                            echo '<div class="producto-info">';
                            echo '<div class="producto-nombre">' . $producto['nombre'] . '</div>';
                            if(!empty($producto['descripcion'])) {
                                echo '<div class="producto-descripcion">' . $producto['descripcion'] . '</div>';
                            }
                            echo '</div>';
                            
                            if($categoria['nombre'] === 'Cafés') {
                                echo '<div class="precios-cafe">';
                                echo '<div class="precio-size">' . ($producto['precio_chico'] > 0 ? '$' . number_format($producto['precio_chico'], 0) : '-') . '</div>';
                                echo '<div class="precio-size">' . ($producto['precio_mediano'] > 0 ? '$' . number_format($producto['precio_mediano'], 0) : '-') . '</div>';
                                echo '<div class="precio-size">' . ($producto['precio_grande'] > 0 ? '$' . number_format($producto['precio_grande'], 0) : '-') . '</div>';
                                echo '<div class="precio-size">' . ($producto['precio_extra_grande'] > 0 ? '$' . number_format($producto['precio_extra_grande'], 0) : '-') . '</div>';
                                echo '</div>';
                            } else {
                                if($producto['precio'] > 0) {
                                    echo '<div class="producto-precio">$' . number_format($producto['precio'], 0) . '</div>';
                                }
                            }
                            echo '</div>';
                        }
                        
                        echo '</div>';
                        
                        if($seccion['nombre'] == 'Deli') {
                            echo '</div>';
                        }
                    }
                    
                    if($seccion['nombre'] == 'Deli') {
                        echo '</div>';
                    }
                    
                    echo '</div>';
                }
                
                echo '</div>';
                ?>
            </div>
        </section>
    </main>

    <!-- Modal para agregar/editar -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Formulario -->
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Función global para mostrar el modal
        let editModal;
        
        document.addEventListener('DOMContentLoaded', function() {
            editModal = new bootstrap.Modal(document.getElementById('editModal'));
            
            // Event listeners para los botones de agregar
            document.getElementById('btnAddSeccion').addEventListener('click', () => showAddModal('seccion'));
            document.getElementById('btnAddCategoria').addEventListener('click', () => showAddModal('categoria'));
            document.getElementById('btnAddProducto').addEventListener('click', () => showAddModal('producto'));
            
            // Función para manejar el envío del formulario
            async function handleSubmit(e) {
                e.preventDefault();
                const formData = new FormData(e.target);
                
                try {
                    const response = await fetch('panel.php', {
                        method: 'POST',
                        body: formData
                    });
                    
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        throw new Error('La respuesta del servidor no es JSON válido');
                    }
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        location.reload();
                    } else {
                        alert('Error al guardar los cambios: ' + (result.error || 'Error desconocido'));
                        console.error('Error details:', result);
                    }
                } catch (error) {
                    console.error('Error completo:', error);
                    alert('Error al procesar la solicitud: ' + error.message);
                }
            }
            
            // Mostrar el modal de agregar
            async function showAddModal(type) {
                const modalTitle = document.querySelector('.modal-title');
                modalTitle.textContent = `Agregar ${type.charAt(0).toUpperCase() + type.slice(1)}`;
                
                const modalBody = document.querySelector('.modal-body');
                
                // Obtener secciones para el select de categorías
                let selectOptions = '';
                if (type === 'categoria') {
                    try {
                        const response = await fetch('get_options.php?type=secciones');
                        const result = await response.json();
                        if (result.success) {
                            selectOptions = result.data.map(item => 
                                `<option value="${item.id}">${item.nombre}</option>`
                            ).join('');
                        } else {
                            throw new Error(result.error || 'Error al obtener secciones');
                        }
                    } catch (error) {
                        console.error('Error al obtener secciones:', error);
                        selectOptions = '<option value="">Error al cargar secciones</option>';
                    }
                }
                
                // Obtener categorías para el select de productos
                let categoriaOptions = '';
                if (type === 'producto') {
                    try {
                        const response = await fetch('get_options.php?type=categorias');
                        const result = await response.json();
                        if (result.success) {
                            categoriaOptions = result.data.map(item => 
                                `<option value="${item.id}">${item.nombre}</option>`
                            ).join('');
                        } else {
                            throw new Error(result.error || 'Error al obtener categorías');
                        }
                    } catch (error) {
                        console.error('Error al obtener categorías:', error);
                        categoriaOptions = '<option value="">Error al cargar categorías</option>';
                    }
                }
                
                modalBody.innerHTML = `
                    <form id="addForm">
                        <input type="hidden" name="action" value="add_item">
                        <input type="hidden" name="table" value="${type === 'seccion' ? 'secciones' : type === 'categoria' ? 'categorias' : 'productos'}">
                        
                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="nombre" required>
                        </div>
                        
                        ${type === 'categoria' ? `
                            <div class="mb-3">
                                <label class="form-label">Sección</label>
                                <select class="form-control" name="id_seccion" required>
                                    <option value="">Seleccionar sección</option>
                                    ${selectOptions}
                                </select>
                            </div>
                        ` : ''}
                        
                        ${type === 'producto' ? `
                            <div class="mb-3">
                                <label class="form-label">Categoría</label>
                                <select class="form-control" name="id_categoria" required onchange="handleCategoriaChange(this)">
                                    <option value="">Seleccionar categoría</option>
                                    ${categoriaOptions}
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Descripción (opcional)</label>
                                <textarea class="form-control" name="descripcion" rows="2"></textarea>
                            </div>
                            <div id="precios-container">
                                <div class="mb-3">
                                    <label class="form-label">Precio</label>
                                    <input type="number" step="1" class="form-control" name="precio" value="0">
                                </div>
                            </div>
                        ` : ''}
                        
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                `;
                
                const form = document.getElementById('addForm');
                form.addEventListener('submit', handleSubmit);
                
                editModal.show();
            }
            
            // Función para manejar el cambio de categoría
            window.handleCategoriaChange = function(select) {
                const preciosContainer = document.getElementById('precios-container');
                if (select.options[select.selectedIndex].text === 'Cafés') {
                    preciosContainer.innerHTML = `
                        <div class="mb-3">
                            <label class="form-label">Precio Chico</label>
                            <input type="number" step="1" class="form-control" name="precio_chico" value="0">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Precio Mediano</label>
                            <input type="number" step="1" class="form-control" name="precio_mediano" value="0">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Precio Grande</label>
                            <input type="number" step="1" class="form-control" name="precio_grande" value="0">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Precio Extra Grande</label>
                            <input type="number" step="1" class="form-control" name="precio_extra_grande" value="0">
                        </div>
                    `;
                } else {
                    preciosContainer.innerHTML = `
                        <div class="mb-3">
                            <label class="form-label">Precio</label>
                            <input type="number" step="1" class="form-control" name="precio" value="0">
                        </div>
                    `;
                }
            };
            
            // Event listeners para elementos editables
            document.querySelectorAll('.editable').forEach(item => {
                item.addEventListener('click', async function() {
                    const type = this.dataset.type;
                    const id = this.dataset.id;
                    let currentName, currentPrice, currentDescription;
                    
                    // Determinar si el producto está en la categoría Cafés
                    let isInCoffeeCategory = false;
                    if (type === 'producto') {
                        const categoryTitle = this.closest('.menu-category').querySelector('.categoria-title');
                        isInCoffeeCategory = categoryTitle && categoryTitle.textContent.trim() === 'Cafés';
                        
                        // Obtener los precios actuales
                        currentName = this.querySelector('.producto-nombre').childNodes[0].textContent.trim();
                        const descripcionElement = this.querySelector('.producto-descripcion');
                        currentDescription = descripcionElement ? descripcionElement.textContent.trim() : '';
                        
                        if (isInCoffeeCategory) {
                            const preciosElements = this.querySelectorAll('.precio-size');
                            currentPriceChico = preciosElements[0] ? preciosElements[0].textContent.replace('$', '').replace(/,/g, '').trim() : '0';
                            currentPriceMediano = preciosElements[1] ? preciosElements[1].textContent.replace('$', '').replace(/,/g, '').trim() : '0';
                            currentPriceGrande = preciosElements[2] ? preciosElements[2].textContent.replace('$', '').replace(/,/g, '').trim() : '0';
                            currentPriceExtraGrande = preciosElements[3] ? preciosElements[3].textContent.replace('$', '').replace(/,/g, '').trim() : '0';
                        } else {
                            const precioElement = this.querySelector('.producto-precio');
                            currentPrice = precioElement ? precioElement.textContent.replace('$', '').replace(/,/g, '').trim() : '0';
                        }
                    } else {
                        currentName = this.textContent.trim();
                    }
                    
                    const modalTitle = document.querySelector('.modal-title');
                    modalTitle.textContent = `Editar ${type.charAt(0).toUpperCase() + type.slice(1)}`;
                    
                    const modalBody = document.querySelector('.modal-body');
                    modalBody.innerHTML = `
                        <form id="editForm">
                            <input type="hidden" name="action" value="update_item">
                            <input type="hidden" name="table" value="${type === 'seccion' ? 'secciones' : type === 'categoria' ? 'categorias' : 'productos'}">
                            <input type="hidden" name="id" value="${id}">
                            
                            <div class="mb-3">
                                <label class="form-label">Nombre</label>
                                <input type="text" class="form-control" name="nombre" value="${currentName}" required>
                            </div>
                            
                            ${type === 'producto' ? `
                                <div class="mb-3">
                                    <label class="form-label">Descripción</label>
                                    <textarea class="form-control" name="descripcion" rows="2">${currentDescription || ''}</textarea>
                                </div>
                                ${isInCoffeeCategory ? `
                                    <div class="mb-3">
                                        <label class="form-label">Precio Chico</label>
                                        <input type="number" step="1" class="form-control" name="precio_chico" value="${currentPriceChico || 0}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Precio Mediano</label>
                                        <input type="number" step="1" class="form-control" name="precio_mediano" value="${currentPriceMediano || 0}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Precio Grande</label>
                                        <input type="number" step="1" class="form-control" name="precio_grande" value="${currentPriceGrande || 0}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Precio Extra Grande</label>
                                        <input type="number" step="1" class="form-control" name="precio_extra_grande" value="${currentPriceExtraGrande || 0}">
                                    </div>
                                ` : `
                                    <div class="mb-3">
                                        <label class="form-label">Precio</label>
                                        <input type="number" step="1" class="form-control" name="precio" value="${currentPrice || 0}">
                                    </div>
                                `}
                            ` : ''}
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <button type="button" class="btn btn-danger" onclick="deleteItem('${type}', ${id})">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Guardar</button>
                                </div>
                            </div>
                        </form>
                    `;
                    
                    document.getElementById('editForm').addEventListener('submit', handleSubmit);
                    editModal.show();
                });
            });
            
            // Función de eliminar
            window.deleteItem = async function(type, id) {
                if (!confirm('¿Estás seguro de que deseas eliminar este elemento? Esta acción no se puede deshacer.')) {
                    return;
                }
                
                const formData = new FormData();
                formData.append('action', 'delete_item');
                formData.append('table', type === 'seccion' ? 'secciones' : type === 'categoria' ? 'categorias' : 'productos');
                formData.append('id', id);
                
                try {
                    const response = await fetch('panel.php', {
                        method: 'POST',
                        body: formData
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        location.reload();
                    } else {
                        alert('Error al eliminar: ' + (result.error || 'Error desconocido'));
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error al procesar la solicitud: ' + error.message);
                }
            };
        });
    </script>
</body>
</html>