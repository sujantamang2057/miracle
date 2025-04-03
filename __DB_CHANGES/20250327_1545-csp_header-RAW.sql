/*
 Navicat Premium Dump SQL

 Source Server         : 200-MySQL-8.4@3384-mi_3384
 Source Server Type    : MySQL
 Source Server Version : 80404 (8.4.4)
 Source Host           : 192.168.1.200:3384
 Source Schema         : dev_milaravel10app_db_v1_0

 Target Server Type    : MySQL
 Target Server Version : 80404 (8.4.4)
 File Encoding         : 65001

 Date: 27/03/2025 15:45:00
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for csp_header
-- ----------------------------
DROP TABLE IF EXISTS `csp_header`;
CREATE TABLE `csp_header`  (
  `csp_id` smallint NOT NULL AUTO_INCREMENT,
  `directive` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'eg (default-src)',
  `keyword` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'CSV Data eg. (self,unsafe-eval)',
  `value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'CSV Data domains',
  `schema` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `publish` tinyint(1) NOT NULL DEFAULT 2 COMMENT '1:Yes; 2:No',
  `reserved` tinyint(1) NOT NULL DEFAULT 2 COMMENT '1:Yes; 2:No',
  `created_at` timestamp NOT NULL,
  `created_by` bigint NOT NULL DEFAULT 1 COMMENT '1:System',
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` bigint NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint NULL DEFAULT NULL,
  PRIMARY KEY (`csp_id`) USING BTREE,
  UNIQUE INDEX `directive`(`directive` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = 'content security policy headers' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of csp_header
-- ----------------------------
INSERT INTO `csp_header` VALUES (1, 'base-uri', 'self', '', '', 1, 2, '2025-03-27 15:30:00', 1, '2025-03-27 15:40:00', 6, NULL, NULL);
INSERT INTO `csp_header` VALUES (2, 'connect-src', 'self', 'https://cdn.plyr.io,https://noembed.com', 'data:', 1, 2, '2025-03-27 15:30:00', 1, '2025-03-27 15:40:00', NULL, NULL, NULL);
INSERT INTO `csp_header` VALUES (3, 'default-src', 'self', NULL, NULL, 1, 2, '2025-03-27 15:30:00', 1, '2025-03-27 15:40:00', 6, NULL, NULL);
INSERT INTO `csp_header` VALUES (4, 'font-src', 'self', 'https://fonts.googleapis.com,https://fonts.gstatic.com', 'data:', 1, 2, '2025-03-27 15:30:00', 1, '2025-03-27 15:40:00', 11, NULL, NULL);
INSERT INTO `csp_header` VALUES (5, 'form-action', 'self', '', '', 1, 2, '2025-03-27 15:30:00', 1, '2025-03-27 15:40:00', 6, NULL, NULL);
INSERT INTO `csp_header` VALUES (6, 'frame-src', 'self', 'https://www.google.com,https://www.youtube-nocookie.com', 'data:', 1, 2, '2025-03-27 15:30:00', 1, '2025-03-27 15:40:00', 1, NULL, NULL);
INSERT INTO `csp_header` VALUES (7, 'img-src', 'self', 'https://i.ytimg.com', 'data:,blob:', 1, 2, '2025-03-27 15:30:00', 1, '2025-03-27 15:40:00', 6, NULL, NULL);
INSERT INTO `csp_header` VALUES (8, 'manifest-src', 'self', NULL, '', 1, 2, '2025-03-27 15:30:00', 1, '2025-03-27 15:40:00', 6, NULL, NULL);
INSERT INTO `csp_header` VALUES (9, 'media-src', 'self', '', '', 1, 2, '2025-03-27 15:30:00', 1, '2025-03-27 15:40:00', 6, NULL, NULL);
INSERT INTO `csp_header` VALUES (10, 'object-src', 'self', '', '', 1, 2, '2025-03-27 15:30:00', 1, '2025-03-27 15:40:00', 6, NULL, NULL);
INSERT INTO `csp_header` VALUES (11, 'script-src', 'self,unsafe-inline,unsafe-eval', 'https://ajax.googleapis.com,https://cdn.plyr.io,https://www.youtube.com', 'blob:', 1, 2, '2025-03-27 15:30:00', 1, '2025-03-27 15:40:00', 1, NULL, NULL);
INSERT INTO `csp_header` VALUES (12, 'style-src', 'self,unsafe-inline', 'https://fonts.googleapis.com,https://ajax.googleapis.com,https://cdn.plyr.io', 'data:', 1, 2, '2025-03-27 15:30:00', 1, '2025-03-27 15:40:00', 1, NULL, NULL);
INSERT INTO `csp_header` VALUES (13, 'worker-src', 'self', '', 'blob:', 1, 2, '2025-03-27 15:30:00', 1, '2025-03-27 15:40:00', 6, NULL, NULL);

SET FOREIGN_KEY_CHECKS = 1;
