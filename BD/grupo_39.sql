-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 04-11-2015 a las 18:26:14
-- Versión del servidor: 5.5.44-0ubuntu0.14.04.1
-- Versión de PHP: 5.5.9-1ubuntu4.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `grupo_39`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumno`
--

CREATE TABLE IF NOT EXISTS `alumno` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tipoDocumento` varchar(50) NOT NULL,
  `numeroDocumento` int(10) unsigned NOT NULL,
  `apellido` text NOT NULL,
  `nombre` text NOT NULL,
  `fechaNacimiento` date NOT NULL,
  `sexo` char(1) NOT NULL,
  `mail` varchar(50) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `fechaIngreso` date NOT NULL,
  `fechaEgreso` date DEFAULT NULL,
  `fechaAlta` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tipoDocumento/numeroDocumento` (`tipoDocumento`,`numeroDocumento`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Volcado de datos para la tabla `alumno`
--

INSERT INTO `alumno` (`id`, `tipoDocumento`, `numeroDocumento`, `apellido`, `nombre`, `fechaNacimiento`, `sexo`, `mail`, `direccion`, `fechaIngreso`, `fechaEgreso`, `fechaAlta`) VALUES
(10, 'DNI', 36546888, 'Retamar', 'Emiliano', '2015-05-11', 'M', 'emiliano_retamar@hotmail.com.ar', 'alguna', '2015-10-02', NULL, '2015-10-19'),
(11, 'DNI', 39485848, 'Arana', 'Felipe', '2015-10-06', 'M', 'felipe@hotmail.com', 'nose', '2015-10-06', NULL, '2015-10-19'),
(16, 'DNI', 34848588, 'Alvarez', 'Gaston', '2015-10-07', 'M', 'gasti@gmail.com', 'alguna', '2015-10-13', NULL, '2015-10-26'),
(17, 'DNI', 38589394, 'Pepe', 'Pepito', '2015-10-02', 'M', 'pepe@hotmail.com', 'nose', '2015-10-07', NULL, '2015-10-26'),
(18, 'DNI', 40938588, 'Gomez', 'Lola', '2015-10-01', 'F', 'lola@hotmail.com', 'de por ahi', '2015-10-15', NULL, '2015-10-26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumnoResponsable`
--

CREATE TABLE IF NOT EXISTS `alumnoResponsable` (
  `idAlumno` int(10) unsigned NOT NULL,
  `idResponsable` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idAlumno`,`idResponsable`),
  KEY `idAlumno` (`idAlumno`),
  KEY `idResponsable` (`idResponsable`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `alumnoResponsable`
--

INSERT INTO `alumnoResponsable` (`idAlumno`, `idResponsable`) VALUES
(10, 2),
(10, 3),
(11, 4),
(16, 6),
(17, 12),
(18, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion`
--

CREATE TABLE IF NOT EXISTS `configuracion` (
  `titulo` varchar(255) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `elementos` int(100) NOT NULL,
  `habilitado` tinyint(1) NOT NULL,
  `mensaje` varchar(255) NOT NULL,
  PRIMARY KEY (`titulo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `configuracion`
--

INSERT INTO `configuracion` (`titulo`, `descripcion`, `mail`, `elementos`, `habilitado`, `mensaje`) VALUES
('Bienvenido', 'Pagina de la escuela anexa', 'anexa@gmail.com', 5, 1, 'Sitio en mantenimiento... por favor intente mas tarde...');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuota`
--

CREATE TABLE IF NOT EXISTS `cuota` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `anio` int(11) NOT NULL,
  `mes` int(11) NOT NULL,
  `numero` int(11) NOT NULL,
  `monto` int(11) NOT NULL,
  `tipo` tinyint(1) NOT NULL,
  `comisionCobrador` int(11) NOT NULL,
  `fechaAlta` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pago`
--

CREATE TABLE IF NOT EXISTS `pago` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idAlumno` int(11) unsigned NOT NULL,
  `idCuota` int(11) unsigned NOT NULL,
  `becado` tinyint(1) NOT NULL,
  `fechaAlta` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idAlumno/idCuota` (`idAlumno`,`idCuota`),
  KEY `idAlumno` (`idAlumno`),
  KEY `idCuota` (`idCuota`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=46 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `responsable`
--

CREATE TABLE IF NOT EXISTS `responsable` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tipo` varchar(255) NOT NULL,
  `apellido` varchar(255) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `fechaNacimiento` date NOT NULL,
  `sexo` varchar(10) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `telefono` varchar(255) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `idUsuario` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idUsuario` (`idUsuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Volcado de datos para la tabla `responsable`
--

INSERT INTO `responsable` (`id`, `tipo`, `apellido`, `nombre`, `fechaNacimiento`, `sexo`, `mail`, `telefono`, `direccion`, `idUsuario`) VALUES
(2, 'madre', 'Gonzalez', 'Maria', '2015-03-17', 'F', 'maria@hotmail.com', '3524564132', 'deh', NULL),
(3, 'tutor', 'Messi', 'Lionel', '2015-07-15', 'M', 'messi@gmail.com', '1234235443', 'Barcelona', 16),
(4, 'tutor', 'Ronaldo', 'Cristiano', '2015-10-25', 'M', 'ronaldo@gmail.com', '23244345', 'Madrid', NULL),
(5, 'madre', 'Garay', 'Lola', '2015-11-27', 'F', 'lola@gmail.com', '8129831822', 'Jujuy', NULL),
(6, 'tutor', 'Pastore', 'Javier', '2015-02-21', 'M', 'pastore@hotmail.com', '494399439', 'Paris', NULL),
(9, 'tutor', 'Alvarez', 'Jose', '2015-10-22', 'M', 'jose@hotmail.com', '34535345', 'ni idea', 5),
(12, 'tutor', 'Pepe', 'Pepito', '2015-09-30', 'M', 'pepito@hotmail.com', '21312412', 'alguna', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `habilitado` tinyint(1) NOT NULL,
  `rol` varchar(255) NOT NULL,
  `mail` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `username`, `password`, `habilitado`, `rol`, `mail`) VALUES
(1, 'peter', '123456', 1, 'administrador', 'peter@gmail.com'),
(3, 'Emi', '123456', 1, 'administrador', 'emiliano_retamar@hotmail.com.ar'),
(4, 'pepito', '123456', 1, 'consulta', 'pepito@hotmail.com'),
(5, 'josesito', '123456', 1, 'administrador', 'jose@hotmail.com'),
(16, 'Messi', '123456', 1, 'consulta', 'messi@gmail.com'),
(17, 'josefina', '123456', 1, 'gestion', 'josefina@hotmail.com'),
(19, 'Cristiano', '123456', 1, 'gestion', 'ronaldo@gmail.com');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alumnoResponsable`
--
ALTER TABLE `alumnoResponsable`
  ADD CONSTRAINT `alumnoResponsable_alumno` FOREIGN KEY (`idAlumno`) REFERENCES `alumno` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `alumnoResponsable_responsable` FOREIGN KEY (`idResponsable`) REFERENCES `responsable` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pago`
--
ALTER TABLE `pago`
  ADD CONSTRAINT `pago_alumno` FOREIGN KEY (`idAlumno`) REFERENCES `alumno` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `pago_cuota` FOREIGN KEY (`idCuota`) REFERENCES `cuota` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Filtros para la tabla `responsable`
--
ALTER TABLE `responsable`
  ADD CONSTRAINT `responsable_usuario` FOREIGN KEY (`idUsuario`) REFERENCES `usuario` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
