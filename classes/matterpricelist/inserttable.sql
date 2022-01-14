-- phpMyAdmin SQL Dump
-- version 4.9.6
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Янв 14 2022 г., 09:30
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
-- Структура таблицы `calc__matters`
--

CREATE TABLE `calc__matters` (
  `id` int(11) NOT NULL,
  `matter_name` varchar(255) NOT NULL COMMENT 'Наименование материала',
  `matter_unit` varchar(10) NOT NULL COMMENT 'Единицы измерения',
  `matter_factor` float NOT NULL DEFAULT 1 COMMENT 'Коэффициэнт перевода в нормальные единицы'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Справочник материалов для расчета';

-- --------------------------------------------------------

--
-- Структура таблицы `calc__matter_pricelist`
--

CREATE TABLE `calc__matter_pricelist` (
  `id` int(11) NOT NULL,
  `record_date` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'Дата записи',
  `matter_type_id` int(11) NOT NULL COMMENT 'ИД типа из справочника',
  `matter_name_id` int(11) NOT NULL COMMENT 'ИД типа из справочника',
  `discharge_norm_in` float NOT NULL DEFAULT 0 COMMENT 'Норма расхода на внутреннние слои',
  `discharge_norm_out` float NOT NULL DEFAULT 0 COMMENT 'Норма расхода на наружные слои',
  `matter_price` float NOT NULL DEFAULT 0 COMMENT 'Цена в рублях',
  `invoice` varchar(500) DEFAULT NULL COMMENT 'Накладная/приходный ордер',
  `supplier_id` int(11) NOT NULL COMMENT 'ИД поставщика из справочника',
  `change_act` varchar(500) DEFAULT NULL COMMENT 'Акт перевода наименованийй и единиц',
  `coment` varchar(500) DEFAULT NULL COMMENT 'Коментарий'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Список цен на материалы';

-- --------------------------------------------------------

--
-- Структура таблицы `calc__suppliers`
--

CREATE TABLE `calc__suppliers` (
  `id` int(11) NOT NULL,
  `supplier_name` varchar(500) NOT NULL COMMENT 'Наименование поставщика',
  `supplier_shortname` varchar(50) NOT NULL COMMENT 'Короткое наименование для списка выборов'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Справочник поставщиков';

-- --------------------------------------------------------

--
-- Структура таблицы `calc__types`
--

CREATE TABLE `calc__types` (
  `id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Типы материалов для расчета (там около шести типов)';

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `calc__matters`
--
ALTER TABLE `calc__matters`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `calc__matter_pricelist`
--
ALTER TABLE `calc__matter_pricelist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `matter_name_id` (`matter_name_id`),
  ADD KEY `matter_type_id` (`matter_type_id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Индексы таблицы `calc__suppliers`
--
ALTER TABLE `calc__suppliers`
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
-- AUTO_INCREMENT для таблицы `calc__matters`
--
ALTER TABLE `calc__matters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `calc__matter_pricelist`
--
ALTER TABLE `calc__matter_pricelist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `calc__suppliers`
--
ALTER TABLE `calc__suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `calc__types`
--
ALTER TABLE `calc__types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `calc__matter_pricelist`
--
ALTER TABLE `calc__matter_pricelist`
  ADD CONSTRAINT `calc__matter_pricelist_ibfk_1` FOREIGN KEY (`matter_name_id`) REFERENCES `calc__matters` (`id`),
  ADD CONSTRAINT `calc__matter_pricelist_ibfk_2` FOREIGN KEY (`matter_type_id`) REFERENCES `calc__types` (`id`),
  ADD CONSTRAINT `calc__matter_pricelist_ibfk_3` FOREIGN KEY (`supplier_id`) REFERENCES `calc__suppliers` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
