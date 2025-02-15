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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header>
        <section class="hero-section">
            <div class="hero-background"></div>
            <div class="hero-content">
                <img src="images/ISOLOGO-KAWHE.png" alt="Kawhe Logo" class="logo">
                <p class="hero-text">Siempre sos bienvenido a disfrutar de un buen café ☕️</p>
                <p class="hero-text"><strong>Todos los días de 8 a 20hs</strong></p>
            </div>
        </section>
    
        <nav class="navbar navbar-expand-lg navbar-light" id="menu-nav">
            <div class="container">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <img src="images/LOGOTIPO-KAWHE.png" alt="Logo Kawhe" class="nav-logo">
                <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                    <ul class="navbar-nav">
                        <?php
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
    </header>

    <main>
        <section class="menu-section">
            <div class="container">
                <?php
                // Secciones
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
                    
                    // Categorías
                    $query_categorias = "SELECT * FROM categorias WHERE id_seccion = {$seccion['id_seccion']} AND activo = 1";
                    $result_categorias = mysqli_query($conexion, $query_categorias);
                    
                    while($categoria = mysqli_fetch_assoc($result_categorias)) {
                        if($seccion['nombre'] == 'Deli') {
                            echo '<div class="col-md-6">';
                        }
                        
                        echo '<div class="menu-category mb-4">';
                        echo "<!-- Debug: Categoría actual: '" . $categoria['nombre'] . "' -->";
                        if($categoria['nombre'] === 'Cafés') {
                            echo '<div class="categoria-header">';
                            echo '<h3 class="categoria-title mb-0">' . $categoria['nombre'] . '</h3>';
                            echo '<div class="coffee-sizes">';
                            echo '<i class="fas fa-coffee coffee-icon size-s"></i>';
                            echo '<i class="fas fa-coffee coffee-icon size-m"></i>';
                            echo '<i class="fas fa-coffee coffee-icon size-l"></i>';
                            echo '<i class="fas fa-coffee coffee-icon size-xl"></i>';
                            echo '</div>';
                            echo '</div>';
                        } else {
                            echo '<h3 class="categoria-title mb-3">' . $categoria['nombre'] . '</h3>';
                        }
                        
                        // Productos
                        $query_productos = "SELECT id_producto, nombre, descripcion, precio, 
                                              precio_chico, precio_mediano, precio_grande, precio_extra_grande 
                                       FROM productos 
                                       WHERE id_categoria = {$categoria['id_categoria']} 
                                       AND activo = 1";
                        $result_productos = mysqli_query($conexion, $query_productos);
                        
                        while($producto = mysqli_fetch_assoc($result_productos)) {
                            echo '<div class="menu-item">';
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
        
        <section class="sobre-kawhe">
            <div class="container">
                <h2 class="menu-title text-center mb-4">Sobre nosotros</h2>
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="sobre-kawhe-content">
                            <p class="sobre-kawhe-destacado">Kahwe es café en Maorí</p>
                            
                            <div class="sobre-kawhe-texto">
                                <p>La cultura Maorí se basa en la reciprocidad, el respeto, la hospitalidad y el equilibrio.</p>
                                <p>Buscamos honrar sus valores en nuestra cafetería.</p>
                                
                                <p class="mb-4">Descubrí el espíritu de la hospitalidad Maorí en Kawhe ❤</p>
                                
                                <p>Nuestro cafe es un espacio pensado para compartir historias, sabores y tradiciones 🌿</p>
                                
                                <p>Por eso, nos inspiramos en los valores maoríes para que te sientas como en casa 🏠🤎</p>
                                
                                <p class="sobre-kawhe-cta">¡Vení a probar el mejor café!</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="instagram-grid">
            <div class="container">
                <h2 class="menu-title text-center mb-4">Seguinos en Instagram</h2>
                <div class="row g-4 justify-content-center">
                    <div class="col-md-6 col-lg-4">
                        <div class="instagram-item">
                            <a href="https://www.instagram.com/reel/DFf1SZKxEYP/" target="_blank">
                                <img src="images/post1.jpg" alt="Kawhe Instagram Post" class="img-fluid">
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="instagram-item">
                            <a href="https://www.instagram.com/reel/DE_AeJuSHeP/" target="_blank">
                                <img src="images/post2.jpg" alt="Kawhe Instagram Post" class="img-fluid">
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="instagram-item">
                            <a href="https://www.instagram.com/reel/DEiARQbRxQi/" target="_blank">
                                <img src="images/post3.jpg" alt="Kawhe Instagram Post" class="img-fluid">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <section class="galeria-section">
            <div class="container">
                <h2 class="menu-title text-center mb-4">Conocenos</h2>
                
                <!--------------------------- Carrusel Desktop ------------------------->
                <div id="galeriaCarousel" class="carousel slide d-none d-md-block">
                    <!-- Indicadores -->
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#galeriaCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#galeriaCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                        <button type="button" data-bs-target="#galeriaCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                    </div>
                    
                    <!-- Slides -->
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <div class="galeria-item">
                                        <img src="images/galeria/galeria1.jpg" alt="Kawhe Galería 1" class="img-fluid">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="galeria-item">
                                        <img src="images/galeria/galeria2.jpg" alt="Kawhe Galería 2" class="img-fluid">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="galeria-item">
                                        <img src="images/galeria/galeria3.jpg" alt="Kawhe Galería 3" class="img-fluid">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="carousel-item">
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <div class="galeria-item">
                                        <img src="images/galeria/galeria4.jpg" alt="Kawhe Galería 4" class="img-fluid">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="galeria-item">
                                        <img src="images/galeria/galeria5.jpg" alt="Kawhe Galería 5" class="img-fluid">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="galeria-item">
                                        <img src="images/galeria/galeria6.jpg" alt="Kawhe Galería 6" class="img-fluid">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="carousel-item">
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <div class="galeria-item">
                                        <img src="images/galeria/galeria7.jpg" alt="Kawhe Galería 7" class="img-fluid">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="galeria-item">
                                        <img src="images/galeria/galeria8.jpg" alt="Kawhe Galería 8" class="img-fluid">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="galeria-item">
                                        <img src="images/galeria/galeria9.jpg" alt="Kawhe Galería 9" class="img-fluid">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Controles -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#galeriaCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#galeriaCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Siguiente</span>
                    </button>
                </div>
                

                <!------------------------------ Carrusel Mobile ------------------------->
                <div id="galeriaCarouselMobile" class="carousel slide d-md-none">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#galeriaCarouselMobile" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#galeriaCarouselMobile" data-bs-slide-to="1" aria-label="Slide 2"></button>
                        <button type="button" data-bs-target="#galeriaCarouselMobile" data-bs-slide-to="2" aria-label="Slide 3"></button>
                        <button type="button" data-bs-target="#galeriaCarouselMobile" data-bs-slide-to="3" aria-label="Slide 4"></button>
                        <button type="button" data-bs-target="#galeriaCarouselMobile" data-bs-slide-to="4" aria-label="Slide 5"></button>
                        <button type="button" data-bs-target="#galeriaCarouselMobile" data-bs-slide-to="5" aria-label="Slide 6"></button>
                        <button type="button" data-bs-target="#galeriaCarouselMobile" data-bs-slide-to="6" aria-label="Slide 7"></button>
                        <button type="button" data-bs-target="#galeriaCarouselMobile" data-bs-slide-to="7" aria-label="Slide 8"></button>
                        <button type="button" data-bs-target="#galeriaCarouselMobile" data-bs-slide-to="8" aria-label="Slide 9"></button>
                    </div>
                    
                    <!-- Slides -->
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <div class="galeria-item">
                                <img src="images/galeria/galeria1.jpg" alt="Kawhe Galería 1" class="img-fluid">
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="galeria-item">
                                <img src="images/galeria/galeria2.jpg" alt="Kawhe Galería 2" class="img-fluid">
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="galeria-item">
                                <img src="images/galeria/galeria3.jpg" alt="Kawhe Galería 3" class="img-fluid">
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="galeria-item">
                                <img src="images/galeria/galeria4.jpg" alt="Kawhe Galería 4" class="img-fluid">
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="galeria-item">
                                <img src="images/galeria/galeria5.jpg" alt="Kawhe Galería 5" class="img-fluid">
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="galeria-item">
                                <img src="images/galeria/galeria6.jpg" alt="Kawhe Galería 6" class="img-fluid">
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="galeria-item">
                                <img src="images/galeria/galeria7.jpg" alt="Kawhe Galería 7" class="img-fluid">
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="galeria-item">
                                <img src="images/galeria/galeria8.jpg" alt="Kawhe Galería 8" class="img-fluid">
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="galeria-item">
                                <img src="images/galeria/galeria9.jpg" alt="Kawhe Galería 9" class="img-fluid">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Controles -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#galeriaCarouselMobile" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#galeriaCarouselMobile" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Siguiente</span>
                    </button>
                </div>
            </div>
        </section>
        
        <section class="ubicacion-section">
            <div class="container">
                <h2 class="menu-title text-center mb-4">Dónde estamos</h2>
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
    </main>

    <footer class="footer">
            <div class="container text-center">
                <p class="footer-title">¡Seguinos en nuestras redes sociales!</p>
                <div class="social-links">
                    <a href="https://www.instagram.com/cafekawhe/" target="_blank" title="Síguenos en Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="https://www.facebook.com/profile.php?id=61569199813804" target="_blank" title="Síguenos en Facebook">
                        <i class="fab fa-facebook"></i>
                    </a>
                </div>
                <p class="copyright"> Copyright 2025 © Kawhe Café & Deli - Todos los derechos reservados.</p>
            </div>
        </footer>
        
        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const nav = document.getElementById('menu-nav');
                const hero = document.querySelector('.hero-section');
                
                // Scroll
                function handleScroll() {
                    if (window.scrollY >= hero.offsetHeight) {
                        nav.classList.add('fixed-nav');
                    } else {
                        nav.classList.remove('fixed-nav');
                    }
                }
                
                window.addEventListener('scroll', handleScroll);
                
                // Smooth scroll
                document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                    anchor.addEventListener('click', function (e) {
                        e.preventDefault();
                        const targetId = this.getAttribute('href');
                        const targetElement = document.querySelector(targetId);
                        
                        if (targetElement) {
                            const navHeight = nav.offsetHeight;
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