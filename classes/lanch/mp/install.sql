-- phpMyAdmin SQL Dump
-- version 4.1.5
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Янв 23 2014 г., 09:34
-- Версия сервера: 5.5.35
-- Версия PHP: 5.4.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
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

CREATE TABLE IF NOT EXISTS `masterplate` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `mpdate` date NOT NULL DEFAULT '0000-00-00',
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `posid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'Идентификатор позиции в ТЗ',
  `block_id` bigint(20) unsigned NOT NULL COMMENT 'Идентификатор блока',
  PRIMARY KEY (`id`),
  KEY `customer_id` (`block_id`),
  KEY `mpdate` (`mpdate`),
  KEY `block_id` (`block_id`),
  KEY `user_id` (`user_id`),
  KEY `posid` (`posid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Мастерплаты' ;

--
-- Ограничения внешнего ключа таблицы `masterplate`
--
ALTER TABLE `masterplate`
  ADD CONSTRAINT `masterplate_ibfk_1` FOREIGN KEY (`block_id`) REFERENCES `blocks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `masterplate_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
