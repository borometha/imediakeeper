-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-06-2013 a las 09:03:45
-- Versión del servidor: 5.5.27
-- Versión de PHP: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `imediakeeper`
--
DROP DATABASE IF EXISTS `imediakeeper`;
CREATE DATABASE `imediakeeper` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `imediakeeper`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actor`
--

CREATE TABLE IF NOT EXISTS `actor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `actor` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=140 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `datos_pelicula`
--

CREATE TABLE IF NOT EXISTS `datos_pelicula` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(200) NOT NULL,
  `anno` int(4) NOT NULL,
  `duracion` int(3) NOT NULL,
  `id_pais` int(11) DEFAULT NULL,
  `id_director` int(11) DEFAULT NULL,
  `sinopsis` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_pais` (`id_pais`),
  KEY `id_director` (`id_director`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `director`
--

CREATE TABLE IF NOT EXISTS `director` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `director` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `formato`
--

CREATE TABLE IF NOT EXISTS `formato` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `formato` varchar(2) NOT NULL,
  `resolucion` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `formato`
--

INSERT INTO `formato` (`id`,`formato`, `resolucion`) VALUES
(1,'HD',''),
(2,'SD','');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `genero`
--

CREATE TABLE IF NOT EXISTS `genero` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `genero` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `genero_pelicula`
--

CREATE TABLE IF NOT EXISTS `genero_pelicula` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_genero` int(11) NOT NULL,
  `id_datos_pelicula` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_genero` (`id_genero`),
  KEY `id_datos_pelicula` (`id_datos_pelicula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pais`
--

CREATE TABLE IF NOT EXISTS `pais` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pais` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pelicula`
--

CREATE TABLE IF NOT EXISTS `pelicula` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(20) NOT NULL,
  `id_datos_pelicula` int(11) NOT NULL,
  `caratula` varchar(250) DEFAULT NULL,
  `id_formato` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_datos_pelicula` (`id_datos_pelicula`),
  KEY `id_formato` (`id_formato`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reparto`
--

CREATE TABLE IF NOT EXISTS `reparto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_datos_pelicula` int(11) NOT NULL,
  `id_actor` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_datos_pelicula` (`id_datos_pelicula`),
  KEY `id_actor` (`id_actor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
