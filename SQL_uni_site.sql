-- --------------------------------------------------------
-- 主机:                           127.0.0.1
-- 服务器版本:                        8.0.21 - MySQL Community Server - GPL
-- 服务器操作系统:                      Win64
-- HeidiSQL 版本:                  10.3.0.5771
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- 导出  表 uni_site.auth 结构
CREATE TABLE IF NOT EXISTS `auth` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '权限名称',
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '权限链接',
  `pid` int unsigned NOT NULL DEFAULT '0' COMMENT '父级ID',
  `module_id` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '模块ID',
  `sort_no` tinyint NOT NULL DEFAULT '0' COMMENT '排序，值越小越靠前',
  `status` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '状态 0停用/1正常',
  PRIMARY KEY (`id`),
  KEY `url` (`url`),
  KEY `pid` (`pid`),
  KEY `sort_no` (`sort_no`),
  KEY `status` (`status`),
  KEY `module_id` (`module_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='权限表';

-- 正在导出表  uni_site.auth 的数据：~0 rows (大约)
DELETE FROM `auth`;
/*!40000 ALTER TABLE `auth` DISABLE KEYS */;
INSERT INTO `auth` (`id`, `title`, `url`, `pid`, `module_id`, `sort_no`, `status`) VALUES
	(1, '后台框架共用', '', 0, 1, 0, 1),
	(2, '获取后台首页所需信息', '/api/index', 1, 1, 0, 1),
	(3, '修改密码', '/api/change_password', 1, 1, 0, 1),
	(4, '注销登录', '/api/logout', 1, 1, 0, 1);
/*!40000 ALTER TABLE `auth` ENABLE KEYS */;

-- 导出  表 uni_site.menu 结构
CREATE TABLE IF NOT EXISTS `menu` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '菜单名称',
  `url` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '菜单url',
  `icon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '菜单图标',
  `pid` int unsigned NOT NULL DEFAULT '0' COMMENT '父级菜单ID',
  `module_id` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '模块ID',
  `sort_no` tinyint NOT NULL DEFAULT '0' COMMENT '排序，值越小越靠前',
  `status` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '状态 0停用/1正常',
  PRIMARY KEY (`id`),
  KEY `url` (`url`),
  KEY `sort_no` (`sort_no`),
  KEY `status` (`status`),
  KEY `pid` (`pid`),
  KEY `module_id` (`module_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='菜单表';

-- 正在导出表  uni_site.menu 的数据：~0 rows (大约)
DELETE FROM `menu`;
/*!40000 ALTER TABLE `menu` DISABLE KEYS */;
INSERT INTO `menu` (`id`, `title`, `url`, `icon`, `pid`, `module_id`, `sort_no`, `status`) VALUES
	(1, '后台主页', '/home.html', 'el-icon-trophy', 0, 1, 0, 1);
/*!40000 ALTER TABLE `menu` ENABLE KEYS */;

-- 导出  表 uni_site.module 结构
CREATE TABLE IF NOT EXISTS `module` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '模块名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='模块表';

-- 正在导出表  uni_site.module 的数据：~0 rows (大约)
DELETE FROM `module`;
/*!40000 ALTER TABLE `module` DISABLE KEYS */;
INSERT INTO `module` (`id`, `name`) VALUES
	(1, '管理后台');
/*!40000 ALTER TABLE `module` ENABLE KEYS */;

-- 导出  表 uni_site.user 结构
CREATE TABLE IF NOT EXISTS `user` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `username` char(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '用户名',
  `nickname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '昵称',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '头像url',
  `pwd` char(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '密码',
  `pwd_time` int unsigned NOT NULL DEFAULT '0' COMMENT '修改密码时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `pwd` (`pwd`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='用户表';

-- 正在导出表  uni_site.user 的数据：~0 rows (大约)
DELETE FROM `user`;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`id`, `username`, `nickname`, `avatar`, `pwd`, `pwd_time`) VALUES
	(1, 'admin', 'Qbit', 'https://fuss10.elemecdn.com/e/5d/4a731a90594a4af544c0c25941171jpeg.jpeg', 'ec827ed9bc033d77f3ec08b05995feb6f13dcc3a', 1601620167);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;

-- 导出  表 uni_site.user_auth 结构
CREATE TABLE IF NOT EXISTS `user_auth` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `auth_id` int unsigned NOT NULL DEFAULT '0' COMMENT '权限ID',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `auth_id` (`auth_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='用户权限表';

-- 正在导出表  uni_site.user_auth 的数据：~0 rows (大约)
DELETE FROM `user_auth`;
/*!40000 ALTER TABLE `user_auth` DISABLE KEYS */;
INSERT INTO `user_auth` (`id`, `user_id`, `auth_id`) VALUES
	(1, 1, 1),
	(2, 1, 2),
	(3, 1, 3),
	(4, 1, 4);
/*!40000 ALTER TABLE `user_auth` ENABLE KEYS */;

-- 导出  表 uni_site.user_menu 结构
CREATE TABLE IF NOT EXISTS `user_menu` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `menu_id` int unsigned NOT NULL DEFAULT '0' COMMENT '菜单ID',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `menu_id` (`menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='用户菜单';

-- 正在导出表  uni_site.user_menu 的数据：~0 rows (大约)
DELETE FROM `user_menu`;
/*!40000 ALTER TABLE `user_menu` DISABLE KEYS */;
INSERT INTO `user_menu` (`id`, `user_id`, `menu_id`) VALUES (1, 1, 1);
/*!40000 ALTER TABLE `user_menu` ENABLE KEYS */;

-- 导出  表 uni_site.user_token 结构
CREATE TABLE IF NOT EXISTS `user_token` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `token` char(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '登录凭证',
  `token_time` int unsigned NOT NULL DEFAULT '0' COMMENT '凭证生成时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='用户登录token';

-- 正在导出表  uni_site.user_token 的数据：~0 rows (大约)
DELETE FROM `user_token`;
/*!40000 ALTER TABLE `user_token` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_token` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
