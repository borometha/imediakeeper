-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 28-01-2017 a las 12:59:30
-- Versión del servidor: 5.7.17-0ubuntu0.16.04.1
-- Versión de PHP: 7.0.13-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `imediakeeper`
--
CREATE DATABASE IF NOT EXISTS `imediakeeper` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `imediakeeper`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actor`
--
-- Creación: 28-01-2017 a las 11:52:19
-- Última actualización: 28-01-2017 a las 11:52:20
--

CREATE TABLE `actor` (
  `id` int(11) NOT NULL,
  `actor` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELACIONES PARA LA TABLA `actor`:
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `datos_pelicula`
--
-- Creación: 28-01-2017 a las 11:52:27
-- Última actualización: 28-01-2017 a las 11:52:27
--

CREATE TABLE `datos_pelicula` (
  `id` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `anno` int(4) NOT NULL,
  `duracion` int(3) NOT NULL,
  `id_pais` int(11) DEFAULT NULL,
  `id_director` int(11) DEFAULT NULL,
  `sinopsis` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELACIONES PARA LA TABLA `datos_pelicula`:
--   `id_pais`
--       `pais` -> `id`
--   `id_director`
--       `director` -> `id`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `director`
--
-- Creación: 28-01-2017 a las 11:52:21
-- Última actualización: 28-01-2017 a las 11:52:22
--

CREATE TABLE `director` (
  `id` int(11) NOT NULL,
  `director` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELACIONES PARA LA TABLA `director`:
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `formato`
--
-- Creación: 28-01-2017 a las 11:52:22
-- Última actualización: 28-01-2017 a las 11:52:22
--

CREATE TABLE `formato` (
  `id` int(11) NOT NULL,
  `formato` varchar(2) NOT NULL,
  `resolucion` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELACIONES PARA LA TABLA `formato`:
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `genero`
--
-- Creación: 28-01-2017 a las 11:52:22
-- Última actualización: 28-01-2017 a las 11:52:22
--

CREATE TABLE `genero` (
  `id` int(11) NOT NULL,
  `genero` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELACIONES PARA LA TABLA `genero`:
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `genero_pelicula`
--
-- Creación: 28-01-2017 a las 11:52:28
-- Última actualización: 28-01-2017 a las 11:52:28
--

CREATE TABLE `genero_pelicula` (
  `id` int(11) NOT NULL,
  `id_genero` int(11) NOT NULL,
  `id_datos_pelicula` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELACIONES PARA LA TABLA `genero_pelicula`:
--   `id_genero`
--       `genero` -> `id`
--   `id_datos_pelicula`
--       `datos_pelicula` -> `id`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `login`
--
-- Creación: 28-01-2017 a las 11:52:28
-- Última actualización: 28-01-2017 a las 11:52:28
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `user` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `rol` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELACIONES PARA LA TABLA `login`:
--   `rol`
--       `rol` -> `id`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pais`
--
-- Creación: 28-01-2017 a las 11:52:23
-- Última actualización: 28-01-2017 a las 11:52:23
--

