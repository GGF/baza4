-- phpMyAdmin SQL Dump
-- version 4.2.8
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Сен 13 2018 г., 11:35
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
CREATE DATABASE IF NOT EXISTS `zaompp` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `zaompp`;

-- --------------------------------------------------------

--
-- Структура таблицы `bash`
--

CREATE TABLE IF NOT EXISTS `bash` (
`id` bigint(10) NOT NULL,
  `quote` text NOT NULL,
  `rate` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=24759 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `blockpos`
--

CREATE TABLE IF NOT EXISTS `blockpos` (
`id` bigint(10) NOT NULL,
  `block_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'Блок',
  `board_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'Плата в блоке',
  `nib` smallint(4) NOT NULL DEFAULT '0' COMMENT 'Количество в блоке',
  `nx` enum('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','0') DEFAULT '0' COMMENT 'Количество по горизонтали',
  `ny` enum('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','0') DEFAULT '0' COMMENT 'Количество по вертикали'
) ENGINE=InnoDB AUTO_INCREMENT=42767 DEFAULT CHARSET=utf8 COMMENT='Позиции в блоках';

-- --------------------------------------------------------

--
-- Структура таблицы `blocks`
--

CREATE TABLE IF NOT EXISTS `blocks` (
`id` bigint(20) unsigned NOT NULL,
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
  `comment_id` bigint(20) unsigned NOT NULL DEFAULT '1' COMMENT 'Идентификатор комментария'
) ENGINE=InnoDB AUTO_INCREMENT=7827 DEFAULT CHARSET=utf8 COMMENT='Блоки плат';

-- --------------------------------------------------------

--
-- Структура таблицы `boards`
--

CREATE TABLE IF NOT EXISTS `boards` (
`id` bigint(20) unsigned NOT NULL,
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
  `comment_id` bigint(20) unsigned NOT NULL DEFAULT '1' COMMENT 'Коментарий'
) ENGINE=InnoDB AUTO_INCREMENT=7771 DEFAULT CHARSET=utf8 COMMENT='Даные по плате, только те что относятся к плате';

-- --------------------------------------------------------

--
-- Структура таблицы `coments`
--

CREATE TABLE IF NOT EXISTS `coments` (
`id` bigint(20) unsigned NOT NULL,
  `comment` longtext NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10478 DEFAULT CHARSET=utf8 COMMENT='Свалка коментариев к различным документам';

-- --------------------------------------------------------

--
-- Структура таблицы `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
`id` bigint(20) unsigned NOT NULL,
  `forobject` varchar(50) NOT NULL COMMENT 'Имя таблицы',
  `record_id` bigint(20) unsigned NOT NULL COMMENT 'Идентификатор записи в таблице',
  `coment_id` bigint(20) unsigned NOT NULL COMMENT 'Идентификатор комментария из coment',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Время комментария',
  `author_id` bigint(20) unsigned NOT NULL COMMENT 'Автор комментария'
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8 COMMENT='Хранит кросидентификаторы для коментариев к объектам (другим';

-- --------------------------------------------------------

--
-- Структура таблицы `conductors`
--

CREATE TABLE IF NOT EXISTS `conductors` (
`id` bigint(11) NOT NULL,
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
  `ready` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=115 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `customers`
--

CREATE TABLE IF NOT EXISTS `customers` (
`id` bigint(10) NOT NULL,
  `customer` varchar(40) NOT NULL DEFAULT '',
  `fullname` varchar(200) NOT NULL DEFAULT '',
  `kdir` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB AUTO_INCREMENT=184 DEFAULT CHARSET=utf8 COMMENT='Заказчики';

-- --------------------------------------------------------

--
-- Структура таблицы `filelinks`
--

CREATE TABLE IF NOT EXISTS `filelinks` (
`id` bigint(10) NOT NULL,
  `file_link` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=50596 DEFAULT CHARSET=utf8 COMMENT='Ссылки на файлы';

-- --------------------------------------------------------

--
-- Структура таблицы `files`
--

CREATE TABLE IF NOT EXISTS `files` (
`id` bigint(20) NOT NULL,
  `table` varchar(100) NOT NULL COMMENT 'Таблица к которой относится запись',
  `rec_id` bigint(20) NOT NULL COMMENT 'Идентификатор записи к которой относятся файлы',
  `fileid` bigint(10) NOT NULL COMMENT 'Идентификатор файловой ссылки из таблицы filelinks',
  `type` varchar(50) NOT NULL DEFAULT 'http' COMMENT 'тип ссылки, в принципе только вэб или шара'
) ENGINE=InnoDB AUTO_INCREMENT=6377 DEFAULT CHARSET=utf8 COMMENT='Для прикрепления множества файлов к одному документу';

-- --------------------------------------------------------

--
-- Структура таблицы `lanch`
--

CREATE TABLE IF NOT EXISTS `lanch` (
`id` bigint(20) unsigned NOT NULL,
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
  `pos_in_tz_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'Какую позицию запускаем'
) ENGINE=InnoDB AUTO_INCREMENT=33368 DEFAULT CHARSET=utf8 COMMENT='Заупски';

-- --------------------------------------------------------

--
-- Структура таблицы `lanched`
--

CREATE TABLE IF NOT EXISTS `lanched` (
  `block_id` bigint(20) unsigned NOT NULL COMMENT 'Идентификатор блока',
  `lastdate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `masterplate`
--

CREATE TABLE IF NOT EXISTS `masterplate` (
`id` bigint(10) NOT NULL,
  `mpdate` date NOT NULL DEFAULT '0000-00-00',
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `posid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'Идентификатор позиции в ТЗ',
  `block_id` bigint(20) unsigned NOT NULL COMMENT 'Идентификатор блока'
) ENGINE=InnoDB AUTO_INCREMENT=1930 DEFAULT CHARSET=utf8 COMMENT='Мастерплаты';

-- --------------------------------------------------------

--
-- Структура таблицы `moneyfororder`
--

CREATE TABLE IF NOT EXISTS `moneyfororder` (
`id` bigint(20) NOT NULL COMMENT 'идентификатор записи',
  `hash` varchar(32) NOT NULL COMMENT 'MD5 хэш от заказчика+заказ+материал+труд',
  `customer` varchar(250) NOT NULL COMMENT 'Заказчик текстовый',
  `order` varchar(250) NOT NULL COMMENT 'Заказ текстовый',
  `board` varchar(250) NOT NULL COMMENT 'Плата текстовая',
  `position` int(11) NOT NULL COMMENT 'позиция трудоемкости или материала',
  `mater` varchar(250) DEFAULT NULL COMMENT 'Материал текстовый',
  `matedizm` varchar(10) NOT NULL COMMENT 'единица измерения расхода материала',
  `matras` float(7,4) DEFAULT NULL COMMENT 'Расход материала',
  `matcost` float(8,2) DEFAULT NULL COMMENT 'Стоимость материала',
  `trud` varchar(250) DEFAULT NULL COMMENT 'Название операции текстовый',
  `trudem` float(7,4) DEFAULT NULL COMMENT 'Трудоемкость нормочасов',
  `trudcost` float(10,2) DEFAULT NULL COMMENT 'Стоимость труда'
) ENGINE=InnoDB AUTO_INCREMENT=203 DEFAULT CHARSET=utf8mb4 COMMENT='Заказ и Заказчик текстовые потому как заполняя расчет могут набрать по разному';

-- --------------------------------------------------------

--
-- Структура таблицы `move_in_production`
--

CREATE TABLE IF NOT EXISTS `move_in_production` (
`id` int(11) NOT NULL COMMENT 'Идентификатор',
  `lanch_id` bigint(20) unsigned NOT NULL COMMENT 'Идентификатор СЛ',
  `blocktype` enum('dpp','mpp') NOT NULL DEFAULT 'dpp' COMMENT 'Тип блока для двух журналов',
  `lastoperation` tinyint(4) DEFAULT NULL COMMENT 'id последней операции или -1  для завершенных-удаленных',
  `action_date` date DEFAULT NULL COMMENT 'Дата последней операции',
  `coment_id` bigint(20) unsigned NOT NULL COMMENT 'Коментарий: JSON с операциями'
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='Позиция платы в запуске';

-- --------------------------------------------------------

--
-- Структура таблицы `operations`
--

CREATE TABLE IF NOT EXISTS `operations` (
`id` tinyint(4) unsigned NOT NULL COMMENT 'Ижентификатор операции',
  `block_type` enum('dpp','mpp','both') NOT NULL COMMENT 'Для каких плат операция',
  `operation` varchar(50) NOT NULL COMMENT 'Название операции',
  `shortname` varchar(10) NOT NULL COMMENT 'Короткое имя для заголовка',
  `priority` tinyint(3) unsigned zerofill NOT NULL COMMENT 'Приоритет операции - расположение в таблице'
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COMMENT='Таблица операций которые проходит плата';

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
`id` bigint(10) NOT NULL,
  `orderdate` date NOT NULL DEFAULT '0000-00-00',
  `number` varchar(50) NOT NULL DEFAULT '',
  `customer_id` bigint(10) NOT NULL DEFAULT '0',
  `filelink` bigint(10) NOT NULL DEFAULT '1' COMMENT '13-09-2018  С сегодняшнего дня я буду использовать его как набор флагов для заказа. Пока ввожу единственный флаг, будет ли заказ запускаться в производство или только посчитается. Это для того чтобы почистить раздел незапущщеные. Если установлен второй бит, то это только расчет.'
) ENGINE=InnoDB AUTO_INCREMENT=5754 DEFAULT CHARSET=utf8 COMMENT='Заказы';

-- --------------------------------------------------------

--
-- Структура таблицы `phototemplates`
--

CREATE TABLE IF NOT EXISTS `phototemplates` (
`id` bigint(10) NOT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `filenames` longtext NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=17502 DEFAULT CHARSET=utf8 COMMENT='Фотошаблоны отправленные на рисование';

-- --------------------------------------------------------

--
-- Структура таблицы `posintz`
--

CREATE TABLE IF NOT EXISTS `posintz` (
`id` bigint(20) unsigned NOT NULL,
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
  `numpl1` int(11) NOT NULL DEFAULT '0',
  `numpl2` int(11) NOT NULL,
  `numpl3` int(11) NOT NULL,
  `numpl4` int(11) NOT NULL,
  `numpl5` int(11) NOT NULL,
  `numpl6` int(11) NOT NULL,
  `numbl` int(11) NOT NULL DEFAULT '0',
  `ldate` date NOT NULL DEFAULT '0000-00-00',
  `comment_id` bigint(10) NOT NULL DEFAULT '0',
  `pitz_mater` varchar(100) DEFAULT NULL,
  `mask` tinyint(1) DEFAULT NULL,
  `mark` tinyint(1) DEFAULT NULL,
  `eltest` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'DEPRICATED Ненесет информации для расчета и т.п.'
) ENGINE=InnoDB AUTO_INCREMENT=29337 DEFAULT CHARSET=utf8 COMMENT='Позиции в ТЗ';

-- --------------------------------------------------------

--
-- Структура таблицы `rights`
--

CREATE TABLE IF NOT EXISTS `rights` (
`id` bigint(20) NOT NULL,
  `u_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `type_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `rtype_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `right` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=8630 DEFAULT CHARSET=utf8 COMMENT='Таблица прав доступа к управлению базой';

-- --------------------------------------------------------

--
-- Структура таблицы `rrtypes`
--

CREATE TABLE IF NOT EXISTS `rrtypes` (
`id` bigint(20) unsigned NOT NULL,
  `rtype` varchar(50) NOT NULL DEFAULT '',
  `value` int(11) NOT NULL COMMENT 'Установленный бит'
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COMMENT='Типы доступа';

-- --------------------------------------------------------

--
-- Структура таблицы `rtypes`
--

CREATE TABLE IF NOT EXISTS `rtypes` (
`id` bigint(20) unsigned NOT NULL,
  `type` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB AUTO_INCREMENT=97 DEFAULT CHARSET=utf8 COMMENT='Типы для доступа';

-- --------------------------------------------------------

--
-- Структура таблицы `session`
--

CREATE TABLE IF NOT EXISTS `session` (
  `session` varchar(255) NOT NULL DEFAULT '',
  `u_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MEMORY DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `todo`
--

CREATE TABLE IF NOT EXISTS `todo` (
`id` bigint(20) NOT NULL,
  `what` text NOT NULL,
  `cts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `rts` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `u_id` bigint(20) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8 COMMENT='Что сделать таблица';

-- --------------------------------------------------------

--
-- Структура таблицы `tz`
--

CREATE TABLE IF NOT EXISTS `tz` (
`id` bigint(10) NOT NULL,
  `order_id` bigint(10) NOT NULL DEFAULT '0',
  `tz_date` date NOT NULL DEFAULT '0000-00-00',
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `pos_in_order` tinyint(4) NOT NULL DEFAULT '1',
  `file_link_id` bigint(10) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=13209 DEFAULT CHARSET=utf8 COMMENT='Технические задания';

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`id` bigint(20) unsigned NOT NULL,
  `nik` varchar(15) NOT NULL DEFAULT '',
  `fullname` varchar(50) NOT NULL DEFAULT '',
  `position` varchar(50) NOT NULL DEFAULT '',
  `password` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8 COMMENT='Пользватели базы данных';

-- --------------------------------------------------------

--
-- Структура таблицы `users__settings_types`
--

CREATE TABLE IF NOT EXISTS `users__settings_types` (
`id` tinyint(4) NOT NULL,
  `key` varchar(50) NOT NULL COMMENT 'Название типа',
  `description` varchar(250) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `workers`
--

CREATE TABLE IF NOT EXISTS `workers` (
`id` bigint(10) NOT NULL,
  `tabn` varchar(10) DEFAULT NULL,
  `stat` enum('stat','sovm1','sovm2') NOT NULL DEFAULT 'stat',
  `fio` varchar(50) NOT NULL DEFAULT '',
  `f` varchar(20) DEFAULT NULL,
  `i` varchar(20) DEFAULT NULL,
  `o` varchar(20) DEFAULT NULL,
  `dolz` varchar(50) DEFAULT NULL,
  `doli` varchar(50) DEFAULT NULL,
  `dr` date NOT NULL DEFAULT '0000-00-00'
) ENGINE=InnoDB AUTO_INCREMENT=418 DEFAULT CHARSET=utf8 COMMENT='Рабочие с днями рождения';

-- --------------------------------------------------------

--
-- Структура таблицы `zadel`
--

CREATE TABLE IF NOT EXISTS `zadel` (
`id` bigint(10) NOT NULL,
  `board_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'Плата',
  `ldate` date NOT NULL DEFAULT '0000-00-00',
  `niz` varchar(10) NOT NULL DEFAULT '',
  `number` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=10777 DEFAULT CHARSET=utf8 COMMENT='Задел в ОТК';

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `bash`
--
ALTER TABLE `bash`
 ADD PRIMARY KEY (`id`), ADD KEY `rate` (`rate`);

--
-- Индексы таблицы `blockpos`
--
ALTER TABLE `blockpos`
 ADD PRIMARY KEY (`id`), ADD KEY `fk_blockpos_blocks` (`block_id`), ADD KEY `fk_blockpos_boards` (`board_id`);

--
-- Индексы таблицы `blocks`
--
ALTER TABLE `blocks`
 ADD PRIMARY KEY (`id`), ADD KEY `blockname` (`blockname`), ADD KEY `smalldrill` (`smalldrill`,`bigdrill`), ADD KEY `customer_id` (`customer_id`), ADD KEY `comment_id` (`comment_id`);

--
-- Индексы таблицы `boards`
--
ALTER TABLE `boards`
 ADD PRIMARY KEY (`id`), ADD KEY `board_id` (`board_name`,`textolite`), ADD KEY `customer_id` (`customer_id`), ADD KEY `comment_id` (`comment_id`), ADD KEY `board_name` (`board_name`);

--
-- Индексы таблицы `coments`
--
ALTER TABLE `coments`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `comments`
--
ALTER TABLE `comments`
 ADD PRIMARY KEY (`id`), ADD KEY `forobject` (`forobject`,`record_id`,`coment_id`), ADD KEY `time` (`time`,`author_id`), ADD KEY `author_id` (`author_id`), ADD KEY `coment_id` (`coment_id`);

--
-- Индексы таблицы `conductors`
--
ALTER TABLE `conductors`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `customers`
--
ALTER TABLE `customers`
 ADD PRIMARY KEY (`id`), ADD KEY `customer` (`customer`);

--
-- Индексы таблицы `filelinks`
--
ALTER TABLE `filelinks`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `files`
--
ALTER TABLE `files`
 ADD PRIMARY KEY (`id`), ADD KEY `fileid` (`fileid`), ADD KEY `table` (`table`), ADD KEY `rec_id` (`rec_id`), ADD KEY `type` (`type`), ADD KEY `fileid_2` (`fileid`), ADD KEY `rec_id_2` (`rec_id`);

--
-- Индексы таблицы `lanch`
--
ALTER TABLE `lanch`
 ADD PRIMARY KEY (`id`), ADD KEY `pos_in_tz` (`pos_in_tz`), ADD KEY `fk_lanch_user` (`user_id`), ADD KEY `edate` (`edate`), ADD KEY `block_id` (`block_id`), ADD KEY `pos_in_tz_id` (`pos_in_tz_id`), ADD KEY `tz_id` (`tz_id`), ADD KEY `file_link_id` (`file_link_id`);

--
-- Индексы таблицы `lanched`
--
ALTER TABLE `lanched`
 ADD KEY `fk_lanched_blocks` (`block_id`);

--
-- Индексы таблицы `masterplate`
--
ALTER TABLE `masterplate`
 ADD PRIMARY KEY (`id`), ADD KEY `customer_id` (`block_id`), ADD KEY `mpdate` (`mpdate`), ADD KEY `block_id` (`block_id`), ADD KEY `user_id` (`user_id`), ADD KEY `posid` (`posid`);

--
-- Индексы таблицы `moneyfororder`
--
ALTER TABLE `moneyfororder`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `hash` (`hash`), ADD KEY `customer` (`customer`(191),`order`(191),`mater`(191),`trud`(191)), ADD KEY `board` (`board`(191)), ADD KEY `position` (`position`);

--
-- Индексы таблицы `move_in_production`
--
ALTER TABLE `move_in_production`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `lanch_id` (`lanch_id`), ADD KEY `ad_index` (`action_date`), ADD KEY `fk_mip_lanch` (`lanch_id`), ADD KEY `fk_mip_operation` (`lastoperation`), ADD KEY `coment_id` (`coment_id`);

--
-- Индексы таблицы `operations`
--
ALTER TABLE `operations`
 ADD PRIMARY KEY (`id`), ADD KEY `block_type` (`block_type`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
 ADD PRIMARY KEY (`id`), ADD KEY `customer_id` (`customer_id`), ADD KEY `filelink` (`filelink`);

--
-- Индексы таблицы `phototemplates`
--
ALTER TABLE `phototemplates`
 ADD PRIMARY KEY (`id`), ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `posintz`
--
ALTER TABLE `posintz`
 ADD PRIMARY KEY (`id`), ADD KEY `ldate` (`ldate`), ADD KEY `comment_id` (`comment_id`), ADD KEY `tz_id` (`tz_id`), ADD KEY `block_id` (`block_id`);

--
-- Индексы таблицы `rights`
--
ALTER TABLE `rights`
 ADD PRIMARY KEY (`id`), ADD KEY `fk_users` (`u_id`), ADD KEY `fk_rtype` (`rtype_id`), ADD KEY `fk_type` (`type_id`);

--
-- Индексы таблицы `rrtypes`
--
ALTER TABLE `rrtypes`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `rtypes`
--
ALTER TABLE `rtypes`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `type` (`type`);

--
-- Индексы таблицы `session`
--
ALTER TABLE `session`
 ADD PRIMARY KEY (`session`);

--
-- Индексы таблицы `todo`
--
ALTER TABLE `todo`
 ADD PRIMARY KEY (`id`), ADD KEY `u_id` (`u_id`);

--
-- Индексы таблицы `tz`
--
ALTER TABLE `tz`
 ADD PRIMARY KEY (`id`), ADD KEY `order_id` (`order_id`), ADD KEY `user_id` (`user_id`), ADD KEY `file_link_id` (`file_link_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `nik` (`nik`), ADD UNIQUE KEY `password` (`password`);

--
-- Индексы таблицы `users__settings_types`
--
ALTER TABLE `users__settings_types`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `workers`
--
ALTER TABLE `workers`
 ADD PRIMARY KEY (`id`), ADD KEY `fio` (`fio`,`f`,`i`,`o`,`dr`);

--
-- Индексы таблицы `zadel`
--
ALTER TABLE `zadel`
 ADD PRIMARY KEY (`id`), ADD KEY `board_id` (`board_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `bash`
--
ALTER TABLE `bash`
MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=24759;
--
-- AUTO_INCREMENT для таблицы `blockpos`
--
ALTER TABLE `blockpos`
MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=42767;
--
-- AUTO_INCREMENT для таблицы `blocks`
--
ALTER TABLE `blocks`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7827;
--
-- AUTO_INCREMENT для таблицы `boards`
--
ALTER TABLE `boards`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7771;
--
-- AUTO_INCREMENT для таблицы `coments`
--
ALTER TABLE `coments`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10478;
--
-- AUTO_INCREMENT для таблицы `comments`
--
ALTER TABLE `comments`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=70;
--
-- AUTO_INCREMENT для таблицы `conductors`
--
ALTER TABLE `conductors`
MODIFY `id` bigint(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=115;
--
-- AUTO_INCREMENT для таблицы `customers`
--
ALTER TABLE `customers`
MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=184;
--
-- AUTO_INCREMENT для таблицы `filelinks`
--
ALTER TABLE `filelinks`
MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=50596;
--
-- AUTO_INCREMENT для таблицы `files`
--
ALTER TABLE `files`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6377;
--
-- AUTO_INCREMENT для таблицы `lanch`
--
ALTER TABLE `lanch`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=33368;
--
-- AUTO_INCREMENT для таблицы `masterplate`
--
ALTER TABLE `masterplate`
MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1930;
--
-- AUTO_INCREMENT для таблицы `moneyfororder`
--
ALTER TABLE `moneyfororder`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'идентификатор записи',AUTO_INCREMENT=203;
--
-- AUTO_INCREMENT для таблицы `move_in_production`
--
ALTER TABLE `move_in_production`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор',AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT для таблицы `operations`
--
ALTER TABLE `operations`
MODIFY `id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Ижентификатор операции',AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5754;
--
-- AUTO_INCREMENT для таблицы `phototemplates`
--
ALTER TABLE `phototemplates`
MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17502;
--
-- AUTO_INCREMENT для таблицы `posintz`
--
ALTER TABLE `posintz`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=29337;
--
-- AUTO_INCREMENT для таблицы `rights`
--
ALTER TABLE `rights`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8630;
--
-- AUTO_INCREMENT для таблицы `rrtypes`
--
ALTER TABLE `rrtypes`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT для таблицы `rtypes`
--
ALTER TABLE `rtypes`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=97;
--
-- AUTO_INCREMENT для таблицы `todo`
--
ALTER TABLE `todo`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=41;
--
-- AUTO_INCREMENT для таблицы `tz`
--
ALTER TABLE `tz`
MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13209;
--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=64;
--
-- AUTO_INCREMENT для таблицы `users__settings_types`
--
ALTER TABLE `users__settings_types`
MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `workers`
--
ALTER TABLE `workers`
MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=418;
--
-- AUTO_INCREMENT для таблицы `zadel`
--
ALTER TABLE `zadel`
MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10777;
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
