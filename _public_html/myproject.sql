/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 80030
Source Host           : localhost:3306
Source Database       : myproject

Target Server Type    : MYSQL
Target Server Version : 80030
File Encoding         : 65001

Date: 2025-12-03 20:22:42
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `admin`
-- ----------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `name` varchar(255) NOT NULL,
  `profile` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of admin
-- ----------------------------
INSERT INTO `admin` VALUES ('9', 'audreilouisrapista5@gmail.com', '$2y$12$1loMcqLNXJiDWOlWfufy3umr.5gYfhcoYSutYj1MTVrqKzn0hh0FW', 'admin', 'Audrei Louis Rapista', 'images/PSLOcAKcYGWLfJm6crKLT0tklc8IID4Ry4oq2g6j.png');

-- ----------------------------
-- Table structure for `grade_level`
-- ----------------------------
DROP TABLE IF EXISTS `grade_level`;
CREATE TABLE `grade_level` (
  `grade_id` int NOT NULL AUTO_INCREMENT,
  `grade_title` varchar(100) NOT NULL,
  PRIMARY KEY (`grade_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of grade_level
-- ----------------------------
INSERT INTO `grade_level` VALUES ('7', 'Grade 7');
INSERT INTO `grade_level` VALUES ('8', 'Grade 8');
INSERT INTO `grade_level` VALUES ('9', 'Grade 9');
INSERT INTO `grade_level` VALUES ('10', 'Grade 10');
INSERT INTO `grade_level` VALUES ('11', 'Grade 11');
INSERT INTO `grade_level` VALUES ('12', 'Grade 12');

-- ----------------------------
-- Table structure for `schedules`
-- ----------------------------
DROP TABLE IF EXISTS `schedules`;
CREATE TABLE `schedules` (
  `schedule_id` int NOT NULL AUTO_INCREMENT,
  `subject_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `teachers_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `sub_date` varchar(100) NOT NULL,
  `sub_Stime` time NOT NULL,
  `sub_Etime` time NOT NULL,
  `sched_status` varchar(100) NOT NULL,
  `grade_id` varchar(100) NOT NULL,
  `sched_year` varchar(100) NOT NULL,
  PRIMARY KEY (`schedule_id`)
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of schedules
-- ----------------------------
INSERT INTO `schedules` VALUES ('72', '14', '71', 'Wednesday', '00:19:00', '00:20:00', '1', '2', '2025-2026');
INSERT INTO `schedules` VALUES ('73', '16', '70', 'Tuesday', '13:19:00', '13:19:00', '1', '2', '2025-2026');
INSERT INTO `schedules` VALUES ('74', '14', '71', 'Monday-Tuesday', '09:40:00', '09:40:00', '1', '1', '2025-2026');
INSERT INTO `schedules` VALUES ('75', '16', '71', 'Monday-Tuesday', '10:22:00', '10:22:00', '1', '2', '2025-2026');
INSERT INTO `schedules` VALUES ('76', '15', '71', 'Tuesday-Wednesday-Thursday', '10:05:00', '10:05:00', '1', '2', '2025-2026');

-- ----------------------------
-- Table structure for `status`
-- ----------------------------
DROP TABLE IF EXISTS `status`;
CREATE TABLE `status` (
  `status_id` int NOT NULL AUTO_INCREMENT,
  `status_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of status
-- ----------------------------
INSERT INTO `status` VALUES ('0', 'Unassigned ');
INSERT INTO `status` VALUES ('1', 'Assigned ');

-- ----------------------------
-- Table structure for `subject`
-- ----------------------------
DROP TABLE IF EXISTS `subject`;
CREATE TABLE `subject` (
  `subject_id` int NOT NULL AUTO_INCREMENT,
  `subject_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `subject_status` varchar(100) NOT NULL,
  `grade_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`subject_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of subject
-- ----------------------------
INSERT INTO `subject` VALUES ('14', 'Filipino-1', 'unassigned', '1');
INSERT INTO `subject` VALUES ('15', 'Programming-2', 'unassigned', '2');
INSERT INTO `subject` VALUES ('16', 'Math-1', 'unassigned', '2');

-- ----------------------------
-- Table structure for `teacher`
-- ----------------------------
DROP TABLE IF EXISTS `teacher`;
CREATE TABLE `teacher` (
  `teachers_id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `phone` varchar(100) NOT NULL,
  `t_status` varchar(100) NOT NULL,
  `gender` varchar(100) NOT NULL,
  `profile` varchar(255) NOT NULL,
  `role` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `age` int NOT NULL,
  `teacher_major` varchar(100) NOT NULL,
  PRIMARY KEY (`teachers_id`)
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of teacher
-- ----------------------------
INSERT INTO `teacher` VALUES ('70', 'superman@gmail.com', '$2y$12$IgwPGM19tUo7YMBnjAcyeeXNwlLilEwbQHh3hqwzH5dGYUB0JIq9a', 'superman', '09709809029', '1', 'gender', 'images/6Jf5mrK2w29wjz7pudfyKUq8c4SgTq1mzrWaQBLY.jpg', 'Teacher', '21', '');
INSERT INTO `teacher` VALUES ('71', 'kathy11@gmail.com', '$2y$12$HpjQmDO2EfSjc0xiK6XUFeHkWxUvyL2J.1jJElDwKs4OyfWEbbvnC', 'Kathy Angle', '09709809029', '1', 'gender', 'images/pZRSrZpvOS5fSREPcj5hZdF2MRoV39TGjGbit7lp.jpg', 'Teacher', '25', '');
INSERT INTO `teacher` VALUES ('72', 'ethan@gmail.com', '$2y$12$qzEqy5A7PIYJG1v3S4sBweQGY18r3uGbLQRR1dcL9FTWlx0hlm.pC', 'dasd', '09709809029', '0', 'female', 'images/TJFxdAGxcsCQYBE1GPcyl47oUopH39SWxCxW9j3Z.jpg', 'Teacher', '21', '');
INSERT INTO `teacher` VALUES ('73', 'ethan25@gmail.com', '$2y$12$ufCTIRR1JHEiLtNnyeXml.sduQ8tZlKczcgGkKaMPA8PHPsvDWus.', 'asda', '09709809029', '0', 'gender', 'images/2wb0O2XCzzQPUE2vKmvpAdFppahRjSpsC5QHBuPr.jpg', 'Teacher', '21', '');
INSERT INTO `teacher` VALUES ('74', 'angle@gmail.com', '$2y$12$qQylnlJKYa7/g5l2q7IcTO7IDhoi5R1EVHHILPg/nIr4xrX1.rYWy', 'Kathy', '09709809029', '0', 'gender', 'images/XdrKXT3K7d8eNCsPeIDWkaV46WXIwtOJnE15Fef6.jpg', 'Teacher', '21', '');
INSERT INTO `teacher` VALUES ('75', 'asad@gmail.com', '$2y$12$fR4BOTjm66vFZbvJko2CqOZW/fjwyyZmJobiunJWEeQOo97n4vXv.', 'adsadad', '09709809029', '0', 'gender', 'images/SLlLnEl3FKazGxYWF90qDftBgWuARNAExZIlvPXb.png', 'Teacher', '21', '');
INSERT INTO `teacher` VALUES ('76', 'asad@gmail.com', '$2y$12$ju2oMz43wBVpRrglx1jVqeGKkE50cZICZFP6NK1/QRrzO/jFP6Tvu', 'adsadad', '09709809029', '0', 'gender', 'images/P0d7DuKAwaRGRWd6uyodca5u6uqL6o9q7m48sFo2.png', 'Teacher', '21', '');
INSERT INTO `teacher` VALUES ('77', 'dsadas@gmial.com', '$2y$12$pFQGLOz/1.Ye5AB1S9QDD.jxTCSzqtZU.T4WqyxmpAV1uGMAE/XtW', 'asdadad', '09709809029', '0', 'gender', 'images/PO6yib9xIA4fj5Sjim74R7XdZGGH8xtUmzbEajcc.jpg', 'Teacher', '21', '');
INSERT INTO `teacher` VALUES ('78', 'akosiikaw@gmail.com', '$2y$12$Rw7kX6Dh85Uo7FMuFrtDne3WiHm5nk.aolOKJ4ADrxYoVapBuZI1i', 'ako knini', '09709809029', '0', 'male', 'images/FayQGbvnlGmT6UdBvJDCWSbLsDlDHdnaLSLG06pC.jpg', 'Teacher', '21', '');
INSERT INTO `teacher` VALUES ('79', 'rolandojr@gmail.com', '$2y$12$z3WXKO.pMxN1ZYS956c2iePDuFK3L8202HHIbGAObKjkUEp4zDNBm', 'angle kath', '09709809029', '0', 'male', 'images/6RaUy56oinAqZJV4od4lioQBSauth7PtxBbybnkf.jpg', 'Teacher', '21', '');
INSERT INTO `teacher` VALUES ('81', 'messi@gmail.com', '$2y$12$jvavVztGKLRQm3eHyMqUZO5mSRa45aMuT02qtxBeOKJuhp791HTua', 'Messi KO', '09709809029', '0', 'male', 'images/VwQgPvPOFUw0kmu8tfZDhd4WapQA4zd8cAf9G4OO.jpg', 'Teacher', '25', 'PE');
