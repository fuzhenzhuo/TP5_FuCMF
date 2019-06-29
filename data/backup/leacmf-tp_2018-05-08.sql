# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 192.168.33.10 (MySQL 5.7.21)
# Database: leacmf-tp
# Generation Time: 2018-05-08 01:47:49 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table ad
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ad`;

CREATE TABLE `ad` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) DEFAULT NULL,
  `title` varchar(64) DEFAULT NULL,
  `picture` varchar(128) DEFAULT NULL,
  `sort` smallint(5) DEFAULT NULL,
  `action_type` tinyint(1) DEFAULT NULL,
  `action_param` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `create_time` int(11) unsigned DEFAULT NULL,
  `update_time` int(11) unsigned DEFAULT NULL,
  `status` int(1) unsigned NOT NULL DEFAULT '0' COMMENT '0-禁用 1-启用 2-删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `ad` WRITE;
/*!40000 ALTER TABLE `ad` DISABLE KEYS */;

INSERT INTO `ad` (`id`, `type`, `title`, `picture`, `sort`, `action_type`, `action_param`, `remark`, `create_time`, `update_time`, `status`)
VALUES
	(4,1,'banner','Fka58TZrkGuuNXnbQKSAoume1SP1',0,0,'','',1508125727,1508138666,1),
	(5,1,'banner','Fka58TZrkGuuNXnbQKSAoume1SP1',0,0,'','',1508132308,1508138491,1);

/*!40000 ALTER TABLE `ad` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table admin
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin`;

CREATE TABLE `admin` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL COMMENT '用户名',
  `nickname` varchar(20) NOT NULL COMMENT '昵称',
  `password` varchar(255) NOT NULL DEFAULT '' COMMENT '密码',
  `face` varchar(64) NOT NULL DEFAULT '0',
  `token` varchar(32) DEFAULT NULL COMMENT 'token',
  `login_times` int(10) NOT NULL DEFAULT '0' COMMENT '登录次数',
  `last_login_time` int(10) DEFAULT NULL COMMENT '上次登陆时间',
  `last_login_ip` char(16) DEFAULT NULL COMMENT '上次登陆ip',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0-禁用 1-正常',
  `create_time` int(11) NOT NULL COMMENT '注册时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='后台管理员表';

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;

INSERT INTO `admin` (`id`, `username`, `nickname`, `password`, `face`, `token`, `login_times`, `last_login_time`, `last_login_ip`, `status`, `create_time`)
VALUES
	(1,'admin','admin','$2y$10$yC/BqKoxi2mhMIrPcyfhE.4kphrDgNhq9ri5f60ZwQSihIhsdLIeK','/uploads/image/20180425/f31867256aa9ed55be32422a7bb5119a.gif','287b7eb75d5bb3e376f5932a311bc87d',732,1524643351,'192.168.33.1',1,1),
	(2,'manage','管理员','$2y$10$wBqQmkeBqpLIlNTtpjlsyO3AiuwmNU0RVmLQgkes6FueVxfEolXYi','/uploads/image/20180208/3efc0a1dad180a0b786827a7292d508d.png','$2y$10$qIwwvblkQETKaKBtiBTMO.T.K',47,1504764750,'1.25.227.160',1,1496306374);

/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table article
# ------------------------------------------------------------

DROP TABLE IF EXISTS `article`;

