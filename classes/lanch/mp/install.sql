-- phpMyAdmin SQL Dump
-- version 4.9.6
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Окт 12 2020 г., 06:02
-- Версия сервера: 10.3.17-MariaDB
-- Версия PHP: 7.2.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- База данных: `dev`
--

-- --------------------------------------------------------

--
-- Структура таблицы `masterplate`
--

DROP TABLE IF EXISTS `masterplate`;
CREATE TABLE `masterplate` (
  `id` bigint(10) NOT NULL,
  `mpdate` date NOT NULL DEFAULT '0000-00-00',
  `user_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `posid` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Идентификатор позиции в ТЗ',
  `block_id` bigint(20) UNSIGNED NOT NULL COMMENT 'Идентификатор блока'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Мастерплаты';

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `masterplate`
--
ALTER TABLE `masterplate`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`block_id`),
  ADD KEY `mpdate` (`mpdate`),
  ADD KEY `block_id` (`block_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `posid` (`posid`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `masterplate`
--
ALTER TABLE `masterplate`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `masterplate`
--
ALTER TABLE `masterplate`
  ADD CONSTRAINT `masterplate_ibfk_1` FOREIGN KEY (`block_id`) REFERENCES `blocks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `masterplate_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `masterplate_ibfk_3` FOREIGN KEY (`posid`) REFERENCES `posintz` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;
