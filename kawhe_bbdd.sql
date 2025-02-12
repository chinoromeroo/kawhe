-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-02-2025 a las 14:03:06
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `kawhe`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `created_at`) VALUES
(2, 'kawhe-admin', '$2y$10$cObt3nCkC8klXcXdTgT6p.UFv2x3WUkeznEHUEh.yflaRnxpOMfIO', '2025-02-06 22:47:45');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int(11) NOT NULL,
  `id_seccion` int(11) DEFAULT NULL,
  `nombre` varchar(50) NOT NULL,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `id_seccion`, `nombre`, `activo`) VALUES
(1, 1, 'Cafés ', 1),
(2, 1, 'Infusiones', 1),
(3, 1, 'Otras', 1),
(4, 2, 'Clásico', 1),
(5, 2, 'Saludable', 1),
(6, 2, 'Full', 1),
(7, 2, 'Mini', 1),
(11, 3, 'Medialunas', 1),
(12, 3, 'Croissants', 1),
(13, 3, 'Chipas', 1),
(14, 3, 'Clásicos', 1),
(15, 3, 'Tostados', 1),
(16, 3, 'Cuadrados', 1),
(17, 3, 'Alfajores', 1),
(18, 3, 'Budines', 1),
(19, 3, 'Muffins', 1),
(20, 3, 'Cookies', 1),
(21, 3, 'Rolls', 1),
(22, 3, 'Tortas', 1),
(26, 4, 'Platos', 1),
(27, 4, 'Menú del día', 1),
(28, 5, 'CERDOS', 0),
(30, 6, 'PROB-1', 0),
(31, 7, 'Prueba Categoria', 0),
(32, 8, 'Prueba Categoria Mobile', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `id_categoria` int(11) DEFAULT NULL,
  `nombre` varchar(200) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_modificacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `id_categoria`, `nombre`, `precio`, `activo`, `fecha_creacion`, `fecha_modificacion`, `descripcion`) VALUES