CREATE TABLE `article` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cate` smallint(1) unsigned NOT NULL COMMENT '1-系统单页 2-文章',
  `title` varchar(64) NOT NULL,
  `cover_id` int(11) unsigned DEFAULT NULL,
  `content` text NOT NULL,
  `create_time` int(11) NOT NULL,
  `create_aid` smallint(5) NOT NULL,
  `update_time` int(11) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1-正常 0-下架 2-删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `article` WRITE;
/*!40000 ALTER TABLE `article` DISABLE KEYS */;

INSERT INTO `article` (`id`, `cate`, `title`, `cover_id`, `content`, `create_time`, `create_aid`, `update_time`, `status`)
VALUES
	(1,1,'冠军助力怡宝，优惠活动来袭~！',44,'<p><span>华润怡宝，中国饮用水市场的领先品牌，在华南地区市场占有率连续多年稳居首位，2007年销量达到108万吨。1989年，华润怡宝在国内率先推出</span><a target=\"_blank\" href=\"http://baike.baidu.com/item/%E7%BA%AF%E5%87%80%E6%B0%B4\">纯净水</a><span>，是国内最早专业化生产包装水的企业之一。华润怡宝也是国家质监和卫生</span><a target=\"_blank\" href=\"http://baike.baidu.com/item/%E9%A5%AE%E7%94%A8%E7%BA%AF%E5%87%80%E6%B0%B4\">饮用纯净水</a><span>国家标准的主要发起和起草单位之一。华润怡宝始终以优于“国标”的生产标准为消费者提供健康满意的优质产品，并通过良好的服务，赢得消费者的认同。华润怡宝多年来得到了各级政府部门的肯定与嘉奖，获得</span><a target=\"_blank\" href=\"http://baike.baidu.com/item/%E4%B8%AD%E5%9B%BD%E5%90%8D%E7%89%8C\">中国名牌</a><span>产品、中国最具竞争力品牌等荣誉。2008年，华润怡宝水业务的管理纳入</span><a target=\"_blank\" href=\"http://baike.baidu.com/item/%E5%8D%8E%E6%B6%A6%E9%9B%86%E5%9B%A2\">华润集团</a><span>6S管理体系，华润怡宝食品饮料（深圳）有限公司列入</span><a target=\"_blank\" href=\"http://baike.baidu.com/item/%E5%8D%8E%E6%B6%A6\">华润</a><span>（集团）有限公司一级利润中心序列。</span></p><p><span><img src=\"/image/lJxzmzzqg\" alt=\"undefined\"><br></span></p><p><span><img src=\"/image/lQSNiXmqe\" alt=\"undefined\"><br></span></p><p><span>华润怡宝，继2009年“怡宝VBA广东省大学生三人篮球赛”后，2011年5月24日，华润怡宝携手中国乒乓球队在深圳举行了盛大的战略合作签约酒会，怡宝同时也获得了“中国国家乒乓球队唯一指定饮料”称号。分析人士认为，作为华南地区饮用水品牌的领头羊，此次携手中国兵乓球队，是华润怡宝对多年来体育营销策略的深化，将助其在竞争白热化的饮用水市场处于更稳固的地位。</span></p>',1498713850,1,1498713850,1);

/*!40000 ALTER TABLE `article` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table auth_group
# ------------------------------------------------------------

DROP TABLE IF EXISTS `auth_group`;

CREATE TABLE `auth_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL DEFAULT '',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '组名',
  `rules` text COMMENT '规则ID',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `sort` smallint(5) NOT NULL DEFAULT '0',
  `remark` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='分组表';

LOCK TABLES `auth_group` WRITE;
/*!40000 ALTER TABLE `auth_group` DISABLE KEYS */;

INSERT INTO `auth_group` (`id`, `name`, `title`, `rules`, `status`, `sort`, `remark`)
VALUES
	(1,'super admin','超级管理员','*',1,0,''),
	(2,'admin','管理员','92,93,58,61,59,56,57,135,60,13,15,14,11,12,34,36,35,32,33,116,114,115,96,97,94,95,129,70,72,71,68,69,113,142,118,122,119,117,120,121,88,125,90,91,89,1,83,84,103,124,101,102,123,104,126,127,128,131,141,85,86,87,98,99,100,138,139,130,136,137,105,108,110,106,109,107,4,81,82,132,140,133,65,66,67,134,76,78,77,74,75,80,79',1,0,'最高权限管理员'),
	(4,'test','测试组','36,40,41,32,34,33,31,35,13,3,11',1,0,'测试组');

/*!40000 ALTER TABLE `auth_group` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table auth_group_access
# ------------------------------------------------------------

DROP TABLE IF EXISTS `auth_group_access`;

CREATE TABLE `auth_group_access` (
  `uid` int(10) unsigned NOT NULL COMMENT '会员ID',
  `group_id` int(10) unsigned NOT NULL COMMENT '组ID',
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE,
  KEY `group_id` (`group_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='权限分组表';

LOCK TABLES `auth_group_access` WRITE;
/*!40000 ALTER TABLE `auth_group_access` DISABLE KEYS */;

