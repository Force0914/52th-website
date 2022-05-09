-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2022-04-16 08:49:48
-- 伺服器版本： 10.4.22-MariaDB
-- PHP 版本： 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫: `52`
--

-- --------------------------------------------------------

--
-- 資料表結構 `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `account` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `password` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `groups` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 傾印資料表的資料 `user`
--

INSERT INTO `user` (`id`, `name`, `account`, `password`, `groups`) VALUES
(1, '超級管理員', 'admin', '1234', 'admin'),
(2, '白癡使用者', 'abcd', '1234', 'user');

-- --------------------------------------------------------

--
-- 資料表結構 `userlog`
--

CREATE TABLE `userlog` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `action` varchar(50) NOT NULL,
  `time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 傾印資料表的資料 `userlog`
--

INSERT INTO `userlog` (`id`, `userid`, `action`, `time`) VALUES
(1, 1, '圖形驗證碼有誤', '2022-04-16 10:37:44'),
(2, 1, '登入成功', '2022-04-16 10:37:49'),
(3, 1, '登入成功', '2022-04-16 10:37:50'),
(4, 1, '登入成功', '2022-04-16 10:37:50'),
(5, 1, '登入成功', '2022-04-16 10:37:51'),
(6, 1, '圖形驗證碼有誤', '2022-04-16 10:50:55'),
(7, 1, '登入成功', '2022-04-16 10:51:53'),
(8, 1, '登出成功', '2022-04-16 12:01:20'),
(9, 2, '登入成功', '2022-04-16 12:01:30'),
(10, 2, '登入成功', '2022-04-16 12:28:33'),
(11, 2, '登入成功', '2022-04-16 13:15:37'),
(12, 1, '登入成功', '2022-04-16 13:16:05'),
(13, 1, '登出成功', '2022-04-16 13:16:11'),
(14, 2, '登入成功', '2022-04-16 13:16:19'),
(15, 2, '登入成功', '2022-04-16 13:17:13'),
(16, 2, '登入成功', '2022-04-16 13:18:30'),
(17, 1, '登入成功', '2022-04-16 13:18:58'),
(18, 2, '登入成功', '2022-04-16 13:19:26'),
(19, 2, '登入成功', '2022-04-16 13:20:09'),
(20, 2, '登出成功', '2022-04-16 13:28:23'),
(21, 1, '密碼有誤', '2022-04-16 13:28:35'),
(22, 1, '圖形驗證碼有誤', '2022-04-16 13:28:49'),
(23, 1, '登入成功', '2022-04-16 13:29:00'),
(24, 1, '登出成功', '2022-04-16 13:32:25'),
(25, 2, '登入成功', '2022-04-16 13:32:32'),
(26, 2, '登出成功', '2022-04-16 14:13:55'),
(27, 2, '密碼有誤', '2022-04-16 14:28:02'),
(28, 2, '圖形驗證碼有誤', '2022-04-16 14:28:21'),
(29, 2, '圖形驗證碼有誤', '2022-04-16 14:28:50'),
(30, 2, '登入成功', '2022-04-16 14:29:13'),
(31, 2, '登出成功', '2022-04-16 14:29:38'),
(32, 2, '登入成功', '2022-04-16 14:30:17'),
(33, 2, '登出成功', '2022-04-16 14:30:20'),
(34, 2, '圖形驗證碼有誤', '2022-04-16 14:30:31'),
(35, 2, '圖形驗證碼有誤', '2022-04-16 14:30:38'),
(36, 2, '登入成功', '2022-04-16 14:30:42'),
(37, 2, '登出成功', '2022-04-16 14:40:23'),
(38, 1, '登入成功', '2022-04-16 14:40:34'),
(39, 1, '登出成功', '2022-04-16 14:41:39'),
(45, 1, '登入成功', '2022-04-16 14:42:58'),
(46, 1, '登出成功', '2022-04-16 14:43:25'),
(49, 1, '登入成功', '2022-04-16 14:44:20'),
(50, 1, '登出成功', '2022-04-16 14:44:29'),
(51, 2, '圖形驗證碼有誤', '2022-04-16 14:46:44'),
(52, 2, '登入成功', '2022-04-16 14:46:49');

-- --------------------------------------------------------

--
-- 資料表結構 `works`
--

CREATE TABLE `works` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `startTime` int(11) NOT NULL,
  `endTime` int(11) NOT NULL,
  `status` varchar(50) NOT NULL,
  `speed` varchar(50) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 傾印資料表的資料 `works`
--

INSERT INTO `works` (`id`, `userid`, `name`, `date`, `startTime`, `endTime`, `status`, `speed`, `description`) VALUES
(1, 2, 'bla', '2022-04-16', 1, 12, 'b', 'a', 'blabla'),
(4, 2, 'bladd', '2022-04-16', 4, 5, 'b', 'a', 'blabladd'),
(13, 2, '明天的工作', '2022-04-17', 0, 24, 'a', 'a', '睡覺'),
(14, 2, 'asdadas', '2022-04-16', 6, 12, 'a', 'b', 'adasda'),
(15, 2, '', '2022-04-16', 1, 4, 'a', 'b', '');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `userlog`
--
ALTER TABLE `userlog`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userid` (`userid`);

--
-- 資料表索引 `works`
--
ALTER TABLE `works`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userid` (`userid`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `userlog`
--
ALTER TABLE `userlog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `works`
--
ALTER TABLE `works`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- 已傾印資料表的限制式
--

--
-- 資料表的限制式 `userlog`
--
ALTER TABLE `userlog`
  ADD CONSTRAINT `userlog_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 資料表的限制式 `works`
--
ALTER TABLE `works`
  ADD CONSTRAINT `works_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
