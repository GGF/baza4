-- phpMyAdmin SQL Dump
-- version 3.4.2
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Июл 08 2011 г., 09:24
-- Версия сервера: 5.1.56
-- Версия PHP: 5.3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `zaompp`
--

-- --------------------------------------------------------

--
-- Структура таблицы `conductors`
--

CREATE TABLE IF NOT EXISTS `conductors` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `board_id` bigint(11) NOT NULL DEFAULT '0',
  `plate_id` bigint(20) NOT NULL,
  `side` enum('TOP','BOT','TOPBOT') NOT NULL DEFAULT 'TOP',
  `lays` enum('3','5') NOT NULL DEFAULT '3',
  `sizex` int(11) NOT NULL DEFAULT '0',
  `sizey` int(11) NOT NULL DEFAULT '0',
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_id` bigint(11) NOT NULL DEFAULT '0',
  `dir` varchar(255) NOT NULL DEFAULT '',
  `dirk` varchar(255) NOT NULL DEFAULT '',
  `pib` int(11) NOT NULL DEFAULT '1',
  `comment_id` bigint(20) NOT NULL DEFAULT '0',
  `ready` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=115 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