INSERT INTO `auth_group_access` (`uid`, `group_id`)
VALUES
	(1,1),
	(2,2),
	(3,2);

/*!40000 ALTER TABLE `auth_group_access` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table auth_rule
# ------------------------------------------------------------

DROP TABLE IF EXISTS `auth_rule`;

CREATE TABLE `auth_rule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '规则名称',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '规则标题',
  `icon` varchar(50) NOT NULL DEFAULT '' COMMENT '图标',
  `condition` varchar(255) NOT NULL DEFAULT '' COMMENT '条件',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `is_menu` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否为菜单',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(10) NOT NULL COMMENT '权重',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='节点表';

LOCK TABLES `auth_rule` WRITE;
/*!40000 ALTER TABLE `auth_rule` DISABLE KEYS */;

INSERT INTO `auth_rule` (`id`, `pid`, `name`, `title`, `icon`, `condition`, `remark`, `is_menu`, `create_time`, `update_time`, `sort`, `status`)
VALUES
	(1,0,'/','控制台','fa fa-dashboard','','',0,1494570009,1517986765,-99,1),
	(2,0,'/system/config','系统管理','fa-cogs','','',1,1494570009,1498534886,-8,1),
	(3,0,'/auth/admin','权限管理','fa-sitemap','','',1,1494570136,1498534901,-7,1),
	(4,2,'/system/config/group','系统设置','fa-cog','','配置项快速设置',1,1494570435,1494570435,0,1),
	(5,2,'/system/config/index','配置管理','fa-magic','','用于管理一些字典数据,通常以键值格式进行录入,程序将解析成相应的类型',1,1494570482,1494570490,0,1),
	(6,5,'/system/config/add','添加配置','','','',0,1494570541,1494570541,0,1),
	(7,5,'/system/config/edit','修改配置','','','',0,1494570557,1494570565,0,1),
	(8,5,'/system/config/lists','配置列表','','','',0,1494570600,1494570600,0,1),
	(9,5,'/system/config/sort','快速排序','','','',0,1494570721,1494570753,0,1),
	(10,5,'/system/config/lock','状态锁定','','','',0,1494570739,1494570761,0,1),
	(11,3,'/auth/admin/index','后台用户管理',' fa-user','','',1,1494570920,1495850074,0,1),
	(12,11,'/auth/lists','用户列表','','','',0,1494570935,1494570952,0,1),
	(13,11,'/auth/add','添加用户','','','该权限包含分配用户组',0,1494570998,1494571178,0,1),
	(14,11,'/auth/edit','修改用户','','','该权限包含分配用户组',0,1494571005,1494571005,0,1),
	(15,11,'/auth/delete','删除用户','','','',0,1494571013,1494571013,0,1),
	(16,5,'/system/config/delete','删除配置','','','',0,1494571037,1496307872,0,1),
	(17,3,'/auth/group/index','用户组管理','fa-users','','',1,1494571157,1494571157,0,1),
	(18,17,'/auth/group/lists','角色列表','','','',0,1494571297,1495697646,0,1),
	(19,17,'/auth/group/add','添加角色','','','',0,1494571308,1494571308,0,1),
	(20,17,'/auth/group/edit','修改角色','','','',0,1494571327,1494571327,0,1),
	(21,17,'/auth/group/sort','排序','','','',0,1494571345,1494571345,0,1),
	(22,17,'/auth/group/delete','删除','','','',0,1494571353,1494571353,0,1),
	(23,17,'/auth/group/assigned','分配权限','','','',0,1494571376,1494571376,0,1),
	(24,3,'/auth/rule/index','规则&菜单','fa-child','','',1,1494571444,1494571444,0,1),
	(25,24,'/auth/rule/lists','规则列表','','','',0,1494571479,1494571479,0,1),
	(26,24,'/auth/rule/add','添加规则','','','',0,1494571490,1494571490,0,1),
	(27,24,'/auth/rule/edit','修改规则','','','',0,1494571497,1494571497,0,1),
	(28,24,'/auth/rule/sort','快速排序','','','',0,1494571516,1494571516,0,1),
	(29,24,'/auth/rule/delete','删除','','','',0,1494571525,1494571525,0,1),
	(30,24,'/auth/rule/menu','设置菜单','','','',0,1494571554,1494571554,0,1),
	(31,0,'/article/index','文章管理','fa fa fa-feed','','',1,1507616189,1507616189,0,1),
	(32,31,'/article/add','添加','','','',0,1507616311,1507616311,0,1),
	(33,31,'/article/edit','修改','','','',0,1507616329,1507616329,0,1),
	(34,31,'/article/delete','删除','','','',0,1507616374,1507616374,0,1),
	(35,31,'/article/setStatus','设置状态','','','',0,1507624177,1507624177,0,1),
	(83,0,'/user/index','用户管理','fa fa-users','','',1,1508296838,1508296838,0,1),
	(84,83,'/user/lists','用户列表','','','',0,1508296856,1508296856,0,1),
	(85,83,'/user/setStatus','状态设置','','','',0,1508297900,1508297900,0,1),
	(86,0,'/info/user','个人中心','','','',0,1518068730,1518068730,0,1),
	(87,86,'/info/updateNickname','修改昵称','','','',0,1518068760,1518068760,0,1),
	(88,86,'/info/updateFace','修改头像','','','',0,1518068784,1518068784,0,1),
	(89,86,'/user/updatePassword','修改密码','','','',0,1518068804,1518068804,0,1);

