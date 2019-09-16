SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for ztb_commonly_group
-- ----------------------------
DROP TABLE IF EXISTS `cms_commonly_group`;
CREATE TABLE `cms_commonly_group`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` int(15) NULL DEFAULT NULL COMMENT '父级id',
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '分类名称',
  `inputtime` int(18) UNSIGNED NOT NULL DEFAULT 0,
  `updatetime` int(18) UNSIGNED NOT NULL DEFAULT 0,
  `type` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '类型',
  `listorder` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
  `is_display` int(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否显示',
  `is_delete` int(1) NOT NULL COMMENT '是否删除',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id`(`id`) USING BTREE,
  INDEX `listorder`(`listorder`) USING BTREE,
  INDEX `is_display`(`is_display`) USING BTREE,
  INDEX `is_delete`(`is_delete`) USING BTREE,
  INDEX `parent_id`(`parent_id`) USING BTREE,
  INDEX `type`(`type`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;


