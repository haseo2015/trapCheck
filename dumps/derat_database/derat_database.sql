-- phpMyAdmin SQL Dump
-- version 4.0.10.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generato il: Giu 19, 2015 alle 10:05
-- Versione del server: 5.5.42-cll
-- Versione PHP: 5.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `fabiomon_derat`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `dr_app_users`
--

DROP TABLE IF EXISTS `dr_app_users`;
CREATE TABLE IF NOT EXISTS `dr_app_users` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `username` varchar(45) NOT NULL,
  `pwd` varchar(32) NOT NULL,
  `user_type` tinyint(1) unsigned zerofill NOT NULL DEFAULT '3',
  `creation_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `applicationDB` varchar(255) DEFAULT NULL,
  `customer_id` int(11) unsigned zerofill NOT NULL DEFAULT '00000000000',
  `id_trap_group` int(11) unsigned zerofill DEFAULT '00000000000',
  PRIMARY KEY (`id`,`username`),
  UNIQUE KEY `username_UNIQUE` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dump dei dati per la tabella `dr_app_users`
--

INSERT INTO `dr_app_users` (`id`, `username`, `pwd`, `user_type`, `creation_date`, `applicationDB`, `customer_id`, `id_trap_group`) VALUES
(0000000001, 'appUsr1', 'b11fe0aa8be0ef1c16d190349eeeefed', 3, '2015-06-11 15:38:39', NULL, 00000000000, 00000000000),
(0000000002, 'appUsr2', 'd755d00d06d0258e9adc21eb3657e40b', 2, '2015-06-11 15:38:40', NULL, 00000000000, 00000000000),
(0000000003, 'appUsr3', '19329410f7bc41cbe71167805f6ba2b8', 1, '2015-06-11 15:38:41', NULL, 00000000000, 00000000000);

-- --------------------------------------------------------

--
-- Struttura della tabella `dr_covered_areas`
--

DROP TABLE IF EXISTS `dr_covered_areas`;
CREATE TABLE IF NOT EXISTS `dr_covered_areas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) unsigned zerofill NOT NULL,
  `area_name` varchar(100) NOT NULL,
  `area_address` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dump dei dati per la tabella `dr_covered_areas`
--

INSERT INTO `dr_covered_areas` (`id`, `customer_id`, `area_name`, `area_address`) VALUES
(1, 00000000001, 'Area #1', 'Via tibutina 33'),
(2, 00000000002, 'Area #2', 'Via Appia, 44'),
(3, 00000000003, 'Area #3', 'Via Francesco Borromini, 37');

-- --------------------------------------------------------

--
-- Struttura della tabella `dr_customers`
--

DROP TABLE IF EXISTS `dr_customers`;
CREATE TABLE IF NOT EXISTS `dr_customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(50) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1' COMMENT '1=attivo | 0 = non attivo',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dump dei dati per la tabella `dr_customers`
--

INSERT INTO `dr_customers` (`id`, `customer_name`, `status`) VALUES
(1, 'TRAP AND RAT', 1),
(2, 'RAT AND TRAPS', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `dr_mobile_users`
--

DROP TABLE IF EXISTS `dr_mobile_users`;
CREATE TABLE IF NOT EXISTS `dr_mobile_users` (
  `id` int(11) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `pwd` varchar(32) NOT NULL,
  `user_type` tinyint(1) unsigned zerofill NOT NULL DEFAULT '3',
  `id_trap_group` int(11) unsigned zerofill NOT NULL DEFAULT '00000000000',
  `customer_id` int(11) unsigned zerofill NOT NULL DEFAULT '00000000000',
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username_UNIQUE` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Dump dei dati per la tabella `dr_mobile_users`
--

INSERT INTO `dr_mobile_users` (`id`, `username`, `pwd`, `user_type`, `id_trap_group`, `customer_id`, `creation_date`) VALUES
(00000000010, 'pippo', '234257e99df3c52670e114cf17690e3a', 2, 00000000001, 00000000000, '2015-06-18 22:20:26'),
(00000000002, 'mobUsr2', 'b89994af42cdaf9805b9c735b57c52f8', 1, 00000000002, 00000000002, '2015-06-05 17:44:23'),
(00000000003, 'mobUsr3', '31c9fea64043af8b95650eb95eaf435c', 2, 00000000002, 00000000002, '2015-05-22 21:35:39'),
(00000000004, 'mobUsr4', '0a75b4a0845009ec4923809700574db5', 1, 00000000002, 00000000002, '2015-06-08 17:28:04'),
(00000000006, 'mobUsr6', '44d3a9a3a3cf08d016ea2a77eca58127', 2, 00000000001, 00000000001, '2015-06-05 17:06:16'),
(00000000011, 'grppo', '809eefcca8ad3ceeabcd310805cb7f94', 1, 00000000000, 00000000001, '2015-06-18 22:25:07'),
(00000000012, 'araarara', '5f7e1dcd72b34f21880a8b6f63e69803', 2, 00000000000, 00000000000, '2015-06-18 22:25:35');

