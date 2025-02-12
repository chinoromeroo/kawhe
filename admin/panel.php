<?php
require_once('check_auth.php');
require_once('../conexion.php');

// Procesar actualizaciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'update_item':
                try {
                    // Validar datos
                    $table = mysqli_real_escape_string($conexion, $_POST['table']);
                    $id = (int)$_POST['id'];
                    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
                    
                    // Consulta según el tipo de tabla
                    switch ($table) {
                        case 'secciones':
                            $query = "UPDATE secciones SET nombre = ? WHERE id_seccion = ?";
                            $types = "si";
                            $params = array($nombre, $id);
                            break;
                            
                        case 'categorias':
                            $query = "UPDATE categorias SET nombre = ? WHERE id_categoria = ?";
                            $types = "si";
                            $params = array($nombre, $id);
                            break;
                            
                        case 'productos':
                            $precio = isset($_POST['precio']) ? floatval($_POST['precio']) : 0;
                            $descripcion = isset($_POST['descripcion']) ? mysqli_real_escape_string($conexion, $_POST['descripcion']) : null;
                            $query = "UPDATE productos SET nombre = ?, descripcion = ?, precio = ? WHERE id_producto = ?";
                            $types = "ssdi";
                            $params = array($nombre, $descripcion, $precio, $id);
                            break;
                            
                        default:
                            throw new Exception('Tipo de tabla no válido: ' . $table);
                    }
                    
                    $stmt = mysqli_prepare($conexion, $query);
                    if ($stmt === false) {
                        throw new Exception('Error en la preparación de la consulta: ' . mysqli_error($conexion));
                    }
                    
                    mysqli_stmt_bind_param($stmt, $types, ...$params);
                    
                    if (mysqli_stmt_execute($stmt)) {
                        $response = ['success' => true];
                    } else {
                        throw new Exception('Error al ejecutar la consulta: ' . mysqli_stmt_error($stmt));
                    }
                    
                    mysqli_stmt_close($stmt);
                    
                } catch (Exception $e) {
                    $response = ['success' => false, 'error' => $e->getMessage()];
                }
                
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            case 'add_item':
                try {
                    $table = mysqli_real_escape_string($conexion, $_POST['table']);
                    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
                    
                    switch ($table) {
                        case 'secciones':
                            $query = "INSERT INTO secciones (nombre, activo) VALUES (?, 1)";
                            $types = "s";
                            $params = array($nombre);
                            break;
                            
                        case 'categorias':
                            $id_seccion = (int)$_POST['id_seccion'];
                            $query = "INSERT INTO categorias (nombre, id_seccion, activo) VALUES (?, ?, 1)";
                            $types = "si";
                            $params = array($nombre, $id_seccion);
                            break;
                            
                        case 'productos':
                            $id_categoria = (int)$_POST['id_categoria'];
                            $precio = isset($_POST['precio']) ? floatval($_POST['precio']) : 0;
                            $descripcion = isset($_POST['descripcion']) ? mysqli_real_escape_string($conexion, $_POST['descripcion']) : null;
                            $query = "INSERT INTO productos (nombre, descripcion, precio, id_categoria, activo) VALUES (?, ?, ?, ?, 1)";
                            $types = "ssdi";
                            $params = array($nombre, $descripcion, $precio, $id_categoria);
                            break;
                            
                        default:
                            throw new Exception('Tipo de tabla no válido: ' . $table);
                    }
                    
                    $stmt = mysqli_prepare($conexion, $query);
                    if ($stmt === false) {
                        throw new Exception('Error en la preparación de la consulta: ' . mysqli_error($conexion));
                    }
                    
                    mysqli_stmt_bind_param($stmt, $types, ...$params);
                    
                    if (mysqli_stmt_execute($stmt)) {
                        $response = ['success' => true];
                    } else {
                        throw new Exception('Error al ejecutar la consulta: ' . mysqli_stmt_error($stmt));
                    }
                    
                    mysqli_stmt_close($stmt);
                    
                } catch (Exception $e) {
                    $response = ['success' => false, 'error' => $e->getMessage()];
                }
                
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
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
                        $response = ['success' => true];
                    } else {
                        throw new Exception('Error al ejecutar la consulta: ' . mysqli_stmt_error($stmt));
                    }
                    
                    mysqli_stmt_close($stmt);
                    
                } catch (Exception $e) {
                    $response = ['success' => false, 'error' => $e->getMessage()];
                }
                
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrativo - Kawhe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
                    <button class="btn btn-success" onclick="showAddModal('seccion')">
                        <i class="fas fa-plus"></i> Agregar Sección
                    </button>
                    <button class="btn btn-success" onclick="showAddModal('categoria')">
                        <i class="fas fa-plus"></i> Agregar Categoría
                    </button>
                    <button class="btn btn-success" onclick="showAddModal('producto')">
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
                        echo '<h3 class="categoria-title mb-3 editable" 
                                 data-type="categoria" 
                                 data-id="' . $categoria['id_categoria'] . '">' . $categoria['nombre'] . '</h3>';
                        
                        // Obtener productos
                        $query_productos = "SELECT * FROM productos WHERE id_categoria = {$categoria['id_categoria']} AND activo = 1";
                        $result_productos = mysqli_query($conexion, $query_productos);
                        
                        while($producto = mysqli_fetch_assoc($result_productos)) {
                            echo '<div class="menu-item editable" 
                                      data-type="producto" 
                                      data-id="' . $producto['id_producto'] . '">';
                            echo '<div class="producto-info">';
                            echo '<div class="producto-nombre">' . $producto['nombre'];
                            if(!empty($producto['descripcion'])) {
                                echo '<div class="producto-descripcion">' . $producto['descripcion'] . '</div>';
                            }
                            echo '</div>';
                            echo '</div>';
                            if($producto['precio'] > 0) {
                                echo '<div class="producto-precio">$' . number_format($producto['precio'], 0) . '</div>';
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
        document.addEventListener('DOMContentLoaded', function() {
            const editModal = new bootstrap.Modal(document.getElementById('editModal'));
            
            // Mostrar el modal de agregar
            window.showAddModal = async function(type) {
                const modalTitle = document.querySelector('.modal-title');
                modalTitle.textContent = `Agregar ${type.charAt(0).toUpperCase() + type.slice(1)}`;
                
                // Obtener secciones o categorías 
                let selectOptions = '';
                if (type === 'categoria' || type === 'producto') {
                    const response = await fetch(`get_options.php?type=${type}`);
                    const data = await response.json();
                    selectOptions = data.map(item => 
                        `<option value="${item.id}">${item.nombre}</option>`
                    ).join('');
                }
                
                const modalBody = document.querySelector('.modal-body');
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
                                <select class="form-control" name="id_categoria" required>
                                    <option value="">Seleccionar categoría</option>
                                    ${selectOptions}
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Descripción (opcional)</label>
                                <textarea class="form-control" name="descripcion" rows="2"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Precio</label>
                                <input type="number" step="1" class="form-control" name="precio" value="0">
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
                form.addEventListener('keypress', handleEnterKey);
                
                editModal.show();
            };
            
            // Manejo tecla Enter
            function handleEnterKey(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    this.dispatchEvent(new Event('submit'));
                }
            }
            
            // Manejar envío del formulario
            async function handleSubmit(e) {
                e.preventDefault();
                const formData = new FormData(this);
                
                try {
                    const response = await fetch('panel.php', {
                        method: 'POST',
                        body: formData
                    });
                    
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
            
            document.querySelectorAll('.editable').forEach(item => {
                item.addEventListener('click', function() {
                    const type = this.dataset.type;
                    const id = this.dataset.id;
                    let currentName, currentPrice, currentDescription;
                    
                    if (type === 'producto') {
                        // Obtener nombre, precio y descripción
                        currentName = this.querySelector('.producto-nombre').childNodes[0].textContent.trim();
                        const precioElement = this.querySelector('.producto-precio');
                        currentPrice = precioElement ? 
                            precioElement.textContent
                                .replace('$', '')
                                .replace(/,/g, '')
                                .replace(/\s/g, '')
                                .trim() : 
                            '0';
                        const descripcionElement = this.querySelector('.producto-descripcion');
                        currentDescription = descripcionElement ? descripcionElement.textContent.trim() : '';
                    } else {
                        currentName = this.textContent.trim();
                    }
                    
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
                                    <textarea class="form-control" name="descripcion" rows="2">${currentDescription}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Precio</label>
                                    <input type="number" step="1" class="form-control" name="precio" value="${currentPrice}">
                                </div>
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
                    
                    editModal.show();
                    
                    // Enter
                    const form = document.getElementById('editForm');
                    form.addEventListener('keypress', function(e) {
                        if (e.key === 'Enter' && !e.shiftKey) {
                            e.preventDefault(); 
                            form.dispatchEvent(new Event('submit'));
                        }
                    });
                    
                    form.addEventListener('submit', async function(e) {
                        e.preventDefault();
                        
                        const formData = new FormData(this);
                        try {
                            const response = await fetch('panel.php', {
                                method: 'POST',
                                body: formData
                            });
                            
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
                    });
                });
            });

            // Función de eliminar
            window.deleteItem = async function(type, id) {
                if (!confirm('¿Estás seguro de que deseas eliminar este elemento? Esta acción no se puede deshacer.')) {
                    return;
                }

                try {
                    const formData = new FormData();
                    formData.append('action', 'delete_item');
                    formData.append('table', type === 'seccion' ? 'secciones' : type === 'categoria' ? 'categorias' : 'productos');
                    formData.append('id', id);

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