/*!40000 ALTER TABLE `auth_rule` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table config
# ------------------------------------------------------------

DROP TABLE IF EXISTS `config`;

CREATE TABLE `config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '配置ID',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '配置名称',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置类型',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '配置说明',
  `group` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置分组',
  `extra` varchar(522) NOT NULL DEFAULT '' COMMENT '配置值',
  `remark` varchar(100) NOT NULL DEFAULT '' COMMENT '配置说明',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `lock` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `value` text COMMENT '配置值',
  `sort` smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`name`) USING BTREE,
  KEY `type` (`type`) USING BTREE,
  KEY `group` (`group`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统配置表';

LOCK TABLES `config` WRITE;
/*!40000 ALTER TABLE `config` DISABLE KEYS */;

INSERT INTO `config` (`id`, `name`, `type`, `title`, `group`, `extra`, `remark`, `create_time`, `update_time`, `lock`, `value`, `sort`)
VALUES
	(7,'admin_allow_ip',2,'后台允许访问IP',0,'','多个用逗号分隔，如果不配置表示不限制IP访问',1387165454,1387165553,0,'',0),
	(8,'config_group_list',3,'配置分组',0,'','配置分组',1379228036,1477766924,1,'1:基本\r\n3:业务\r\n5:Api\r\n6:企业',0),
	(9,'allow_visit',3,'不受限控制器方法',0,'','',1386644047,1386644741,1,'0:/admin/index/index',0),
	(10,'config_type_list',3,'配置类型列表',4,'','主要用于数据解析和页面表单的生成',1378898976,1379235348,1,'0:数字\r\n1:字符\r\n2:文本\r\n3:数组\r\n4:枚举',0),
	(13,'token_expiration_date',0,'token过期时间',5,'','单位：秒，0表示用不过期',0,0,0,'604800',0),
	(15,'sms_template',1,'短信模板',5,'','用于配置注册和找回密码时发送短信的模板，{code}为验证码。',0,0,0,'【Leacmf】您的验证码是{code}，请在5分钟内填写。',0);

/*!40000 ALTER TABLE `config` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table jobs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `jobs`;

CREATE TABLE `jobs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table msg
# ------------------------------------------------------------

DROP TABLE IF EXISTS `msg`;

CREATE TABLE `msg` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '0-系统信息 其他推送信息相关id',
  `user_id` int(10) unsigned NOT NULL COMMENT '用户id',
  `msg` varchar(155) NOT NULL,
  `data` varchar(522) NOT NULL DEFAULT '',
  `at_time` int(11) unsigned NOT NULL,
  `is_read` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0未读 1-已读',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `msg` WRITE;
/*!40000 ALTER TABLE `msg` DISABLE KEYS */;

