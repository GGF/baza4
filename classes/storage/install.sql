-- phpMyAdmin SQL Dump
-- version 3.4.2
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Июл 07 2011 г., 13:16
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

USE `%storagebase%`;

--
-- Структура таблицы `sk_%storage%_dvizh`
--

CREATE TABLE IF NOT EXISTS `sk_%storage%_dvizh` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `numd` varchar(10) NOT NULL DEFAULT '',
  `numdf` varchar(10) NOT NULL DEFAULT '',
  `docyr` int(11) NOT NULL DEFAULT '0',
  `spr_id` bigint(10) NOT NULL DEFAULT '0',
  `quant` float NOT NULL DEFAULT '0',
  `ddate` date NOT NULL DEFAULT '0000-00-00',
  `post_id` bigint(10) NOT NULL DEFAULT '0',
  `comment_id` bigint(10) NOT NULL DEFAULT '0',
  `price` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `type` (`type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_%storage%_dvizh_arc`
--

CREATE TABLE IF NOT EXISTS `sk_%storage%_dvizh_arc` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `numd` varchar(10) NOT NULL DEFAULT '',
  `numdf` varchar(10) NOT NULL DEFAULT '',
  `docyr` int(11) NOT NULL DEFAULT '0',
  `spr_id` bigint(10) NOT NULL DEFAULT '0',
  `quant` float NOT NULL DEFAULT '0',
  `ddate` date NOT NULL DEFAULT '0000-00-00',
  `post_id` bigint(10) NOT NULL DEFAULT '0',
  `comment_id` bigint(10) NOT NULL DEFAULT '0',
  `price` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_%storage%_ost`
--

CREATE TABLE IF NOT EXISTS `sk_%storage%_ost` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `spr_id` bigint(10) NOT NULL DEFAULT '0',
  `ost` float(12,3) NOT NULL DEFAULT '0.000',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_%storage%_postav`
--

CREATE TABLE IF NOT EXISTS `sk_%storage%_postav` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `supply` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_%storage%_spr`
--

CREATE TABLE IF NOT EXISTS `sk_%storage%_spr` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `nazv` varchar(50) NOT NULL DEFAULT '',
  `edizm` varchar(10) NOT NULL DEFAULT '',
  `spr_price` float unsigned NOT NULL COMMENT 'Цена последняя',
  `koeff` float unsigned NOT NULL COMMENT 'Коэффициент перевода в обычные единицы',
  `krost` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=87 ;

--
-- База данных: `zaomppsklads`
--

-- --------------------------------------------------------

--
-- Структура таблицы `sk_arc_%storage%_dvizh`
--

CREATE TABLE IF NOT EXISTS `sk_arc_%storage%_dvizh` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `numd` varchar(10) NOT NULL DEFAULT '',
  `numdf` varchar(10) NOT NULL DEFAULT '',
  `docyr` int(11) NOT NULL DEFAULT '0',
  `spr_id` bigint(10) NOT NULL DEFAULT '0',
  `quant` float NOT NULL DEFAULT '0',
  `ddate` date NOT NULL DEFAULT '0000-00-00',
  `post_id` bigint(10) NOT NULL DEFAULT '0',
  `comment_id` bigint(10) NOT NULL DEFAULT '0',
  `price` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `type` (`type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_arc_%storage%_ost`
--

CREATE TABLE IF NOT EXISTS `sk_arc_%storage%_ost` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `spr_id` bigint(10) NOT NULL DEFAULT '0',
  `ost` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_arc_%storage%_spr`
--

CREATE TABLE IF NOT EXISTS `sk_arc_%storage%_spr` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `nazv` varchar(50) NOT NULL DEFAULT '',
  `edizm` varchar(10) NOT NULL DEFAULT '',
  `spr_price` float unsigned NOT NULL COMMENT 'Цена последняя',
  `koeff` float unsigned NOT NULL COMMENT 'Коэффициент перевода в обычные единицы',
  `krost` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
