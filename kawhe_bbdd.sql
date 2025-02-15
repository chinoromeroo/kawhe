-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-02-2025 a las 21:24:18
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
(1, 1, 'Cafés', 1),
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
(32, 8, 'Prueba Categoria Mobile', 0),
(33, 10, 'PROBANDO cate', 0),
(34, 11, 'PREUHJFG', 0),
(35, 12, 'PREUVBA RAPIDA', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `id_categoria` int(11) DEFAULT NULL,
  `nombre` varchar(200) NOT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_modificacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `descripcion` text DEFAULT NULL,
  `precio_chico` decimal(10,2) DEFAULT NULL,
  `precio_mediano` decimal(10,2) DEFAULT NULL,
  `precio_grande` decimal(10,2) DEFAULT NULL,
  `precio_extra_grande` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `id_categoria`, `nombre`, `precio`, `activo`, `fecha_creacion`, `fecha_modificacion`, `descripcion`, `precio_chico`, `precio_mediano`, `precio_grande`, `precio_extra_grande`) VALUES
(1, 1, 'Expresso', 0.00, 1, '2025-01-30 22:34:44', '2025-02-15 15:11:10', '', 2400.00, 0.00, 0.00, 0.00),
(2, 1, 'Doppio', 0.00, 1, '2025-01-30 22:34:44', '2025-02-15 15:11:24', '', 2700.00, 0.00, 0.00, 0.00),
(3, 1, 'Cortado', 0.00, 1, '2025-01-30 22:34:44', '2025-02-15 15:11:45', '', 2600.00, 2900.00, 0.00, 0.00),
(4, 1, 'Moca', 0.00, 1, '2025-01-30 22:34:44', '2025-02-15 15:12:17', '', 0.00, 0.00, 4000.00, 0.00),
(5, 1, 'Moca blanco', 0.00, 1, '2025-01-30 22:34:44', '2025-02-15 04:42:25', '', 0.00, 0.00, 0.00, 0.00),
(6, 1, 'Americano', 0.00, 1, '2025-01-30 22:34:44', '2025-02-15 15:14:44', '', 0.00, 2900.00, 0.00, 0.00),
(7, 1, 'Flat white', 0.00, 1, '2025-01-30 22:34:44', '2025-02-15 15:12:36', '', 0.00, 0.00, 3600.00, 0.00),
(8, 1, 'Capucchino', 0.00, 1, '2025-01-30 22:34:44', '2025-02-15 15:12:41', '', 0.00, 0.00, 3800.00, 0.00),
(9, 1, 'Filtrado', 0.00, 1, '2025-01-30 22:34:44', '2025-02-15 15:14:35', '', 0.00, 0.00, 5000.00, 0.00),
(10, 1, 'Matcha Latte', 0.00, 1, '2025-01-30 22:34:44', '2025-02-15 15:12:51', '', 0.00, 0.00, 0.00, 4400.00),
(11, 1, 'Latte', 0.00, 1, '2025-01-30 22:34:44', '2025-02-15 15:13:01', '', 0.00, 0.00, 3600.00, 4000.00),
(12, 1, 'Latte saborizado', 0.00, 1, '2025-01-30 22:34:44', '2025-02-15 15:14:29', '(vainilla, caramelo o avellanas)', 0.00, 0.00, 4000.00, 0.00),
(16, 2, 'Té', 0.00, 1, '2025-01-30 22:34:44', '2025-02-15 04:23:36', '', NULL, NULL, NULL, NULL),
(17, 2, 'Té saborizado', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL, NULL, NULL, NULL, NULL),
(18, 2, 'Té en hebras', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL, NULL, NULL, NULL, NULL),
(19, 3, 'Submarino', 0.00, 1, '2025-01-30 22:34:44', '2025-02-15 05:23:03', '', NULL, NULL, NULL, NULL),
(20, 3, 'Agua', 0.00, 1, '2025-01-30 22:34:44', '2025-02-15 03:20:01', NULL, NULL, NULL, NULL, NULL),
(21, 3, 'Agua con gas', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL, NULL, NULL, NULL, NULL),
(22, 3, 'Jugo de naranja', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL, NULL, NULL, NULL, NULL),
(23, 3, 'Limonada', 0.00, 1, '2025-01-30 22:34:44', '2025-02-15 04:29:48', '', NULL, NULL, NULL, NULL),
(24, 3, 'Gaseosas', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL, NULL, NULL, NULL, NULL),
(26, 4, 'Café clásico y tostadas con dips', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL, NULL, NULL, NULL, NULL),
(27, 4, 'Café mediano y 2 medialunas', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL, NULL, NULL, NULL, NULL),
(29, 5, 'Café mediano y pancakes de avena con miel', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL, NULL, NULL, NULL, NULL),
(30, 5, 'Café mediano y muffin', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL, NULL, NULL, NULL, NULL),
(31, 5, 'Café mediano y huevos revueltos', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL, NULL, NULL, NULL, NULL),
(32, 6, '2 infusiones, 2 jugos de naranja, Sandwich de chipá de jyq, Croissant de jyq, Avocado toast, Budín o cuadrado dulce, Porción de torta', 0.00, 1, '2025-01-30 22:34:44', '2025-02-06 23:52:16', NULL, NULL, NULL, NULL, NULL),
(33, 7, '2 infusiones, Tostado de jyq, Torta del día', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL, NULL, NULL, NULL, NULL),
(34, 11, 'Medialuna', 0.00, 1, '2025-01-30 22:34:44', '2025-02-06 23:52:20', NULL, NULL, NULL, NULL, NULL),
(35, 11, 'Medialuna con jyq', 0.00, 1, '2025-01-30 22:34:44', '2025-02-06 23:52:22', NULL, NULL, NULL, NULL, NULL),
(37, 12, 'Jamón, queso y huevos revueltos', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL, NULL, NULL, NULL, NULL),
(38, 12, 'Crudo, queso, tomate y rúcula', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL, NULL, NULL, NULL, NULL),
(39, 12, 'Nutella, banana y frutillas', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL, NULL, NULL, NULL, NULL),
(40, 13, 'Chipacito', 0.00, 1, '2025-01-30 22:34:44', '2025-02-15 14:54:49', '', NULL, NULL, NULL, NULL),
(41, 13, 'Sandwich de chipá de jyq', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL, NULL, NULL, NULL, NULL),
(42, 13, 'Sandwich de chipá de hongos y queso', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL, NULL, NULL, NULL, NULL),
(43, 14, 'Tostadas con dips', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL, NULL, NULL, NULL, NULL),
(44, 14, 'Avocado toast con huevos revueltos', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL, NULL, NULL, NULL, NULL),
(45, 14, 'Yogurt con granola y frutas', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL, NULL, NULL, NULL, NULL),
(46, 14, 'Pancakes de avena', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL, NULL, NULL, NULL, NULL),
(50, 15, 'Jamón y queso', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL, NULL, NULL, NULL, NULL),
(51, 15, 'Caprese', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL, NULL, NULL, NULL, NULL),
(52, 15, 'Croque madame', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL, NULL, NULL, NULL, NULL),
(53, 16, 'Brownie con nuez', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL, NULL, NULL, NULL, NULL),
(54, 16, 'Chocotorta', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL, NULL, NULL, NULL, NULL),
(55, 16, 'Tiramisú', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL, NULL, NULL, NULL, NULL),
(56, 17, 'Alfajor con nuez', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL, NULL, NULL, NULL, NULL),
(57, 17, 'Alfajor de maicena', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL, NULL, NULL, NULL, NULL),
(58, 17, 'Alfajor de pistacho', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL, NULL, NULL, NULL, NULL),
(59, 18, 'Limón y amapola', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL, NULL, NULL, NULL, NULL),
(60, 18, 'Crumble y arándanos', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL, NULL, NULL, NULL, NULL),
(61, 18, 'Carrot cake', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL, NULL, NULL, NULL, NULL),
(62, 19, 'Chocolate', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL, NULL, NULL, NULL, NULL),
(63, 19, 'Manzana', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL, NULL, NULL, NULL, NULL),
(64, 19, 'Arándanos', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL, NULL, NULL, NULL, NULL),
(65, 20, 'Vainilla con chips de chocolate', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL, NULL, NULL, NULL, NULL),
(66, 20, 'Red velvet', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL, NULL, NULL, NULL, NULL),
(67, 20, 'Pistacho', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL, NULL, NULL, NULL, NULL),
(68, 20, 'Avena con chips de chocolate', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL, NULL, NULL, NULL, NULL),
(72, 21, 'Canela', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL, NULL, NULL, NULL, NULL),
(73, 21, 'Nutella', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL, NULL, NULL, NULL, NULL),
(75, 22, 'Key lime pie', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL, NULL, NULL, NULL, NULL),
(76, 22, 'Cheesecake de frutos rojos', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL, NULL, NULL, NULL, NULL),
(77, 22, 'Marquisse de chocolate', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL, NULL, NULL, NULL, NULL),
(78, 22, 'Rogel', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL, NULL, NULL, NULL, NULL),
(82, 26, 'Ensalada caesar', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL, NULL, NULL, NULL, NULL),
(83, 26, 'Ensalada Kawhe', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL, NULL, NULL, NULL, NULL),
(84, 26, 'Pancakes de avena con atún, huevo, tomate y pepino', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL, NULL, NULL, NULL, NULL),
(85, 26, 'Sandwich de bondiola con boniato', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL, NULL, NULL, NULL, NULL),
(86, 26, 'Pinchos de pollo con dips', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL, NULL, NULL, NULL, NULL),
(87, 26, 'Mini tarta de jamón y queso', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL, NULL, NULL, NULL, NULL),
(88, 26, 'Mini tarta de puerro y queso', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL, NULL, NULL, NULL, NULL),
(89, 27, 'Plato principal, Bebida, Infusión o mini postre a elección', 0.00, 1, '2025-01-30 22:34:44', '2025-01-30 22:34:44', NULL, NULL, NULL, NULL, NULL),
(127, 1, 'CAFECITO', NULL, 0, '2025-02-15 15:57:16', '2025-02-15 15:57:25', 'lolo', 9000.00, 0.00, 0.00, 0.00),
(128, 33, 'PRODUCTO PRUEBA ksa', 2000.00, 0, '2025-02-15 20:11:56', '2025-02-15 20:20:32', 'sakhd', NULL, NULL, NULL, NULL),
(129, 1, 'CAFE PRUEBA', NULL, 0, '2025-02-15 20:13:18', '2025-02-15 20:13:26', 'lalsa', 2000.00, 3000.00, 4000.00, 5000.00),
(130, 1, 'V', NULL, 0, '2025-02-15 20:18:18', '2025-02-15 20:18:36', '', 1000.00, 2000.00, 665656.00, 4000.00),
(131, 34, 'PROD P', 100.00, 0, '2025-02-15 20:19:30', '2025-02-15 20:20:21', '', NULL, NULL, NULL, NULL),
(132, 35, 'PROD PUREBA RAPUIDA', 999.00, 0, '2025-02-15 20:22:54', '2025-02-15 20:23:07', 'DSAD', NULL, NULL, NULL, NULL);

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
(8, 'Prueba Mobile', 0),
(9, 'SEXION', 0),
(10, 'PROBANDO sec', 0),
(11, 'PRUE PASKSAD', 0),
(12, 'PRUEBA RAPIDA', 0);

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
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=133;

--
-- AUTO_INCREMENT de la tabla `secciones`
--
ALTER TABLE `secciones`
  MODIFY `id_seccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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
