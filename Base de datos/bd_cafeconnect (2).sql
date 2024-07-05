-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 06-07-2024 a las 01:03:25
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bd cafeconnect`
--
CREATE DATABASE IF NOT EXISTS `bd cafeconnect` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `bd cafeconnect`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbbanco`
--

DROP TABLE IF EXISTS `tbbanco`;
CREATE TABLE `tbbanco` (
  `IdBancoPK` int(10) NOT NULL,
  `BanNombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbbanco`
--

INSERT INTO `tbbanco` (`IdBancoPK`, `BanNombre`) VALUES
(1, 'bancolombia'),
(2, 'davivienda'),
(3, 'banco bogota'),
(4, 'banco w'),
(5, 'banco av villas'),
(6, 'caja social');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbentregas`
--

DROP TABLE IF EXISTS `tbentregas`;
CREATE TABLE `tbentregas` (
  `IdEntregasPK` int(10) NOT NULL,
  `IdprovedorFK` int(10) NOT NULL,
  `EntreValorCosto` float(10,0) NOT NULL,
  `EntreCantidad` int(10) NOT NULL,
  `EntreFecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbentregas`
--

INSERT INTO `tbentregas` (`IdEntregasPK`, `IdprovedorFK`, `EntreValorCosto`, `EntreCantidad`, `EntreFecha`) VALUES
(7, 1, 50000, 25, '2023-11-26'),
(8, 4, 25000, 15, '2023-11-25'),
(9, 7, 15600, 24, '2023-11-24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbfacturas`
--

DROP TABLE IF EXISTS `tbfacturas`;
CREATE TABLE `tbfacturas` (
  `IdFacturaPK` int(10) NOT NULL,
  `FacFecha` date NOT NULL,
  `FacHora` time NOT NULL,
  `IdMedioPagoFK` int(10) NOT NULL,
  `FacValorFactura` float(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbfacturas`
--

INSERT INTO `tbfacturas` (`IdFacturaPK`, `FacFecha`, `FacHora`, `IdMedioPagoFK`, `FacValorFactura`) VALUES
(1, '2023-11-25', '05:30:00', 1, 10000),
(2, '2023-11-26', '08:00:00', 1, 2200),
(3, '2023-11-26', '12:00:00', 2, 12500);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbinventario`
--

DROP TABLE IF EXISTS `tbinventario`;
CREATE TABLE `tbinventario` (
  `IdProductoFK` int(10) NOT NULL,
  `IdEnttregasFK` int(10) NOT NULL,
  `InveValorVenta` float(10,0) NOT NULL,
  `InveStock` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbinventario`
--

INSERT INTO `tbinventario` (`IdProductoFK`, `IdEnttregasFK`, `InveValorVenta`, `InveStock`) VALUES
(1, 7, 2500, 25),
(5, 8, 1700, 15),
(7, 9, 650, 24);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbitems`
--

DROP TABLE IF EXISTS `tbitems`;
CREATE TABLE `tbitems` (
  `IdproductoFK` int(10) NOT NULL,
  `IdFacturaFK` int(10) NOT NULL,
  `ItemCantidad` int(10) NOT NULL,
  `ItemValorTotal` float(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbitems`
--

INSERT INTO `tbitems` (`IdproductoFK`, `IdFacturaFK`, `ItemCantidad`, `ItemValorTotal`) VALUES
(9, 3, 3, 7500),
(7, 3, 5, 4500),
(1, 1, 4, 10000);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbmediopago`
--

DROP TABLE IF EXISTS `tbmediopago`;
CREATE TABLE `tbmediopago` (
  `IdMedioPagoPK` int(10) NOT NULL,
  `MedNombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbmediopago`
--

INSERT INTO `tbmediopago` (`IdMedioPagoPK`, `MedNombre`) VALUES
(1, 'efectivo'),
(2, 'tarjeta de credito/debito'),
(3, 'pago movil');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbproducto`
--

DROP TABLE IF EXISTS `tbproducto`;
CREATE TABLE `tbproducto` (
  `IdProductoPK` int(10) NOT NULL,
  `ProNombre` varchar(100) NOT NULL,
  `ProPresentacion` text NOT NULL,
  `ProMinimoStock` int(10) NOT NULL DEFAULT 3
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbproducto`
--

INSERT INTO `tbproducto` (`IdProductoPK`, `ProNombre`, `ProPresentacion`, `ProMinimoStock`) VALUES
(1, 'gaseosa coca cola', '400 ml', 20),
(2, 'jugo Hit', ' 500 ml', 20),
(3, ' Agua Salva', '600ml', 20),
(4, 'jugo Cifrut', '250 ml', 10),
(5, 'Gaseosa postobon', '250 ml', 20),
(6, 'papas margarita natural', '35 g', 15),
(7, 'Galletas Festival', ' x 4 unidades', 24),
(8, ' Galletas chokis chocobase ', ' x 6 unidades', 12),
(9, ' Empanadas de pollo', ' 15 cm', 5),
(10, ' papa rellena ', ' grande ', 5),
(11, ' pastel de yuca', ' grande', 5),
(12, ' trocipollo', ' 30 gr', 10),
(13, 'ponky ', '30 gr', 15),
(14, 'papas Margarita pollo', '35 gr', 15);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbproovedores`
--

DROP TABLE IF EXISTS `tbproovedores`;
CREATE TABLE `tbproovedores` (
  `IdProvedorPK` int(10) NOT NULL,
  `ProvIdentificacion` varchar(20) NOT NULL,
  `ProvNombre` varchar(100) NOT NULL,
  `ProvEmail` varchar(100) NOT NULL,
  `ProvCelular` varchar(20) NOT NULL,
  `ProvNcuenta` varchar(20) NOT NULL,
  `ProvTipoCuenta` varchar(30) NOT NULL,
  `IdBancoFK` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbproovedores`
--

INSERT INTO `tbproovedores` (`IdProvedorPK`, `ProvIdentificacion`, `ProvNombre`, `ProvEmail`, `ProvCelular`, `ProvNcuenta`, `ProvTipoCuenta`, `IdBancoFK`) VALUES
(1, '2345678901', 'Roberto López', 'roberto.lopez@hotmail.com', '3101234567', '1234567890123456', 'corriente', 1),
(2, '10324789456', 'Carlos ´Garcia', 'Carlos.Garcia1@hotmail.com', '3124096754', '123786789012355', 'ahorro', 6),
(3, '1033456734', 'María Rodríguez', 'maria.rodriguez@gmail.com', '3204567834', '9876543210987654', 'ahorro', 2),
(4, '1234-567890-12-9', 'leidy vanesa García', 'leidy.vanesa.garcia@hotmail.com', '3113347890', '2345678901234567', 'ahorro', 1),
(5, '1031278653', 'Laura Hernández', 'laura.h@hotmail.com', '3224902136', '8901234567890123', 'corriente', 3),
(6, '555123456', 'Pedro Sánchez', 'pedro.3131@outlokk.es', '3012550987', '7654321098765432', 'ahorros', 4),
(7, '197039', 'remigio prada', 'remiprada@gmail.com', '3001279908', '3456789012345678', 'corriente', 5),
(8, '1076716133', 'karina almario', 'karina1312@gmail.com', '3134094567', '0987654321098765', 'corriente', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbrol`
--

DROP TABLE IF EXISTS `tbrol`;
CREATE TABLE `tbrol` (
  `IdRolPK` int(10) NOT NULL,
  `RolNombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbrol`
--

INSERT INTO `tbrol` (`IdRolPK`, `RolNombre`) VALUES
(1, 'Administrador'),
(2, 'Desarollador'),
(3, 'Vendedor');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbusuario`
--

DROP TABLE IF EXISTS `tbusuario`;
CREATE TABLE `tbusuario` (
  `IdUsuarioPK` int(20) NOT NULL,
  `IdRolFK` int(10) NOT NULL,
  `UsuNombre` varchar(100) NOT NULL,
  `UsuApellidos` varchar(100) NOT NULL,
  `UsuEmail` varchar(100) NOT NULL,
  `UsuContrasena` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbusuario`
--

INSERT INTO `tbusuario` (`IdUsuarioPK`, `IdRolFK`, `UsuNombre`, `UsuApellidos`, `UsuEmail`, `UsuContrasena`) VALUES
(8, 2, 'Diego Andres', 'Mora ', 'damg1312@hotmail.com', '827ccb0eea8a706c4c34a16891f84e7b'),
(9, 1, 'Gloria Marcela ', 'Prada ', 'gmarprada@gmail.com', 'ead9c5a4ac924793f984a1b86ed38203'),
(10, 3, 'Blanca ', 'Gacha', 'gachacecilia@gmail.com', '43844a3e4c594a2dbbc66f14c52b0945');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `token_acceso`
--

DROP TABLE IF EXISTS `token_acceso`;
CREATE TABLE `token_acceso` (
  `ID_TOKEN` varchar(100) NOT NULL,
  `ID_USUARIO_FK` varchar(50) NOT NULL,
  `USUARIO` varchar(200) NOT NULL,
  `FECHA_REG` varchar(20) NOT NULL,
  `HORA_REG` varchar(20) NOT NULL,
  `ESTADO` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `token_acceso`
--

INSERT INTO `token_acceso` (`ID_TOKEN`, `ID_USUARIO_FK`, `USUARIO`, `FECHA_REG`, `HORA_REG`, `ESTADO`) VALUES
('06e19f8970bdf8827d37d6d9c8cccbcd', '8', 'Diego Andres Mora ', '2024-07-05 11:22:21', '2024-07-05 11:22:21', 'ACTIVO'),
('17e370294b2783ec44e0708523364024', '8', 'Diego Andres Mora ', '2024-07-05 11:23:54', '2024-07-05 11:23:54', 'ACTIVO'),
('269021fc90dd31c76ff3b55e50da9b41', '8', 'Diego Andres Mora ', '2024-07-02 11:03:00', '2024-07-02 11:03:00', 'ACTIVO'),
('3b7fd97b549b0eaa5409f050e6e4e9fa', '8', 'Diego Andres Mora ', '2024-07-02 11:31:40', '2024-07-02 11:31:40', 'ACTIVO'),
('3bb4db031f4f27678d3bebf8e83b4e74', '8', 'Diego Andres Mora ', '2024-07-05 09:41:23', '2024-07-05 09:41:23', 'ACTIVO'),
('3f5dff888917f3d70b16205210727034', '8', 'Diego Andres Mora ', '2024-07-04 12:20:39', '2024-07-04 12:20:39', 'ACTIVO'),
('3fbece235652d610e9e757d7ee449af5', '8', 'Diego Andres Mora ', '2024-07-04 12:01:39', '2024-07-04 12:01:39', 'ACTIVO'),
('48d6cc3c337dac7fffecff2e933fa6b8', '8', 'Diego Andres Mora ', '2024-07-05 17:44:43', '2024-07-05 17:44:43', 'ACTIVO'),
('648afeb3b98a8388de99eb8a4e5cbdcf', '8', 'Diego Andres Mora ', '2024-07-05 17:45:45', '2024-07-05 17:45:45', 'ACTIVO'),
('648bda1e524d1fcd6b5a65f69c1a1fb8', '8', 'Diego Andres ', '2024-07-02 09:52:00', '2024-07-02 09:52:00', 'ACTIVO'),
('6c248c4a3471722b847b24cf364e9684', '8', 'Diego Andres Mora ', '2024-07-02 11:41:32', '2024-07-02 11:41:32', 'ACTIVO'),
('74dad13664385c8efa290efa560dfa13', '8', 'Diego Andres Mora ', '2024-07-02 11:30:29', '2024-07-02 11:30:29', 'ACTIVO'),
('82e24c600f182c54763cf46a2d6b25f3', '8', 'Diego Andres Mora ', '2024-07-05 11:33:04', '2024-07-05 11:33:04', 'ACTIVO'),
('8584f58519d71f76199abe6aaa83ebc3', '8', 'Diego Andres Mora ', '2024-07-05 17:57:59', '2024-07-05 17:57:59', 'ACTIVO'),
('8b9a817b671da0500d9cfebe82621053', '8', 'Diego Andres Mora ', '2024-07-05 11:26:42', '2024-07-05 11:26:42', 'ACTIVO'),
('9503c07ce51d00dcffc1bffbb47f7016', '8', 'Diego Andres Mora ', '2024-07-05 11:43:13', '2024-07-05 11:43:13', 'ACTIVO'),
('a1d14a3f2ef762fb891fb2ffad2bf05f', '8', 'Diego Andres Mora ', '2024-07-05 17:32:47', '2024-07-05 17:32:47', 'ACTIVO'),
('c09f7ec8e5365574ea37be6fa7aced54', '8', 'Diego Andres Mora ', '2024-07-05 11:27:32', '2024-07-05 11:27:32', 'ACTIVO'),
('d708c7dfc1042063f97ee9107c2f220a', '8', 'Diego Andres Mora ', '2024-07-05 17:36:43', '2024-07-05 17:36:43', 'ACTIVO'),
('dbfe48335739681d7fe1fd52641ff8bf', '8', 'Diego Andres Mora ', '2024-07-05 11:59:03', '2024-07-05 11:59:03', 'ACTIVO'),
('ded5ca4aacb3c97bf9f2e99ed7fef996', '8', 'Diego Andres Mora ', '2024-07-05 11:50:02', '2024-07-05 11:50:02', 'ACTIVO'),
('df344b83b4898c4cd5a236cbe80676e4', '8', 'Diego Andres ', '2024-07-02 09:52:28', '2024-07-02 09:52:28', 'ACTIVO'),
('e1a30d97eb70a4202571cd1edfa4082e', '8', 'Diego Andres Mora ', '2024-07-02 11:17:39', '2024-07-02 11:17:39', 'ACTIVO');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `tbbanco`
--
ALTER TABLE `tbbanco`
  ADD PRIMARY KEY (`IdBancoPK`);

--
-- Indices de la tabla `tbentregas`
--
ALTER TABLE `tbentregas`
  ADD PRIMARY KEY (`IdEntregasPK`),
  ADD KEY `tbEntregas_fk0` (`IdprovedorFK`);

--
-- Indices de la tabla `tbfacturas`
--
ALTER TABLE `tbfacturas`
  ADD PRIMARY KEY (`IdFacturaPK`),
  ADD KEY `tbFacturas_fk0` (`IdMedioPagoFK`);

--
-- Indices de la tabla `tbinventario`
--
ALTER TABLE `tbinventario`
  ADD KEY `tbInventario_fk0` (`IdProductoFK`),
  ADD KEY `tbInventario_fk1` (`IdEnttregasFK`);

--
-- Indices de la tabla `tbitems`
--
ALTER TABLE `tbitems`
  ADD KEY `tbItems_fk0` (`IdproductoFK`),
  ADD KEY `tbItems_fk1` (`IdFacturaFK`);

--
-- Indices de la tabla `tbmediopago`
--
ALTER TABLE `tbmediopago`
  ADD PRIMARY KEY (`IdMedioPagoPK`);

--
-- Indices de la tabla `tbproducto`
--
ALTER TABLE `tbproducto`
  ADD PRIMARY KEY (`IdProductoPK`);

--
-- Indices de la tabla `tbproovedores`
--
ALTER TABLE `tbproovedores`
  ADD PRIMARY KEY (`IdProvedorPK`),
  ADD UNIQUE KEY `ProvNcuenta` (`ProvNcuenta`),
  ADD KEY `tbproovedores_fk0` (`IdBancoFK`);

--
-- Indices de la tabla `tbrol`
--
ALTER TABLE `tbrol`
  ADD PRIMARY KEY (`IdRolPK`);

--
-- Indices de la tabla `tbusuario`
--
ALTER TABLE `tbusuario`
  ADD PRIMARY KEY (`IdUsuarioPK`),
  ADD KEY `tbUsuario_fk0` (`IdRolFK`);

--
-- Indices de la tabla `token_acceso`
--
ALTER TABLE `token_acceso`
  ADD PRIMARY KEY (`ID_TOKEN`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `tbbanco`
--
ALTER TABLE `tbbanco`
  MODIFY `IdBancoPK` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `tbentregas`
--
ALTER TABLE `tbentregas`
  MODIFY `IdEntregasPK` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `tbfacturas`
--
ALTER TABLE `tbfacturas`
  MODIFY `IdFacturaPK` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tbmediopago`
--
ALTER TABLE `tbmediopago`
  MODIFY `IdMedioPagoPK` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tbproducto`
--
ALTER TABLE `tbproducto`
  MODIFY `IdProductoPK` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `tbproovedores`
--
ALTER TABLE `tbproovedores`
  MODIFY `IdProvedorPK` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `tbrol`
--
ALTER TABLE `tbrol`
  MODIFY `IdRolPK` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tbusuario`
--
ALTER TABLE `tbusuario`
  MODIFY `IdUsuarioPK` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1032441822;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `tbentregas`
--
ALTER TABLE `tbentregas`
  ADD CONSTRAINT `tbEntregas_fk0` FOREIGN KEY (`IdprovedorFK`) REFERENCES `tbproovedores` (`IdProvedorPK`);

--
-- Filtros para la tabla `tbfacturas`
--
ALTER TABLE `tbfacturas`
  ADD CONSTRAINT `tbFacturas_fk0` FOREIGN KEY (`IdMedioPagoFK`) REFERENCES `tbmediopago` (`IdMedioPagoPK`);

--
-- Filtros para la tabla `tbinventario`
--
ALTER TABLE `tbinventario`
  ADD CONSTRAINT `tbInventario_fk0` FOREIGN KEY (`IdProductoFK`) REFERENCES `tbproducto` (`IdProductoPK`),
  ADD CONSTRAINT `tbInventario_fk1` FOREIGN KEY (`IdEnttregasFK`) REFERENCES `tbentregas` (`IdEntregasPK`);

--
-- Filtros para la tabla `tbitems`
--
ALTER TABLE `tbitems`
  ADD CONSTRAINT `tbItems_fk0` FOREIGN KEY (`IdproductoFK`) REFERENCES `tbproducto` (`IdProductoPK`),
  ADD CONSTRAINT `tbItems_fk1` FOREIGN KEY (`IdFacturaFK`) REFERENCES `tbfacturas` (`IdFacturaPK`);

--
-- Filtros para la tabla `tbproovedores`
--
ALTER TABLE `tbproovedores`
  ADD CONSTRAINT `tbproovedores_fk0` FOREIGN KEY (`IdBancoFK`) REFERENCES `tbbanco` (`IdBancoPK`);

--
-- Filtros para la tabla `tbusuario`
--
ALTER TABLE `tbusuario`
  ADD CONSTRAINT `tbUsuario_fk0` FOREIGN KEY (`IdRolFK`) REFERENCES `tbrol` (`IdRolPK`);
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
