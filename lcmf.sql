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
/*Table structure for table `lcmf_admin` */

CREATE TABLE `lcmf_admin` (
  `adminid` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` varchar(40) NOT NULL COMMENT '用户名',
  `password` varchar(40) NOT NULL COMMENT '密码',
  `loginip` varchar(20) DEFAULT NULL COMMENT '登陆IP',
  `logintime` int(11) DEFAULT NULL COMMENT '登陆时间',
  `createtime` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`adminid`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `lcmf_column` */

CREATE TABLE `lcmf_column` (
  `columnid` int(11) NOT NULL AUTO_INCREMENT COMMENT '栏目ID',
  `parentid` int(11) NOT NULL DEFAULT '0' COMMENT '父栏目',
  `name` varchar(100) NOT NULL COMMENT '名称',
  `tableid` int(11) DEFAULT NULL COMMENT '模型',
  `link` varchar(255) DEFAULT NULL COMMENT '链接',
  `sequence` int(11) DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`columnid`),
  KEY `parentid` (`parentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `lcmf_data` */

CREATE TABLE `lcmf_data` (
  `dataid` int(11) NOT NULL AUTO_INCREMENT COMMENT '数据ID',
  `columnid` int(11) NOT NULL COMMENT '栏目',
  `enable` enum('1','0') DEFAULT '1' COMMENT '启用',
  `recommend` enum('0','1') DEFAULT '0' COMMENT '推荐',
  `headline` enum('0','1') DEFAULT '0' COMMENT '头条',
  `modifiedtime` int(11) DEFAULT NULL COMMENT '修改时间',
  `createtime` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`dataid`),
  KEY `columnid` (`columnid`),
  CONSTRAINT `FK_lcmf_data` FOREIGN KEY (`columnid`) REFERENCES `lcmf_column` (`columnid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `lcmf_message` */

CREATE TABLE `lcmf_message` (
  `messageid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '姓名',
  `sex` enum('男','女') DEFAULT NULL COMMENT '性别',
  `age` smallint(6) DEFAULT NULL COMMENT '年龄',
  `address` varchar(255) DEFAULT NULL COMMENT '地址',
  `email` varchar(50) DEFAULT NULL COMMENT '邮箱',
  `phone` varchar(50) DEFAULT NULL COMMENT '电话',
  `file` varchar(2040) DEFAULT NULL COMMENT '文件',
  `extend` text COMMENT '其他',
  `content` text COMMENT '内容',
  `reply` text COMMENT '回复',
  `clientip` varchar(50) NOT NULL COMMENT '客户IP地址',
  `createtime` int(11) NOT NULL COMMENT '提交时间',
  PRIMARY KEY (`messageid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `lcmf_table` */

CREATE TABLE `lcmf_table` (
  `tableid` int(11) NOT NULL AUTO_INCREMENT COMMENT '模型ID',
  `name` varchar(50) NOT NULL COMMENT '数据表名',
  `title` varchar(50) NOT NULL COMMENT '模型名称',
  `description` varchar(255) DEFAULT NULL COMMENT '模型描述',
  `type` enum('list','image','content') NOT NULL DEFAULT 'list' COMMENT '数据类型',
  `action` varchar(255) DEFAULT NULL COMMENT '操作方法',
  PRIMARY KEY (`tableid`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `lcmf_table_field` */

CREATE TABLE `lcmf_table_field` (
  `fieldid` int(11) NOT NULL AUTO_INCREMENT COMMENT '字段ID',
  `tableid` int(11) NOT NULL COMMENT '模型ID',
  `name` varchar(20) NOT NULL COMMENT '字段名',
  `title` varchar(50) NOT NULL COMMENT '字段标题',
  `element` varchar(20) NOT NULL COMMENT '表单元素',
  `validate` varchar(20) DEFAULT NULL COMMENT '表单验证',
  `list` enum('0','1') DEFAULT '0' COMMENT '列表展示',
  `sequence` int(11) DEFAULT '0' COMMENT '表单顺序',
  PRIMARY KEY (`fieldid`),
  UNIQUE KEY `table-field-name` (`tableid`,`name`),
  CONSTRAINT `FK_lcmf_table_field` FOREIGN KEY (`tableid`) REFERENCES `lcmf_table` (`tableid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
