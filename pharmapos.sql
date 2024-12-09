/*
 Navicat Premium Dump SQL

 Source Server         : Local Mysql
 Source Server Type    : MySQL
 Source Server Version : 80030 (8.0.30)
 Source Host           : localhost:3306
 Source Schema         : pharmapos

 Target Server Type    : MySQL
 Target Server Version : 80030 (8.0.30)
 File Encoding         : 65001

 Date: 09/12/2024 07:58:29
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for bill_items
-- ----------------------------
DROP TABLE IF EXISTS `bill_items`;
CREATE TABLE `bill_items`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `bill_id` int NOT NULL,
  `medicine_id` int NOT NULL,
  `batch` int NOT NULL,
  `qty` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `saleprice` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `gross` decimal(10, 2) NULL DEFAULT NULL,
  `discounttype` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `discount` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `discountvalue` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `tax` decimal(10, 2) NULL DEFAULT NULL,
  `taxprice` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `price` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_bill_medicine_id`(`medicine_id` ASC) USING BTREE,
  INDEX `fk_bill_bill_id`(`bill_id` ASC) USING BTREE,
  INDEX `fk_bill_batch`(`batch` ASC) USING BTREE,
  CONSTRAINT `fk_bill_batch` FOREIGN KEY (`batch`) REFERENCES `medicine_batch` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `fk_bill_bill_id` FOREIGN KEY (`bill_id`) REFERENCES `medicine_bill` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `fk_bill_medicine_id` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 87 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of bill_items
-- ----------------------------
INSERT INTO `bill_items` VALUES (72, 55, 2, 34, '2', '250.00', 0.00, '1', '0.00', '0', 0.00, '', '500');
INSERT INTO `bill_items` VALUES (73, 56, 8, 37, '2', '50.00', 0.00, '1', '0.00', '0', 0.00, '', '100');
INSERT INTO `bill_items` VALUES (74, 57, 5, 38, '29', '60.00', 0.00, '1', '0.00', '0', 0.00, '', '1740');
INSERT INTO `bill_items` VALUES (75, 58, 4, 45, '2', '30.00', 0.00, '1', '0.00', '0', 0.00, '', '60');
INSERT INTO `bill_items` VALUES (76, 59, 6, 46, '5', '50.00', 0.00, '1', '0.00', '0', 0.00, '', '250');
INSERT INTO `bill_items` VALUES (77, 60, 2, 32, '20', '456.00', 0.00, '1', '20', '1824', 0.00, '', '7296');
INSERT INTO `bill_items` VALUES (78, 61, 8, 37, '5', '50.00', 0.00, '1', '0.00', '0', 0.00, '', '250');
INSERT INTO `bill_items` VALUES (79, 62, 12, 40, '2', '45.00', 0.00, '1', '0.00', '0', 0.00, '', '90');
INSERT INTO `bill_items` VALUES (81, 64, 2, 34, '5', '250.00', 0.00, '1', '0.00', '0', 0.00, '', '1250');
INSERT INTO `bill_items` VALUES (82, 65, 11, 44, '15', '60.00', 0.00, '1', '0.00', '0', 0.00, '', '900');
INSERT INTO `bill_items` VALUES (83, 53, 1, 31, '2', '30.00', 60.00, '1', '1.00', '0.6', 2.00, '1.19', '60.59');
INSERT INTO `bill_items` VALUES (84, 66, 2, 32, '10', '456.00', 4560.00, '2', '6.00', '6.00', 1.00, '45.54', '4599.54');
INSERT INTO `bill_items` VALUES (85, 66, 3, 42, '2', '30.00', 60.00, '1', '0.00', '0', 0.00, '0', '60');
INSERT INTO `bill_items` VALUES (86, 67, 13, 48, '5', '20.00', 100.00, '1', '0.00', '0', 0.00, '0', '100');

-- ----------------------------
-- Table structure for customers
-- ----------------------------
DROP TABLE IF EXISTS `customers`;
CREATE TABLE `customers`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `firstname` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `lastname` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `mobile` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `gender` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_date` datetime NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 474 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of customers
-- ----------------------------
INSERT INTO `customers` VALUES (14, 'Jane', 'Smith', 'jane.smith1@example.com', '5', 'Female', 1, '2023-11-21 10:25:43');
INSERT INTO `customers` VALUES (15, 'Michael', 'Brown', 'michael.brown1@example.com', '8', 'Male', 1, '2023-12-02 08:15:30');
INSERT INTO `customers` VALUES (16, 'Emily', 'Davis', 'emily.davis1@example.com', '4567890123', 'Female', 1, '2023-10-15 12:05:55');
INSERT INTO `customers` VALUES (76, 'Emily', 'Anderson', 'emily@example.com', '4567890123', 'Female', 1, '2023-10-15 12:05:55');
INSERT INTO `customers` VALUES (82, 'Isabella', 'Lewis', 'isabella.lewis1@example.com', '5432109876', 'Female', 1, '2023-09-30 11:50:22');
INSERT INTO `customers` VALUES (466, 'asf', 'asf', 'test2@gmail.com', '2147483647', '', 1, '2024-12-07 13:40:17');
INSERT INTO `customers` VALUES (467, 'Noman1', 'Shoukat1', 'nomanaadma1@gmail.com', '032226917899', 'Female', 1, '2024-12-07 13:54:07');
INSERT INTO `customers` VALUES (468, 'Muhammad ', 'Arif', 'aarifahmad135@gmail.com', '03008911760', 'Male', 1, '2024-12-07 21:10:16');
INSERT INTO `customers` VALUES (469, 'BASHIR', 'BASHIR', 'BASHIR@email.com', '1231231231', '', 1, '2024-12-07 21:14:05');
INSERT INTO `customers` VALUES (470, 'hamza', 'hamza', 'hamza@email.com', '1231231231', '', 1, '2024-12-07 21:16:43');
INSERT INTO `customers` VALUES (471, 'moiz', 'moiz', 'moiz@email.com', '12312312312', '', 1, '2024-12-07 21:17:23');
INSERT INTO `customers` VALUES (472, 'Arif', 'hamed', 'arif@email.com', '123123123', '', 1, '2024-12-07 21:18:04');
INSERT INTO `customers` VALUES (473, 'Ramzan', 'Ramzan', 'ramzan@gmail.com', '12345678', '', 1, '2024-12-08 10:46:33');

-- ----------------------------
-- Table structure for login_attempts
-- ----------------------------
DROP TABLE IF EXISTS `login_attempts`;
CREATE TABLE `login_attempts`  (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(96) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `ip` varchar(40) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `count` int NOT NULL,
  `date_added` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  PRIMARY KEY (`user_id`) USING BTREE,
  UNIQUE INDEX `email`(`email` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of login_attempts
-- ----------------------------

-- ----------------------------
-- Table structure for media
-- ----------------------------
DROP TABLE IF EXISTS `media`;
CREATE TABLE `media`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `media` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `ext` varchar(5) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of media
-- ----------------------------
INSERT INTO `media` VALUES (6, 'media-399817862674f3a931cfd8.png', 'png', '2024-12-03 22:36:27');
INSERT INTO `media` VALUES (7, 'media-1547234326674f3a9b5d2e1.png', 'png', '2024-12-03 22:36:35');
INSERT INTO `media` VALUES (8, 'media-1980415954674f3a9b7d4bc.png', 'png', '2024-12-03 22:36:35');
INSERT INTO `media` VALUES (9, 'media-2047737911674f3a9c5ce3f.jpg', 'jpg', '2024-12-03 22:36:36');

-- ----------------------------
-- Table structure for medicine_batch
-- ----------------------------
DROP TABLE IF EXISTS `medicine_batch`;
CREATE TABLE `medicine_batch`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `batch` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `expiry` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `pqty` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `qty` decimal(10, 2) NOT NULL,
  `saleprice` decimal(10, 2) NOT NULL,
  `purchaseprice` decimal(10, 2) NOT NULL,
  `gross` decimal(10, 2) NOT NULL,
  `discounttype` int NOT NULL,
  `discount` decimal(10, 2) NOT NULL,
  `discountvalue` decimal(10, 2) NOT NULL,
  `tax` decimal(10, 2) NOT NULL,
  `taxprice` decimal(10, 2) NOT NULL,
  `price` decimal(10, 2) NOT NULL,
  `sold` decimal(10, 2) NOT NULL DEFAULT 0.00,
  `medicine_id` int NOT NULL,
  `purchase_id` int NOT NULL,
  `status` tinyint NULL DEFAULT 1,
  `created_date` datetime NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_medicine_id`(`medicine_id` ASC) USING BTREE,
  INDEX `fk_purchase_id`(`purchase_id` ASC) USING BTREE,
  CONSTRAINT `fk_medicine_id` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `fk_purchase_id` FOREIGN KEY (`purchase_id`) REFERENCES `medicine_purchase` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 49 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of medicine_batch
-- ----------------------------
INSERT INTO `medicine_batch` VALUES (31, '123', '2025-07', '', 10.00, 30.00, 20.00, 200.00, 2, 10.00, 10.00, 2.00, 3.80, 193.80, 2.00, 1, 24, 1, '2024-12-07 01:41:15');
INSERT INTO `medicine_batch` VALUES (32, '1234', '2025-07', '123', 124.00, 456.00, 123.00, 15252.00, 1, 3.00, 457.56, 4.00, 591.78, 15386.22, 30.00, 2, 25, 1, '2024-12-07 13:51:07');
INSERT INTO `medicine_batch` VALUES (33, '1', '2025-12', '15', 10.00, 30.00, 20.00, 0.00, 1, 0.00, 0.00, 0.00, 0.00, 200.00, 0.00, 10, 26, 1, '2024-12-07 20:53:58');
INSERT INTO `medicine_batch` VALUES (34, '2', '2025-07', '100', 10.00, 250.00, 200.00, 0.00, 1, 0.00, 0.00, 0.00, 0.00, 2000.00, 6.00, 2, 27, 0, '2024-12-07 20:55:12');
INSERT INTO `medicine_batch` VALUES (35, '2', '2025-10', '10', 20.00, 30.00, 13.00, 0.00, 1, 0.00, 0.00, 0.00, 0.00, 260.00, 0.00, 10, 28, 1, '2024-12-07 20:55:59');
INSERT INTO `medicine_batch` VALUES (36, '10', '2025-07', '10', 20.00, 20.00, 15.00, 0.00, 1, 0.00, 0.00, 0.00, 0.00, 300.00, 0.00, 7, 29, 1, '2024-12-07 20:57:00');
INSERT INTO `medicine_batch` VALUES (37, '1', '2025-06', '20', 50.00, 50.00, 40.00, 0.00, 1, 0.00, 0.00, 0.00, 0.00, 2000.00, 7.00, 8, 30, 1, '2024-12-07 20:57:48');
INSERT INTO `medicine_batch` VALUES (38, '2', '2025-07', '50', 120.00, 60.00, 46.00, 0.00, 1, 0.00, 0.00, 0.00, 0.00, 5520.00, 29.00, 5, 31, 1, '2024-12-07 20:58:38');
INSERT INTO `medicine_batch` VALUES (40, '2', '2025-07', '20', 50.00, 45.00, 39.00, 0.00, 1, 0.00, 0.00, 0.00, 0.00, 1950.00, 2.00, 12, 33, 1, '2024-12-07 21:03:42');
INSERT INTO `medicine_batch` VALUES (42, '6', '2025-07', '10', 25.00, 30.00, 20.00, 0.00, 1, 0.00, 0.00, 0.00, 0.00, 500.00, 2.00, 3, 35, 1, '2024-12-07 21:05:28');
INSERT INTO `medicine_batch` VALUES (43, '7', '2025-07', '10', 25.00, 30.00, 20.00, 0.00, 1, 0.00, 0.00, 0.00, 0.00, 500.00, 0.00, 3, 36, 1, '2024-12-07 21:05:31');
INSERT INTO `medicine_batch` VALUES (44, '2', '2025-10', '20', 30.00, 60.00, 45.00, 0.00, 1, 0.00, 0.00, 0.00, 0.00, 1350.00, 15.00, 11, 37, 1, '2024-12-07 21:07:32');
INSERT INTO `medicine_batch` VALUES (45, '4', '2025-06', '20', 20.00, 30.00, 20.00, 0.00, 1, 0.00, 0.00, 0.00, 0.00, 400.00, 2.00, 4, 38, 1, '2024-12-07 21:08:19');
INSERT INTO `medicine_batch` VALUES (46, '8', '2025-05', '10', 28.00, 50.00, 43.00, 0.00, 1, 0.00, 0.00, 0.00, 0.00, 1204.00, 5.00, 6, 39, 1, '2024-12-07 21:09:01');
INSERT INTO `medicine_batch` VALUES (47, '3', '2025-07', '10', 20.00, 35.00, 30.00, 600.00, 1, 0.00, 0.00, 0.00, 0.00, 600.00, 0.00, 2, 40, 1, '2024-12-08 09:26:08');
INSERT INTO `medicine_batch` VALUES (48, '123', '2025-07', '10', 20.00, 20.00, 10.00, 200.00, 2, 10.00, 10.00, 2.00, 3.80, 193.80, 5.00, 13, 41, 1, '2024-12-08 10:44:21');

-- ----------------------------
-- Table structure for medicine_bill
-- ----------------------------
DROP TABLE IF EXISTS `medicine_bill`;
CREATE TABLE `medicine_bill`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `mobile` int NOT NULL,
  `method` int NOT NULL,
  `bill_date` date NOT NULL,
  `subtotal` decimal(10, 2) NOT NULL,
  `tax` decimal(10, 2) NOT NULL,
  `discount_value` decimal(10, 2) NOT NULL,
  `amount` decimal(10, 2) NOT NULL,
  `note` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `customer_id` int NOT NULL,
  `created_date` datetime NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_method`(`method` ASC) USING BTREE,
  INDEX `fk_customer_id`(`customer_id` ASC) USING BTREE,
  CONSTRAINT `fk_customer_id` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `fk_method` FOREIGN KEY (`method`) REFERENCES `payment_method` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 68 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of medicine_bill
-- ----------------------------
INSERT INTO `medicine_bill` VALUES (53, 'asf', 'test2@gmail.com', 2147483647, 1, '2024-12-07', 150.00, 3.44, 15.60, 137.84, '', 466, '2024-12-07 03:26:07');
INSERT INTO `medicine_bill` VALUES (54, 'Noman1 Shoukat1', 'nomanaadma1@gmail.com', 2147483647, 1, '2024-12-07', 164.00, 0.00, 0.00, 164.00, '', 467, '2024-12-07 21:09:49');
INSERT INTO `medicine_bill` VALUES (55, 'Muhammad  Arif', 'aarifahmad135@gmail.com', 2147483647, 1, '2024-12-02', 500.00, 0.00, 0.00, 500.00, '', 468, '2024-12-07 21:10:43');
INSERT INTO `medicine_bill` VALUES (56, 'Emily Davis', 'emily.davis1@example.com', 2147483647, 1, '2024-12-07', 100.00, 0.00, 0.00, 100.00, '', 16, '2024-12-07 21:11:19');
INSERT INTO `medicine_bill` VALUES (57, 'Isabella Lewis', 'isabella.lewis1@example.com', 2147483647, 2, '2024-12-07', 1740.00, 0.00, 0.00, 1740.00, '', 82, '2024-12-07 21:12:17');
INSERT INTO `medicine_bill` VALUES (58, 'Isabella Lewis', 'isabella.lewis1@example.com', 2147483647, 1, '2024-12-07', 60.00, 0.00, 0.00, 60.00, '', 82, '2024-12-07 21:13:15');
INSERT INTO `medicine_bill` VALUES (59, 'BASHIR', 'BASHIR@email.com', 1231231231, 1, '2024-12-07', 250.00, 0.00, 0.00, 250.00, '', 469, '2024-12-07 21:14:05');
INSERT INTO `medicine_bill` VALUES (60, 'Jane Smith', 'jane.smith1@example.com', 5, 1, '2024-12-07', 7296.00, 0.00, 1824.00, 7296.00, '', 14, '2024-12-07 21:15:10');
INSERT INTO `medicine_bill` VALUES (61, 'Michael Brown', 'michael.brown1@example.com', 8, 2, '2024-12-07', 250.00, 0.00, 0.00, 250.00, '', 15, '2024-12-07 21:16:09');
INSERT INTO `medicine_bill` VALUES (62, 'hamza', 'hamza@email.com', 1231231231, 1, '2024-12-07', 90.00, 0.00, 0.00, 90.00, '', 470, '2024-12-07 21:16:43');
INSERT INTO `medicine_bill` VALUES (63, 'moiz', 'moiz@email.com', 2147483647, 2, '2024-12-07', 246.00, 0.00, 0.00, 246.00, '', 471, '2024-12-07 21:17:23');
INSERT INTO `medicine_bill` VALUES (64, 'Arif hamed', 'arif@email.com', 123123123, 1, '2024-12-05', 1250.00, 0.00, 0.00, 1250.00, '', 472, '2024-12-07 21:18:04');
INSERT INTO `medicine_bill` VALUES (65, 'Emily Davis', 'emily.davis1@example.com', 2147483647, 1, '2024-12-07', 900.00, 0.00, 0.00, 900.00, '', 76, '2024-12-07 21:20:07');
INSERT INTO `medicine_bill` VALUES (66, 'moiz moiz', 'moiz@email.com', 2147483647, 1, '2024-12-08', 4620.00, 45.54, 6.00, 4659.54, '', 471, '2024-12-08 09:23:13');
INSERT INTO `medicine_bill` VALUES (67, 'Ramzan', 'ramzan@gmail.com', 12345678, 1, '2024-12-01', 100.00, 0.00, 0.00, 100.00, '', 473, '2024-12-08 10:46:33');

-- ----------------------------
-- Table structure for medicine_category
-- ----------------------------
DROP TABLE IF EXISTS `medicine_category`;
CREATE TABLE `medicine_category`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `created_date` datetime NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of medicine_category
-- ----------------------------
INSERT INTO `medicine_category` VALUES (1, 'Tablet', '2024-12-04 12:30:58');
INSERT INTO `medicine_category` VALUES (2, 'Syrup', '2024-12-04 12:31:05');
INSERT INTO `medicine_category` VALUES (3, 'Liquid', '2024-12-04 12:31:11');

-- ----------------------------
-- Table structure for medicine_purchase
-- ----------------------------
DROP TABLE IF EXISTS `medicine_purchase`;
CREATE TABLE `medicine_purchase`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `supplier` int NOT NULL,
  `date` date NOT NULL,
  `total` decimal(10, 2) NOT NULL,
  `tax` decimal(10, 2) NOT NULL,
  `discount_value` decimal(10, 2) NOT NULL,
  `amount` decimal(10, 2) NOT NULL,
  `note` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `created_date` datetime NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_supplier`(`supplier` ASC) USING BTREE,
  CONSTRAINT `fk_supplier` FOREIGN KEY (`supplier`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 42 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of medicine_purchase
-- ----------------------------
INSERT INTO `medicine_purchase` VALUES (24, 1, '2024-12-07', 200.00, 3.80, 10.00, 193.80, '', '2024-12-07 01:41:15');
INSERT INTO `medicine_purchase` VALUES (25, 1, '2024-12-07', 15252.00, 591.78, 457.56, 15386.22, '', '2024-12-07 13:51:07');
INSERT INTO `medicine_purchase` VALUES (26, 3, '2024-12-04', 200.00, 0.00, 0.00, 200.00, '', '2024-12-07 20:53:58');
INSERT INTO `medicine_purchase` VALUES (27, 4, '2024-12-04', 2000.00, 0.00, 0.00, 2000.00, '', '2024-12-07 20:55:12');
INSERT INTO `medicine_purchase` VALUES (28, 5, '2024-12-07', 260.00, 0.00, 0.00, 260.00, '', '2024-12-07 20:55:59');
INSERT INTO `medicine_purchase` VALUES (29, 6, '2024-11-30', 300.00, 0.00, 0.00, 300.00, '', '2024-12-07 20:57:00');
INSERT INTO `medicine_purchase` VALUES (30, 6, '2024-12-04', 2000.00, 0.00, 0.00, 2000.00, '', '2024-12-07 20:57:48');
INSERT INTO `medicine_purchase` VALUES (31, 4, '2024-12-05', 5520.00, 0.00, 0.00, 5520.00, '', '2024-12-07 20:58:38');
INSERT INTO `medicine_purchase` VALUES (33, 2, '2024-12-07', 1950.00, 0.00, 0.00, 1950.00, '', '2024-12-07 21:03:42');
INSERT INTO `medicine_purchase` VALUES (34, 3, '2024-11-08', 3015.00, 0.00, 0.00, 3015.00, '', '2024-12-07 21:04:35');
INSERT INTO `medicine_purchase` VALUES (35, 3, '2024-12-03', 500.00, 0.00, 0.00, 500.00, '', '2024-12-07 21:05:28');
INSERT INTO `medicine_purchase` VALUES (36, 3, '2024-12-03', 500.00, 0.00, 0.00, 500.00, '', '2024-12-07 21:05:31');
INSERT INTO `medicine_purchase` VALUES (37, 1, '2024-12-01', 7621.00, 0.00, 0.00, 7621.00, '', '2024-12-07 21:07:32');
INSERT INTO `medicine_purchase` VALUES (38, 6, '2024-11-09', 400.00, 0.00, 0.00, 400.00, '', '2024-12-07 21:08:19');
INSERT INTO `medicine_purchase` VALUES (39, 4, '2024-12-07', 1204.00, 0.00, 0.00, 1204.00, '', '2024-12-07 21:09:01');
INSERT INTO `medicine_purchase` VALUES (40, 4, '2024-12-08', 600.00, 0.00, 0.00, 600.00, '', '2024-12-08 09:26:08');
INSERT INTO `medicine_purchase` VALUES (41, 1, '2024-12-08', 200.00, 3.80, 10.00, 193.80, '', '2024-12-08 10:44:21');

-- ----------------------------
-- Table structure for medicines
-- ----------------------------
DROP TABLE IF EXISTS `medicines`;
CREATE TABLE `medicines`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `company` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `generic` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `medicine_group` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `category` int NOT NULL,
  `storebox` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `minlevel` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `reorderlevel` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `unit` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `unitpacking` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `note` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `created_date` datetime NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_category`(`category` ASC) USING BTREE,
  CONSTRAINT `fk_category` FOREIGN KEY (`category`) REFERENCES `medicine_category` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of medicines
-- ----------------------------
INSERT INTO `medicines` VALUES (1, 'Enterogermina', 'Bayer', 'Resfriado', 'Home', 2, '6000', '2', '10', '10', '5000', 'None', '2024-12-04 12:40:02');
INSERT INTO `medicines` VALUES (2, 'Panadol', 'Glaxo Smith Kline', 'Resfriado 2', 'Home 2', 1, 'Drawer', '2', '10', '10', '300', '', '2024-12-04 23:46:26');
INSERT INTO `medicines` VALUES (3, 'Disprine', 'random', 'Resfriado 2', 'home', 1, '', '10', '', '10', '20', '', '2024-12-07 20:41:26');
INSERT INTO `medicines` VALUES (4, 'flagyl', 'Random', 'Cough', 'none', 1, 'Rag', '10', '', '20', '15', '', '2024-12-07 20:42:29');
INSERT INTO `medicines` VALUES (5, 'sancos', 'abbot', 'random', 'Home', 2, 'Box', '10', '', '10', '23', '', '2024-12-07 20:43:33');
INSERT INTO `medicines` VALUES (6, 'cosome', 'Bosche', 'Random', 'home', 2, '', '2', '1', '10', '10', '', '2024-12-07 20:44:40');
INSERT INTO `medicines` VALUES (7, 'strepsils', 'Abbot', 'Abc', 'abc', 1, 'Box', '10', '5', '30', '20', '', '2024-12-07 20:47:19');
INSERT INTO `medicines` VALUES (8, 'caflam', 'Getz', 'head', 'random', 1, 'Drawer', '4', '5', '20', '15', '', '2024-12-07 20:48:17');
INSERT INTO `medicines` VALUES (10, 'Pandol CF', 'abbot', 'none', 'none', 1, 'box', '2', '5', '30', '10', '', '2024-12-07 20:50:48');
INSERT INTO `medicines` VALUES (11, 'ascad', 'Getz', 'Random', 'Random', 1, 'box', '10', '5', '50', '20', '', '2024-12-07 20:51:52');
INSERT INTO `medicines` VALUES (12, 'Angised', 'GSK', 'Random', 'random', 1, 'Drawer', '6', '3', '10', '20', '', '2024-12-07 20:53:15');
INSERT INTO `medicines` VALUES (13, 'paracetamol', 'glaxo smith kline', 'formula', '', 1, '', '', '', '10', '', '', '2024-12-08 10:38:14');

-- ----------------------------
-- Table structure for menu
-- ----------------------------
DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `link` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `parent` int NOT NULL DEFAULT 0,
  `active` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `priority` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 45 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of menu
-- ----------------------------
INSERT INTO `menu` VALUES (1, 'Dashboard', 'dashboard', 'las la-chalkboard', 0, 'dashboard', 1, 2000);
INSERT INTO `menu` VALUES (2, 'Customers', 'customers', 'las la-user-md', 0, 'customers', 1, 1970);
INSERT INTO `menu` VALUES (11, 'Users', '#', 'las la-users', 0, 'users', 1, 1160);
INSERT INTO `menu` VALUES (12, 'Users List', 'users', '', 11, 'users', 1, 1140);
INSERT INTO `menu` VALUES (13, 'User Role', 'role', '', 11, 'users', 1, 1120);
INSERT INTO `menu` VALUES (17, 'Setting', '#', 'las la-cog', 0, 'settings', 1, 1020);
INSERT INTO `menu` VALUES (18, 'System Info', 'info', '', 17, 'settings', 1, 1000);
INSERT INTO `menu` VALUES (25, 'Payment Methods', 'paymentmethod', '', 17, 'settings', 1, 940);
INSERT INTO `menu` VALUES (35, 'Medicines', 'medicines', 'las la-pills', 0, 'medicine', 1, 1705);
INSERT INTO `menu` VALUES (36, 'POS/Bill', 'medicine/billing', 'las la-cart-plus', 0, 'billing', 1, 1715);
INSERT INTO `menu` VALUES (37, 'Purchase', 'medicine/purchase', 'las la-file-invoice-dollar', 0, 'purchase', 1, 1710);
INSERT INTO `menu` VALUES (38, 'Suppliers', 'suppliers', '', 17, 'settings', 1, 909);
INSERT INTO `menu` VALUES (39, 'Stock adjustment', 'medicine/stock', 'las la-balance-scale', 0, 'stockadjustment', 1, 1709);
INSERT INTO `menu` VALUES (40, 'Medicine Category', 'medicine/category', '', 17, 'Setting', 1, 970);
INSERT INTO `menu` VALUES (44, 'Report', 'reports', 'las la-chalkboard-teacher', 0, 'reports', 1, 1350);

-- ----------------------------
-- Table structure for payment_method
-- ----------------------------
DROP TABLE IF EXISTS `payment_method`;
CREATE TABLE `payment_method`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_date` datetime NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of payment_method
-- ----------------------------
INSERT INTO `payment_method` VALUES (1, 'Cash Payment', 1, '2024-12-04 12:45:30');
INSERT INTO `payment_method` VALUES (2, 'Card Payment', 1, '2024-12-04 12:45:37');

-- ----------------------------
-- Table structure for setting
-- ----------------------------
DROP TABLE IF EXISTS `setting`;
CREATE TABLE `setting`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `data` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of setting
-- ----------------------------
INSERT INTO `setting` VALUES (1, 'siteinfo', '{\"logo\":\"media-399817862674f3a931cfd8.png\",\"logo_icon\":\"\",\"lg_background\":\"\",\"favicon\":\"\",\"name\":\"Dvago\",\"legal_name\":\"Dvago\",\"mail\":\"dvago@gmail.com\",\"phone\":\"03222691789\",\"address\":{\"address1\":\"Address Line 11\",\"address2\":\"Address Line 2\",\"city\":\"City\",\"country\":\"Country\",\"postal\":\"012345\"}}');

-- ----------------------------
-- Table structure for suppliers
-- ----------------------------
DROP TABLE IF EXISTS `suppliers`;
CREATE TABLE `suppliers`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `phone` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `address` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `created_date` datetime NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of suppliers
-- ----------------------------
INSERT INTO `suppliers` VALUES (1, 'Galaxy Pharma', 'galaxypharma@gmail.com', '090078601', 'Karachi', '2024-12-04 12:47:13');
INSERT INTO `suppliers` VALUES (2, 'supp', 'supp@gmail.com', '109238908210', '', '2024-12-05 00:06:10');
INSERT INTO `suppliers` VALUES (3, 'Abbot agent', 'abbot_agent@email.com', '123123123', '', '2024-12-07 20:38:32');
INSERT INTO `suppliers` VALUES (4, 'Time medicons', 'time@email.com', '123123123', '', '2024-12-07 20:38:50');
INSERT INTO `suppliers` VALUES (5, 'Bismillah medical agent', 'bma@email.com', '12312312312', 'Karachi', '2024-12-07 20:39:31');
INSERT INTO `suppliers` VALUES (6, 'Dvago agent', 'dva_agent@email.com', '12312312', '', '2024-12-07 20:40:10');

-- ----------------------------
-- Table structure for user_role
-- ----------------------------
DROP TABLE IF EXISTS `user_role`;
CREATE TABLE `user_role`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `permission` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of user_role
-- ----------------------------
INSERT INTO `user_role` VALUES (1, 'Admin', 'You can not change Admin role setting', '[\"dashboard\",\"customers\",\"customer\\/add\",\"customer\\/edit\",\"customer\\/delete\",\"customer\\/view\",\"medicine\\/billing\",\"medicine\\/billing\\/add\",\"medicine\\/billing\\/edit\",\"medicine\\/billing\\/delete\",\"medicine\\/billing\\/view\",\"medicine\\/purchase\",\"medicine\\/purchase\\/add\",\"medicine\\/purchase\\/edit\",\"medicine\\/purchase\\/delete\",\"medicine\\/purchase\\/view\",\"medicine\\/stock\",\"medicine\\/stock\\/delete\",\"medicines\",\"medicine\\/add\",\"medicine\\/edit\",\"medicine\\/delete\",\"medicine\\/view\",\"medicine\\/category\",\"medicine\\/category\\/add\",\"medicine\\/category\\/edit\",\"medicine\\/category\\/delete\",\"reportss\",\"users\",\"user\\/add\",\"user\\/edit\",\"user\\/delete\",\"info\",\"tax\",\"tax\\/add\",\"tax\\/edit\",\"tax\\/delete\",\"paymentmethod\",\"paymentmethod\\/add\",\"paymentmethod\\/edit\",\"paymentmethod\\/delete\",\"get\\/media\",\"media\\/upload\",\"media\\/delete\"]', '2018-01-10 23:15:47');
INSERT INTO `user_role` VALUES (2, 'Store Manager', 'Store Manager', '[\"dashboard\",\"customers\",\"customer\\/add\",\"customer\\/edit\",\"customer\\/delete\",\"customer\\/view\",\"medicine\\/billing\",\"medicine\\/billing\\/add\",\"medicine\\/billing\\/edit\",\"medicine\\/billing\\/delete\",\"medicine\\/billing\\/view\",\"medicine\\/purchase\",\"medicine\\/purchase\\/add\",\"medicine\\/purchase\\/edit\",\"medicine\\/purchase\\/delete\",\"medicine\\/purchase\\/view\",\"medicine\\/stock\",\"medicine\\/stock\\/delete\",\"medicines\",\"medicine\\/add\",\"medicine\\/edit\",\"medicine\\/delete\",\"medicine\\/view\",\"medicine\\/category\",\"medicine\\/category\\/add\",\"medicine\\/category\\/edit\",\"medicine\\/category\\/delete\",\"tax\",\"tax\\/add\",\"tax\\/edit\",\"tax\\/delete\",\"paymentmethod\",\"paymentmethod\\/add\",\"paymentmethod\\/edit\",\"paymentmethod\\/delete\",\"get\\/media\",\"media\\/upload\",\"media\\/delete\"]', '2018-01-10 23:37:46');
INSERT INTO `user_role` VALUES (6, 'Employee', 'Employee', '[\"dashboard\",\"customers\",\"customer\\/add\",\"customer\\/edit\",\"customer\\/delete\",\"customer\\/view\",\"medicine\\/billing\",\"medicine\\/billing\\/add\",\"medicine\\/billing\\/edit\",\"medicine\\/billing\\/delete\",\"medicine\\/billing\\/view\"]', '2019-10-24 05:31:44');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `user_role` int NULL DEFAULT NULL,
  `user_name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `firstname` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `lastname` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `mobile` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `gender` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `temp_hash` varchar(225) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `emailconfirmed` bit(1) NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_date` datetime NOT NULL,
  PRIMARY KEY (`user_id`) USING BTREE,
  UNIQUE INDEX `email`(`email` ASC) USING BTREE,
  UNIQUE INDEX `user_name`(`user_name` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 1, 'nomanaadma', 'Noman', 'Shoukat', 'nomanaadma@gmail.com', '111111111', '', '$2y$10$KqEqgeHDc4w1q3lqi5bBme2J.S7O0SukPZThHRn26Qr4EtVlJPuTi', '', b'1', 1, '2024-12-03 13:36:21');
INSERT INTO `users` VALUES (6, 6, 'aminshoukat4', 'Amin', 'Shoukat', 'aminshoukat4@gmail.com', '03222819054', 'Male', '$2y$10$.xKA9QUPTuv5BMtWPOfHX.LD0YsZUe4Yttq4OYUsEyHW1LbhPlvoC', '', b'1', 1, '2024-12-04 20:01:33');
INSERT INTO `users` VALUES (7, 2, 'areeb120', 'areeb', 'shakeel', 'areebshakeel120@gmail.com', '03008911760', 'Male', '$2y$10$Iz0P9LccUa6IHxPfzy.YqO7NkbVa0c9Wyv1PtiouiNXm1BVSF.yLW', '75acc3e83f6bef250ab1a0ebb62ec071', NULL, 1, '2024-12-07 21:18:52');
INSERT INTO `users` VALUES (8, 6, 'afnan', 'Syed ', 'Afnan', 'afnan@gmail.com', '03222819054123', 'Male', '$2y$10$5hLf27E9wPomEoyFYP4MdeS9HrtjjPB3dEIMiObAP5/m.wrKxmrlK', 'd64ef3a9b6143b13dc3b7b5ae0dffa45', NULL, 1, '2024-12-08 10:53:36');

-- ----------------------------
-- View structure for bill_items_view
-- ----------------------------
DROP VIEW IF EXISTS `bill_items_view`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `bill_items_view` AS select `bill_items`.`medicine_id` AS `medicine_id`,`bill_items`.`batch` AS `batch`,sum(`bill_items`.`qty`) AS `qty`,sum(`bill_items`.`price`) AS `price` from `bill_items` group by `bill_items`.`medicine_id`,`bill_items`.`batch`;

-- ----------------------------
-- View structure for medicine_batch_view
-- ----------------------------
DROP VIEW IF EXISTS `medicine_batch_view`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `medicine_batch_view` AS select `medicines`.`name` AS `name`,`medicine_batch`.`id` AS `id`,`medicine_batch`.`batch` AS `batch`,`medicine_batch`.`expiry` AS `expiry`,`medicine_batch`.`pqty` AS `pqty`,`medicine_batch`.`qty` AS `qty`,`medicine_batch`.`saleprice` AS `saleprice`,`medicine_batch`.`purchaseprice` AS `purchaseprice`,`medicine_batch`.`gross` AS `gross`,`medicine_batch`.`discounttype` AS `discounttype`,`medicine_batch`.`discount` AS `discount`,`medicine_batch`.`discountvalue` AS `discountvalue`,`medicine_batch`.`tax` AS `tax`,`medicine_batch`.`taxprice` AS `taxprice`,`medicine_batch`.`price` AS `price`,round((`medicine_batch`.`price` / nullif(`medicine_batch`.`qty`,0)),2) AS `unit_purchase_price`,`medicine_batch`.`sold` AS `sold`,`medicine_batch`.`medicine_id` AS `medicine_id`,`medicine_batch`.`purchase_id` AS `purchase_id`,`medicine_batch`.`status` AS `status`,`medicine_batch`.`created_date` AS `created_date`,round((`medicine_bill`.`price` / nullif(`medicine_bill`.`qty`,0)),2) AS `unit_sale_price`,round(((`medicine_batch`.`price` / nullif(`medicine_batch`.`qty`,0)) * `medicine_batch`.`sold`),2) AS `total_purchase`,round((round((`medicine_bill`.`price` / nullif(`medicine_bill`.`qty`,0)),2) * `medicine_batch`.`sold`),2) AS `total_sales`,round((round((round((`medicine_bill`.`price` / nullif(`medicine_bill`.`qty`,0)),2) * `medicine_batch`.`sold`),2) - round(((`medicine_batch`.`price` / nullif(`medicine_batch`.`qty`,0)) * `medicine_batch`.`sold`),2)),2) AS `profit` from ((`medicine_batch` join `medicines` on((`medicine_batch`.`medicine_id` = `medicines`.`id`))) left join `bill_items_view` `medicine_bill` on(((`medicine_batch`.`medicine_id` = `medicine_bill`.`medicine_id`) and (`medicine_batch`.`id` = `medicine_bill`.`batch`))));

SET FOREIGN_KEY_CHECKS = 1;
