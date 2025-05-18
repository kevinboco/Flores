-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 18-05-2025 a las 00:22:10
-- Versión del servidor: 8.0.30
-- Versión de PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `pedidos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido`
--

CREATE TABLE `pedido` (
  `id` int NOT NULL,
  `nombre_cliente` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `celular` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `direccion` text COLLATE utf8mb4_general_ci NOT NULL,
  `valor_ramo` int NOT NULL,
  `cantidad_pagada` int DEFAULT '0',
  `estado` enum('En proceso','Listo','Enviado') COLLATE utf8mb4_general_ci DEFAULT 'En proceso',
  `fecha_entrega` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedido`
--

INSERT INTO `pedido` (`id`, `nombre_cliente`, `celular`, `direccion`, `valor_ramo`, `cantidad_pagada`, `estado`, `fecha_entrega`) VALUES
(6, 'pru3', '30440404', 'allo3', 0, 10, 'En proceso', '2025-05-17'),
(7, 'yo', '304334', 'lol', 100000, 30, 'En proceso', '2025-06-01'),
(8, 'pedido2', '00303343', '2dd', 0, 50, 'En proceso', '2025-05-17'),
(9, 'calis', '3043434', 'd', 100000, 100000, 'Enviado', '2027-01-17'),
(10, 'lopez3', '033423', 'c', 100000, 100000, '', '2027-01-17'),
(11, 'f', '32432423', 'r', 3434, 3434, 'Enviado', '2027-02-28'),
(13, 'carlos junior villarreal', '3044868656', 'calle23 k1', 33000, 33000, 'En proceso', '2025-05-18'),
(14, 'alfonzo', '44556554', 'santa', 25000, 25000, 'En proceso', '2025-05-20');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `pedido`
--
ALTER TABLE `pedido`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
