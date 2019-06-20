/*
MySQL Data Transfer
Source Host: localhost
Source Database: csdn_blog
Target Host: localhost
Target Database: csdn_blog
Date: 2019/6/2 16:48:38
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for blog
-- ----------------------------
DROP TABLE IF EXISTS `blog`;
CREATE TABLE `blog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `classify_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `pic` varchar(255) NOT NULL DEFAULT '',
  `content` varchar(2000) NOT NULL DEFAULT '',
  `status` int(2) NOT NULL,
  `create_time` int(20) NOT NULL,
  `update_time` int(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for classify
-- ----------------------------
DROP TABLE IF EXISTS `classify`;
CREATE TABLE `classify` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `status` int(2) NOT NULL,
  `create_time` int(20) NOT NULL,
  `update_time` int(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for comment
-- ----------------------------
DROP TABLE IF EXISTS `comment`;
CREATE TABLE `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `blog_id` int(11) NOT NULL,
  `fromuser` int(11) NOT NULL,
  `content` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for token
-- ----------------------------
DROP TABLE IF EXISTS `token`;
CREATE TABLE `token` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `expire_time` int(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `phone` varchar(12) NOT NULL,
  `password` varchar(50) NOT NULL DEFAULT '',
  `status` int(2) NOT NULL,
  `create_time` int(20) NOT NULL,
  `update_time` int(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records 
-- ----------------------------
INSERT INTO `blog` VALUES ('2', '5', '1', 'python读取大文件踩过的坑——读取txt文件词向量', '', 'thiscontent', '1', '1559376711', '1559376711');
INSERT INTO `blog` VALUES ('3', '5', '1', 'python真的有那么神？\n\n你真的认为Python 在最近这些年的兴起只是运气么？', '', 'thiscontent', '1', '1559441512', '1559441512');
INSERT INTO `blog` VALUES ('5', '5', '1', '一个屌丝程序猿的人生（八十九）', '', 'thiscontent', '1', '1559441831', '1559441831');
INSERT INTO `blog` VALUES ('6', '5', '1', 'Swift 势必取代 Python？', '', 'thiscontent', '1', '1559441834', '1559441834');
INSERT INTO `blog` VALUES ('7', '5', '3', 'WEB前端响应式布局之BootStarp使用', '', '1. 概念： 一个前端开发的框架，Bootstrap，来自 Twitter，是目前很受欢迎的前端框架。Bootstrap 是基于 HTML、CSS、JavaScript 的，它简洁灵活，使得 Web 开发更加快捷。', '1', '0', '0');
INSERT INTO `blog` VALUES ('8', '5', '4', 'Dubbo 一些你不一定知道但是很好用的功能', '', 'moke', '1', '0', '0');
INSERT INTO `blog` VALUES ('9', '5', '5', '比特币冲到9000美元, 你就能找个好工作?', '', 'moke', '1', '0', '0');
INSERT INTO `blog` VALUES ('10', '5', '6', '比特币冲到9000美元, 你就能找个好工作?', '', 'moke', '1', '0', '0');
INSERT INTO `blog` VALUES ('11', '5', '7', '数据库类', '', 'moke', '1', '0', '0');
INSERT INTO `blog` VALUES ('12', '5', '8', '游戏开发类', '', 'moke', '1', '0', '0');
INSERT INTO `classify` VALUES ('1', '运维', '0', '0', '0');
INSERT INTO `classify` VALUES ('2', '程序人生', '0', '0', '0');
INSERT INTO `classify` VALUES ('3', '前端', '0', '0', '0');
INSERT INTO `classify` VALUES ('4', '架构', '0', '0', '0');
INSERT INTO `classify` VALUES ('5', '区块链', '0', '0', '0');
INSERT INTO `classify` VALUES ('6', '编译语言', '0', '0', '0');
INSERT INTO `classify` VALUES ('7', '数据库', '0', '0', '0');
INSERT INTO `classify` VALUES ('8', '游戏开发', '0', '0', '0');
INSERT INTO `token` VALUES ('1', 'test_token', 'a:4:{s:2:\"id\";i:5;s:4:\"name\";s:9:\"陈仁杰\";s:5:\"phone\";s:11:\"12345678910\";s:8:\"password\";s:9:\"asd123asd\";}', '1559367194');
INSERT INTO `token` VALUES ('2', 'user_token_45a0586264f8f960e514d9e4c9ad430a', 'a:4:{s:2:\"id\";i:5;s:4:\"name\";s:9:\"陈仁杰\";s:5:\"phone\";s:11:\"12345678910\";s:8:\"password\";s:9:\"asd123asd\";}', '1562033480');
INSERT INTO `user` VALUES ('5', '陈仁杰', '12345678910', 'asd123asd', '1', '1559367194', '1559367194');
