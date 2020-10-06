-- --------------------------------------------------------
-- 主机:                           localhost
-- 服务器版本:                        8.0.21 - MySQL Community Server - GPL
-- 服务器操作系统:                      Win64
-- HeidiSQL 版本:                  10.3.0.5771
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- 导出  表 dwz.auth 结构
CREATE TABLE IF NOT EXISTS `auth` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '权限名称',
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '权限链接',
  `pid` int unsigned NOT NULL DEFAULT '0' COMMENT '父级ID',
  `pre_ids` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '父级菜单树',
  `module_id` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '模块ID',
  `sort_no` tinyint NOT NULL DEFAULT '0' COMMENT '排序，值越小越靠前',
  `status` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '状态 0停用/1正常',
  PRIMARY KEY (`id`),
  KEY `url` (`url`),
  KEY `pid` (`pid`),
  KEY `sort_no` (`sort_no`),
  KEY `status` (`status`),
  KEY `module_id` (`module_id`),
  KEY `pre_ids` (`pre_ids`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='权限表';

-- 正在导出表  dwz.auth 的数据：~17 rows (大约)
DELETE FROM `auth`;
/*!40000 ALTER TABLE `auth` DISABLE KEYS */;
INSERT INTO `auth` (`id`, `title`, `url`, `pid`, `pre_ids`, `module_id`, `sort_no`, `status`) VALUES
	(1, '后台框架共用', '', 0, NULL, 1, 0, 1),
	(2, '获取后台首页所需信息', '/api/index', 1, '1', 1, 0, 1),
	(3, '修改密码', '/api/change_password', 1, '1', 1, 0, 1),
	(4, '注销登录', '/api/logout', 1, '1', 1, 0, 1),
	(5, '菜单管理', '/api/menu', 0, NULL, 1, 0, 1),
	(6, '新增/编辑菜单', '/api/edit_menu', 5, '5', 1, 0, 1),
	(7, '删除菜单', '/api/remove_menu', 5, '5', 1, 0, 1),
	(8, '权限管理', '/api/auth', 0, NULL, 1, 0, 1),
	(9, '新增/编辑权限', '/api/edit_auth', 8, '8', 1, 0, 1),
	(10, '删除权限', '/api/remove_auth', 8, '8', 1, 0, 1),
	(11, '角色管理', '/api/role', 0, '', 1, 0, 1),
	(12, '新增/编辑角色', '/api/edit_role', 11, '11', 1, 0, 1),
	(13, '删除角色', '/api/remove_role', 11, '11', 1, 0, 1),
	(14, '分配权限', '/api/role_auth', 11, '11', 1, 0, 1),
	(15, '保存分配的权限', '/api/role_auth_save', 14, '11,14', 1, 0, 1),
	(16, '分配菜单', '/api/role_menu', 11, '11', 1, 0, 1),
	(17, '保存分配的菜单', '/api/role_menu_save', 16, '11,16', 1, 0, 1),
	(18, '用户列表', '/api/user', 0, '', 1, 0, 1),
	(19, '新增/编辑用户', '/api/user_edit', 18, '18', 1, 0, 1),
	(20, '删除用户', '/api/user_remove', 18, '18', 1, 0, 1),
	(21, '修改用户密码', '/api/user_change_password', 18, '18', 1, 0, 1),
	(22, '绑定角色', '/api/user_add_role', 18, '18', 1, 0, 1),
	(23, '移除角色', '/api/user_remove_role', 18, '18', 1, 0, 1);
/*!40000 ALTER TABLE `auth` ENABLE KEYS */;

-- 导出  表 dwz.menu 结构
CREATE TABLE IF NOT EXISTS `menu` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '菜单名称',
  `url` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '菜单url',
  `icon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '菜单图标',
  `pid` int unsigned NOT NULL DEFAULT '0' COMMENT '父级菜单ID',
  `pre_ids` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '父级菜单树',
  `module_id` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '模块ID',
  `sort_no` tinyint NOT NULL DEFAULT '0' COMMENT '排序，值越小越靠前',
  `status` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '状态 0停用/1正常',
  PRIMARY KEY (`id`),
  KEY `url` (`url`),
  KEY `sort_no` (`sort_no`),
  KEY `status` (`status`),
  KEY `pid` (`pid`),
  KEY `module_id` (`module_id`),
  KEY `pre_ids` (`pre_ids`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='菜单表';

-- 正在导出表  dwz.menu 的数据：~5 rows (大约)
DELETE FROM `menu`;
/*!40000 ALTER TABLE `menu` DISABLE KEYS */;
INSERT INTO `menu` (`id`, `title`, `url`, `icon`, `pid`, `pre_ids`, `module_id`, `sort_no`, `status`) VALUES
	(1, '后台主页', '/home.html', 'el-icon-trophy', 0, NULL, 1, 0, 1),
	(2, '菜单管理', '/menu.html', 'el-icon-trophy', 4, '4', 1, 0, 1),
	(3, '权限管理', '/auth.html', 'el-icon-user', 4, '4', 1, 1, 1),
	(4, '系统管理', '#', 'el-icon-setting', 0, '', 1, 0, 1),
	(5, '角色管理', '/role.html', 'el-icon-s-custom', 4, '4', 1, 0, 1),
	(6, '用户管理', '#', 'el-icon-user', 0, '', 1, 0, 1),
	(7, '用户列表', '/user.html', 'el-icon-user', 6, '6', 1, 0, 1);
/*!40000 ALTER TABLE `menu` ENABLE KEYS */;

-- 导出  表 dwz.module 结构
CREATE TABLE IF NOT EXISTS `module` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '模块名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='模块表';

-- 正在导出表  dwz.module 的数据：~0 rows (大约)
DELETE FROM `module`;
/*!40000 ALTER TABLE `module` DISABLE KEYS */;
INSERT INTO `module` (`id`, `name`) VALUES
	(1, '管理后台');
/*!40000 ALTER TABLE `module` ENABLE KEYS */;

-- 导出  表 dwz.role 结构
CREATE TABLE IF NOT EXISTS `role` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL COMMENT '名称',
  `status` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '状态 0停用/1正常',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='角色表';

-- 正在导出表  dwz.role 的数据：~1 rows (大约)
DELETE FROM `role`;
/*!40000 ALTER TABLE `role` DISABLE KEYS */;
INSERT INTO `role` (`id`, `name`, `status`) VALUES
	(1, '后台管理员', 1),
	(2, '测试角色', 1);
/*!40000 ALTER TABLE `role` ENABLE KEYS */;

-- 导出  表 dwz.role_auth 结构
CREATE TABLE IF NOT EXISTS `role_auth` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int unsigned NOT NULL DEFAULT '0' COMMENT '角色ID',
  `auth_id` int unsigned NOT NULL DEFAULT '0' COMMENT '权限ID',
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`),
  KEY `auth_id` (`auth_id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='角色权限关联表';

-- 正在导出表  dwz.role_auth 的数据：~17 rows (大约)
DELETE FROM `role_auth`;
/*!40000 ALTER TABLE `role_auth` DISABLE KEYS */;
INSERT INTO `role_auth` (`id`, `role_id`, `auth_id`) VALUES
	(1, 1, 1),
	(2, 1, 2),
	(4, 1, 4),
	(5, 1, 5),
	(6, 1, 6),
	(7, 1, 7),
	(8, 1, 8),
	(9, 1, 9),
	(10, 1, 10),
	(11, 1, 11),
	(12, 1, 12),
	(13, 1, 13),
	(14, 1, 14),
	(15, 1, 15),
	(19, 1, 3),
	(20, 1, 16),
	(21, 1, 17),
	(22, 1, 18),
	(23, 1, 19),
	(24, 1, 20),
	(25, 1, 21),
	(26, 1, 22),
	(27, 1, 23);
/*!40000 ALTER TABLE `role_auth` ENABLE KEYS */;

-- 导出  表 dwz.role_menu 结构
CREATE TABLE IF NOT EXISTS `role_menu` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int unsigned NOT NULL DEFAULT '0' COMMENT '角色ID',
  `menu_id` int unsigned NOT NULL DEFAULT '0' COMMENT '菜单ID',
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`),
  KEY `menu_id` (`menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='角色菜单关联表';

-- 正在导出表  dwz.role_menu 的数据：~5 rows (大约)
DELETE FROM `role_menu`;
/*!40000 ALTER TABLE `role_menu` DISABLE KEYS */;
INSERT INTO `role_menu` (`id`, `role_id`, `menu_id`) VALUES
	(1, 1, 1),
	(3, 1, 3),
	(4, 1, 4),
	(5, 1, 5),
	(6, 1, 2),
	(7, 1, 6),
	(8, 1, 7);
/*!40000 ALTER TABLE `role_menu` ENABLE KEYS */;

-- 导出  表 dwz.user 结构
CREATE TABLE IF NOT EXISTS `user` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `username` char(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '用户名',
  `nickname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '昵称',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '头像url',
  `pwd` char(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '密码',
  `pwd_time` int unsigned NOT NULL DEFAULT '0' COMMENT '修改密码时间',
  `status` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '状态 0停用/1正常',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `pwd` (`pwd`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='用户表';

-- 正在导出表  dwz.user 的数据：~1 rows (大约)
DELETE FROM `user`;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`id`, `username`, `nickname`, `avatar`, `pwd`, `pwd_time`, `status`) VALUES
	(1, 'admin', 'Qbit', 'https://fuss10.elemecdn.com/e/5d/4a731a90594a4af544c0c25941171jpeg.jpeg', '002ddd49a47bbd5697889e2d18e1f17b7db504c4', 1601969854, 1),
	(2, 'test', 'test', '', '969c3c48aba73cbb270b221bcafcbaf409d2c33e', 1601993736, 1);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;

-- 导出  表 dwz.user_role 结构
CREATE TABLE IF NOT EXISTS `user_role` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `role_id` int unsigned NOT NULL DEFAULT '0' COMMENT '角色ID',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='用户角色关联表';

-- 正在导出表  dwz.user_role 的数据：~1 rows (大约)
DELETE FROM `user_role`;
/*!40000 ALTER TABLE `user_role` DISABLE KEYS */;
INSERT INTO `user_role` (`id`, `user_id`, `role_id`) VALUES
	(1, 1, 1),
	(4, 2, 1);
/*!40000 ALTER TABLE `user_role` ENABLE KEYS */;

-- 导出  表 dwz.user_token 结构
CREATE TABLE IF NOT EXISTS `user_token` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `token` char(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '登录凭证',
  `token_time` int unsigned NOT NULL DEFAULT '0' COMMENT '凭证生成时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='用户登录token';

-- 正在导出表  dwz.user_token 的数据：~1 rows (大约)
DELETE FROM `user_token`;
/*!40000 ALTER TABLE `user_token` DISABLE KEYS */;
INSERT INTO `user_token` (`id`, `user_id`, `token`, `token_time`) VALUES
	(3, 1, '4dd8efa5f1780ac889849ace9565c61634a05191', 1601994062);
/*!40000 ALTER TABLE `user_token` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