INSERT INTO `msg` (`id`, `user_id`, `msg`, `data`, `at_time`, `is_read`)
VALUES
	(1,0,'test','',1507692726,0);

/*!40000 ALTER TABLE `msg` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table uploads
# ------------------------------------------------------------

DROP TABLE IF EXISTS `uploads`;

CREATE TABLE `uploads` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` char(10) NOT NULL COMMENT '文件类型',
  `ext` varchar(20) DEFAULT NULL COMMENT '文件扩展名',
  `size` int(10) unsigned NOT NULL,
  `path` varchar(64) DEFAULT NULL,
  `filename` varchar(64) NOT NULL,
  `sha1` varchar(64) NOT NULL,
  `width` smallint(5) unsigned NOT NULL DEFAULT '0',
  `height` smallint(5) unsigned NOT NULL DEFAULT '0',
  `mime` varchar(32) DEFAULT NULL,
  `at_time` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `uploads` WRITE;
/*!40000 ALTER TABLE `uploads` DISABLE KEYS */;

INSERT INTO `uploads` (`id`, `type`, `ext`, `size`, `path`, `filename`, `sha1`, `width`, `height`, `mime`, `at_time`)
VALUES
	(1,'image','png',2193,'20171010/944739d1573bf553f9c442c0c411868a.png','944739d1573bf553f9c442c0c411868a.png','6d65d00419aee23581f6dc029e70a3843863e425',128,128,'image/png',1507605540),
	(2,'image','png',1584,'20171010/86a37e9c7b6122609f3a399acb3ae4a1.png','86a37e9c7b6122609f3a399acb3ae4a1.png','7aa5158e3ae8515449e6715b081966a959de1e7b',128,128,'image/png',1507605560),
	(3,'image','png',2275,'20171010/94f4451791d244a5f38689e13eb5c613.png','94f4451791d244a5f38689e13eb5c613.png','573020a9cc75e3682bff0d9d551ade7728e4fb59',128,128,'image/png',1507605584),
	(4,'image','png',2269,'20171010/17eb4eb9f0450a6be4ae63dc99f741f8.png','17eb4eb9f0450a6be4ae63dc99f741f8.png','193057cf3016fca5d3b176b7d957c9c54ad50ebb',128,128,'image/png',1507606260),
	(5,'image','png',1989,'20171010/8bb78f056ee2c36c22f59b7d70c3699a.png','8bb78f056ee2c36c22f59b7d70c3699a.png','90d8778c998b5a1de645957bd89b473a6144ccde',128,128,'image/png',1507606268),
	(6,'image','png',7578,'20171010/ea11f01241a0eb3f20f1a04bb6adff16.png','ea11f01241a0eb3f20f1a04bb6adff16.png','3af84861aaacebcddef13ef2783cb1088faf236b',215,215,'image/png',1507606501),
	(7,'image','png',7578,'20171010/3297e497b4f0d3bf01011b65af716573.png','3297e497b4f0d3bf01011b65af716573.png','3af84861aaacebcddef13ef2783cb1088faf236b',215,215,'image/png',1507619504),
	(8,'image','png',7578,'20171010/f6a7a2f4da7b401ceb0049e5055f6202.png','f6a7a2f4da7b401ceb0049e5055f6202.png','3af84861aaacebcddef13ef2783cb1088faf236b',215,215,'image/png',1507622100),
	(9,'image','png',13539,'20171010/50a2ee64aa0d52c9057257766278250c.png','50a2ee64aa0d52c9057257766278250c.png','c295afd4311682807535e1a02a4c90007da00a28',215,215,'image/png',1507622214),
	(10,'image','png',8262,'20171010/0baa9176b0a42a34b08e39f915a93e28.png','0baa9176b0a42a34b08e39f915a93e28.png','221bffc125e97decada22541605793c6e57c6466',215,215,'image/png',1507622245),
	(11,'image','png',9241,'20171011/c88ec54570fdab8d01254b7130ffb4d2.png','c88ec54570fdab8d01254b7130ffb4d2.png','98764e968dd1f5b7a92a15494d5cc377e113e2fe',215,215,'image/png',1507690798),
	(12,'image','png',7578,'20171011/30d385e003ce236ef80ac633e2d7e112.png','30d385e003ce236ef80ac633e2d7e112.png','3af84861aaacebcddef13ef2783cb1088faf236b',215,215,'image/png',1507692074),
	(13,'image','png',9241,'20180208/3efc0a1dad180a0b786827a7292d508d.png','3efc0a1dad180a0b786827a7292d508d.png','98764e968dd1f5b7a92a15494d5cc377e113e2fe',0,0,'image/png',1518070791),
	(14,'um-editor','png',63802,'20180222/30f720d2a91a09c798765813cdc0d2c0.png','30f720d2a91a09c798765813cdc0d2c0.png','2431ad9b3f40c052db769029343cb6b67a9bf042',0,0,'image/png',1519277542);

