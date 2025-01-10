-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-11-2023 a las 15:19:27
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `gestion_incidencias`
--

-- Eliminar la base de datos si existe
DROP DATABASE IF EXISTS `gestion_incidencias`;

-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS `gestion_incidencias` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `gestion_incidencias`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `campus`
--

CREATE TABLE `campus` (
  `id_campus` bigint(20) UNSIGNED NOT NULL,
  `nombre_campus` varchar(255) NOT NULL,
  `habilitado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
   PRIMARY KEY (`id_campus`),
  UNIQUE KEY `campus_nombre_campus_unique` (`nombre_campus`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `maquinas`
--

CREATE TABLE `maquinas` (
  `id_maquina` bigint(20) UNSIGNED NOT NULL,
  `nombre_maquina` varchar(255) NOT NULL,
  `numero_interno` varchar(255) NOT NULL,
  `tipo_maquina` varchar(255) DEFAULT NULL,
  `id_campus` bigint(20) UNSIGNED DEFAULT NULL,
  `id_seccion` bigint(20) UNSIGNED DEFAULT NULL,
  `habilitado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id_maquina`),
  KEY `maquinas_id_campus_foreign` (`id_campus`),
  KEY `maquinas_id_seccion_foreign` (`id_seccion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registros_trabajo`
--

CREATE TABLE `registros_trabajo` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_usuario` bigint(20) UNSIGNED NOT NULL,
  `id_tarea` bigint(20) UNSIGNED NOT NULL,
  `fecha_trabajo` date NOT NULL,
  `horas_trabajo` decimal(10,2) NOT NULL,
    `habilitado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `registros_trabajo_id_usuario_foreign` (`id_usuario`),
  KEY `registros_trabajo_id_tarea_foreign` (`id_tarea`),
    UNIQUE KEY `registros_trabajo_id_usuario_id_tarea_fecha_trabajo_unique` (`id_usuario`,`id_tarea`,`fecha_trabajo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seccion`
--

CREATE TABLE `seccion` (
  `id_seccion` bigint(20) UNSIGNED NOT NULL,
  `nombre_seccion` varchar(255) NOT NULL,
  `id_campus` bigint(20) UNSIGNED NOT NULL,
  `habilitado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id_seccion`),
  KEY `seccion_id_campus_foreign` (`id_campus`),
    UNIQUE KEY `seccion_nombre_seccion_id_campus_unique` (`nombre_seccion`,`id_campus`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tareas`
--

CREATE TABLE `tareas` (
  `id_tarea` bigint(20) UNSIGNED NOT NULL,
  `tipo_tarea` enum('incidencia','mantenimiento') NOT NULL,
  `id_maquina` bigint(20) UNSIGNED NOT NULL,
  `fecha_creacion` datetime NOT NULL,
  `fecha_cierre` datetime DEFAULT NULL,
  `prioridad_maquina` int(11) NOT NULL,
  `prioridad_averia` int(11) DEFAULT NULL,
  `tipo_averia` varchar(255) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `estado` enum('pendiente','en curso','en espera','finalizado') NOT NULL,
  `usuario_creacion` bigint(20) UNSIGNED NOT NULL,
  `horas_trabajo_total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `periodicidad` varchar(255) DEFAULT NULL,
  `fecha_ultima_ejecucion` datetime DEFAULT NULL,
    `habilitado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_tarea`),
  KEY `tareas_id_maquina_foreign` (`id_maquina`),
  KEY `tareas_usuario_creacion_foreign` (`usuario_creacion`),
  KEY `tareas_id_maquina_index` (`id_maquina`),
  KEY `tareas_prioridad_maquina_index` (`prioridad_maquina`),
  KEY `tareas_prioridad_averia_index` (`prioridad_averia`),
  KEY `tareas_estado_index` (`estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tarea_usuario`
--

CREATE TABLE `tarea_usuario` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_tarea` bigint(20) UNSIGNED NOT NULL,
  `id_usuario` bigint(20) UNSIGNED NOT NULL,
  `fecha_inicio` datetime DEFAULT NULL,
  `fecha_fin` datetime DEFAULT NULL,
  `horas_trabajo` decimal(10,2) NOT NULL DEFAULT 0.00,
  `habilitado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tarea_usuario_id_tarea_foreign` (`id_tarea`),
  KEY `tarea_usuario_id_usuario_foreign` (`id_usuario`),
    UNIQUE KEY `tarea_usuario_id_tarea_id_usuario_unique` (`id_tarea`,`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` bigint(20) UNSIGNED NOT NULL,
  `nombre_usuario` varchar(255) NOT NULL,
  `primer_apellido` varchar(255) NOT NULL,
  `segundo_apellido` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('operario','tecnico','administrador') NOT NULL,
  `foto_perfil` varchar(255) DEFAULT NULL,
   `habilitado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
 PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `usuarios_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `campus`
--
ALTER TABLE `campus`
  MODIFY `id_campus` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `maquinas`
--
ALTER TABLE `maquinas`
  MODIFY `id_maquina` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `registros_trabajo`
--
ALTER TABLE `registros_trabajo`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `seccion`
--
ALTER TABLE `seccion`
  MODIFY `id_seccion` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tareas`
--
ALTER TABLE `tareas`
  MODIFY `id_tarea` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tarea_usuario`
--
ALTER TABLE `tarea_usuario`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `maquinas`
--
ALTER TABLE `maquinas`
  ADD CONSTRAINT `maquinas_id_campus_foreign` FOREIGN KEY (`id_campus`) REFERENCES `campus` (`id_campus`),
  ADD CONSTRAINT `maquinas_id_seccion_foreign` FOREIGN KEY (`id_seccion`) REFERENCES `seccion` (`id_seccion`);

--
-- Filtros para la tabla `registros_trabajo`
--
ALTER TABLE `registros_trabajo`
  ADD CONSTRAINT `registros_trabajo_id_tarea_foreign` FOREIGN KEY (`id_tarea`) REFERENCES `tareas` (`id_tarea`) ON DELETE CASCADE,
  ADD CONSTRAINT `registros_trabajo_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `seccion`
--
ALTER TABLE `seccion`
  ADD CONSTRAINT `seccion_id_campus_foreign` FOREIGN KEY (`id_campus`) REFERENCES `campus` (`id_campus`);

--
-- Filtros para la tabla `tareas`
--
ALTER TABLE `tareas`
  ADD CONSTRAINT `tareas_id_maquina_foreign` FOREIGN KEY (`id_maquina`) REFERENCES `maquinas` (`id_maquina`),
  ADD CONSTRAINT `tareas_usuario_creacion_foreign` FOREIGN KEY (`usuario_creacion`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `tarea_usuario`
--
ALTER TABLE `tarea_usuario`
  ADD CONSTRAINT `tarea_usuario_id_tarea_foreign` FOREIGN KEY (`id_tarea`) REFERENCES `tareas` (`id_tarea`) ON DELETE CASCADE,
  ADD CONSTRAINT `tarea_usuario_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;