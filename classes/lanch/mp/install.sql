-- phpMyAdmin SQL Dump
-- version 3.4.2
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Июл 07 2011 г., 14:25
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
-- Структура таблицы `masterplate`
--

DROP TABLE IF EXISTS `masterplate`;
CREATE TABLE IF NOT EXISTS `masterplate` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `tz_id` bigint(10) NOT NULL DEFAULT '0',
  `posintz` int(11) NOT NULL DEFAULT '0',
  `mpdate` date NOT NULL DEFAULT '0000-00-00',
  `user_id` bigint(10) NOT NULL DEFAULT '0',
  `posid` bigint(20) NOT NULL DEFAULT '0',
  `customer_id` bigint(20) NOT NULL COMMENT 'Идентификатор заказчика',
  `block_id` bigint(20) NOT NULL COMMENT 'Идентификатор блока',
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`,`block_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Мастерплаты' AUTO_INCREMENT=397 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