/*!40000 ALTER TABLE `uploads` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(13) DEFAULT NULL,
  `mobile` varchar(20) NOT NULL COMMENT '手机号',
  `nickname` varchar(64) NOT NULL DEFAULT '',
  `openid` varchar(64) DEFAULT NULL COMMENT '微信id',
  `token` varchar(64) DEFAULT NULL,
  `face` varchar(128) DEFAULT NULL COMMENT '头像',
  `sex` enum('男','女') DEFAULT NULL,
  `password` varchar(40) NOT NULL,
  `register_time` datetime NOT NULL COMMENT '注册时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;

INSERT INTO `user` (`id`, `uuid`, `mobile`, `nickname`, `openid`, `token`, `face`, `sex`, `password`, `register_time`, `update_time`, `status`)
VALUES
	(1,'2D885888','18622991098','你好',NULL,'bc0c93a62d0ca83264694e5c8f9a5460974e1bb5','Fq3QXJG0TzPhINcKy7HNtt7iiJrJ','男','d3e3aad7256f373a52a9cfb99bb54c98','2017-10-11 14:50:30',1507704630,1),
	(4,'27395D92','18622991099','家长vbmOe',NULL,NULL,NULL,NULL,'d3e3aad7256f373a52a9cfb99bb54c98','2017-10-16 18:04:48',1508148288,1),
	(5,'23563592','18210905679','家长penRe',NULL,NULL,NULL,NULL,'d3e3aad7256f373a52a9cfb99bb54c98','2017-10-16 18:35:50',1508150150,1),
	(6,'2BE9BD6D','18210905678','家长xboja',NULL,NULL,NULL,NULL,'d3e3aad7256f373a52a9cfb99bb54c98','2017-10-17 11:34:28',1508211268,1),
	(7,'2E8546AB','18201430000','家长mep2b',NULL,NULL,NULL,NULL,'d3e3aad7256f373a52a9cfb99bb54c98','2017-10-18 09:34:29',1508290469,1),
	(8,'28D48859','17600115070','家长zbq2d',NULL,NULL,NULL,NULL,'7caab0a8b3b888cafc9febed7d08f2d0','2017-10-21 14:41:25',1508568085,1),
	(9,'2959DA35','18201430001','家长YerEd',NULL,NULL,NULL,NULL,'d3e3aad7256f373a52a9cfb99bb54c98','2017-10-25 18:09:34',1508926174,1);

/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_token
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_token`;

CREATE TABLE `user_token` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL COMMENT '用户id',
  `token` varchar(32) NOT NULL COMMENT 'token',
  `ip` char(16) NOT NULL,
  `time` datetime NOT NULL COMMENT '登陆时间',
  `agent` varchar(255) DEFAULT NULL COMMENT 'user_agent',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='记录用户登陆情况';

LOCK TABLES `user_token` WRITE;
/*!40000 ALTER TABLE `user_token` DISABLE KEYS */;

