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
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-background"></div>
        <div class="hero-content">
            <img src="images/kawhe-logo.png" alt="Kawhe Logo" class="logo">
            <p class="hero-text">Siempre sos bienvenido a disfrutar de un buen café ☕️</p>
            <p class="hero-text">Todos los días de 8 a 20hs</p>
        </div>
    </section>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg sticky-top" id="menu-nav">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav">
                    <?php
                    // Obtener secciones para el nav
                    $query_nav = "SELECT * FROM secciones WHERE activo = 1";
                    $result_nav = mysqli_query($conexion, $query_nav);
                    
                    while($seccion = mysqli_fetch_assoc($result_nav)) {
                        echo '<li class="nav-item">';
                        echo '<a class="nav-link" href="#seccion-' . strtolower($seccion['nombre']) . '">' . $seccion['nombre'] . '</a>';
                        echo '</li>';
                    }
                    ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Menu Section -->
    <section class="menu-section">
        <div class="container">
            <?php
            // Obtener secciones
            $query_secciones = "SELECT * FROM secciones WHERE activo = 1";
            $result_secciones = mysqli_query($conexion, $query_secciones);

            while($seccion = mysqli_fetch_assoc($result_secciones)) {
                echo '<div class="row mb-5" id="seccion-' . strtolower($seccion['nombre']) . '">';
                echo '<div class="col-12"><h1 class="text-center mb-4">' . $seccion['nombre'] . '</h1></div>';

                // Obtener categorías de la sección
                $query_categorias = "SELECT * FROM categorias WHERE id_seccion = {$seccion['id_seccion']} AND activo = 1";
                $result_categorias = mysqli_query($conexion, $query_categorias);

                while($categoria = mysqli_fetch_assoc($result_categorias)) {
                    echo '<div class="col-12 col-md-6 col-lg-4 mb-4">';
                    echo '<div class="menu-category">';
                    echo '<h2>' . $categoria['nombre'] . '</h2>';

                    // Obtener productos de la categoría
                    $query_productos = "SELECT * FROM productos WHERE id_categoria = {$categoria['id_categoria']} AND activo = 1";
                    $result_productos = mysqli_query($conexion, $query_productos);

                    while($producto = mysqli_fetch_assoc($result_productos)) {
                        echo '<div class="menu-item">';
                        echo '<h3 class="h5">' . $producto['nombre'] . '</h3>';
                        if($producto['precio'] > 0) {
                            echo '<p class="mb-0">$' . number_format($producto['precio'], 2) . '</p>';
                        }
                        echo '</div>';
                    }

                    echo '</div>'; 
                    echo '</div>'; 
                }

                echo '</div>'; 
            }
            ?>
        </div>
    </section>

    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3284.3531833979914!2d-58.443261!3d-34.595229599999996!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95bcb5f5809d48e5%3A0x498411a94d1b2e66!2sThames%20520%2C%20C1414%20Villa%20Crespo%2C%20Cdad.%20Autónoma%20de%20Buenos%20Aires!5e0!3m2!1ses-419!2sar!4v1738279433830!5m2!1ses-419!2sar" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Add before closing body tag -->
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);
                
                if (targetElement) {
                    const navHeight = document.querySelector('#menu-nav').offsetHeight;
                    const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - navHeight;
                    
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>

    <!-- Add to your existing CSS -->
    <style>
        /* ... existing styles ... */

        #menu-nav {
            background-color: var(--color-verde-pastel);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        #menu-nav .nav-link {
            color: white;
            font-weight: 500;
            padding: 1rem 1.5rem;
            transition: all 0.3s ease;
        }

        #menu-nav .nav-link:hover {
            color: var(--color-crema);
            transform: translateY(-2px);
        }

        .navbar-toggler {
            border-color: white;
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 1%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        @media (max-width: 991.98px) {
            #menu-nav .navbar-collapse {
                background-color: var(--color-verde-pastel);
                padding: 1rem;
                border-radius: 0 0 10px 10px;
            }
        }
    </style>
</body>
</html>