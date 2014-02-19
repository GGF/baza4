-- phpMyAdmin SQL Dump
-- version 4.1.7
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Фев 19 2014 г., 16:06
-- Версия сервера: 5.5.36
-- Версия PHP: 5.4.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `zaomppsklads`
--

-- --------------------------------------------------------

--
-- Структура таблицы `coments`
--

CREATE TABLE IF NOT EXISTS `coments` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `comment` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Свалка коментариев к различным документам' AUTO_INCREMENT=2879 ;

-- --------------------------------------------------------

--
-- Структура таблицы `filelinks`
--

CREATE TABLE IF NOT EXISTS `filelinks` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `file_link` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Ссылки на файлы' AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Для прикрепления множества файлов к одному документу' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_arc_hal__dvizh`
--

CREATE TABLE IF NOT EXISTS `sk_arc_hal__dvizh` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=331 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_arc_hal__ost`
--

CREATE TABLE IF NOT EXISTS `sk_arc_hal__ost` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `spr_id` bigint(10) NOT NULL DEFAULT '0',
  `ost` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=38 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_arc_hal__spr`
--

CREATE TABLE IF NOT EXISTS `sk_arc_hal__spr` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `nazv` varchar(50) NOT NULL DEFAULT '',
  `edizm` varchar(10) NOT NULL DEFAULT '',
  `spr_price` float unsigned NOT NULL COMMENT 'Цена последняя',
  `koeff` float unsigned NOT NULL COMMENT 'Коэффициент перевода в обычные единицы',
  `krost` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=46 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_arc_him1__dvizh`
--

CREATE TABLE IF NOT EXISTS `sk_arc_him1__dvizh` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1626 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_arc_him1__ost`
--

CREATE TABLE IF NOT EXISTS `sk_arc_him1__ost` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `spr_id` bigint(10) NOT NULL DEFAULT '0',
  `ost` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=174 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_arc_him1__spr`
--

CREATE TABLE IF NOT EXISTS `sk_arc_him1__spr` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `nazv` varchar(50) NOT NULL DEFAULT '',
  `edizm` varchar(10) NOT NULL DEFAULT '',
  `spr_price` float unsigned NOT NULL COMMENT 'Цена последняя',
  `koeff` float unsigned NOT NULL COMMENT 'Коэффициент перевода в обычные единицы',
  `krost` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=187 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_arc_him__dvizh`
--

CREATE TABLE IF NOT EXISTS `sk_arc_him__dvizh` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2828 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_arc_him__ost`
--

CREATE TABLE IF NOT EXISTS `sk_arc_him__ost` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `spr_id` bigint(10) NOT NULL DEFAULT '0',
  `ost` float(12,3) NOT NULL DEFAULT '0.000',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=198 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_arc_him__spr`
--

CREATE TABLE IF NOT EXISTS `sk_arc_him__spr` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `nazv` varchar(50) NOT NULL DEFAULT '',
  `edizm` varchar(10) NOT NULL DEFAULT '',
  `spr_price` float unsigned NOT NULL COMMENT 'Цена последняя',
  `koeff` float unsigned NOT NULL COMMENT 'Коэффициент перевода в обычные единицы',
  `krost` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=301 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_arc_inst__dvizh`
--

CREATE TABLE IF NOT EXISTS `sk_arc_inst__dvizh` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=265 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_arc_inst__ost`
--

CREATE TABLE IF NOT EXISTS `sk_arc_inst__ost` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `spr_id` bigint(10) NOT NULL DEFAULT '0',
  `ost` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_arc_inst__spr`
--

CREATE TABLE IF NOT EXISTS `sk_arc_inst__spr` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `nazv` varchar(50) NOT NULL DEFAULT '',
  `edizm` varchar(10) NOT NULL DEFAULT '',
  `spr_price` float unsigned NOT NULL COMMENT 'Цена последняя',
  `koeff` float unsigned NOT NULL COMMENT 'Коэффициент перевода в обычные единицы',
  `krost` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_arc_maloc__dvizh`
--

CREATE TABLE IF NOT EXISTS `sk_arc_maloc__dvizh` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1303 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_arc_maloc__ost`
--

