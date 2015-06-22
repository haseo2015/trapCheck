/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50624
Source Host           : 127.0.0.1:3306
Source Database       : derat_central_admin

Target Server Type    : MYSQL
Target Server Version : 50624
File Encoding         : 65001

Date: 2015-06-09 17:50:41
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for dr_customers
-- ----------------------------
DROP TABLE IF EXISTS `dr_customers`;
CREATE TABLE `dr_customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(100) DEFAULT NULL,
  `cod_cli` varchar(20) NOT NULL,
  `licence_number` int(11) unsigned zerofill NOT NULL,
  `licence_total` int(11) unsigned zerofill DEFAULT NULL,
  `installations` int(11) unsigned zerofill DEFAULT NULL,
  `used` int(11) unsigned zerofill DEFAULT NULL,
  `expiration_date` timestamp NULL DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `mobile_licence_total` int(11) unsigned zerofill DEFAULT NULL,
  `db_vars` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`,`cod_cli`,`licence_number`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of dr_customers
-- ----------------------------
INSERT INTO `dr_customers` VALUES ('1', 'De. Ratt & Co.', '123456', '01111111111', '00000000010', '00000000008', '00000000009', '2015-05-25 08:50:06', '123456', '00000000000', null);
INSERT INTO `dr_customers` VALUES ('2', 'Ratt e Top', '654321', '00222222222', '00000000003', '00000000005', '00000000005', '2015-06-30 08:50:47', '654321', '00000000000', '{\"host\":\"localhost\",\"dbu\":\"root\",\"dbp\":\"admin\"}');
INSERT INTO `dr_customers` VALUES ('3', 'Gatt & Ratt', '567890', '00333333333', '00000000020', '00000000010', '00000000000', '2015-05-29 09:06:50', '567890', '00000000000', null);
