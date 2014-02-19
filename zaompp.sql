-- phpMyAdmin SQL Dump
-- version 4.1.7
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Фев 19 2014 г., 16:05
-- Версия сервера: 5.5.36
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
-- Структура таблицы `bash`
--

CREATE TABLE IF NOT EXISTS `bash` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `quote` text NOT NULL,
  `rate` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `rate` (`rate`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=24759 ;

-- --------------------------------------------------------

--
-- Структура таблицы `blockpos`
--

CREATE TABLE IF NOT EXISTS `blockpos` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `block_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'Блок',
  `board_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'Плата в блоке',
  `nib` smallint(4) NOT NULL DEFAULT '0' COMMENT 'Количество в блоке',
  `nx` enum('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','0') DEFAULT '0' COMMENT 'Количество по горизонтали',
  `ny` enum('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','0') DEFAULT '0' COMMENT 'Количество по вертикали',
  PRIMARY KEY (`id`),
  KEY `fk_blockpos_blocks` (`block_id`),
  KEY `fk_blockpos_boards` (`board_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Позиции в блоках' AUTO_INCREMENT=14882 ;

-- --------------------------------------------------------

--
-- Структура таблицы `blocks`
--

CREATE TABLE IF NOT EXISTS `blocks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint(10) NOT NULL DEFAULT '0' COMMENT 'Заказчик',
  `blockname` varchar(100) NOT NULL DEFAULT '' COMMENT 'Название блока',
  `sizex` float(8,3) NOT NULL DEFAULT '0.000' COMMENT 'Размер по длине',
  `sizey` float(8,3) NOT NULL DEFAULT '0.000' COMMENT 'Размер по высоте',
  `thickness` float(5,2) NOT NULL DEFAULT '0.00' COMMENT 'Толщина заготовки',
  `scomp` float(10,2) DEFAULT '0.00' COMMENT 'Площадь меди сторона установки',
  `ssolder` float(10,2) DEFAULT '0.00' COMMENT 'Площадь меди сторона пайки',
  `drlname` varchar(10) NOT NULL DEFAULT '' COMMENT 'Имя файла сверловки',
  `auarea` float(4,2) DEFAULT '0.00' COMMENT 'Площадь золочения заготовки',
  `smalldrill` smallint(6) DEFAULT '0' COMMENT 'Количество отверстий диаметром меньше или равным 0.6',
  `bigdrill` smallint(6) DEFAULT '0' COMMENT 'Количество отверстий диаметром более 0.6',
  `comment_id` bigint(20) unsigned NOT NULL DEFAULT '1' COMMENT 'Идентификатор комментария',
  PRIMARY KEY (`id`),
  KEY `blockname` (`blockname`),
  KEY `smalldrill` (`smalldrill`,`bigdrill`),
  KEY `customer_id` (`customer_id`),
  KEY `comment_id` (`comment_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Блоки плат' AUTO_INCREMENT=4635 ;

-- --------------------------------------------------------

--
-- Структура таблицы `boards`
--

CREATE TABLE IF NOT EXISTS `boards` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint(10) NOT NULL DEFAULT '0' COMMENT 'Заказчик',
  `board_name` varchar(255) NOT NULL DEFAULT '0' COMMENT 'Децимальный номер платы',
  `layers` int(2) NOT NULL DEFAULT '0' COMMENT 'Количество слоев',
  `class` enum('1','2','3','4','4+','5','6') NOT NULL DEFAULT '1' COMMENT 'Класс точности',
  `sizex` float(7,3) NOT NULL DEFAULT '0.000' COMMENT 'длина',
  `sizey` float(7,3) NOT NULL DEFAULT '0.000' COMMENT 'ширина',
  `textolite` varchar(50) NOT NULL DEFAULT '0' COMMENT 'Тип текстолита',
  `thickness` varchar(10) NOT NULL DEFAULT '0.00' COMMENT 'Толщина (текстом потому что включает допуск с символом плюс-минус)',
  `glasscloth` varchar(40) DEFAULT NULL COMMENT 'Тип стеклоткани',
  `mask` varchar(20) NOT NULL DEFAULT '0' COMMENT 'Тип маски и количество',
  `mark` varchar(20) NOT NULL DEFAULT '0' COMMENT 'Тип маркировки и количество',
  `rmark` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Ручная маркировка данет',
  `frezcorner` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Фрезеровка (1-контур, 2-углы)',
  `razr` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Разрубка-доводка данет',
  `immer` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Иммерсиооное покрытие (данет)',
  `numlam` int(11) NOT NULL DEFAULT '0' COMMENT 'Количество золотоламелей',
  `lsizex` float(5,2) NOT NULL DEFAULT '0.00' COMMENT 'Длина ламели',
  `lsizey` float(5,2) NOT NULL DEFAULT '0.00' COMMENT 'Ширина ламели',
  `complexity_factor` float(3,1) NOT NULL DEFAULT '0.0' COMMENT 'Коэф сложности',
  `frez_factor` float(3,1) NOT NULL DEFAULT '0.0' COMMENT 'Коэф фрезеровки',
  `comment_id` bigint(20) unsigned NOT NULL DEFAULT '1' COMMENT 'Коментарий',
  PRIMARY KEY (`id`),
  KEY `board_id` (`board_name`,`textolite`),
  KEY `customer_id` (`customer_id`),
  KEY `comment_id` (`comment_id`),
  KEY `board_name` (`board_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Даные по плате, только те что относятся к плате' AUTO_INCREMENT=4629 ;

-- --------------------------------------------------------

--
-- Структура таблицы `coments`
--

CREATE TABLE IF NOT EXISTS `coments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `comment` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Свалка коментариев к различным документам' AUTO_INCREMENT=3617 ;

-- --------------------------------------------------------

--
-- Структура таблицы `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `forobject` varchar(50) NOT NULL COMMENT 'Имя таблицы',
  `record_id` bigint(20) unsigned NOT NULL COMMENT 'Идентификатор записи в таблице',
  `coment_id` bigint(20) unsigned NOT NULL COMMENT 'Идентификатор комментария из coment',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Время комментария',
  `author_id` bigint(20) unsigned NOT NULL COMMENT 'Автор комментария',
  PRIMARY KEY (`id`),
  KEY `forobject` (`forobject`,`record_id`,`coment_id`),
  KEY `time` (`time`,`author_id`),
  KEY `author_id` (`author_id`),
  KEY `coment_id` (`coment_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Хранит кросидентификаторы для коментариев к объектам (другим' AUTO_INCREMENT=58 ;

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

-- --------------------------------------------------------

--
-- Структура таблицы `customers`
--

CREATE TABLE IF NOT EXISTS `customers` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `customer` varchar(40) NOT NULL DEFAULT '',
  `fullname` varchar(200) NOT NULL DEFAULT '',
  `kdir` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `customer` (`customer`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Заказчики' AUTO_INCREMENT=145 ;

-- --------------------------------------------------------

--
-- Структура таблицы `filelinks`
--

CREATE TABLE IF NOT EXISTS `filelinks` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `file_link` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Ссылки на файлы' AUTO_INCREMENT=22794 ;

-- --------------------------------------------------------

--
-- Структура таблицы `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `table` varchar(100) NOT NULL COMMENT 'Таблица к которой относится запись',
  `rec_id` bigint(20) NOT NULL COMMENT 'Идентификатор записи к которой относятся файлы',
  `fileid` bigint(10) NOT NULL COMMENT 'Идентификатор файловой ссылки из таблицы filelinks',
  `type` varchar(50) NOT NULL DEFAULT 'http' COMMENT 'тип ссылки, в принципе только вэб или шара',
  PRIMARY KEY (`id`),
  KEY `fileid` (`fileid`),
  KEY `table` (`table`),
  KEY `rec_id` (`rec_id`),
  KEY `type` (`type`),
  KEY `fileid_2` (`fileid`),
  KEY `rec_id_2` (`rec_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Для прикрепления множества файлов к одному документу' AUTO_INCREMENT=1747 ;

-- --------------------------------------------------------

--
-- Структура таблицы `lanch`
--

CREATE TABLE IF NOT EXISTS `lanch` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ldate` date NOT NULL DEFAULT '0000-00-00' COMMENT 'Дата запуска',
  `edate` date NOT NULL DEFAULT '0000-00-00' COMMENT 'Дата сдачи заказчику',
  `block_id` bigint(20) unsigned NOT NULL COMMENT 'идентификатор блока',
  `part` int(11) NOT NULL DEFAULT '0' COMMENT 'Номер партии',
  `numbz` int(11) NOT NULL DEFAULT '0' COMMENT 'Количество заготовок',
  `numbp` int(11) NOT NULL DEFAULT '0' COMMENT 'Количество плат',
  `comment_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'Идентификатор коментария',
  `file_link_id` bigint(10) NOT NULL DEFAULT '0' COMMENT 'Файл сопроводительного листа',
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'Кто запускал',
  `pos_in_tz` int(1) NOT NULL DEFAULT '0' COMMENT 'Номер позиции (не идентификатор) DEPRECATED (работала в комплекте с  идентификатором ТЗ, сейчас только идетификатор позиции))',
  `tz_id` bigint(10) NOT NULL DEFAULT '0' COMMENT 'DEPRECATED (достаточно идентификатора позиции в ТЗ)',
  `pos_in_tz_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'Какую позицию запускаем',
  PRIMARY KEY (`id`),
  KEY `pos_in_tz` (`pos_in_tz`),
  KEY `fk_lanch_user` (`user_id`),
  KEY `edate` (`edate`),
  KEY `block_id` (`block_id`),
  KEY `pos_in_tz_id` (`pos_in_tz_id`),
  KEY `tz_id` (`tz_id`),
  KEY `file_link_id` (`file_link_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Заупски' AUTO_INCREMENT=17031 ;

-- --------------------------------------------------------

--
-- Структура таблицы `lanched`
--

CREATE TABLE IF NOT EXISTS `lanched` (
  `block_id` bigint(20) unsigned NOT NULL COMMENT 'Идентификатор блока',
  `lastdate` date DEFAULT NULL,
  KEY `fk_lanched_blocks` (`block_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Мастерплаты' AUTO_INCREMENT=1062 ;

-- --------------------------------------------------------

--
-- Структура таблицы `move_in_production`
--

CREATE TABLE IF NOT EXISTS `move_in_production` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор',
  `lanch_id` bigint(20) unsigned NOT NULL COMMENT 'Идентификатор СЛ',
  `blocktype` enum('dpp','mpp') NOT NULL DEFAULT 'dpp' COMMENT 'Тип блока для двух журналов',
  `lastoperation` tinyint(4) DEFAULT NULL COMMENT 'id последней операции или -1  для завершенных-удаленных',
  `action_date` date DEFAULT NULL COMMENT 'Дата последней операции',
  `coment_id` bigint(20) unsigned NOT NULL COMMENT 'Коментарий: JSON с операциями',
  PRIMARY KEY (`id`),
  UNIQUE KEY `lanch_id` (`lanch_id`),
  KEY `ad_index` (`action_date`),
  KEY `fk_mip_lanch` (`lanch_id`),
  KEY `fk_mip_operation` (`lastoperation`),
  KEY `coment_id` (`coment_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Позиция платы в запуске' AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Структура таблицы `operations`
--

CREATE TABLE IF NOT EXISTS `operations` (
  `id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Ижентификатор операции',
  `block_type` enum('dpp','mpp','both') NOT NULL COMMENT 'Для каких плат операция',
  `operation` varchar(50) NOT NULL COMMENT 'Название операции',
  `shortname` varchar(10) NOT NULL COMMENT 'Короткое имя для заголовка',
  `priority` tinyint(3) unsigned zerofill NOT NULL COMMENT 'Приоритет операции - расположение в таблице',
  PRIMARY KEY (`id`),
  KEY `block_type` (`block_type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Таблица операций которые проходит плата' AUTO_INCREMENT=27 ;

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `orderdate` date NOT NULL DEFAULT '0000-00-00',
  `number` varchar(50) NOT NULL DEFAULT '',
  `customer_id` bigint(10) NOT NULL DEFAULT '0',
  `filelink` bigint(10) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`),
  KEY `filelink` (`filelink`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Заказы' AUTO_INCREMENT=2929 ;

-- --------------------------------------------------------

--
-- Структура таблицы `phototemplates`
--

CREATE TABLE IF NOT EXISTS `phototemplates` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `filenames` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Фотошаблоны отправленные на рисование' AUTO_INCREMENT=10980 ;

-- --------------------------------------------------------

--
-- Структура таблицы `posintz`
--

CREATE TABLE IF NOT EXISTS `posintz` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tz_id` bigint(10) NOT NULL DEFAULT '0',
  `posintz` enum('1','2','3','4','5','6') NOT NULL DEFAULT '1',
  `block_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `numbers` varchar(60) NOT NULL DEFAULT '0',
  `first` tinyint(1) NOT NULL DEFAULT '0',
  `srok` int(11) NOT NULL DEFAULT '0',
  `priem` varchar(40) NOT NULL DEFAULT 'ОТК',
  `constr` int(11) NOT NULL DEFAULT '0',
  `template_check` int(11) NOT NULL DEFAULT '0',
  `template_make` int(11) NOT NULL DEFAULT '0',
  `mask` tinyint(1) DEFAULT NULL,
  `mark` tinyint(1) DEFAULT NULL,
  `eltest` tinyint(1) NOT NULL DEFAULT '0',
  `numpl1` int(11) NOT NULL DEFAULT '0' COMMENT 'Количество в заказе позиции в блоке 1',
  `numpl2` int(11) NOT NULL COMMENT 'Количество в заказе позиции в блоке 2',
  `numpl3` int(11) NOT NULL COMMENT 'Количество в заказе позиции в блоке 3',
  `numpl4` int(11) NOT NULL COMMENT 'Количество в заказе позиции в блоке 4',
  `numpl5` int(11) NOT NULL COMMENT 'Количество в заказе позиции в блоке 5',
  `numpl6` int(11) NOT NULL COMMENT 'Количество в заказе позиции в блоке 6',
  `numbl` int(11) NOT NULL DEFAULT '0',
  `ldate` date NOT NULL DEFAULT '0000-00-00',
  `comment_id` bigint(10) NOT NULL DEFAULT '0',
  `pitz_mater` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ldate` (`ldate`),
  KEY `comment_id` (`comment_id`),
  KEY `tz_id` (`tz_id`),
  KEY `block_id` (`block_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Позиции в ТЗ' AUTO_INCREMENT=14484 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Таблица прав доступа к управлению базой' AUTO_INCREMENT=7827 ;

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

-- --------------------------------------------------------

--
-- Структура таблицы `rtypes`
--

CREATE TABLE IF NOT EXISTS `rtypes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `type` (`type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Типы для доступа' AUTO_INCREMENT=96 ;

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

-- --------------------------------------------------------

--
-- Структура таблицы `todo`
--

CREATE TABLE IF NOT EXISTS `todo` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `what` text NOT NULL,
  `cts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `rts` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `u_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `u_id` (`u_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Что сделать таблица' AUTO_INCREMENT=41 ;

-- --------------------------------------------------------

--
-- Структура таблицы `tz`
--

CREATE TABLE IF NOT EXISTS `tz` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `order_id` bigint(10) NOT NULL DEFAULT '0',
  `tz_date` date NOT NULL DEFAULT '0000-00-00',
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `pos_in_order` tinyint(4) NOT NULL DEFAULT '1',
  `file_link_id` bigint(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `user_id` (`user_id`),
  KEY `file_link_id` (`file_link_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Технические задания' AUTO_INCREMENT=5783 ;

-- --------------------------------------------------------

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Пользватели базы данных' AUTO_INCREMENT=29 ;

-- --------------------------------------------------------

--
-- Структура таблицы `users__settings_types`
--

CREATE TABLE IF NOT EXISTS `users__settings_types` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `key` varchar(50) NOT NULL COMMENT 'Название типа',
  `description` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Структура таблицы `workers`
--

CREATE TABLE IF NOT EXISTS `workers` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `tabn` varchar(10) DEFAULT NULL,
  `stat` enum('stat','sovm1','sovm2') NOT NULL DEFAULT 'stat',
  `fio` varchar(50) NOT NULL DEFAULT '',
  `f` varchar(20) DEFAULT NULL,
  `i` varchar(20) DEFAULT NULL,
  `o` varchar(20) DEFAULT NULL,
  `dolz` varchar(50) DEFAULT NULL,
  `doli` varchar(50) DEFAULT NULL,
  `dr` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`id`),
  KEY `fio` (`fio`,`f`,`i`,`o`,`dr`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Рабочие с днями рождения' AUTO_INCREMENT=115 ;

-- --------------------------------------------------------

--
-- Структура таблицы `zadel`
--

CREATE TABLE IF NOT EXISTS `zadel` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `board_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'Плата',
  `ldate` date NOT NULL DEFAULT '0000-00-00',
  `niz` varchar(10) NOT NULL DEFAULT '',
  `number` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `board_id` (`board_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Задел в ОТК' AUTO_INCREMENT=5204 ;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `blockpos`
--
ALTER TABLE `blockpos`
  ADD CONSTRAINT `blockpos_ibfk_1` FOREIGN KEY (`board_id`) REFERENCES `boards` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `blockpos_ibfk_2` FOREIGN KEY (`block_id`) REFERENCES `blocks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `blocks`
--
ALTER TABLE `blocks`
  ADD CONSTRAINT `blocks_ibfk_1` FOREIGN KEY (`comment_id`) REFERENCES `coments` (`id`),
  ADD CONSTRAINT `fk_blocks_customers` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `boards`
--
ALTER TABLE `boards`
  ADD CONSTRAINT `boards_ibfk_1` FOREIGN KEY (`comment_id`) REFERENCES `coments` (`id`),
  ADD CONSTRAINT `fk_boards_customers` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`coment_id`) REFERENCES `coments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `files_ibfk_1` FOREIGN KEY (`fileid`) REFERENCES `filelinks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `lanch`
--
ALTER TABLE `lanch`
  ADD CONSTRAINT `fk_lanch_filelink` FOREIGN KEY (`file_link_id`) REFERENCES `filelinks` (`id`),
  ADD CONSTRAINT `fk_lanch_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `lanch_ibfk_1` FOREIGN KEY (`pos_in_tz_id`) REFERENCES `posintz` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `lanched`
--
ALTER TABLE `lanched`
  ADD CONSTRAINT `fk_lanched_blocks` FOREIGN KEY (`block_id`) REFERENCES `blocks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `masterplate`
--
ALTER TABLE `masterplate`
  ADD CONSTRAINT `masterplate_ibfk_1` FOREIGN KEY (`block_id`) REFERENCES `blocks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `masterplate_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `masterplate_ibfk_3` FOREIGN KEY (`posid`) REFERENCES `posintz` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `move_in_production`
--
ALTER TABLE `move_in_production`
  ADD CONSTRAINT `fk_mip_lanch` FOREIGN KEY (`lanch_id`) REFERENCES `lanch` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`filelink`) REFERENCES `filelinks` (`id`);

--
-- Ограничения внешнего ключа таблицы `phototemplates`
--
ALTER TABLE `phototemplates`
  ADD CONSTRAINT `phototemplates_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `posintz`
--
ALTER TABLE `posintz`
  ADD CONSTRAINT `fk_posintz_blocks` FOREIGN KEY (`block_id`) REFERENCES `blocks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `posintz_ibfk_1` FOREIGN KEY (`tz_id`) REFERENCES `tz` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `rights`
--
ALTER TABLE `rights`
  ADD CONSTRAINT `fk_rtype` FOREIGN KEY (`rtype_id`) REFERENCES `rrtypes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_type` FOREIGN KEY (`type_id`) REFERENCES `rtypes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_users` FOREIGN KEY (`u_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `todo`
--
ALTER TABLE `todo`
  ADD CONSTRAINT `todo_ibfk_1` FOREIGN KEY (`u_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `tz`
--
ALTER TABLE `tz`
  ADD CONSTRAINT `tz_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tz_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `tz_ibfk_3` FOREIGN KEY (`file_link_id`) REFERENCES `filelinks` (`id`);

--
-- Ограничения внешнего ключа таблицы `zadel`
--
ALTER TABLE `zadel`
  ADD CONSTRAINT `zadel_ibfk_1` FOREIGN KEY (`board_id`) REFERENCES `boards` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