(1, 1, 'Expresso', 0.00, 1, '2025-01-30 22:34:44', '2025-02-12 13:00:30', NULL),
(2, 1, 'Doppio', 0.00, 1, '2025-01-30 22:34:44', '2025-02-12 12:59:56', NULL),
(3, 1, 'Cortado', 0.00, 1, '2025-01-30 22:34:44', '2025-02-12 13:00:22', NULL),
(4, 1, 'Moca', 0.00, 1, '2025-01-30 22:34:44', '2025-02-06 23:52:49', NULL),
(5, 1, 'Moca blanco', 0.00, 1, '2025-01-30 22:34:44', '2025-02-12 13:00:28', NULL),
(6, 1, 'Americano', 0.00, 1, '2025-01-30 22:34:44', '2025-02-12 13:00:02', NULL),
(7, 1, 'Flat white', 0.00, 1, '2025-01-30 22:34:44', '2025-02-12 13:00:04', NULL),
(8, 1, 'Capucchino', 0.00, 1, '2025-01-30 22:34:44', '2025-02-06 23:52:40', NULL),
(9, 1, 'Filtrado', 0.00, 1, '2025-01-30 22:34:44', '2025-02-06 23:52:38', NULL),
(10, 1, 'Matcha Latte', 0.00, 1, '2025-01-30 22:34:44', '2025-02-06 23:52:34', NULL),
(11, 1, 'Latte', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(12, 1, 'Latte saborizado (vainilla, caramelo o avellanas)', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(16, 2, 'Té', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(17, 2, 'Té saborizado', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(18, 2, 'Té en hebras', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(19, 3, 'Submarino', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(20, 3, 'Agua', 0.00, 1, '2025-01-30 22:34:44', '2025-02-08 12:02:21', ''),
(21, 3, 'Agua con gas', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(22, 3, 'Jugo de naranja', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(23, 3, 'Limonada', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(24, 3, 'Gaseosas', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(26, 4, 'Café clásico y tostadas con dips', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(27, 4, 'Café mediano y 2 medialunas', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(29, 5, 'Café mediano y pancakes de avena con miel', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(30, 5, 'Café mediano y muffin', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(31, 5, 'Café mediano y huevos revueltos', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(32, 6, '2 infusiones, 2 jugos de naranja, Sandwich de chipá de jyq, Croissant de jyq, Avocado toast, Budín o cuadrado dulce, Porción de torta', 0.00, 1, '2025-01-30 22:34:44', '2025-02-06 23:52:16', NULL),
(33, 7, '2 infusiones, Tostado de jyq, Torta del día', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(34, 11, 'Medialuna', 0.00, 1, '2025-01-30 22:34:44', '2025-02-06 23:52:20', NULL),
(35, 11, 'Medialuna con jyq', 0.00, 1, '2025-01-30 22:34:44', '2025-02-06 23:52:22', NULL),
(37, 12, 'Jamón, queso y huevos revueltos', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(38, 12, 'Crudo, queso, tomate y rúcula', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(39, 12, 'Nutella, banana y frutillas', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(40, 13, 'Chipacito', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(41, 13, 'Sandwich de chipá de jyq', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(42, 13, 'Sandwich de chipá de hongos y queso', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(43, 14, 'Tostadas con dips', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(44, 14, 'Avocado toast con huevos revueltos', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(45, 14, 'Yogurt con granola y frutas', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(46, 14, 'Pancakes de avena', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(50, 15, 'Jamón y queso', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(51, 15, 'Caprese', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(52, 15, 'Croque madame', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(53, 16, 'Brownie con nuez', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(54, 16, 'Chocotorta', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(55, 16, 'Tiramisú', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(56, 17, 'Alfajor con nuez', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(57, 17, 'Alfajor de maicena', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(58, 17, 'Alfajor de pistacho', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(59, 18, 'Limón y amapola', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(60, 18, 'Crumble y arándanos', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(61, 18, 'Carrot cake', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(62, 19, 'Chocolate', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(63, 19, 'Manzana', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(64, 19, 'Arándanos', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(65, 20, 'Vainilla con chips de chocolate', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(66, 20, 'Red velvet', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(67, 20, 'Pistacho', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(68, 20, 'Avena con chips de chocolate', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(72, 21, 'Canela', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(73, 21, 'Nutella', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(75, 22, 'Key lime pie', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(76, 22, 'Cheesecake de frutos rojos', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(77, 22, 'Marquisse de chocolate', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(78, 22, 'Rogel', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(82, 26, 'Ensalada caesar', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(83, 26, 'Ensalada Kawhe', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(84, 26, 'Pancakes de avena con atún, huevo, tomate y pepino', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(85, 26, 'Sandwich de bondiola con boniato', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(86, 26, 'Pinchos de pollo con dips', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(87, 26, 'Mini tarta de jamón y queso', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(88, 26, 'Mini tarta de puerro y queso', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(89, 27, 'Plato principal, Bebida, Infusión o mini postre a elección', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL),
(91, 1, 'CAFECITO', 0.00, 1, '2025-02-08 12:35:06', '2025-02-12 13:00:14', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `secciones`
--

CREATE TABLE `secciones` (
  `id_seccion` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `secciones`
--

INSERT INTO `secciones` (`id_seccion`, `nombre`, `activo`) VALUES
(1, 'Bebidas', 1),
(2, 'Combos', 1),
(3, 'Deli', 1),
(4, 'Almuerzo', 1),
(5, 'MORFI', 0),
(6, 'PROBANDO', 0),
(7, 'Prueba', 0),
(8, 'Prueba Mobile', 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`),
  ADD KEY `id_seccion` (`id_seccion`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Indices de la tabla `secciones`
--
ALTER TABLE `secciones`
  ADD PRIMARY KEY (`id_seccion`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT de la tabla `secciones`
--
ALTER TABLE `secciones`
  MODIFY `id_seccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD CONSTRAINT `categorias_ibfk_1` FOREIGN KEY (`id_seccion`) REFERENCES `secciones` (`id_seccion`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
