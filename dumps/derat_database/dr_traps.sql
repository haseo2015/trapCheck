-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Giu 19, 2015 alle 12:34
-- Versione del server: 5.6.24-log
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `derat_database`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `dr_traps`
--

DROP TABLE IF EXISTS `dr_traps`;
CREATE TABLE IF NOT EXISTS `dr_traps` (
  `trap_id` int(11) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `trap_code` varchar(255) NOT NULL,
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
  PRIMARY KEY (`trap_id`,`trap_code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=40 ;

--
-- Dump dei dati per la tabella `dr_traps`
--

INSERT INTO `dr_traps` (`trap_id`, `trap_code`, `trap_name`, `customer_id`, `address`, `citta`, `latitude`, `longitude`, `x`, `y`, `trap_type`, `trap_status`, `product_id`, `covered_area_id`, `trap_group_id`, `notes`) VALUES
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

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
