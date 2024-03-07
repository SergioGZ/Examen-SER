-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 07-03-2024 a las 12:59:38
-- Versión del servidor: 8.0.31
-- Versión de PHP: 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bdblog`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

DROP TABLE IF EXISTS `categorias`;
CREATE TABLE IF NOT EXISTS `categorias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`) VALUES
(1, 'Deportes'),
(2, 'Política'),
(3, 'Entretenimiento');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entradas`
--

DROP TABLE IF EXISTS `entradas`;
CREATE TABLE IF NOT EXISTS `entradas` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int DEFAULT NULL,
  `categoria_id` int DEFAULT NULL,
  `titulo` varchar(255) NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `descripcion` text,
  `fecha` date DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `usuario_id` (`usuario_id`),
  KEY `categoria_id` (`categoria_id`)
) ENGINE=MyISAM AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `entradas`
--

INSERT INTO `entradas` (`ID`, `usuario_id`, `categoria_id`, `titulo`, `imagen`, `descripcion`, `fecha`) VALUES
(41, 1, 2, 'Entrada', '1709752848-bombilla.png', '<p>entrada 1</p>\r\n', '2024-03-29'),
(42, 2, 3, 'Entrada de user', NULL, '<p>user</p>\r\n', '2024-03-07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logs`
--

DROP TABLE IF EXISTS `logs`;
CREATE TABLE IF NOT EXISTS `logs` (
  `usuario_id` int NOT NULL,
  `fecha` datetime(6) NOT NULL,
  `accion` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `logs`
--

INSERT INTO `logs` (`usuario_id`, `fecha`, `accion`) VALUES
(1, '2024-03-06 17:42:32.000000', 'Se añadió una nueva entrada con el título El Getaf'),
(1, '2024-03-06 17:42:38.000000', 'Se actualizó la entrada 39'),
(1, '2024-03-06 17:43:13.000000', 'Se eliminó la entrada 39'),
(2, '2024-03-06 17:56:19.000000', 'Se añadió una nueva entrada con el título Gana el '),
(2, '2024-03-06 17:56:24.000000', 'Se actualizó la entrada 40'),
(2, '2024-03-06 17:56:27.000000', 'Se eliminó la entrada 40'),
(1, '2024-03-06 18:46:55.000000', 'Se añadió una nueva entrada con el título Entrada'),
(2, '2024-03-06 18:59:36.000000', 'Se añadió una nueva entrada con el título Entrada '),
(1, '2024-03-06 19:20:48.000000', 'Se actualizó la entrada 41'),
(2, '2024-03-07 12:52:17.000000', 'Se añadió una nueva entrada con el título asdfdsf'),
(2, '2024-03-07 12:58:04.000000', 'Se eliminó la entrada 43');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nick` varchar(50) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellidos` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` varchar(10) NOT NULL,
  `imagen-avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nick`, `nombre`, `apellidos`, `email`, `password`, `rol`, `imagen-avatar`) VALUES
(1, 'admin', 'admin', 'admin', 'admin', 'admin', 'admin', NULL),
(2, 'user', 'user', 'user', 'user', 'user', 'user', NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
