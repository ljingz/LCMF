/*
SQLyog 企业版 - MySQL GUI v8.14 
MySQL - 5.5.20 : Database - lcmf
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`lcmf` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `lcmf`;

/*Table structure for table `lcmf_admin` */

DROP TABLE IF EXISTS `lcmf_admin`;

CREATE TABLE `lcmf_admin` (
  `adminid` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` varchar(40) NOT NULL COMMENT '用户名',
  `password` varchar(40) NOT NULL COMMENT '密码',
  `loginip` varchar(20) DEFAULT NULL COMMENT '登陆IP',
  `logintime` int(11) DEFAULT NULL COMMENT '登陆时间',
  `createtime` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`adminid`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `lcmf_admin` */

insert  into `lcmf_admin`(`adminid`,`username`,`password`,`loginip`,`logintime`,`createtime`) values (1,'admin','21232f297a57a5a743894a0e4a801fc3','127.0.0.1',1400987955,1),(2,'test','21232f297a57a5a743894a0e4a801fc3',NULL,NULL,1),(3,'test2','222',NULL,NULL,1),(4,'test1','96e79218965eb72c92a549dd5a330112',NULL,NULL,1400939568),(6,'user','ee11cbb19052e40b07aac0ca060c23ee',NULL,NULL,1400988526);

/*Table structure for table `lcmf_column` */

DROP TABLE IF EXISTS `lcmf_column`;

CREATE TABLE `lcmf_column` (
  `columnid` int(11) NOT NULL AUTO_INCREMENT COMMENT '栏目ID',
  `parentid` int(11) NOT NULL DEFAULT '0' COMMENT '父栏目',
  `name` varchar(100) NOT NULL COMMENT '名称',
  `tableid` int(11) DEFAULT NULL COMMENT '模型',
  `sequence` int(11) DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`columnid`),
  KEY `parentid` (`parentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `lcmf_column` */

/*Table structure for table `lcmf_data` */

DROP TABLE IF EXISTS `lcmf_data`;

CREATE TABLE `lcmf_data` (
  `dataid` int(11) NOT NULL COMMENT '数据ID',
  `columnid` int(11) NOT NULL COMMENT '栏目',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `enable` enum('1','0') DEFAULT '1' COMMENT '启用',
  `recommend` enum('0','1') DEFAULT '0' COMMENT '推荐',
  `headline` enum('0','1') DEFAULT '0' COMMENT '头条',
  `modifiedtime` int(11) DEFAULT NULL COMMENT '修改时间',
  `createtime` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`dataid`,`columnid`),
  KEY `columnid` (`columnid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `lcmf_data` */

/*Table structure for table `lcmf_table` */

DROP TABLE IF EXISTS `lcmf_table`;

CREATE TABLE `lcmf_table` (
  `tableid` int(11) NOT NULL AUTO_INCREMENT COMMENT '模型ID',
  `name` varchar(50) NOT NULL COMMENT '数据表名',
  `title` varchar(50) NOT NULL COMMENT '模型名称',
  `description` varchar(255) DEFAULT NULL COMMENT '模型描述',
  PRIMARY KEY (`tableid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `lcmf_table` */

insert  into `lcmf_table`(`tableid`,`name`,`title`,`description`) values (1,'111','111',NULL),(2,'222','222',NULL),(3,'333','333',NULL),(4,'444','444',NULL),(5,'555','555',NULL),(6,'666','666',NULL);

/*Table structure for table `lcmf_table_field` */

DROP TABLE IF EXISTS `lcmf_table_field`;

CREATE TABLE `lcmf_table_field` (
  `fieldid` int(11) NOT NULL AUTO_INCREMENT COMMENT '字段ID',
  `tableid` int(11) NOT NULL COMMENT '模型ID',
  `name` varchar(20) NOT NULL COMMENT '字段名',
  `title` varchar(50) NOT NULL COMMENT '字段标题',
  `element` varchar(20) NOT NULL COMMENT '表单元素',
  `validate` varchar(20) DEFAULT NULL COMMENT '表单验证',
  `sequence` int(11) DEFAULT '0' COMMENT '表单顺序',
  PRIMARY KEY (`fieldid`),
  KEY `tableid` (`tableid`),
  CONSTRAINT `FK_lcmf_table_field` FOREIGN KEY (`tableid`) REFERENCES `lcmf_table` (`tableid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `lcmf_table_field` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

