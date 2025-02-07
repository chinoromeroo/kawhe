<?php
require_once('check_auth.php');
require_once('../conexion.php');

// Procesar actualizaciones si hay POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'update_item':
                try {
                    // Validar los datos recibidos
                    $table = mysqli_real_escape_string($conexion, $_POST['table']);
                    $id = (int)$_POST['id'];
                    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
                    
                    // Construir la consulta según el tipo de tabla
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
                            $query = "UPDATE productos SET nombre = ?, precio = ? WHERE id_producto = ?";
                            $types = "sdi";
                            $params = array($nombre, $precio, $id);
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
    <style>
        .editable:hover {
            background-color: rgba(194,206,184,0.2);
            cursor: pointer;
        }
        
        .menu-item.editing {
            background-color: rgba(194,206,184,0.3);
            padding: 10px;
            border-radius: 5px;
        }
        
        .edit-form {
            margin-top: 10px;
        }
        
        .edit-buttons {
            margin-top: 10px;
            display: flex;
            gap: 10px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Kawhe Admin</a>
            <div>
                <a href="logout.php" class="btn btn-danger">Cerrar Sesión</a>
            </div>
        </div>
    </nav>
    
    <main>
        <section class="menu-section">
            <div class="container">
                <h1 class="text-center mb-4">Gestión del Menú</h1>
                <p class="text-center mb-4">Haz clic en cualquier elemento para editarlo</p>
                
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

    <!-- Modal para edición -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- El formulario se insertará aquí dinámicamente -->
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editModal = new bootstrap.Modal(document.getElementById('editModal'));
            
            document.querySelectorAll('.editable').forEach(item => {
                item.addEventListener('click', function() {
                    const type = this.dataset.type;
                    const id = this.dataset.id;
                    let currentName, currentPrice;
                    
                    if (type === 'producto') {
                        currentName = this.querySelector('.producto-nombre').textContent.trim();
                        const precioElement = this.querySelector('.producto-precio');
                        currentPrice = precioElement ? 
                            precioElement.textContent
                                .replace('$', '')
                                .replace(/,/g, '') 
                                .replace(/\s/g, '')
                                .trim() : 
                            '0';
                    } else {
                        // Para secciones y categorías, usar el texto directamente
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
                                    <label class="form-label">Precio</label>
                                    <input type="number" step="1" class="form-control" name="precio" value="${currentPrice}">
                                </div>
                            ` : ''}
                            
                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>
                        </form>
                    `;
                    
                    editModal.show();
                    
                    document.getElementById('editForm').addEventListener('submit', async function(e) {
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
        });
    </script>
</body>
</html>