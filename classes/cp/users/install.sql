-- phpMyAdmin SQL Dump
-- version 3.4.2
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Июл 08 2011 г., 09:13
-- Версия сервера: 5.1.56
-- Версия PHP: 5.3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `zaompp1`
--

-- --------------------------------------------------------

--
-- Структура таблицы `rights`
--

CREATE TABLE IF NOT EXISTS `rights` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `u_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `type_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `rtype_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `right` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_users` (`u_id`),
  KEY `fk_rtype` (`rtype_id`),
  KEY `fk_type` (`type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Таблица прав доступа к управлению базой' AUTO_INCREMENT=6167 ;

--
-- Дамп данных таблицы `rights`
--

INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5676, 1, 69, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5677, 1, 69, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5678, 1, 69, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5679, 1, 70, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5680, 1, 70, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5681, 1, 70, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5682, 1, 71, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5683, 1, 71, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5684, 1, 71, 13, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `rrtypes`
--

CREATE TABLE IF NOT EXISTS `rrtypes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `rtype` varchar(50) NOT NULL DEFAULT '',
  `value` int(11) NOT NULL COMMENT 'Установленный бит',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Типы доступа' AUTO_INCREMENT=16 ;

--
-- Дамп данных таблицы `rrtypes`
--

INSERT INTO `rrtypes` (`id`, `rtype`, `value`) VALUES(13, 'del', 4);
INSERT INTO `rrtypes` (`id`, `rtype`, `value`) VALUES(14, 'edit', 2);
INSERT INTO `rrtypes` (`id`, `rtype`, `value`) VALUES(15, 'view', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `rtypes`
--

CREATE TABLE IF NOT EXISTS `rtypes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `type` (`type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Типы для доступа' AUTO_INCREMENT=90 ;

--
-- Дамп данных таблицы `rtypes`
--

INSERT INTO `rtypes` (`id`, `type`) VALUES(69, 'cp');
INSERT INTO `rtypes` (`id`, `type`) VALUES(71, 'cp_todo');
INSERT INTO `rtypes` (`id`, `type`) VALUES(70, 'cp_users');

-- --------------------------------------------------------

--
-- Структура таблицы `session`
--

CREATE TABLE IF NOT EXISTS `session` (
  `session` varchar(255) NOT NULL DEFAULT '',
  `u_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`session`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8;

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nik` varchar(15) NOT NULL DEFAULT '',
  `fullname` varchar(50) NOT NULL DEFAULT '',
  `position` varchar(50) NOT NULL DEFAULT '',
  `password` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `nik` (`nik`),
  UNIQUE KEY `password` (`password`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Пользватели базы данных' AUTO_INCREMENT=22 ;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `nik`, `fullname`, `position`, `password`) VALUES(1, 'igor', 'Игорь Юрьевич Федоров', 'Конструктор', '14874');

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `rights`
--
ALTER TABLE `rights`
  ADD CONSTRAINT `fk_rtype` FOREIGN KEY (`rtype_id`) REFERENCES `rrtypes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_type` FOREIGN KEY (`type_id`) REFERENCES `rtypes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_users` FOREIGN KEY (`u_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