-- --------------------------------------------------------

--
-- Struttura della tabella `dr_photoxhistory`
--

DROP TABLE IF EXISTS `dr_photoxhistory`;
CREATE TABLE IF NOT EXISTS `dr_photoxhistory` (
  `history_trap_id` int(11) unsigned zerofill NOT NULL,
  `photo_id` int(11) unsigned zerofill DEFAULT NULL,
  PRIMARY KEY (`history_trap_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `dr_photo_galleries`
--

DROP TABLE IF EXISTS `dr_photo_galleries`;
CREATE TABLE IF NOT EXISTS `dr_photo_galleries` (
  `id` int(11) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `image_url` varchar(255) DEFAULT NULL,
  `photo_name` varchar(45) DEFAULT NULL,
  `id_covered_area` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dump dei dati per la tabella `dr_photo_galleries`
--

INSERT INTO `dr_photo_galleries` (`id`, `image_url`, `photo_name`, `id_covered_area`) VALUES
(00000000001, 'Via_Appia_acquedotti_1010299.JPG', 'Via Appia', 1),
(00000000002, 'Via-Tiburtina.jpg', 'Via Tiburtina', 2),
(00000000003, 'via-francesco-borromini.jpg', 'Via Francesco Borromini', 3);

-- --------------------------------------------------------

--
-- Struttura della tabella `dr_products`
--

DROP TABLE IF EXISTS `dr_products`;
CREATE TABLE IF NOT EXISTS `dr_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_name` varchar(100) DEFAULT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dump dei dati per la tabella `dr_products`
--

INSERT INTO `dr_products` (`id`, `product_name`, `brand`, `stock`) VALUES
(1, 'Scaccia Ratti', 'Geronimo Stillton', 100),
(2, 'Ammazza Topi', 'Topolino Muori', 100),
(3, 'No more SQUIT!', 'Hatemouses & Co.', 200);

-- --------------------------------------------------------

--
-- Struttura della tabella `dr_roles`
--

DROP TABLE IF EXISTS `dr_roles`;
CREATE TABLE IF NOT EXISTS `dr_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(45) NOT NULL,
  `role_permission` varchar(10) NOT NULL DEFAULT '*',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dump dei dati per la tabella `dr_roles`
--

INSERT INTO `dr_roles` (`id`, `role_name`, `role_permission`) VALUES
(1, 'SUPER ADMIN', '*'),
(2, 'APP USER', 'W'),
(3, 'TECNICO', 'W|C|E|D');

-- --------------------------------------------------------

--
-- Struttura della tabella `dr_settings`
--

DROP TABLE IF EXISTS `dr_settings`;
CREATE TABLE IF NOT EXISTS `dr_settings` (
  `db_vars` varchar(100) NOT NULL,
  PRIMARY KEY (`db_vars`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `dr_settings`
--

INSERT INTO `dr_settings` (`db_vars`) VALUES
('{"host":"localhost","dbu":"root","dbp":"admin"}');

-- --------------------------------------------------------

--
-- Struttura della tabella `dr_traps`
--

DROP TABLE IF EXISTS `dr_traps`;
CREATE TABLE IF NOT EXISTS `dr_traps` (
  `id` int(11) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `trap_id` varchar(255) NOT NULL,
  `trap_name` text NOT NULL,
  `customer_id` int(11) unsigned zerofill NOT NULL,
  `address` varchar(255) NOT NULL,
  `citta` varchar(255) NOT NULL,
  `latitude` float(10,6) unsigned zerofill DEFAULT NULL,
  `longitude` float(10,6) unsigned zerofill DEFAULT NULL,
  `x` varchar(45) DEFAULT NULL,
  `y` varchar(45) DEFAULT NULL,
  `trap_type` int(11) unsigned zerofill NOT NULL DEFAULT '00000000001',
  `trap_status` int(11) unsigned zerofill NOT NULL DEFAULT '00000000001',
  `product_id` int(11) unsigned zerofill NOT NULL DEFAULT '00000000001',
  `covered_area_id` int(11) unsigned zerofill NOT NULL DEFAULT '00000000001',
  `trap_group_id` int(11) unsigned zerofill NOT NULL,
  `notes` text,
  PRIMARY KEY (`id`,`trap_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=40 ;

--
-- Dump dei dati per la tabella `dr_traps`
--

INSERT INTO `dr_traps` (`id`, `trap_id`, `trap_name`, `customer_id`, `address`, `citta`, `latitude`, `longitude`, `x`, `y`, `trap_type`, `trap_status`, `product_id`, `covered_area_id`, `trap_group_id`, `notes`) VALUES
(00000000039, '9874238', 'Trappola 01', 00000000001, 'Via Assano 10', 'Cesena', 045.835258, 045.835258, NULL, NULL, 00000000002, 00000000001, 00000000033, 00000000022, 00000000001, NULL),
(00000000030, '9874238', 'Trappola 02', 00000000002, 'Via Assano 20', 'Cesena', 045.835258, 045.835258, NULL, NULL, 00000000002, 00000000001, 00000000033, 00000000022, 00000000001, NULL),
(00000000031, '9874238', 'Trappola 03', 00000000002, 'Via Lombardia 13', 'Cesena', 045.835258, 045.835258, NULL, NULL, 00000000002, 00000000001, 00000000033, 00000000022, 00000000003, NULL),
(00000000032, '9874238', 'Trappola 04', 00000000002, 'Via Picopallo 54', 'Cesena', 045.835258, 045.835258, NULL, NULL, 00000000002, 00000000001, 00000000033, 00000000022, 00000000003, NULL),
(00000000033, '9874238', 'Trappola 05', 00000000001, 'Piazza Cuoco 2', 'Cesena', 045.835258, 045.835258, NULL, NULL, 00000000002, 00000000001, 00000000033, 00000000022, 00000000001, NULL),
(00000000034, '9874238', 'Trappola 06', 00000000001, 'Viale Gorizia 129', 'Cesena', 045.835258, 045.835258, NULL, NULL, 00000000002, 00000000001, 00000000033, 00000000022, 00000000002, NULL),
(00000000035, '9874238', 'Trappola 07', 00000000002, 'Viale Gorizia 158', 'Cesena', 045.835258, 045.835258, NULL, NULL, 00000000002, 00000000001, 00000000033, 00000000022, 00000000002, NULL),
(00000000036, '9874238', 'Trappola 08', 00000000001, 'Piazzale Lotto 5', 'Cesena', 045.835258, 045.835258, NULL, NULL, 00000000002, 00000000001, 00000000033, 00000000022, 00000000003, NULL),
(00000000037, '9874238', 'Trappola 09', 00000000001, 'Via Pitucco 68', 'Cesena', 045.835258, 045.835258, NULL, NULL, 00000000002, 00000000001, 00000000033, 00000000022, 00000000003, NULL),
(00000000038, '9874238', 'Trappola 10', 00000000001, 'Largo Geppo 57', 'Cesena', 045.835258, 045.835258, NULL, NULL, 00000000002, 00000000001, 00000000033, 00000000022, 00000000001, NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `dr_trap_groups`
--

DROP TABLE IF EXISTS `dr_trap_groups`;
CREATE TABLE IF NOT EXISTS `dr_trap_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trap_group_name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dump dei dati per la tabella `dr_trap_groups`
--

INSERT INTO `dr_trap_groups` (`id`, `trap_group_name`) VALUES
(1, 'TRAPPOLE PER TOPI'),
(2, 'TRAPPOLE PER SCARAFAGGI'),
(3, 'TRAPPOLE PER RATTI');

-- --------------------------------------------------------

--
-- Struttura della tabella `dr_trap_history`
--

DROP TABLE IF EXISTS `dr_trap_history`;
CREATE TABLE IF NOT EXISTS `dr_trap_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trap_id` int(11) unsigned zerofill NOT NULL,
  `signal` tinyint(1) DEFAULT '1' COMMENT '1 - GOOD\n2 - BAD',
  `mobile_user_id` int(11) unsigned zerofill NOT NULL,
  `bait_type` int(11) DEFAULT NULL,
  `bait_consumption` varchar(45) DEFAULT NULL,
  `grams_putted` int(4) unsigned zerofill NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `dr_trap_status`
--

DROP TABLE IF EXISTS `dr_trap_status`;
CREATE TABLE IF NOT EXISTS `dr_trap_status` (
  `id` int(11) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `trap_state_name` varchar(45) NOT NULL,
  `order` int(11) unsigned zerofill NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dump dei dati per la tabella `dr_trap_status`
--

INSERT INTO `dr_trap_status` (`id`, `trap_state_name`, `order`, `active`) VALUES
(00000000001, 'Normale', 00000000001, 1),
(00000000002, 'Vuota', 00000000002, 1),
(00000000003, 'Danneggiata', 00000000003, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `dr_trap_type`
--

DROP TABLE IF EXISTS `dr_trap_type`;
CREATE TABLE IF NOT EXISTS `dr_trap_type` (
  `id` int(11) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `type_name` varchar(45) NOT NULL,
  `order` int(11) unsigned zerofill NOT NULL DEFAULT '00000000001',
  `active` tinyint(1) unsigned zerofill NOT NULL DEFAULT '1' COMMENT '1=active\n0 = inactive',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dump dei dati per la tabella `dr_trap_type`
--

INSERT INTO `dr_trap_type` (`id`, `type_name`, `order`, `active`) VALUES
(00000000001, 'Esca fresca', 00000000001, 1),
(00000000002, 'Paraffinato', 00000000002, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `dr_userxroles`
--

DROP TABLE IF EXISTS `dr_userxroles`;
CREATE TABLE IF NOT EXISTS `dr_userxroles` (
  `user_id` int(11) unsigned zerofill NOT NULL,
  `role_id` int(11) unsigned zerofill NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
