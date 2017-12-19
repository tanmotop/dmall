/*
Navicat MySQL Data Transfer

Source Server         : 192.168.10.10
Source Server Version : 50720
Source Host           : 192.168.10.10:3306
Source Database       : mall.com

Target Server Type    : MYSQL
Target Server Version : 50720
File Encoding         : 65001

Date: 2017-12-19 15:32:18
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for config
-- ----------------------------
DROP TABLE IF EXISTS `config`;
CREATE TABLE `config` (
  `key` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '配置key',
  `value` text COLLATE utf8_unicode_ci NOT NULL COMMENT '配置内容',
  `describe` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '描述',
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of config
-- ----------------------------
INSERT INTO `config` VALUES ('stock_warning', '200', '库存预警设置', '2017-12-19 07:32:03', null);
