-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-11-2022 a las 01:05:40
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `primerentrega`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `casas`
--

CREATE TABLE `casas` (
  `id` int(11) NOT NULL,
  `nombre_casa` varchar(50) NOT NULL,
  `colores` varchar(50) NOT NULL,
  `simbolo` varchar(50) NOT NULL,
  `fundador` varchar(50) NOT NULL,
  `escudo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `casas`
--

INSERT INTO `casas` (`id`, `nombre_casa`, `colores`, `simbolo`, `fundador`, `escudo`) VALUES
(11, 'Hufflepuff', 'amarillo y negro', 'Tejon', ' Helga Hufflepuff', 'images/houseShields/6347376d0b833.jpg'),
(12, 'Gryffindor', 'escarlata y dorado', 'Leon', 'Godric Gryffindor', 'images/houseShields/6347399814a82.jpg'),
(14, 'Slytherin', 'verde y plata', 'serpiente', 'Salazar Slytherin', 'images/houseShields/63460bcc4f6f9.png'),
(27, 'Ravenclaw', 'azul y bronce', 'aguila', 'Rowena Ravenclaw', 'images/houseShields/63460558bff62.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personajes`
--

CREATE TABLE `personajes` (
  `id` int(11) NOT NULL,
  `id_casa` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `rol` varchar(50) NOT NULL,
  `nucleo_varita` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `personajes`
--

INSERT INTO `personajes` (`id`, `id_casa`, `nombre`, `rol`, `nucleo_varita`) VALUES
(7, 12, 'Hermione Granger', 'Alumna', 'fibra de corazon de dragon'),
(8, 12, 'Harry Potter', 'alumno', 'pluma de fenix'),
(19, 14, 'Lord Voldemort', 'Mago', 'Pluma de fénix'),
(20, 14, 'Bellatrix Lestrange', 'Maga', 'Nervio de dragon'),
(21, 11, 'Cedric Diggory', 'Alumno', 'Pelo de unicornio'),
(25, 12, 'josepe', 'alumno', 'pelota'),
(27, 11, 'Leo Messieg', 'Maestro', 'fibra de cesped'),
(28, 12, 'Pedro gomez', 'mago', 'bigote de gato negro');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre_usuario` varchar(50) NOT NULL,
  `contrasenia` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre_usuario`, `contrasenia`) VALUES
(3, 'jose', '$2y$10$pmmdTjjv5Mluv75eIWTe7u09144E2gpDsadSLrWCKj/u1Y6sn0m6S');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `casas`
--
ALTER TABLE `casas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `personajes`
--
ALTER TABLE `personajes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_casa` (`id_casa`) USING BTREE;

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `casas`
--
ALTER TABLE `casas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de la tabla `personajes`
--
ALTER TABLE `personajes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