CREATE TABLE `pais` (
  `id` int(11) NOT NULL,
  `pais` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELACIONES PARA LA TABLA `pais`:
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pelicula`
--
-- Creación: 28-01-2017 a las 11:52:29
-- Última actualización: 28-01-2017 a las 11:52:29
--

CREATE TABLE `pelicula` (
  `id` int(11) NOT NULL,
  `codigo` varchar(20) NOT NULL,
  `id_datos_pelicula` int(11) NOT NULL,
  `caratula` varchar(250) DEFAULT NULL,
  `id_formato` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELACIONES PARA LA TABLA `pelicula`:
--   `id_datos_pelicula`
--       `datos_pelicula` -> `id`
--   `id_formato`
--       `formato` -> `id`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reparto`
--
-- Creación: 28-01-2017 a las 11:52:31
-- Última actualización: 28-01-2017 a las 11:52:30
--

CREATE TABLE `reparto` (
  `id` int(11) NOT NULL,
  `id_datos_pelicula` int(11) NOT NULL,
  `id_actor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELACIONES PARA LA TABLA `reparto`:
--   `id_actor`
--       `actor` -> `id`
--   `id_datos_pelicula`
--       `datos_pelicula` -> `id`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--
-- Creación: 28-01-2017 a las 11:52:26
-- Última actualización: 28-01-2017 a las 11:52:26
--

CREATE TABLE `rol` (
  `id` int(11) NOT NULL,
  `rol` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELACIONES PARA LA TABLA `rol`:
--

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actor`
--
ALTER TABLE `actor`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `datos_pelicula`
--
ALTER TABLE `datos_pelicula`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pais` (`id_pais`),
  ADD KEY `id_director` (`id_director`);

--
-- Indices de la tabla `director`
--
ALTER TABLE `director`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `formato`
--
ALTER TABLE `formato`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `genero`
--
ALTER TABLE `genero`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `genero_pelicula`
--
ALTER TABLE `genero_pelicula`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_genero` (`id_genero`),
  ADD KEY `id_datos_pelicula` (`id_datos_pelicula`);

--
-- Indices de la tabla `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user` (`user`),
  ADD KEY `rol` (`rol`);

--
-- Indices de la tabla `pais`
--
ALTER TABLE `pais`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pelicula`
--
ALTER TABLE `pelicula`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_datos_pelicula` (`id_datos_pelicula`),
  ADD KEY `id_formato` (`id_formato`);

--
-- Indices de la tabla `reparto`
--
ALTER TABLE `reparto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_datos_pelicula` (`id_datos_pelicula`),
  ADD KEY `id_actor` (`id_actor`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rol` (`rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `actor`
--
ALTER TABLE `actor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5308;
--
-- AUTO_INCREMENT de la tabla `datos_pelicula`
--
ALTER TABLE `datos_pelicula`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=573;
--
-- AUTO_INCREMENT de la tabla `director`
--
ALTER TABLE `director`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=367;
--
-- AUTO_INCREMENT de la tabla `formato`
--
ALTER TABLE `formato`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `genero`
--
ALTER TABLE `genero`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=233;
--
-- AUTO_INCREMENT de la tabla `genero_pelicula`
--
ALTER TABLE `genero_pelicula`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2646;
--
-- AUTO_INCREMENT de la tabla `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `pais`
--
ALTER TABLE `pais`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT de la tabla `pelicula`
--
ALTER TABLE `pelicula`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=557;
--
-- AUTO_INCREMENT de la tabla `reparto`
--
ALTER TABLE `reparto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7418;
--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `datos_pelicula`
--
ALTER TABLE `datos_pelicula`
  ADD CONSTRAINT `datos_pelicula_ibfk_1` FOREIGN KEY (`id_pais`) REFERENCES `pais` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `datos_pelicula_ibfk_2` FOREIGN KEY (`id_director`) REFERENCES `director` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Filtros para la tabla `genero_pelicula`
--
ALTER TABLE `genero_pelicula`
  ADD CONSTRAINT `genero_pelicula_ibfk_1` FOREIGN KEY (`id_genero`) REFERENCES `genero` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `genero_pelicula_ibfk_2` FOREIGN KEY (`id_datos_pelicula`) REFERENCES `datos_pelicula` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `login`
--
ALTER TABLE `login`
  ADD CONSTRAINT `login_ibfk_1` FOREIGN KEY (`rol`) REFERENCES `rol` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pelicula`
--
ALTER TABLE `pelicula`
  ADD CONSTRAINT `pelicula_ibfk_1` FOREIGN KEY (`id_datos_pelicula`) REFERENCES `datos_pelicula` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pelicula_ibfk_2` FOREIGN KEY (`id_formato`) REFERENCES `formato` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Filtros para la tabla `reparto`
--
ALTER TABLE `reparto`
  ADD CONSTRAINT `reparto_ibfk_2` FOREIGN KEY (`id_actor`) REFERENCES `actor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reparto_ibfk_3` FOREIGN KEY (`id_datos_pelicula`) REFERENCES `datos_pelicula` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