CREATE TABLE IF NOT EXISTS `sk_arc_maloc__ost` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `spr_id` bigint(10) NOT NULL DEFAULT '0',
  `ost` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=146 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_arc_maloc__spr`
--

CREATE TABLE IF NOT EXISTS `sk_arc_maloc__spr` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `nazv` varchar(50) NOT NULL DEFAULT '',
  `edizm` varchar(10) NOT NULL DEFAULT '',
  `spr_price` float unsigned NOT NULL COMMENT 'Цена последняя',
  `koeff` float unsigned NOT NULL COMMENT 'Коэффициент перевода в обычные единицы',
  `krost` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=146 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_arc_mat__dvizh`
--

CREATE TABLE IF NOT EXISTS `sk_arc_mat__dvizh` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1877 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_arc_mat__ost`
--

CREATE TABLE IF NOT EXISTS `sk_arc_mat__ost` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `spr_id` bigint(10) NOT NULL DEFAULT '0',
  `ost` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=117 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_arc_mat__spr`
--

CREATE TABLE IF NOT EXISTS `sk_arc_mat__spr` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `nazv` varchar(200) NOT NULL DEFAULT '',
  `edizm` varchar(10) NOT NULL DEFAULT '',
  `spr_price` float unsigned NOT NULL COMMENT 'Цена последняя',
  `koeff` float unsigned NOT NULL COMMENT 'Коэффициент перевода в обычные единицы',
  `krost` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=165 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_arc_nepon__dvizh`
--

CREATE TABLE IF NOT EXISTS `sk_arc_nepon__dvizh` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=546 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_arc_nepon__ost`
--

CREATE TABLE IF NOT EXISTS `sk_arc_nepon__ost` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `spr_id` bigint(10) NOT NULL DEFAULT '0',
  `ost` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=82 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_arc_nepon__spr`
--

CREATE TABLE IF NOT EXISTS `sk_arc_nepon__spr` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `nazv` varchar(50) NOT NULL DEFAULT '',
  `edizm` varchar(10) NOT NULL DEFAULT '',
  `spr_price` float unsigned NOT NULL COMMENT 'Цена последняя',
  `koeff` float unsigned NOT NULL COMMENT 'Коэффициент перевода в обычные единицы',
  `krost` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=82 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_arc_stroy__dvizh`
--

