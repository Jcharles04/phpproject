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
CREATE DATABASE /*!32312 IF NOT EXISTS*/`groupomania` /*!40100 DEFAULT CHARACTER SET utf8 */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `groupomania`;

/*Table structure for table `commentaire` */

DROP TABLE IF EXISTS `commentaire`;

CREATE TABLE `commentaire` (
  `id` int NOT NULL AUTO_INCREMENT,
  `User_id` int DEFAULT NULL,
  `DateCreation` datetime DEFAULT CURRENT_TIMESTAMP,
  `ImgUrl` longtext,
  `Text` varchar(250) DEFAULT NULL,
  `Like` int DEFAULT NULL,
  `UserLike` json DEFAULT NULL,
  `Suppression` datetime DEFAULT NULL,
  `ReplyTo_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Commentaire_User_idx` (`User_id`),
  KEY `fk_Commentaire_Commentaire1_idx` (`ReplyTo_id`),
  CONSTRAINT `fk_Commentaire_Commentaire1` FOREIGN KEY (`ReplyTo_id`) REFERENCES `commentaire` (`id`),
  CONSTRAINT `fk_Commentaire_User` FOREIGN KEY (`User_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8;

/*Data for the table `commentaire` */

insert  into `commentaire`(`id`,`User_id`,`DateCreation`,`ImgUrl`,`Text`,`Like`,`UserLike`,`Suppression`,`ReplyTo_id`) values 
(1,1,'2021-03-24 10:10:42',NULL,'Ceci est mon text',NULL,NULL,NULL,NULL),
(22,34,'2021-03-26 13:33:00',NULL,'aaa',NULL,NULL,NULL,NULL),
(57,34,'2021-03-29 15:45:55',NULL,'dezd',NULL,NULL,NULL,NULL),
(76,50,'2021-04-06 17:09:38','606c79a00e6d27.13904415.jpg','',NULL,NULL,NULL,NULL),
(77,50,'2021-04-06 17:34:20','606c7f79186a46.41691468.jpg','',NULL,NULL,NULL,NULL);

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(45) DEFAULT NULL,
  `FirstName` varchar(45) DEFAULT NULL,
  `Service` varchar(45) DEFAULT NULL,
  `Email` varchar(250) DEFAULT NULL,
  `MotDePasse` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `Moderateur` tinyint DEFAULT '0',
  `DateCreation` datetime DEFAULT CURRENT_TIMESTAMP,
  `Suppression` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8;

/*Data for the table `user` */

insert  into `user`(`id`,`Name`,`FirstName`,`Service`,`Email`,`MotDePasse`,`Moderateur`,`DateCreation`,`Suppression`) values 
(1,'Toto','To','','toto@gmail.com','ABCDEF',0,NULL,NULL),
(34,'aaa','aaa','aaa','aaa@aaa','$2b$10$EBUsURwRalcszMTdxsR0FugxMOWeKi9qMBnEf7G69qmAI5sP/vcPu',0,'2021-03-23 10:13:22',NULL),
(43,'Charles','Julien','Compta','j.charles031290@gmail.com','a',0,'2021-04-01 15:19:02',NULL),
(44,'aaa','aaa','aaa','tata@yoyo.com','a',0,'2021-04-01 15:22:23',NULL),
(50,'aaa','aaa','aaa','a@a','$2y$10$DnkoihcUdm.hOb9Y2Rw2Je4LWAlvbc9eLpXTtvblSp0V8O7M6Qfc.',0,'2021-04-01 16:12:24',NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
