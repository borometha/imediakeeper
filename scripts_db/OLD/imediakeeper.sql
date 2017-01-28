-- phpMyAdmin SQL Dump
-- version 3.1.3.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 10-05-2013 a las 13:50:00
-- Versión del servidor: 5.1.33
-- Versión de PHP: 5.2.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `imediakeeper`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actor`
--

CREATE TABLE IF NOT EXISTS `actor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `actor` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `datos_pelicula`
--

CREATE TABLE IF NOT EXISTS `datos_pelicula` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(15) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `anno` int(4) NOT NULL,
  `duracion` int(3) NOT NULL,
  `id_pais` int(11) DEFAULT NULL,
  `id_director` int(11) DEFAULT NULL,
  `sinopsis` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_pais` (`id_pais`),
  KEY `id_director` (`id_director`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `director`
--

CREATE TABLE IF NOT EXISTS `director` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `formato`
--

CREATE TABLE IF NOT EXISTS `formato` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `formato` varchar(2) NOT NULL,
  `resolucion` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pais`
--

CREATE TABLE IF NOT EXISTS `pais` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pais` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pelicula`
--

CREATE TABLE IF NOT EXISTS `pelicula` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(20) NOT NULL,
  `id_datos_pelicula` int(11) NOT NULL,
  `caratula` varchar(250) NOT NULL,
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
-- Filtros para las tablas descargadas (dump)
--

--
-- Filtros para la tabla `datos_pelicula`
--
ALTER TABLE `datos_pelicula`
  ADD CONSTRAINT `datos_pelicula_ibfk_2` FOREIGN KEY (`id_director`) REFERENCES `director` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `datos_pelicula_ibfk_1` FOREIGN KEY (`id_pais`) REFERENCES `pais` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Filtros para la tabla `pelicula`
--
ALTER TABLE `pelicula`
  ADD CONSTRAINT `pelicula_ibfk_2` FOREIGN KEY (`id_formato`) REFERENCES `formato` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `pelicula_ibfk_1` FOREIGN KEY (`id_datos_pelicula`) REFERENCES `datos_pelicula` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `reparto`
--
ALTER TABLE `reparto`
  ADD CONSTRAINT `reparto_ibfk_2` FOREIGN KEY (`id_actor`) REFERENCES `actor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reparto_ibfk_1` FOREIGN KEY (`id_datos_pelicula`) REFERENCES `pelicula` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
