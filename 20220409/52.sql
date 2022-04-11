-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2022-04-09 09:29:10
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
  `account` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `groups` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 傾印資料表的資料 `user`
--

INSERT INTO `user` (`id`, `account`, `password`, `groups`) VALUES
(1, 'admin', '1234', 'admin'),
(2, 'abcd', '1234', 'user');

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
(1, 1, '密碼有誤', '2022-04-09 10:40:28'),
(2, 1, '圖形驗證碼有誤', '2022-04-09 10:40:30'),
(3, 1, '登入成功', '2022-04-09 10:40:49'),
(4, 1, '登入成功', '2022-04-09 10:44:19'),
(5, 1, '登入成功', '2022-04-09 11:16:39'),
(6, 2, '登入成功', '2022-04-09 11:24:11'),
(7, 1, '圖形驗證碼有誤', '2022-04-09 12:46:07'),
(8, 1, '圖形驗證碼有誤', '2022-04-09 12:46:08'),
(9, 1, '圖形驗證碼有誤', '2022-04-09 12:46:09'),
(10, 1, '登入成功', '2022-04-09 12:46:40'),
(11, 2, '登入成功', '2022-04-09 12:47:59'),
(12, 2, '密碼有誤', '2022-04-09 14:59:40'),
(13, 2, '圖形驗證碼有誤', '2022-04-09 15:00:06'),
(14, 2, '登入成功', '2022-04-09 15:00:28'),
(15, 2, '登入成功', '2022-04-09 15:01:23'),
(16, 1, '登入成功', '2022-04-09 15:13:15');

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
  `speed` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 傾印資料表的資料 `works`
--

INSERT INTO `works` (`id`, `userid`, `name`, `date`, `startTime`, `endTime`, `speed`, `status`, `description`) VALUES
(1, 2, 'bla', '2022-04-09', 0, 10, 'a', 'b', 'blabla'),
(2, 2, 'dadad', '2022-04-09', 9, 17, 'b', 'b', 'asdasd');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `userlog`
--
ALTER TABLE `userlog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `works`
--
ALTER TABLE `works`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
