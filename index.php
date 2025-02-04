<?php

include("conexion.php");

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Kawhe Café & Deli</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
</head>
<body>
    <section class="hero-section">
        <div class="hero-background"></div>
        <div class="hero-content">
            <img src="images/kawhe-logo.jpg" alt="Kawhe Logo" class="logo">
            <p class="hero-text">Siempre sos bienvenido a disfrutar de un buen café ☕️</p>
            <p class="hero-text">Todos los días de 8 a 20hs</p>
        </div>
    </section>

    <nav class="navbar navbar-expand-lg sticky-top" id="menu-nav">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <img src="images/LOGO-KAWHE-MIN.png" alt="Logo Kawhe" class="nav-logo">
            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav">
                    <?php
                    // Obtener secciones para el nav
                    $query_nav = "SELECT * FROM secciones WHERE activo = 1";
                    $result_nav = mysqli_query($conexion, $query_nav);
                    
                    while($seccion = mysqli_fetch_assoc($result_nav)) {
                        echo '<li class="nav-item">';
                        echo '<a class="nav-link" href="#' . strtolower($seccion['nombre']) . '">' . $seccion['nombre'] . '</a>';
                        echo '</li>';
                    }
                    ?>
                </ul>
            </div>
        </div>
    </nav>

    <section class="menu-section">
        <div class="container">
            <?php
            // Obtener secciones
            $query_secciones = "SELECT * FROM secciones WHERE activo = 1";
            $result_secciones = mysqli_query($conexion, $query_secciones);
            
            echo '<div class="row">';
            
            while($seccion = mysqli_fetch_assoc($result_secciones)) {
                $columnClass = ($seccion['nombre'] == 'Deli') ? 'col-12' : 'col-md-6';
                
                echo '<div id="' . strtolower($seccion['nombre']) . '" class="' . $columnClass . ' mb-5">';
                echo '<h2 class="menu-title text-center mb-4">' . $seccion['nombre'] . '</h2>';
                
                if($seccion['nombre'] == 'Deli') {
                    echo '<div class="row">';
                }
                
                // Obtener categorías de la sección
                $query_categorias = "SELECT * FROM categorias WHERE id_seccion = {$seccion['id_seccion']} AND activo = 1";
                $result_categorias = mysqli_query($conexion, $query_categorias);
                
                while($categoria = mysqli_fetch_assoc($result_categorias)) {
                    if($seccion['nombre'] == 'Deli') {
                        echo '<div class="col-md-6">';
                    }
                    
                    echo '<div class="menu-category mb-4">';
                    echo '<h3 class="categoria-title mb-3">' . $categoria['nombre'] . '</h3>';
                    
                    // Obtener productos de la categoría
                    $query_productos = "SELECT * FROM productos WHERE id_categoria = {$categoria['id_categoria']} AND activo = 1";
                    $result_productos = mysqli_query($conexion, $query_productos);
                    
                    while($producto = mysqli_fetch_assoc($result_productos)) {
                        echo '<div class="menu-item">';
                        echo '<div class="producto-nombre">' . $producto['nombre'];
                        if(!empty($producto['descripcion'])) {
                            echo '<div class="producto-descripcion">' . $producto['descripcion'] . '</div>';
                        }
                        echo '</div>';
                        if($producto['precio'] > 0) {
                            echo '<div class="producto-precio">( $ ' . number_format($producto['precio'], 0) . ' )</div>';
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

    <section class="ubicacion-section">
        <div class="container">
            <h2 class="menu-title text-center mb-4">Ubicación</h2>
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="mapa-container">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3284.3531833979914!2d-58.443261!3d-34.595229599999996!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95bcb5f5809d48e5%3A0x498411a94d1b2e66!2sThames%20520%2C%20C1414%20Villa%20Crespo%2C%20Cdad.%20Autónoma%20de%20Buenos%20Aires!5e0!3m2!1ses-419!2sar!4v1738279433830!5m2!1ses-419!2sar" 
                            width="100%" 
                            height="450" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                    <div class="direccion-container text-center mt-3">
                        <p class="direccion-texto">Thames 520, Villa Crespo</p>
                        <p class="horario-texto">Abierto todos los días de 8 a 20hs</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href');
                    const targetElement = document.querySelector(targetId);
                    
                    if (targetElement) {
                        const navHeight = document.querySelector('#menu-nav').offsetHeight;
                        const targetPosition = targetElement.getBoundingClientRect().top + window.scrollY - navHeight;
                        
                        window.scrollTo({
                            top: targetPosition,
                            behavior: 'smooth'
                        });
                    }
                });
            });
        });
    </script>

</body>
</html>