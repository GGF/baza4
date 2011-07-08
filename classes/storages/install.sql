-- phpMyAdmin SQL Dump
-- version 3.4.2
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Июл 07 2011 г., 14:45
-- Версия сервера: 5.1.56
-- Версия PHP: 5.3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `zaomppsklads`
--
CREATE DATABASE `zaomppsklads1` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `zaomppsklads1`;


-- --------------------------------------------------------

--
-- Структура таблицы `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `table` varchar(100) NOT NULL COMMENT 'Таблица к которой относится запись',
  `rec_id` bigint(20) NOT NULL COMMENT 'Идентификатор записи к которой относятся файлы',
  `fileid` bigint(20) NOT NULL COMMENT 'Идентификатор файловой ссылки из таблицы filelinks',
  `type` varchar(50) NOT NULL DEFAULT 'http' COMMENT 'тип ссылки, в принципе только вэб или шара',
  PRIMARY KEY (`id`),
  KEY `fileid` (`fileid`),
  KEY `table` (`table`),
  KEY `rec_id` (`rec_id`),
  KEY `type` (`type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Для прикрепления множества файлов к одному документу' AUTO_INCREMENT=7 ;

--
-- Структура таблицы `filelinks`
--

CREATE TABLE IF NOT EXISTS `filelinks` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `file_link` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Ссылки на файлы' AUTO_INCREMENT=7947 ;

--
-- Структура таблицы `coments`
--

CREATE TABLE IF NOT EXISTS `coments` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `comment` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Свалка коментариев к различным документам' AUTO_INCREMENT=2785 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

