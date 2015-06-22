/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50624
Source Host           : 127.0.0.1:3306
Source Database       : derat_database

Target Server Type    : MYSQL
Target Server Version : 50624
File Encoding         : 65001

Date: 2015-06-11 17:41:21
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for dr_app_users
-- ----------------------------
DROP TABLE IF EXISTS `dr_app_users`;
CREATE TABLE `dr_app_users` (
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dr_covered_areas
-- ----------------------------
DROP TABLE IF EXISTS `dr_covered_areas`;
CREATE TABLE `dr_covered_areas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) unsigned zerofill NOT NULL,
  `area_name` varchar(100) NOT NULL,
  `area_address` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dr_customers
-- ----------------------------
DROP TABLE IF EXISTS `dr_customers`;
CREATE TABLE `dr_customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(50) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1' COMMENT '1=attivo | 0 = non attivo',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dr_mobile_users
-- ----------------------------
DROP TABLE IF EXISTS `dr_mobile_users`;
CREATE TABLE `dr_mobile_users` (
  `id` int(11) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `pwd` varchar(32) NOT NULL,
  `user_type` tinyint(1) unsigned zerofill NOT NULL DEFAULT '3',
  `id_trap_group` int(11) unsigned zerofill NOT NULL DEFAULT '00000000000',
  `customer_id` int(11) unsigned zerofill NOT NULL DEFAULT '00000000000',
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username_UNIQUE` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dr_photoxhistory
-- ----------------------------
DROP TABLE IF EXISTS `dr_photoxhistory`;
CREATE TABLE `dr_photoxhistory` (
  `history_trap_id` int(11) unsigned zerofill NOT NULL,
  `photo_id` int(11) unsigned zerofill DEFAULT NULL,
  PRIMARY KEY (`history_trap_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dr_photo_galleries
-- ----------------------------
DROP TABLE IF EXISTS `dr_photo_galleries`;
CREATE TABLE `dr_photo_galleries` (
  `id` int(11) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `image_url` varchar(255) DEFAULT NULL,
  `photo_name` varchar(45) DEFAULT NULL,
  `id_covered_area` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dr_products
-- ----------------------------
DROP TABLE IF EXISTS `dr_products`;
CREATE TABLE `dr_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_name` varchar(100) DEFAULT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dr_roles
-- ----------------------------
DROP TABLE IF EXISTS `dr_roles`;
CREATE TABLE `dr_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(45) NOT NULL,
  `role_permission` varchar(10) NOT NULL DEFAULT '*',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dr_settings
-- ----------------------------
DROP TABLE IF EXISTS `dr_settings`;
CREATE TABLE `dr_settings` (
  `db_vars` varchar(100) NOT NULL,
  PRIMARY KEY (`db_vars`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dr_traps
-- ----------------------------
DROP TABLE IF EXISTS `dr_traps`;
CREATE TABLE `dr_traps` (
  `id` int(11) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `trap_id` varchar(255) DEFAULT NULL,
  `customer_id` int(11) unsigned zerofill NOT NULL,
  `address` varchar(255) NOT NULL,
  `latitude` float(10,6) unsigned zerofill DEFAULT NULL,
  `longitude` float(10,6) unsigned zerofill DEFAULT NULL,
  `x` varchar(45) NOT NULL,
  `y` varchar(45) NOT NULL,
  `trap_type` int(11) unsigned zerofill NOT NULL DEFAULT '00000000001',
  `trap_status` int(11) unsigned zerofill NOT NULL DEFAULT '00000000001',
  `product_id` int(11) unsigned zerofill NOT NULL DEFAULT '00000000001',
  `cover_area_id` int(11) unsigned zerofill NOT NULL DEFAULT '00000000001',
  `trap_group_id` int(11) unsigned zerofill NOT NULL,
  `notes` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dr_trap_groups
-- ----------------------------
DROP TABLE IF EXISTS `dr_trap_groups`;
CREATE TABLE `dr_trap_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trap_group_name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dr_trap_history
-- ----------------------------
DROP TABLE IF EXISTS `dr_trap_history`;
CREATE TABLE `dr_trap_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trap_id` int(11) unsigned zerofill NOT NULL,
  `signal` tinyint(1) DEFAULT '1' COMMENT '1 - GOOD\n2 - BAD',
  `mobile_user_id` int(11) unsigned zerofill NOT NULL,
  `bait_type` int(11) DEFAULT NULL,
  `bait_consumption` varchar(45) DEFAULT NULL,
  `grams_putted` int(4) unsigned zerofill NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dr_trap_status
-- ----------------------------
DROP TABLE IF EXISTS `dr_trap_status`;
CREATE TABLE `dr_trap_status` (
  `id` int(11) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `trap_state_name` varchar(45) NOT NULL,
  `order` int(11) unsigned zerofill NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dr_trap_type
-- ----------------------------
DROP TABLE IF EXISTS `dr_trap_type`;
CREATE TABLE `dr_trap_type` (
  `id` int(11) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `trap_name` varchar(45) NOT NULL,
  `order` int(11) unsigned zerofill NOT NULL DEFAULT '00000000001',
  `active` tinyint(1) unsigned zerofill NOT NULL DEFAULT '1' COMMENT '1=active\n0 = inactive',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dr_userxroles
-- ----------------------------
DROP TABLE IF EXISTS `dr_userxroles`;
CREATE TABLE `dr_userxroles` (
  `user_id` int(11) unsigned zerofill NOT NULL,
  `role_id` int(11) unsigned zerofill NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
