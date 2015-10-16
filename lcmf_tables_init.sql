/*
SQLyog 企业版 - MySQL GUI v8.14 
MySQL - 5.5.20 : Database - hanbenjuicer
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

/*Table structure for table `lcmf_content` */

DROP TABLE IF EXISTS `lcmf_content`;

CREATE TABLE `lcmf_content` (
  `dataid` int(11) NOT NULL,
  `content` longtext COMMENT '内容',
  `summary` text COMMENT '简介',
  PRIMARY KEY (`dataid`),
  CONSTRAINT `FK_content` FOREIGN KEY (`dataid`) REFERENCES `lcmf_data` (`dataid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `lcmf_content` */

/*Table structure for table `lcmf_image` */

DROP TABLE IF EXISTS `lcmf_image`;

CREATE TABLE `lcmf_image` (
  `dataid` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `image` varchar(1020) DEFAULT NULL COMMENT '图片',
  `summary` text COMMENT '简介',
  `content` longtext COMMENT '内容',
  PRIMARY KEY (`dataid`),
  CONSTRAINT `FK_image` FOREIGN KEY (`dataid`) REFERENCES `lcmf_data` (`dataid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `lcmf_image` */

/*Table structure for table `lcmf_list` */

DROP TABLE IF EXISTS `lcmf_list`;

CREATE TABLE `lcmf_list` (
  `dataid` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `image` varchar(1020) DEFAULT NULL COMMENT '图片',
  `summary` text COMMENT '简介',
  `content` longtext COMMENT '内容',
  PRIMARY KEY (`dataid`),
  CONSTRAINT `FK_list` FOREIGN KEY (`dataid`) REFERENCES `lcmf_data` (`dataid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `lcmf_list` */

/*Table structure for table `lcmf_table` */

/*
DROP TABLE IF EXISTS `lcmf_table`;

CREATE TABLE `lcmf_table` (
  `tableid` int(11) NOT NULL AUTO_INCREMENT COMMENT '模型ID',
  `name` varchar(50) NOT NULL COMMENT '数据表名',
  `title` varchar(50) NOT NULL COMMENT '模型名称',
  `description` varchar(255) DEFAULT NULL COMMENT '模型描述',
  `type` enum('list','image','content') NOT NULL DEFAULT 'list' COMMENT '数据类型',
  `action` varchar(255) DEFAULT NULL COMMENT '操作方法',
  PRIMARY KEY (`tableid`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
*/

/*Data for the table `lcmf_table` */

insert  into `lcmf_table`(`tableid`,`name`,`title`,`description`,`type`,`action`) values (1,'list','文字列表模型','','list','[\"add\",\"delete\",\"edit\",\"recommend\"]'),(2,'content','文字内容模型','','content',NULL),(3,'image','图片列表模型','','list','[\"add\",\"delete\",\"edit\",\"recommend\"]');

/*Table structure for table `lcmf_table_field` */

/*
DROP TABLE IF EXISTS `lcmf_table_field`;

CREATE TABLE `lcmf_table_field` (
  `fieldid` int(11) NOT NULL AUTO_INCREMENT COMMENT '字段ID',
  `tableid` int(11) NOT NULL COMMENT '模型ID',
  `name` varchar(20) NOT NULL COMMENT '字段名',
  `title` varchar(50) NOT NULL COMMENT '字段标题',
  `element` varchar(20) NOT NULL COMMENT '表单元素',
  `options` varchar(1020) DEFAULT NULL COMMENT '表单选值',
  `validate` varchar(20) DEFAULT NULL COMMENT '表单验证',
  `list` enum('0','1') DEFAULT '0' COMMENT '列表展示',
  `sequence` int(11) DEFAULT '0' COMMENT '表单顺序',
  PRIMARY KEY (`fieldid`),
  UNIQUE KEY `table-field-name` (`tableid`,`name`),
  CONSTRAINT `FK_lcmf_table_field` FOREIGN KEY (`tableid`) REFERENCES `lcmf_table` (`tableid`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
*/

/*Data for the table `lcmf_table_field` */

insert  into `lcmf_table_field`(`fieldid`,`tableid`,`name`,`title`,`element`,`options`,`validate`,`list`,`sequence`) values (1,1,'title','标题','text','','required','1',0),(2,1,'image','图片','image','','','0',1),(3,1,'summary','简介','textarea','','','0',2),(4,1,'content','内容','editor','','','0',3),(5,2,'content','内容','editor','','required','0',0),(6,2,'summary','简介','textarea','','','0',1),(7,3,'title','标题','text','','required','1',0),(8,3,'image','图片','image','','','0',1),(9,3,'summary','简介','textarea','','','0',2),(10,3,'content','内容','editor','','','0',3);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