CREATE TABLE IF NOT EXISTS `sk_arc_stroy__dvizh` (
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
-- Структура таблицы `sk_arc_stroy__ost`
--

CREATE TABLE IF NOT EXISTS `sk_arc_stroy__ost` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `spr_id` bigint(10) NOT NULL DEFAULT '0',
  `ost` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_arc_stroy__spr`
--

CREATE TABLE IF NOT EXISTS `sk_arc_stroy__spr` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `nazv` varchar(50) NOT NULL DEFAULT '',
  `edizm` varchar(10) NOT NULL DEFAULT '',
  `spr_price` float unsigned NOT NULL COMMENT 'Цена последняя',
  `koeff` float unsigned NOT NULL COMMENT 'Коэффициент перевода в обычные единицы',
  `krost` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=38 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_arc_sver__dvizh`
--

CREATE TABLE IF NOT EXISTS `sk_arc_sver__dvizh` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=612 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_arc_sver__ost`
--

CREATE TABLE IF NOT EXISTS `sk_arc_sver__ost` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `spr_id` bigint(10) NOT NULL DEFAULT '0',
  `ost` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_arc_sver__spr`
--

CREATE TABLE IF NOT EXISTS `sk_arc_sver__spr` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `nazv` varchar(50) NOT NULL DEFAULT '',
  `edizm` varchar(10) NOT NULL DEFAULT '',
  `spr_price` float unsigned NOT NULL COMMENT 'Цена последняя',
  `koeff` float unsigned NOT NULL COMMENT 'Коэффициент перевода в обычные единицы',
  `krost` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_arc_test1__dvizh`
--

CREATE TABLE IF NOT EXISTS `sk_arc_test1__dvizh` (
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
-- Структура таблицы `sk_arc_test1__ost`
--

CREATE TABLE IF NOT EXISTS `sk_arc_test1__ost` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `spr_id` bigint(10) NOT NULL DEFAULT '0',
  `ost` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_arc_test1__spr`
--

CREATE TABLE IF NOT EXISTS `sk_arc_test1__spr` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `nazv` varchar(50) NOT NULL DEFAULT '',
  `edizm` varchar(10) NOT NULL DEFAULT '',
  `spr_price` float unsigned NOT NULL COMMENT 'Цена последняя',
  `koeff` float unsigned NOT NULL COMMENT 'Коэффициент перевода в обычные единицы',
  `krost` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_arc_test__dvizh`
--

CREATE TABLE IF NOT EXISTS `sk_arc_test__dvizh` (
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
-- Структура таблицы `sk_arc_test__ost`
--

CREATE TABLE IF NOT EXISTS `sk_arc_test__ost` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `spr_id` bigint(10) NOT NULL DEFAULT '0',
  `ost` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_arc_test__spr`
--

CREATE TABLE IF NOT EXISTS `sk_arc_test__spr` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `nazv` varchar(50) NOT NULL DEFAULT '',
  `edizm` varchar(10) NOT NULL DEFAULT '',
  `spr_price` float unsigned NOT NULL COMMENT 'Цена последняя',
  `koeff` float unsigned NOT NULL COMMENT 'Коэффициент перевода в обычные единицы',
  `krost` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_arc_zap__dvizh`
--

CREATE TABLE IF NOT EXISTS `sk_arc_zap__dvizh` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=543 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_arc_zap__ost`
--

CREATE TABLE IF NOT EXISTS `sk_arc_zap__ost` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `spr_id` bigint(10) NOT NULL DEFAULT '0',
  `ost` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=130 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_arc_zap__spr`
--

CREATE TABLE IF NOT EXISTS `sk_arc_zap__spr` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `nazv` varchar(50) NOT NULL DEFAULT '',
  `edizm` varchar(10) NOT NULL DEFAULT '',
  `spr_price` float unsigned NOT NULL COMMENT 'Цена последняя',
  `koeff` float unsigned NOT NULL COMMENT 'Коэффициент перевода в обычные единицы',
  `krost` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=130 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_arc__dvizh`
--

CREATE TABLE IF NOT EXISTS `sk_arc__dvizh` (
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
-- Структура таблицы `sk_arc__ost`
--

CREATE TABLE IF NOT EXISTS `sk_arc__ost` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `spr_id` bigint(10) NOT NULL DEFAULT '0',
  `ost` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_arc__spr`
--

CREATE TABLE IF NOT EXISTS `sk_arc__spr` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `nazv` varchar(50) NOT NULL DEFAULT '',
  `edizm` varchar(10) NOT NULL DEFAULT '',
  `spr_price` float unsigned NOT NULL COMMENT 'Цена последняя',
  `koeff` float unsigned NOT NULL COMMENT 'Коэффициент перевода в обычные единицы',
  `krost` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_hal__dvizh`
--

CREATE TABLE IF NOT EXISTS `sk_hal__dvizh` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=269 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_hal__dvizh_arc`
--

CREATE TABLE IF NOT EXISTS `sk_hal__dvizh_arc` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=631 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_hal__ost`
--

CREATE TABLE IF NOT EXISTS `sk_hal__ost` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `spr_id` bigint(10) NOT NULL DEFAULT '0',
  `ost` float(12,3) NOT NULL DEFAULT '0.000',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=67 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_hal__postav`
--

CREATE TABLE IF NOT EXISTS `sk_hal__postav` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `supply` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_hal__spr`
--

CREATE TABLE IF NOT EXISTS `sk_hal__spr` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `nazv` varchar(50) NOT NULL DEFAULT '',
  `edizm` varchar(10) NOT NULL DEFAULT '',
  `spr_price` float unsigned NOT NULL COMMENT 'Цена последняя',
  `koeff` float unsigned NOT NULL COMMENT 'Коэффициент перевода в обычные единицы',
  `krost` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=91 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_him1__dvizh`
--

CREATE TABLE IF NOT EXISTS `sk_him1__dvizh` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=552 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_him1__dvizh_arc`
--

CREATE TABLE IF NOT EXISTS `sk_him1__dvizh_arc` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1856 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_him1__ost`
--

CREATE TABLE IF NOT EXISTS `sk_him1__ost` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `spr_id` bigint(10) NOT NULL DEFAULT '0',
  `ost` float(12,3) NOT NULL DEFAULT '0.000',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=116 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_him1__postav`
--

CREATE TABLE IF NOT EXISTS `sk_him1__postav` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `supply` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_him1__spr`
--

CREATE TABLE IF NOT EXISTS `sk_him1__spr` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `nazv` varchar(50) NOT NULL DEFAULT '',
  `edizm` varchar(10) NOT NULL DEFAULT '',
  `spr_price` float unsigned NOT NULL COMMENT 'Цена последняя',
  `koeff` float unsigned NOT NULL COMMENT 'Коэффициент перевода в обычные единицы',
  `krost` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=263 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_him__dvizh`
--

CREATE TABLE IF NOT EXISTS `sk_him__dvizh` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1361 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_him__dvizh_arc`
--

CREATE TABLE IF NOT EXISTS `sk_him__dvizh_arc` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10901 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_him__ost`
--

CREATE TABLE IF NOT EXISTS `sk_him__ost` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `spr_id` bigint(10) NOT NULL DEFAULT '0',
  `ost` float(12,3) NOT NULL DEFAULT '0.000',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=179 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_him__postav`
--

CREATE TABLE IF NOT EXISTS `sk_him__postav` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `supply` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=239 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_him__spr`
--

CREATE TABLE IF NOT EXISTS `sk_him__spr` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `nazv` varchar(50) NOT NULL DEFAULT '',
  `edizm` varchar(10) NOT NULL DEFAULT '',
  `spr_price` float unsigned NOT NULL COMMENT 'Цена последняя',
  `koeff` float unsigned NOT NULL COMMENT 'Коэффициент перевода в обычные единицы',
  `krost` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=299 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_inst__dvizh`
--

CREATE TABLE IF NOT EXISTS `sk_inst__dvizh` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_inst__dvizh_arc`
--

CREATE TABLE IF NOT EXISTS `sk_inst__dvizh_arc` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_inst__ost`
--

CREATE TABLE IF NOT EXISTS `sk_inst__ost` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `spr_id` bigint(10) NOT NULL DEFAULT '0',
  `ost` float(12,3) NOT NULL DEFAULT '0.000',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_inst__postav`
--

CREATE TABLE IF NOT EXISTS `sk_inst__postav` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `supply` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_inst__spr`
--

CREATE TABLE IF NOT EXISTS `sk_inst__spr` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `nazv` varchar(50) NOT NULL DEFAULT '',
  `edizm` varchar(10) NOT NULL DEFAULT '',
  `spr_price` float unsigned NOT NULL COMMENT 'Цена последняя',
  `koeff` float unsigned NOT NULL COMMENT 'Коэффициент перевода в обычные единицы',
  `krost` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_maloc__dvizh`
--

CREATE TABLE IF NOT EXISTS `sk_maloc__dvizh` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=250 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_maloc__dvizh_arc`
--

CREATE TABLE IF NOT EXISTS `sk_maloc__dvizh_arc` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1332 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_maloc__ost`
--

CREATE TABLE IF NOT EXISTS `sk_maloc__ost` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `spr_id` bigint(10) NOT NULL DEFAULT '0',
  `ost` float(12,3) NOT NULL DEFAULT '0.000',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=206 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_maloc__postav`
--

CREATE TABLE IF NOT EXISTS `sk_maloc__postav` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `supply` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_maloc__spr`
--

CREATE TABLE IF NOT EXISTS `sk_maloc__spr` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `nazv` varchar(50) NOT NULL DEFAULT '',
  `edizm` varchar(10) NOT NULL DEFAULT '',
  `spr_price` float unsigned NOT NULL COMMENT 'Цена последняя',
  `koeff` float unsigned NOT NULL COMMENT 'Коэффициент перевода в обычные единицы',
  `krost` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=303 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_mat__dvizh`
--

CREATE TABLE IF NOT EXISTS `sk_mat__dvizh` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1117 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_mat__dvizh_arc`
--

CREATE TABLE IF NOT EXISTS `sk_mat__dvizh_arc` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3749 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_mat__ost`
--

CREATE TABLE IF NOT EXISTS `sk_mat__ost` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `spr_id` bigint(10) NOT NULL DEFAULT '0',
  `ost` float(12,3) NOT NULL DEFAULT '0.000',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=127 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_mat__postav`
--

CREATE TABLE IF NOT EXISTS `sk_mat__postav` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `supply` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=69 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_mat__spr`
--

CREATE TABLE IF NOT EXISTS `sk_mat__spr` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `nazv` varchar(200) NOT NULL DEFAULT '',
  `edizm` varchar(10) NOT NULL DEFAULT '',
  `spr_price` float unsigned NOT NULL COMMENT 'Цена последняя',
  `koeff` float unsigned NOT NULL COMMENT 'Коэффициент перевода в обычные единицы',
  `krost` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=191 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_nepon__dvizh`
--

CREATE TABLE IF NOT EXISTS `sk_nepon__dvizh` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1246 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_nepon__dvizh_arc`
--

CREATE TABLE IF NOT EXISTS `sk_nepon__dvizh_arc` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1189 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_nepon__ost`
--

CREATE TABLE IF NOT EXISTS `sk_nepon__ost` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `spr_id` bigint(10) NOT NULL DEFAULT '0',
  `ost` float(12,3) NOT NULL DEFAULT '0.000',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=185 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_nepon__postav`
--

CREATE TABLE IF NOT EXISTS `sk_nepon__postav` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `supply` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_nepon__spr`
--

CREATE TABLE IF NOT EXISTS `sk_nepon__spr` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `nazv` varchar(50) NOT NULL DEFAULT '',
  `edizm` varchar(10) NOT NULL DEFAULT '',
  `spr_price` float unsigned NOT NULL COMMENT 'Цена последняя',
  `koeff` float unsigned NOT NULL COMMENT 'Коэффициент перевода в обычные единицы',
  `krost` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=185 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_stroy__dvizh`
--

CREATE TABLE IF NOT EXISTS `sk_stroy__dvizh` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_stroy__dvizh_arc`
--

CREATE TABLE IF NOT EXISTS `sk_stroy__dvizh_arc` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_stroy__ost`
--

CREATE TABLE IF NOT EXISTS `sk_stroy__ost` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `spr_id` bigint(10) NOT NULL DEFAULT '0',
  `ost` float(12,3) NOT NULL DEFAULT '0.000',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_stroy__postav`
--

CREATE TABLE IF NOT EXISTS `sk_stroy__postav` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `supply` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_stroy__spr`
--

CREATE TABLE IF NOT EXISTS `sk_stroy__spr` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `nazv` varchar(50) NOT NULL DEFAULT '',
  `edizm` varchar(10) NOT NULL DEFAULT '',
  `spr_price` float unsigned NOT NULL COMMENT 'Цена последняя',
  `koeff` float unsigned NOT NULL COMMENT 'Коэффициент перевода в обычные единицы',
  `krost` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_sver__dvizh`
--

CREATE TABLE IF NOT EXISTS `sk_sver__dvizh` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=107 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_sver__dvizh_arc`
--

CREATE TABLE IF NOT EXISTS `sk_sver__dvizh_arc` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2724 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_sver__ost`
--

CREATE TABLE IF NOT EXISTS `sk_sver__ost` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `spr_id` bigint(10) NOT NULL DEFAULT '0',
  `ost` float(12,3) NOT NULL DEFAULT '0.000',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=98 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_sver__postav`
--

CREATE TABLE IF NOT EXISTS `sk_sver__postav` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `supply` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=35 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_sver__spr`
--

CREATE TABLE IF NOT EXISTS `sk_sver__spr` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `nazv` varchar(50) NOT NULL DEFAULT '',
  `edizm` varchar(10) NOT NULL DEFAULT '',
  `spr_price` float unsigned NOT NULL COMMENT 'Цена последняя',
  `koeff` float unsigned NOT NULL COMMENT 'Коэффициент перевода в обычные единицы',
  `krost` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=98 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_test1__dvizh`
--

CREATE TABLE IF NOT EXISTS `sk_test1__dvizh` (
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
-- Структура таблицы `sk_test1__dvizh_arc`
--

CREATE TABLE IF NOT EXISTS `sk_test1__dvizh_arc` (
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
-- Структура таблицы `sk_test1__ost`
--

CREATE TABLE IF NOT EXISTS `sk_test1__ost` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `spr_id` bigint(10) NOT NULL DEFAULT '0',
  `ost` float(12,3) NOT NULL DEFAULT '0.000',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_test1__postav`
--

CREATE TABLE IF NOT EXISTS `sk_test1__postav` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `supply` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_test1__spr`
--

CREATE TABLE IF NOT EXISTS `sk_test1__spr` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `nazv` varchar(50) NOT NULL DEFAULT '',
  `edizm` varchar(10) NOT NULL DEFAULT '',
  `spr_price` float unsigned NOT NULL COMMENT 'Цена последняя',
  `koeff` float unsigned NOT NULL COMMENT 'Коэффициент перевода в обычные единицы',
  `krost` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_test__dvizh`
--

CREATE TABLE IF NOT EXISTS `sk_test__dvizh` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_test__dvizh_arc`
--

CREATE TABLE IF NOT EXISTS `sk_test__dvizh_arc` (
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
-- Структура таблицы `sk_test__ost`
--

CREATE TABLE IF NOT EXISTS `sk_test__ost` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `spr_id` bigint(10) NOT NULL DEFAULT '0',
  `ost` float(12,3) NOT NULL DEFAULT '0.000',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_test__postav`
--

CREATE TABLE IF NOT EXISTS `sk_test__postav` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `supply` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_test__spr`
--

CREATE TABLE IF NOT EXISTS `sk_test__spr` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `nazv` varchar(50) NOT NULL DEFAULT '',
  `edizm` varchar(10) NOT NULL DEFAULT '',
  `spr_price` float unsigned NOT NULL COMMENT 'Цена последняя',
  `koeff` float unsigned NOT NULL COMMENT 'Коэффициент перевода в обычные единицы',
  `krost` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=87 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_zap__dvizh`
--

CREATE TABLE IF NOT EXISTS `sk_zap__dvizh` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=144 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_zap__dvizh_arc`
--

CREATE TABLE IF NOT EXISTS `sk_zap__dvizh_arc` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=345 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_zap__ost`
--

CREATE TABLE IF NOT EXISTS `sk_zap__ost` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `spr_id` bigint(10) NOT NULL DEFAULT '0',
  `ost` float(12,3) NOT NULL DEFAULT '0.000',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=146 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_zap__postav`
--

CREATE TABLE IF NOT EXISTS `sk_zap__postav` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `supply` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk_zap__spr`
--

CREATE TABLE IF NOT EXISTS `sk_zap__spr` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `nazv` varchar(50) NOT NULL DEFAULT '',
  `edizm` varchar(10) NOT NULL DEFAULT '',
  `spr_price` float unsigned NOT NULL COMMENT 'Цена последняя',
  `koeff` float unsigned NOT NULL COMMENT 'Коэффициент перевода в обычные единицы',
  `krost` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=146 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk__dvizh`
--

CREATE TABLE IF NOT EXISTS `sk__dvizh` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk__dvizh_arc`
--

CREATE TABLE IF NOT EXISTS `sk__dvizh_arc` (
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
-- Структура таблицы `sk__ost`
--

CREATE TABLE IF NOT EXISTS `sk__ost` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `spr_id` bigint(10) NOT NULL DEFAULT '0',
  `ost` float(12,3) NOT NULL DEFAULT '0.000',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk__postav`
--

CREATE TABLE IF NOT EXISTS `sk__postav` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `supply` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sk__spr`
--

CREATE TABLE IF NOT EXISTS `sk__spr` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `nazv` varchar(50) NOT NULL DEFAULT '',
  `edizm` varchar(10) NOT NULL DEFAULT '',
  `spr_price` float unsigned NOT NULL COMMENT 'Цена последняя',
  `koeff` float unsigned NOT NULL COMMENT 'Коэффициент перевода в обычные единицы',
  `krost` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
