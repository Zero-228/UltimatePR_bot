-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Июл 28 2024 г., 14:42
-- Версия сервера: 10.4.32-MariaDB
-- Версия PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `ultimatepr_bot`
--

-- --------------------------------------------------------

--
-- Структура таблицы `capcha`
--

CREATE TABLE `capcha` (
  `userId` bigint(20) NOT NULL,
  `chanelId` bigint(25) NOT NULL,
  `status` varchar(9) NOT NULL COMMENT '(pending/approved/failed)',
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `chanel`
--

CREATE TABLE `chanel` (
  `chanelId` bigint(25) NOT NULL,
  `title` varchar(250) NOT NULL,
  `users` int(11) DEFAULT NULL,
  `username` varchar(250) NOT NULL,
  `type` varchar(12) NOT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'active',
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `chanel_log`
--

CREATE TABLE `chanel_log` (
  `logId` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `entity` varchar(20) NOT NULL COMMENT '(user/bot/etc.)',
  `entityId` bigint(20) NOT NULL,
  `chanelId` bigint(25) NOT NULL,
  `context` varchar(254) NOT NULL COMMENT '(message/command/..)',
  `message` text NOT NULL,
  `status` varchar(15) NOT NULL COMMENT '(active/edited/deleted/..)',
  `messageId` bigint(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `chanel_settings`
--

CREATE TABLE `chanel_settings` (
  `chanelId` bigint(25) NOT NULL,
  `subscription` varchar(3) NOT NULL DEFAULT 'off' COMMENT '(on/off)',
  `unlocked` varchar(6) NOT NULL DEFAULT 'no' COMMENT '(no/yes/payed)',
  `access` varchar(7) NOT NULL DEFAULT 'admin' COMMENT '(creator/admin)',
  `capcha` varchar(3) NOT NULL DEFAULT 'off' COMMENT '(on/off)',
  `antispam` varchar(3) NOT NULL DEFAULT 'off' COMMENT '(on/off)',
  `antiflood` varchar(3) NOT NULL DEFAULT 'off' COMMENT '(on/off)',
  `antibot` varchar(3) NOT NULL DEFAULT 'off' COMMENT '(on/off)',
  `antilink` varchar(3) NOT NULL DEFAULT 'off' COMMENT '(on/off)',
  `statistics` varchar(8) NOT NULL DEFAULT 'standart' COMMENT '(standart/payed)',
  `timedMessages` int(11) NOT NULL DEFAULT 3 COMMENT '(quantity of avaible messages)',
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `log`
--

CREATE TABLE `log` (
  `logId` int(11) NOT NULL,
  `createdAt` datetime NOT NULL,
  `entity` varchar(15) NOT NULL COMMENT '(user/bot/admin/..)',
  `entityId` bigint(20) NOT NULL,
  `context` varchar(15) NOT NULL COMMENT '(callback/comand/..)',
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `subscription`
--

CREATE TABLE `subscription` (
  `id` int(11) NOT NULL,
  `chanelFrom` bigint(25) NOT NULL,
  `status` varchar(12) NOT NULL COMMENT '(active/done/deleted..)',
  `chanelTo` bigint(25) NOT NULL,
  `timer` varchar(255) DEFAULT NULL,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `support`
--

CREATE TABLE `support` (
  `id` int(11) NOT NULL,
  `userId` bigint(20) NOT NULL,
  `message` text NOT NULL,
  `status` varchar(12) NOT NULL COMMENT '(active/on progress/closed)',
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `timed_message`
--

CREATE TABLE `timed_message` (
  `id` int(11) NOT NULL,
  `chanelId` bigint(25) NOT NULL,
  `msg` text NOT NULL,
  `status` varchar(12) NOT NULL COMMENT '(on/off/deleted)',
  `timer` varchar(12) NOT NULL COMMENT '(3min/5min/10min/...)',
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `userId` bigint(20) NOT NULL,
  `firstName` varchar(30) NOT NULL,
  `lastName` varchar(30) NOT NULL,
  `username` varchar(60) NOT NULL,
  `startedBot` tinyint(1) NOT NULL DEFAULT 0,
  `language` varchar(2) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `lastVisit` timestamp NOT NULL DEFAULT current_timestamp(),
  `registeredAt` datetime NOT NULL,
  `role` varchar(7) NOT NULL DEFAULT 'user' COMMENT '(user/moder/admin)',
  `deleted` varchar(3) NOT NULL DEFAULT 'no',
  `banned` varchar(3) NOT NULL DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users_in_chanels`
--

CREATE TABLE `users_in_chanels` (
  `id` int(11) NOT NULL,
  `userId` bigint(20) NOT NULL,
  `chanelId` bigint(25) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'user',
  `status` varchar(12) NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE ultimatepr_bot_db.payment (
	`paymentId` INT auto_increment NOT NULL,
	`userId` BIGINT NOT NULL,
	`status` varchar(10) NOT NULL,
	`amount` FLOAT NOT NULL,
  `description` TEXT NOT NULL,
	`created_at` DATETIME NOT NULL,
	`updated_at` DATETIME NOT NULL,
  CONSTRAINT payment_pk PRIMARY KEY (paymentId)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `capcha`
--
ALTER TABLE `capcha`
  ADD UNIQUE KEY `ID` (`userId`,`chanelId`) USING BTREE,
  ADD KEY `capcha_ibfk_2` (`chanelId`);

--
-- Индексы таблицы `chanel`
--
ALTER TABLE `chanel`
  ADD PRIMARY KEY (`chanelId`);

--
-- Индексы таблицы `chanel_log`
--
ALTER TABLE `chanel_log`
  ADD PRIMARY KEY (`logId`);

--
-- Индексы таблицы `chanel_settings`
--
ALTER TABLE `chanel_settings`
  ADD PRIMARY KEY (`chanelId`);

--
-- Индексы таблицы `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`logId`),
  ADD KEY `entityId` (`entityId`);

--
-- Индексы таблицы `subscription`
--
ALTER TABLE `subscription`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subs_ibfk_1` (`chanelFrom`),
  ADD KEY `subs_ibfk_2` (`chanelTo`);

--
-- Индексы таблицы `support`
--
ALTER TABLE `support`
  ADD PRIMARY KEY (`id`),
  ADD KEY `support_ibfk_1` (`userId`);

--
-- Индексы таблицы `timed_message`
--
ALTER TABLE `timed_message`
  ADD PRIMARY KEY (`id`),
  ADD KEY `timedMsg_ibfk_1` (`chanelId`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userId`);

--
-- Индексы таблицы `users_in_chanels`
--
ALTER TABLE `users_in_chanels`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bound_ibfk_1` (`chanelId`),
  ADD KEY `bound_ibfk_2` (`userId`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `chanel_log`
--
ALTER TABLE `chanel_log`
  MODIFY `logId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `log`
--
ALTER TABLE `log`
  MODIFY `logId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `subscription`
--
ALTER TABLE `subscription`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `support`
--
ALTER TABLE `support`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `timed_message`
--
ALTER TABLE `timed_message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users_in_chanels`
--
ALTER TABLE `users_in_chanels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `capcha`
--
ALTER TABLE `capcha`
  ADD CONSTRAINT `capcha_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `user` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `capcha_ibfk_2` FOREIGN KEY (`chanelId`) REFERENCES `chanel` (`chanelId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `chanel_settings`
--
ALTER TABLE `chanel_settings`
  ADD CONSTRAINT `settings_ibfk_1` FOREIGN KEY (`chanelId`) REFERENCES `chanel` (`chanelId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `subscription`
--
ALTER TABLE `subscription`
  ADD CONSTRAINT `subs_ibfk_1` FOREIGN KEY (`chanelFrom`) REFERENCES `chanel` (`chanelId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `subs_ibfk_2` FOREIGN KEY (`chanelTo`) REFERENCES `chanel` (`chanelId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `support`
--
ALTER TABLE `support`
  ADD CONSTRAINT `support_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `user` (`userId`);

--
-- Ограничения внешнего ключа таблицы `timed_message`
--
ALTER TABLE `timed_message`
  ADD CONSTRAINT `timedMsg_ibfk_1` FOREIGN KEY (`chanelId`) REFERENCES `chanel` (`chanelId`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `users_in_chanels`
--
ALTER TABLE `users_in_chanels`
  ADD CONSTRAINT `bound_ibfk_2` FOREIGN KEY (`userId`) REFERENCES `user` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `users_in_chanels_ibfk_1` FOREIGN KEY (`chanelId`) REFERENCES `chanel` (`chanelId`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