INSERT INTO `user_token` (`id`, `user_id`, `token`, `ip`, `time`, `agent`)
VALUES
	(1,17196,'90e8346cff29185b96ec5aad9e43b310','192.168.33.1','2017-10-11 13:45:50','PostmanRuntime/6.4.0'),
	(2,1,'fcb29f21c840c5e0574f16da1a12d504','192.168.33.1','2017-10-11 13:48:09','PostmanRuntime/6.4.0'),
	(3,1,'b6efb657d28026aaf0f1f6304a07d5b5','192.168.33.1','2017-10-11 13:48:59','PostmanRuntime/6.4.0'),
	(4,1,'1d0af882b2ab01349ad195db0fd5e328','192.168.33.1','2017-10-11 13:53:46','PostmanRuntime/6.4.0'),
	(5,1,'43ccb83b73be6e466778d4a925310c1f','192.168.33.1','2017-10-11 13:54:01','PostmanRuntime/6.4.0'),
	(6,2,'764d8065d26195db09d96915b0e8a122','192.168.33.1','2017-10-11 14:33:05','PostmanRuntime/6.4.0'),
	(7,1,'e957ec03af4c46b5976dd512ef732a06','192.168.33.1','2017-10-11 14:44:06','PostmanRuntime/6.4.0'),
	(8,1,'8e4ea971887752d22bac961ff0716d8d','192.168.33.1','2017-10-11 14:47:39','PostmanRuntime/6.4.0'),
	(9,1,'72ba1887b90adb83d6d49c260f5308fe','192.168.33.1','2017-10-11 14:49:02','PostmanRuntime/6.4.0'),
	(10,1,'3d505df6b30eb7a884d116e067dde93c','192.168.33.1','2017-10-11 14:50:07','PostmanRuntime/6.4.0'),
	(11,1,'92960a3208f36e55774c31bd9489e2cc','192.168.33.1','2017-10-11 14:50:30','PostmanRuntime/6.4.0'),
	(12,4,'e16826357006d8a1fc238537d821728a','192.168.33.1','2017-10-16 18:04:48','PostmanRuntime/6.4.0'),
	(13,5,'d43a12d12714374c3e33900c4612b854','222.128.15.91','2017-10-16 18:35:50','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36'),
	(14,5,'34e03716c124a1b65673b80de0350bbb','61.148.243.252','2017-10-17 09:22:20','Mozilla/5.0 (Linux; U; Android 6.0.1; zh-cn; MI 5 Build/MXB48T) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30'),
	(15,5,'704e9ea9b3ce96694d65a37592b165f6','61.148.243.252','2017-10-17 09:22:42','Mozilla/5.0 (Linux; U; Android 6.0.1; zh-cn; MI 5 Build/MXB48T) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30'),
	(16,5,'d8014f47382f4cd5aaac14da488e70a1','61.148.243.252','2017-10-17 09:22:57','Mozilla/5.0 (Linux; U; Android 6.0.1; zh-cn; MI 5 Build/MXB48T) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30'),
	(17,5,'5c1142797eb840d1841b19e50098f544','61.148.243.252','2017-10-17 10:53:15','Mozilla/5.0 (Linux; U; Android 6.0.1; zh-cn; MI 5 Build/MXB48T) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30'),
	(18,6,'13cfa9798e7d147366ac216675a056b3','61.148.243.252','2017-10-17 11:34:28','Mozilla/5.0 (Linux; U; Android 6.0.1; zh-cn; MI 5 Build/MXB48T) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30'),
	(19,7,'3eda7325daea5a904e58769a7579e476','222.128.15.91','2017-10-18 09:34:29','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36'),
	(20,1,'24765b97f82fa4ea58350d120a6999b3','192.168.33.1','2017-10-18 15:32:36','PostmanRuntime/6.4.0'),
	(21,1,'4e40c57e3991ff3002de62420e1fae5c','222.128.15.91','2017-10-19 10:21:03','PostmanRuntime/6.4.0'),
	(22,1,'c3138e503d0369d37879a932c382aa86','192.168.33.1','2017-10-19 10:21:25','PostmanRuntime/6.4.0'),
	(23,8,'9909957cc61b2ceeb419cdb3899a5d51','222.128.15.91','2017-10-21 14:41:25','dedao/1.0 (iPhone; iOS 11.0.3; Scale/2.00)'),
	(24,8,'f324069a2dd571fbde61de2f5b5d2e64','222.128.15.91','2017-10-21 15:19:24','dedao/1.0 (iPhone; iOS 11.0.3; Scale/2.00)'),
	(25,8,'987a46a4593450ba60098ff3a6c19cb0','222.128.15.91','2017-10-21 15:21:01','dedao/1.0 (iPhone; iOS 11.0.3; Scale/2.00)'),
	(26,8,'ae5b8029863e5ca936e56e0b6c518922','222.128.15.91','2017-10-21 15:36:21','dedao/1.0 (iPhone; iOS 11.0.3; Scale/2.00)'),
	(27,8,'68c48fa103e092a22034c4e2a897ea92','222.128.15.91','2017-10-21 17:54:28','dedao/1.0 (iPhone; iOS 11.0.3; Scale/2.00)'),
	(28,1,'854ed267b0cbdbc8697243b0d2dbf2c0','222.128.15.91','2017-10-25 14:03:52','PostmanRuntime/6.4.1'),
	(29,1,'c0167e62b6087bc970605e4798399d50','222.128.15.91','2017-10-25 14:04:18','PostmanRuntime/6.4.1'),
	(30,1,'0d224e84e3734fd146e2769cd0e711c5','192.168.33.1','2017-10-25 14:04:29','PostmanRuntime/6.4.1'),
	(31,1,'fc4cae932206f783500ea0aecd18dcc4','192.168.33.1','2017-10-25 14:04:52','PostmanRuntime/6.4.1'),
	(32,7,'2fed1c23467d08887d765e0eb4d4bb7e','222.128.15.91','2017-10-25 18:08:39','Mozilla/5.0 (Linux; U; Android 5.1; zh-cn; m3 note Build/LMY47I) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Mobile Safari/537.36'),
	(33,7,'6f55ebb8ff5ad5df0ed3a853b2e04683','222.128.15.91','2017-10-25 18:08:42','Mozilla/5.0 (Linux; U; Android 5.1; zh-cn; m3 note Build/LMY47I) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Mobile Safari/537.36'),
	(34,7,'2e06e6f8c7945855e6a3f7f01e6c9cdf','222.128.15.91','2017-10-25 18:08:50','Mozilla/5.0 (Linux; U; Android 5.1; zh-cn; m3 note Build/LMY47I) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Mobile Safari/537.36'),
	(35,9,'6cdfbaa2172e9608ed873daa7463ed67','222.128.15.91','2017-10-25 18:09:34','Mozilla/5.0 (Linux; U; Android 5.1; zh-cn; m3 note Build/LMY47I) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Mobile Safari/537.36'),
	(36,9,'a377e7f586b40e1edd6431ad6ec431de','222.128.15.91','2017-10-25 18:09:46','Mozilla/5.0 (Linux; U; Android 5.1; zh-cn; m3 note Build/LMY47I) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Mobile Safari/537.36'),
	(37,9,'260a836ba84cc5eac9afad70a916f132','222.128.15.91','2017-10-25 18:12:40','Mozilla/5.0 (Linux; U; Android 5.1; zh-cn; m3 note Build/LMY47I) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Mobile Safari/537.36'),
	(38,9,'ea36999f2d07f15151b676cb722dd1bd','222.128.15.91','2017-10-25 18:13:18','Mozilla/5.0 (Linux; U; Android 5.1; zh-cn; m3 note Build/LMY47I) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Mobile Safari/537.36'),
	(39,9,'97e5c9ca59b72632c0f9b814f6447f47','222.128.15.91','2017-10-25 18:13:21','Mozilla/5.0 (Linux; U; Android 5.1; zh-cn; m3 note Build/LMY47I) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Mobile Safari/537.36');

/*!40000 ALTER TABLE `user_token` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
