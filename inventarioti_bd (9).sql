-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-08-2025 a las 21:27:54
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
-- Base de datos: `inventarioti_bd`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbarea`
--

CREATE TABLE `tbarea` (
  `id_Area` int(11) NOT NULL,
  `Area` varchar(200) NOT NULL,
  `estado` tinyint(1) NOT NULL,
  `fecha_creacion` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `centro_costos` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbarea`
--

INSERT INTO `tbarea` (`id_Area`, `Area`, `estado`, `fecha_creacion`, `fecha_fin`, `centro_costos`) VALUES
(1, 'T.I', 1, '2025-05-16', NULL, '123-1'),
(2, 'Talento Humano', 1, '2025-05-30', NULL, '123-2'),
(3, 'CGD', 1, '2025-08-21', NULL, '3228-25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbasignado`
--

CREATE TABLE `tbasignado` (
  `id_Asig` int(11) NOT NULL,
  `Id_Empl` int(11) NOT NULL,
  `id_Eq` int(11) NOT NULL,
  `observaciones` varchar(250) NOT NULL,
  `descripcion` varchar(250) NOT NULL,
  `acta` int(12) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `estado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbasignado`
--

INSERT INTO `tbasignado` (`id_Asig`, `Id_Empl`, `id_Eq`, `observaciones`, `descripcion`, `acta`, `fecha_inicio`, `fecha_fin`, `estado`) VALUES
(7, 3, 36, 'N/A', 'COMPUTADOR DELL 245-G7 PROCESADOR AMD RYZEN 5 16GB-RAM DDR4 3200MHZ 240GB-SSD-M.2 CARGADOR DE CORRIENTE ELECTRICA Y MALETIN', 1, '2025-08-21', '0000-00-00', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbcontrato`
--

CREATE TABLE `tbcontrato` (
  `Id_Contrato` int(11) NOT NULL,
  `NumeroContrato` varchar(250) NOT NULL,
  `fecha_Inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `estado` tinyint(1) NOT NULL,
  `id_Empresa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbcontrato`
--

INSERT INTO `tbcontrato` (`Id_Contrato`, `NumeroContrato`, `fecha_Inicio`, `fecha_fin`, `estado`, `id_Empresa`) VALUES
(1, '178899-8', '2025-07-04', '2025-07-11', 1, 5),
(2, '5744498-3', '2025-07-07', '2025-07-24', 1, 6),
(3, 'CA00024-12-2023', '2025-01-01', '2025-12-31', 1, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbdevolucion`
--

CREATE TABLE `tbdevolucion` (
  `id_Dev` int(11) NOT NULL,
  `id_Equip` int(11) NOT NULL,
  `id_Asig` int(11) NOT NULL,
  `descipcion` varchar(250) NOT NULL,
  `id_Empresa` int(11) NOT NULL,
  `fecha_dev` date NOT NULL,
  `fecha_retorno` date NOT NULL,
  `observaciones` varchar(200) NOT NULL,
  `acta` int(12) NOT NULL,
  `estado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Disparadores `tbdevolucion`
--
DELIMITER $$
CREATE TRIGGER `Devolucion_Desactivar_Equipo` AFTER INSERT ON `tbdevolucion` FOR EACH ROW BEGIN
UPDATE tbasignado tba
SET tba.estado=0, tba.fecha_fin=NOW()
WHERE tba.id_Eq=NEW.id_Equip AND tba.estado=1;

UPDATE tbregequip tbr
SET tbr.estado=0, tbr.fecha_finalizacion=NOW()
WHERE tbr.id_Equip=NEW.id_Equip AND tbr.estado=1;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbempleado`
--

CREATE TABLE `tbempleado` (
  `id_Empl` int(11) NOT NULL,
  `nombre` varchar(120) NOT NULL,
  `apellido` varchar(120) NOT NULL,
  `Cargo` varchar(50) NOT NULL,
  `id_Area` int(11) NOT NULL,
  `estado` tinyint(1) NOT NULL,
  `cedula` int(15) NOT NULL,
  `correo` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbempleado`
--

INSERT INTO `tbempleado` (`id_Empl`, `nombre`, `apellido`, `Cargo`, `id_Area`, `estado`, `cedula`, `correo`) VALUES
(3, 'JUAN PABLO', 'GIRALDO HOLGUIN', 'Practicante', 1, 1, 1000548300, 'practicanteti@jbotanico.org'),
(4, 'NOMBRE PRUEBA', 'APELLIDO PRUEBA', 'Prueba', 2, 1, 43088387, 'practicanteti@jbotanico.org'),
(9, 'LUCAS', 'SALAZAR', 'Auxiliar', 1, 1, 103649258, 'lucas.salazar@jbotanico.org'),
(10, 'RODRIGO', 'PAI', 'Coordinador', 1, 1, 2147483647, '1111111@GMAIL.COM'),
(11, 'JUAN', 'GIRALDO', 'Prueba 2', 2, 1, 1036528456, 'myv@gmail.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbempresas`
--

CREATE TABLE `tbempresas` (
  `id_Empresa` int(11) NOT NULL,
  `Empresa` varchar(150) NOT NULL,
  `fecha_creacion` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `estado` tinyint(1) DEFAULT NULL,
  `NIT` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbempresas`
--

INSERT INTO `tbempresas` (`id_Empresa`, `Empresa`, `fecha_creacion`, `fecha_fin`, `estado`, `NIT`) VALUES
(5, 'ALCOM', '2025-05-01', '0000-00-00', 1, '811021798'),
(6, 'MOVISTAR', '2025-05-01', '0000-00-00', 1, '830122566'),
(7, 'JARDIN BOTANICO', '2025-05-01', '0000-00-00', 1, '860030197');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbequipos`
--

CREATE TABLE `tbequipos` (
  `id_Equip` int(11) NOT NULL,
  `placa` int(11) NOT NULL,
  `serial` varchar(120) NOT NULL,
  `fecha_creacion` date NOT NULL,
  `estado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbequipos`
--

INSERT INTO `tbequipos` (`id_Equip`, `placa`, `serial`, `fecha_creacion`, `estado`) VALUES
(36, 7099, '5CG012236', '2025-08-21', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbinventario`
--

CREATE TABLE `tbinventario` (
  `id_Inv` int(11) NOT NULL,
  `descripcion` varchar(180) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `empresadecompra` varchar(200) NOT NULL,
  `fecha_compra` date NOT NULL,
  `id_TipoU` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tborden`
--

CREATE TABLE `tborden` (
  `id_Orden` int(11) NOT NULL,
  `orden_Servicio` varchar(50) NOT NULL,
  `orden_Compra` varchar(50) NOT NULL,
  `fecha_Entrega` date NOT NULL,
  `id_TipoOrden` int(11) NOT NULL,
  `id_Contrato` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tborden`
--

INSERT INTO `tborden` (`id_Orden`, `orden_Servicio`, `orden_Compra`, `fecha_Entrega`, `id_TipoOrden`, `id_Contrato`) VALUES
(13, '3228-25', 'CGD', '2025-08-21', 1, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbregequip`
--

CREATE TABLE `tbregequip` (
  `id_Reg` int(11) NOT NULL,
  `id_Equip` int(11) NOT NULL,
  `descripcion` varchar(200) NOT NULL,
  `observaciones` varchar(200) NOT NULL,
  `accesorios` varchar(120) NOT NULL,
  `fecha_creacion` date NOT NULL,
  `fecha_finalizacion` date NOT NULL,
  `estado` tinyint(1) NOT NULL,
  `id_Empresa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbregequip`
--

INSERT INTO `tbregequip` (`id_Reg`, `id_Equip`, `descripcion`, `observaciones`, `accesorios`, `fecha_creacion`, `fecha_finalizacion`, `estado`, `id_Empresa`) VALUES
(16, 36, 'COMPUTADOR DELL 245-G7 PROCESADOR AMD RYZEN 5 16GB-RAM DDR4 3200MHZ 240GB-SSD-M.2 CARGADOR DE CORRIENTE ELECTRICA Y MALETIN', 'N/A', 'MOUSE Y GUAYA CODIGO:2055', '2025-08-21', '0000-00-00', 1, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbtipoorden`
--

CREATE TABLE `tbtipoorden` (
  `id_TipoOrden` int(11) NOT NULL,
  `Tipo_Orden` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbtipoorden`
--

INSERT INTO `tbtipoorden` (`id_TipoOrden`, `Tipo_Orden`) VALUES
(1, 'Instalacion'),
(2, 'Cambio'),
(3, 'Devolucion');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbtipou`
--

CREATE TABLE `tbtipou` (
  `id_TipoU` int(11) NOT NULL,
  `tipo_usuario` varchar(120) NOT NULL,
  `estado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbtipou`
--

INSERT INTO `tbtipou` (`id_TipoU`, `tipo_usuario`, `estado`) VALUES
(1, 'Coordinador', 1),
(2, 'Practicante', 1),
(3, 'Analista', 1),
(4, 'Auxiliar', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbusado_por`
--

CREATE TABLE `tbusado_por` (
  `id_UP` int(11) NOT NULL,
  `id_Inv` int(11) NOT NULL,
  `Id_Empl` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `fecha` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbusuarios`
--

CREATE TABLE `tbusuarios` (
  `id_Usuarios` int(11) NOT NULL,
  `nombre` varchar(80) NOT NULL,
  `apellidos` varchar(120) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `contraseña` varchar(200) NOT NULL,
  `estado` tinyint(1) DEFAULT NULL,
  `fecha_creacion` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `id_tipoU` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbusuarios`
--

INSERT INTO `tbusuarios` (`id_Usuarios`, `nombre`, `apellidos`, `correo`, `contraseña`, `estado`, `fecha_creacion`, `fecha_fin`, `id_tipoU`) VALUES
(15, 'JUAN', 'GIRALDO', 'practicanteti@jbotanico.org', 'Jardin2025', 1, '2025-04-01', '0000-00-00', 2),
(16, 'LUCAS', 'SALAZAR', 'lucas.salazar@jbotanico.org', 'Jardin2025', 1, '2025-08-22', '0000-00-00', 4),
(17, 'RODRIGO', 'GALLO', 'rodrigo.gallo@jbotanico.org', 'Jardin2025', 1, '2025-08-22', '0000-00-00', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_equipoxorden`
--

CREATE TABLE `tb_equipoxorden` (
  `id_ExO` int(11) NOT NULL,
  `id_Equipo` int(11) NOT NULL,
  `id_Orden` int(11) NOT NULL,
  `fecha_Entrega` date NOT NULL,
  `Fecha_Salida` date NOT NULL,
  `estado` tinyint(1) NOT NULL,
  `estado_devolucion` tinyint(1) NOT NULL,
  `orden_original` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tb_equipoxorden`
--

INSERT INTO `tb_equipoxorden` (`id_ExO`, `id_Equipo`, `id_Orden`, `fecha_Entrega`, `Fecha_Salida`, `estado`, `estado_devolucion`, `orden_original`) VALUES
(21, 36, 13, '2025-08-21', '0000-00-00', 1, 0, 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `tbarea`
--
ALTER TABLE `tbarea`
  ADD PRIMARY KEY (`id_Area`);

--
-- Indices de la tabla `tbasignado`
--
ALTER TABLE `tbasignado`
  ADD PRIMARY KEY (`id_Asig`),
  ADD KEY `Id_Empl` (`Id_Empl`,`id_Eq`),
  ADD KEY `id_Eq` (`id_Eq`);

--
-- Indices de la tabla `tbcontrato`
--
ALTER TABLE `tbcontrato`
  ADD PRIMARY KEY (`Id_Contrato`),
  ADD KEY `id_Empresa` (`id_Empresa`);

--
-- Indices de la tabla `tbdevolucion`
--
ALTER TABLE `tbdevolucion`
  ADD PRIMARY KEY (`id_Dev`),
  ADD KEY `id_Equip` (`id_Equip`),
  ADD KEY `id_Asig` (`id_Asig`) USING BTREE,
  ADD KEY `id_Empresa` (`id_Empresa`);

--
-- Indices de la tabla `tbempleado`
--
ALTER TABLE `tbempleado`
  ADD PRIMARY KEY (`id_Empl`),
  ADD KEY `id_Area` (`id_Area`);

--
-- Indices de la tabla `tbempresas`
--
ALTER TABLE `tbempresas`
  ADD PRIMARY KEY (`id_Empresa`);

--
-- Indices de la tabla `tbequipos`
--
ALTER TABLE `tbequipos`
  ADD PRIMARY KEY (`id_Equip`);

--
-- Indices de la tabla `tbinventario`
--
ALTER TABLE `tbinventario`
  ADD PRIMARY KEY (`id_Inv`),
  ADD KEY `id_Area` (`id_TipoU`);

--
-- Indices de la tabla `tborden`
--
ALTER TABLE `tborden`
  ADD PRIMARY KEY (`id_Orden`),
  ADD KEY `id_TipoOrden` (`id_TipoOrden`,`id_Contrato`),
  ADD KEY `id_Contrato` (`id_Contrato`);

--
-- Indices de la tabla `tbregequip`
--
ALTER TABLE `tbregequip`
  ADD PRIMARY KEY (`id_Reg`),
  ADD KEY `id_Equip` (`id_Equip`),
  ADD KEY `id_Empresa` (`id_Empresa`);

--
-- Indices de la tabla `tbtipoorden`
--
ALTER TABLE `tbtipoorden`
  ADD PRIMARY KEY (`id_TipoOrden`);

--
-- Indices de la tabla `tbtipou`
--
ALTER TABLE `tbtipou`
  ADD PRIMARY KEY (`id_TipoU`);

--
-- Indices de la tabla `tbusado_por`
--
ALTER TABLE `tbusado_por`
  ADD PRIMARY KEY (`id_UP`),
  ADD KEY `id_Inv` (`id_Inv`,`Id_Empl`),
  ADD KEY `Id_Empl` (`Id_Empl`);

--
-- Indices de la tabla `tbusuarios`
--
ALTER TABLE `tbusuarios`
  ADD PRIMARY KEY (`id_Usuarios`),
  ADD KEY `id_tipoU` (`id_tipoU`);

--
-- Indices de la tabla `tb_equipoxorden`
--
ALTER TABLE `tb_equipoxorden`
  ADD PRIMARY KEY (`id_ExO`),
  ADD KEY `id_Equipo` (`id_Equipo`,`id_Orden`),
  ADD KEY `id_Orden` (`id_Orden`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `tbarea`
--
ALTER TABLE `tbarea`
  MODIFY `id_Area` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tbasignado`
--
ALTER TABLE `tbasignado`
  MODIFY `id_Asig` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `tbcontrato`
--
ALTER TABLE `tbcontrato`
  MODIFY `Id_Contrato` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tbdevolucion`
--
ALTER TABLE `tbdevolucion`
  MODIFY `id_Dev` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `tbempleado`
--
ALTER TABLE `tbempleado`
  MODIFY `id_Empl` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `tbempresas`
--
ALTER TABLE `tbempresas`
  MODIFY `id_Empresa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `tbequipos`
--
ALTER TABLE `tbequipos`
  MODIFY `id_Equip` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de la tabla `tbinventario`
--
ALTER TABLE `tbinventario`
  MODIFY `id_Inv` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tborden`
--
ALTER TABLE `tborden`
  MODIFY `id_Orden` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `tbregequip`
--
ALTER TABLE `tbregequip`
  MODIFY `id_Reg` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `tbtipoorden`
--
ALTER TABLE `tbtipoorden`
  MODIFY `id_TipoOrden` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tbtipou`
--
ALTER TABLE `tbtipou`
  MODIFY `id_TipoU` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tbusado_por`
--
ALTER TABLE `tbusado_por`
  MODIFY `id_UP` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbusuarios`
--
ALTER TABLE `tbusuarios`
  MODIFY `id_Usuarios` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `tb_equipoxorden`
--
ALTER TABLE `tb_equipoxorden`
  MODIFY `id_ExO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `tbasignado`
--
ALTER TABLE `tbasignado`
  ADD CONSTRAINT `tbasignado_ibfk_1` FOREIGN KEY (`Id_Empl`) REFERENCES `tbempleado` (`id_Empl`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `tbasignado_ibfk_2` FOREIGN KEY (`id_Eq`) REFERENCES `tbequipos` (`id_Equip`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tbcontrato`
--
ALTER TABLE `tbcontrato`
  ADD CONSTRAINT `tbcontrato_ibfk_1` FOREIGN KEY (`id_Empresa`) REFERENCES `tbempresas` (`id_Empresa`);

--
-- Filtros para la tabla `tbdevolucion`
--
ALTER TABLE `tbdevolucion`
  ADD CONSTRAINT `tbdevolucion_ibfk_1` FOREIGN KEY (`id_Equip`) REFERENCES `tbequipos` (`id_Equip`),
  ADD CONSTRAINT `tbdevolucion_ibfk_2` FOREIGN KEY (`id_Asig`) REFERENCES `tbasignado` (`id_Asig`),
  ADD CONSTRAINT `tbdevolucion_ibfk_3` FOREIGN KEY (`id_Empresa`) REFERENCES `tbempresas` (`id_Empresa`);

--
-- Filtros para la tabla `tbempleado`
--
ALTER TABLE `tbempleado`
  ADD CONSTRAINT `tbempleado_ibfk_1` FOREIGN KEY (`id_Area`) REFERENCES `tbarea` (`id_Area`);

--
-- Filtros para la tabla `tbinventario`
--
ALTER TABLE `tbinventario`
  ADD CONSTRAINT `tbinventario_ibfk_1` FOREIGN KEY (`id_TipoU`) REFERENCES `tbtipou` (`id_TipoU`);

--
-- Filtros para la tabla `tborden`
--
ALTER TABLE `tborden`
  ADD CONSTRAINT `tborden_ibfk_1` FOREIGN KEY (`id_Contrato`) REFERENCES `tbcontrato` (`Id_Contrato`),
  ADD CONSTRAINT `tborden_ibfk_2` FOREIGN KEY (`id_TipoOrden`) REFERENCES `tbtipoorden` (`id_TipoOrden`);

--
-- Filtros para la tabla `tbregequip`
--
ALTER TABLE `tbregequip`
  ADD CONSTRAINT `tbregequip_ibfk_1` FOREIGN KEY (`id_Equip`) REFERENCES `tbequipos` (`id_Equip`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `tbregequip_ibfk_2` FOREIGN KEY (`id_Empresa`) REFERENCES `tbempresas` (`id_Empresa`);

--
-- Filtros para la tabla `tbusado_por`
--
ALTER TABLE `tbusado_por`
  ADD CONSTRAINT `tbusado_por_ibfk_1` FOREIGN KEY (`id_Inv`) REFERENCES `tbinventario` (`id_Inv`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `tbusado_por_ibfk_2` FOREIGN KEY (`Id_Empl`) REFERENCES `tbempleado` (`id_Empl`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tbusuarios`
--
ALTER TABLE `tbusuarios`
  ADD CONSTRAINT `tbusuarios_ibfk_1` FOREIGN KEY (`id_tipoU`) REFERENCES `tbtipou` (`id_TipoU`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tb_equipoxorden`
--
ALTER TABLE `tb_equipoxorden`
  ADD CONSTRAINT `tb_equipoxorden_ibfk_1` FOREIGN KEY (`id_Orden`) REFERENCES `tborden` (`id_Orden`),
  ADD CONSTRAINT `tb_equipoxorden_ibfk_2` FOREIGN KEY (`id_Equipo`) REFERENCES `tbequipos` (`id_Equip`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
