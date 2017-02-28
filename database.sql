-- Adminer 4.2.5 MySQL dump

SET NAMES utf8;
SET time_zone = '+01:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `Users`; 
CREATE TABLE `Users` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id_user`)
);

DROP TABLE IF EXISTS `Printers`; 
CREATE TABLE `Printers` (
  `id_printer` int(11) NOT NULL AUTO_INCREMENT,
  `producer` varchar(255) NOT NULL,
  `model` varchar(255) NOT NULL,
  `type` varchar(50),
  `scanner_res` decimal,
  `printer_res` decimal,
  PRIMARY KEY (`id_printer`)
);

DROP TABLE IF EXISTS `Test_set`; 
CREATE TABLE `Test_set` (
  `id_set` int(11) NOT NULL AUTO_INCREMENT,
  `id_printer` int(11) NOT NULL,
  `id_user` int(11),
  PRIMARY KEY (`id_set`)
);

DROP TABLE IF EXISTS `Test`; 
CREATE TABLE `Test` (
  `id_test` int(11) NOT NULL AUTO_INCREMENT,
  `id_set` int(11) NOT NULL,
  `date_time` timestamp  NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `result` 
  PRIMARY KEY (`id_printer`)
);










