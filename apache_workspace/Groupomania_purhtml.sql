/*
SQLyog Community v13.1.7 (64 bit)
MySQL - 8.0.23 : Database - groupomania
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`groupomania` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `groupomania`;

/*Table structure for table `comments` */

DROP TABLE IF EXISTS `comments`;

CREATE TABLE `comments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `User_id` int DEFAULT NULL,
  `CreationDate` datetime DEFAULT CURRENT_TIMESTAMP,
  `ImgUrl` longtext,
  `Text` varchar(250) DEFAULT NULL,
  `Suppression` datetime DEFAULT NULL,
  `ReplyTo_id` int DEFAULT NULL,
  `checkedByAdmin` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Commentaire_User_idx` (`User_id`),
  KEY `fk_Commentaire_Commentaire1_idx` (`ReplyTo_id`),
  CONSTRAINT `fk_Commentaire_Commentaire1` FOREIGN KEY (`ReplyTo_id`) REFERENCES `comments` (`id`),
  CONSTRAINT `fk_Commentaire_User` FOREIGN KEY (`User_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=142 DEFAULT CHARSET=utf8;

/*Data for the table `comments` */

insert  into `comments`(`id`,`User_id`,`CreationDate`,`ImgUrl`,`Text`,`Suppression`,`ReplyTo_id`,`checkedByAdmin`) values 
(1,1,'2021-03-24 10:10:42',NULL,'Ceci est mon commentaire',NULL,NULL,1),
(22,34,'2021-03-26 13:33:00',NULL,'aaa',NULL,NULL,1),
(57,34,'2021-03-29 15:45:55',NULL,'dezd',NULL,NULL,1),
(76,50,'2021-04-06 17:09:38','6070221e7734d0.21530122.jpg','C\'est mon chat, son ptit nom c\'est Mistigri',NULL,NULL,1),
(77,34,'2021-04-06 17:34:20','606c7f79186a46.41691468.jpg','Super piment',NULL,NULL,1),
(78,50,'2021-04-09 11:07:54',NULL,'aaa','2021-04-09 12:06:30',NULL,1),
(79,44,'2021-04-15 14:33:57',NULL,'Trop bien ta vie',NULL,76,1),
(80,44,'2021-04-12 13:22:14',NULL,'Je blague, il est super mignon',NULL,76,1),
(81,50,'2021-04-12 14:23:01',NULL,'azertyui',NULL,77,1),
(82,50,'2021-04-12 15:32:10',NULL,'Une bonne sauce',NULL,77,1),
(83,50,'2021-04-14 11:39:06',NULL,'blabla\r\n',NULL,57,1),
(84,51,'2021-04-15 12:35:22',NULL,'Blablabla',NULL,NULL,1),
(85,52,'2021-04-15 15:13:18','60783bfd0e15b6.14538922.jpg','Salut, je mets un super commentaire !','2021-04-15 15:17:06',NULL,1),
(86,52,'2021-04-15 15:18:05',NULL,'génial !','2021-04-15 15:18:22',77,1),
(87,51,'2021-04-15 16:01:36','608684e64ddec1.46586158.jpg','',NULL,NULL,1),
(88,51,'2021-04-16 10:29:44',NULL,'bla',NULL,77,1),
(89,51,'2021-04-16 10:29:57',NULL,'blabla',NULL,77,1),
(90,51,'2021-04-16 10:30:05',NULL,'blo',NULL,77,1),
(91,51,'2021-04-16 10:30:11',NULL,'un dernier',NULL,77,1),
(92,51,'2021-04-16 10:40:24',NULL,'encore un',NULL,77,1),
(93,51,'2021-04-16 16:16:40',NULL,'bla',NULL,77,1),
(96,51,'2021-04-21 09:49:59',NULL,NULL,NULL,84,1),
(97,51,'2021-04-21 09:50:06',NULL,'Super!',NULL,87,1),
(98,51,'2021-04-21 11:10:49',NULL,'Super!',NULL,91,1),
(99,51,'2021-04-21 17:44:59','608124288df8f1.06516058.png',NULL,'2021-04-22 09:23:07',NULL,1),
(100,51,'2021-04-22 09:23:12','6081246b127352.11993603.png','Super!','2021-04-22 11:58:50',NULL,1),
(101,51,'2021-04-22 11:59:01','608148e5c05d45.43040245.png','Super!','2021-04-22 14:34:39',NULL,1),
(102,51,'2021-04-22 14:34:49','608182bc27a0c0.43648571.png','Super!!! ça fonctionne !!!','2021-04-22 17:03:59',NULL,1),
(103,51,'2021-04-22 16:06:46','6081852e157c51.99031182.jpg','Super!!!!!!','2021-04-22 16:21:06',NULL,1),
(104,51,'2021-04-22 17:04:06','60819066c33645.36623063.png','Quand c\'est ton premier jour de vacances et qu\'il pleut toute la semaine... Super!',NULL,NULL,1),
(105,51,'2021-04-23 15:52:57','6082d139800002.11867456.jpg','','2021-04-23 16:09:52',NULL,1),
(106,51,'2021-04-23 16:03:29','6082d3b1369bf3.34239735.jpg','',NULL,NULL,1),
(107,51,'2021-04-23 16:10:01','6082d5399ae453.00469109.jpg','Super !!!','2021-04-23 16:58:15',NULL,1),
(108,51,'2021-04-23 16:58:26','608a6b660fb851.55242385.jpg','',NULL,NULL,NULL),
(109,56,'2021-04-29 09:27:53',NULL,'Super!',NULL,141,NULL),
(110,51,'2021-04-29 10:28:59',NULL,'Super!',NULL,141,NULL),
(139,56,'2021-04-30 11:44:39','http://localhost:8080/images/2CV-Special-jaune-cedrat-2.jpg1619783076025.jpg','',NULL,NULL,NULL),
(140,56,'2021-04-30 11:48:01','http://localhost:8080/images/Cap-Vert-1.jpg1619783278907.jpg','Super!',NULL,NULL,NULL),
(141,56,'2021-04-30 11:48:46','http://localhost:8080/images/Cap-Vert-1.jpg1619783324714.jpg','',NULL,NULL,NULL);

/*Table structure for table `like_number` */

DROP TABLE IF EXISTS `like_number`;

CREATE TABLE `like_number` (
  `ComId` int NOT NULL,
  `UserId` int NOT NULL,
  PRIMARY KEY (`ComId`,`UserId`),
  KEY `UserId` (`UserId`),
  CONSTRAINT `like_number_ibfk_1` FOREIGN KEY (`ComId`) REFERENCES `comments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `like_number_ibfk_2` FOREIGN KEY (`UserId`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `like_number` */

insert  into `like_number`(`ComId`,`UserId`) values 
(77,1),
(57,50),
(77,50),
(82,52),
(86,52);

/*Table structure for table `parameters` */

DROP TABLE IF EXISTS `parameters`;

CREATE TABLE `parameters` (
  `param_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `param_value` json DEFAULT NULL,
  PRIMARY KEY (`param_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `parameters` */

insert  into `parameters`(`param_name`,`param_value`) values 
('security','{\"JWT_SECRET_TOKEN\": \"toto\"}');

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(45) DEFAULT NULL,
  `FirstName` varchar(45) DEFAULT NULL,
  `Service` varchar(45) DEFAULT NULL,
  `Mail` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `PassWord` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `Moderator` tinyint DEFAULT '0',
  `ModerationDate` datetime DEFAULT NULL,
  `CreationDate` datetime DEFAULT CURRENT_TIMESTAMP,
  `Suppression` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8;

/*Data for the table `user` */

insert  into `user`(`id`,`Name`,`FirstName`,`Service`,`Mail`,`PassWord`,`Moderator`,`ModerationDate`,`CreationDate`,`Suppression`) values 
(1,'Toto','To','Commercial','toto@gmail.com','ABCDEF',0,NULL,NULL,NULL),
(2,'a','a','a','a','a',0,NULL,'2021-04-14 17:47:33','2021-04-15 15:59:53'),
(34,'aaa','Jean','Comptabilité','aaa@aaa','$2b$10$EBUsURwRalcszMTdxsR0FugxMOWeKi9qMBnEf7G69qmAI5sP/vcPu',0,NULL,'2021-03-23 10:13:22',NULL),
(43,'Charles','Julien','Comptabilité','j.charles031290@gmail.com','a',0,NULL,'2021-04-01 15:19:02',NULL),
(44,'aaa','Pierre','Administration','tata@yoyo.com','a',0,NULL,'2021-04-01 15:22:23',NULL),
(50,'aaa','Paul','Maintenance','a@a','$2y$10$DnkoihcUdm.hOb9Y2Rw2Je4LWAlvbc9eLpXTtvblSp0V8O7M6Qfc.',0,NULL,'2021-04-01 16:12:24',NULL),
(51,'Charles','Julien','Stagiaire','toto@tata.com','$2y$10$lUbMbjuen86dq7fxGLQbnOGkt0mF25WlWjW5zy0gsE/Jpb17JgdVW',1,'2021-04-23 16:12:34','2021-04-14 14:18:28',NULL),
(52,'GIBAND','Julien','Direction','julien.giband@ahpc-services.com','$2y$10$X8mz.dH4YDH3EuBYicXtFuMTADtOkjISUe8j/UAUeBDuhFuW5MkhG',0,NULL,'2021-04-15 15:12:56','2021-04-20 14:34:44'),
(55,'bbb','bbb','Comptabilité','b@b','$2y$10$GqhPAguPiqSmk5r1jlZmfe7ldZk.826tPSkqewAQ4xDdZ2H2GGwmm',0,NULL,'2021-04-15 15:51:18','2021-04-15 15:56:57'),
(56,'Charles','Julien','Stagiaire','toto@toto.com','$2b$10$4Sqfor38FGNoF7gTcK2lNePwURJk8yUl/b7xdpd2QGxFRsCQYgvVS',0,NULL,'2021-04-27 07:33:36',NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
