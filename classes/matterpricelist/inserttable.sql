-- phpMyAdmin SQL Dump
-- version 4.9.6
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Янв 13 2022 г., 12:59
-- Версия сервера: 10.3.28-MariaDB
-- Версия PHP: 7.2.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `dev`
--

-- --------------------------------------------------------

--
-- Структура таблицы `calc__matter`
--

CREATE TABLE `calc__matter` (
  `id` int(11) NOT NULL,
  `matter_name` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT 'Наименование материала',
  `matter_unit` varchar(10) CHARACTER SET utf8 NOT NULL COMMENT 'Единицы измерения',
  `matter_factor` float NOT NULL COMMENT 'Коэффициэнт перевода в нормальные единицы'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Справочник материалов для расчета';

-- --------------------------------------------------------

--
-- Структура таблицы `calc__matter_pricelist`
--

CREATE TABLE `calc__matter_pricelist` (
  `id` int(11) NOT NULL,
  `record_date` datetime NOT NULL COMMENT 'Дата записи',
  `matter_type_id` int(11) NOT NULL COMMENT 'ИД типа из справочника',
  `matter_name_id` int(11) NOT NULL COMMENT 'ИД типа из справочника',
  `matter_price` float NOT NULL COMMENT 'Цена в рублях',
  `invoice` varchar(500) CHARACTER SET utf8 NOT NULL COMMENT 'Накладная/приходный ордер',
  `supplier_id` int(11) NOT NULL COMMENT 'ИД поставщика из справочника',
  `change_act` varchar(500) CHARACTER SET utf8 NOT NULL COMMENT 'Акт перевода наименованийй и единиц',
  `coment` varchar(500) CHARACTER SET utf8 NOT NULL COMMENT 'Коментарий'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Список цен на материалы';

-- --------------------------------------------------------

--
-- Структура таблицы `calc__supplier`
--

CREATE TABLE `calc__supplier` (
  `id` int(11) NOT NULL,
  `suppplier_name` varchar(500) CHARACTER SET utf8 NOT NULL COMMENT 'Наименование поставщика',
  `supplier_shortname` varchar(50) CHARACTER SET utf8 NOT NULL COMMENT 'Короткое наименование для списка выборов'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Справочник поставщиков';

-- --------------------------------------------------------

--
-- Структура таблицы `calc__types`
--

CREATE TABLE `calc__types` (
  `id` int(11) NOT NULL,
  `type` varchar(255) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Типы материалов для расчета (там около шести типов)';

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `calc__matter`
--
ALTER TABLE `calc__matter`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `calc__matter_pricelist`
--
ALTER TABLE `calc__matter_pricelist`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `calc__supplier`
--
ALTER TABLE `calc__supplier`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `calc__types`
--
ALTER TABLE `calc__types`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `calc__matter`
--
ALTER TABLE `calc__matter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `calc__matter_pricelist`
--
ALTER TABLE `calc__matter_pricelist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `calc__supplier`
--
ALTER TABLE `calc__supplier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `calc__types`
--
ALTER TABLE `calc__types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
