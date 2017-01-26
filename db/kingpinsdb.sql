-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 15, 2017 at 04:34 PM
-- Server version: 5.7.14
-- PHP Version: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kingpinsdb`
--
CREATE DATABASE IF NOT EXISTS `kingpinsdb` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `kingpinsdb`;
-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `custphone` varchar(12) NOT NULL,
  `custfirstname` varchar(50) NOT NULL,
  `custlastname` varchar(100) NOT NULL,
  `custgroupcount` int(11) NOT NULL DEFAULT '1',
  `custenter` timestamp NOT NULL,
  `enter_empid` int(11) NOT NULL,
  `custexit` timestamp NULL DEFAULT NULL,
  `exit_empid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`custphone`, `custfirstname`, `custlastname`, `custgroupcount`, `custenter`, `enter_empid`, `custexit`, `exit_empid`) VALUES
('333-333-3333', 'HOWE', 'LANE', 1, '2017-01-09 23:17:44', 1, '2017-01-09 17:39:57', 6),
('732-355-1647', 'KEDURII', 'RAM', 1, '2017-01-10 22:37:16', 7, '2017-01-11 02:03:05', 7),
('888-999-0000', 'DUMBO', 'DUMBO', 5, '2017-01-15 15:54:23', 7, '2017-01-13 03:43:08', 7),
('888-999-0001', 'DUMBOO', 'DUMBO', 1, '2017-01-11 02:21:00', 7, '2017-01-11 02:21:53', 7),
('888-999-0001', 'GEGO', 'GEGOG', 1, '2017-01-15 15:53:45', 7, '2017-01-11 03:30:00', 7),
('444-454-4444', 'CUSTOMER', 'CUSTOMERLNAME', 3, '2017-01-15 15:55:01', 1, '2017-01-13 03:50:13', 7);

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(12) NOT NULL,
  `defaultgroup` int(11) NOT NULL DEFAULT '0',
  `joindate` timestamp NOT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT '1',
  `isManager` tinyint(1) NOT NULL DEFAULT '0',
  `updatedate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`id`, `username`, `password`, `firstname`, `lastname`, `email`, `phone`, `defaultgroup`, `joindate`, `isActive`, `isManager`, `updatedate`) VALUES
(1, 'admin', '$2y$10$27No9uQDYUbUxmWBcqK7g.rj/48QozIqIQrC8d5jxwLgzk/E2SaLa', 'ADMIN', 'ADMIN', 'admin@kingpins.com', '111-111-1111', 0, '2016-12-26 22:14:02', 1, 1, '2017-01-08 22:08:23'),
(2, 'Nikita', '$2y$10$sgww2xr6x6SkSYVxCw0qWO7dnWgcOwVjvThITsPsZMNLTtC.gtlU6', 'NIKITA', 'RAM', 'nram@yahoo.com', '222-333-4455', 1, '2017-01-15 16:04:09', 1, 0, '2017-01-15 15:58:17'),
(3, 'Vivek', '$2y$10$sgww2xr6x6SkSYVxCw0qWO7dnWgcOwVjvThITsPsZMNLTtC.gtlU6', 'VIVEK', 'RAM', 'vivekram@yahoo.com', '555-343-5678', 1, '2017-01-15 16:04:17', 1, 0, '2017-01-15 15:59:18'),
(4, 'SureshRam', '$2y$10$xXpISQThUqdS7UrJv7uvUOe.8qzbjEBPGEx9xY4ii2WGBpn3w3fGG', 'Suresh', 'Ram', 'kedarram@yahoo.com', '333-333-3333', 3, '2017-01-07 05:00:00', 1, 0, '2017-01-08 22:11:39'),
(5, 'JoJoKo', '$2y$10$sgww2xr6x6SkSYVxCw0qWO7dnWgcOwVjvThITsPsZMNLTtC.gtlU6', 'JOSEPH', 'KOCH', 'kochj@fakegmail.com', '232-343-4554', 1, '2017-01-15 16:04:28', 1, 0, '2017-01-15 16:00:43'),
(6, 'Kedar', '$2y$10$IgJqmmJBclyaSJ8SWBDIdepmOcMovS3TJxB0gS7Bv8pphd0cOvyA6', 'Kedar', 'Kedar', 'kedar21ram@gmail.com', '111-111-1111', 2, '2017-01-08 02:01:56', 1, 0, '2017-01-08 01:54:03'),
(7, 'notadmin', '$2y$10$rZr/.3D4O3JNVFjgBxAE.u/ALVEk4QheYNRAN3KdyDaZ6srnzdmpy', 'notadmin', 'notadmin', 'notadmin@gmai.com', '123-123-1234', 1, '2017-01-15 15:56:09', 1, 0, '2017-01-08 21:23:56'),
(8, 'Alice', '$2y$10$sgww2xr6x6SkSYVxCw0qWO7dnWgcOwVjvThITsPsZMNLTtC.gtlU6', 'ALICE', 'LUKE', 'aluke123@fakemail.com', '121-232-2345', 1, '2017-01-15 16:04:38', 1, 0, '2017-01-15 16:02:01'),
(9, 'ManagerOne', '$2y$10$sgww2xr6x6SkSYVxCw0qWO7dnWgcOwVjvThITsPsZMNLTtC.gtlU6', 'Manager', 'ShiftOne', 'mgrone@fakemail.com', '676-888-8989', 1, '2017-01-15 16:34:16', 1, 1, '2017-01-15 16:03:48'),
(10, 'ManagerTwo', '$2y$10$sgww2xr6x6SkSYVxCw0qWO7dnWgcOwVjvThITsPsZMNLTtC.gtlU6', 'Manager', 'Two', 'mgrTwo@fakemail.com', '656-767-6677', 2, '2017-01-15 16:05:59', 1, 1, '2017-01-15 16:05:59'),
(11, 'Jfrey', '$2y$10$sgww2xr6x6SkSYVxCw0qWO7dnWgcOwVjvThITsPsZMNLTtC.gtlU6', 'James', 'Frey', 'freyj@fakemail.com', '545-444-5645', 2, '2017-01-15 16:07:10', 1, 0, '2017-01-15 16:07:10'),
(12, 'Johnson', '$2y$10$sgww2xr6x6SkSYVxCw0qWO7dnWgcOwVjvThITsPsZMNLTtC.gtlU6', 'Jimmy', 'Johnson', 'jj@fakemail.com', '878-768-8765', 2, '2017-01-15 16:08:18', 1, 0, '2017-01-15 16:08:18'),
(13, 'Miles', '$2y$10$sgww2xr6x6SkSYVxCw0qWO7dnWgcOwVjvThITsPsZMNLTtC.gtlU6', 'Miles', 'Davis', 'mdavis@fakemail.com', '545-455-5544', 2, '2017-01-15 16:09:23', 1, 0, '2017-01-15 16:09:23'),
(14, 'ManagerThree', '$2y$10$sgww2xr6x6SkSYVxCw0qWO7dnWgcOwVjvThITsPsZMNLTtC.gtlU6', 'Manager', 'Three', 'mgrthree@fakemail.com', '333-434-2345', 3, '2017-01-15 16:10:41', 1, 1, '2017-01-15 16:10:31'),
(15, 'JohnCina', '$2y$10$sgww2xr6x6SkSYVxCw0qWO7dnWgcOwVjvThITsPsZMNLTtC.gtlU6', 'John', 'Cina', 'jcina@fakemail.com', '732-456-0987', 3, '2017-01-15 16:11:30', 1, 0, '2017-01-15 16:11:30'),
(16, 'benf', '$2y$10$sgww2xr6x6SkSYVxCw0qWO7dnWgcOwVjvThITsPsZMNLTtC.gtlU6', 'Benjamin', 'Franklin', 'bfrank@fakemail.com', '989-676-3443', 3, '2017-01-15 16:12:40', 1, 0, '2017-01-15 16:12:40'),
(17, 'Walter', '$2y$10$sgww2xr6x6SkSYVxCw0qWO7dnWgcOwVjvThITsPsZMNLTtC.gtlU6', 'Nicholas', 'Walter', 'walter@fakemail.com', '123-456-7890', 3, '2017-01-15 16:13:35', 1, 0, '2017-01-15 16:13:35'),
(18, 'Idli', '$2y$10$sgww2xr6x6SkSYVxCw0qWO7dnWgcOwVjvThITsPsZMNLTtC.gtlU6', 'Idli', 'Sambar', 'sambar@fakemail.com', '565-656-5656', 3, '2017-01-15 16:14:42', 1, 0, '2017-01-15 16:14:42'),
(19, 'Employee', '$2y$10$sgww2xr6x6SkSYVxCw0qWO7dnWgcOwVjvThITsPsZMNLTtC.gtlU6', 'Empp', 'Test', 'etest@fakemail.com', '233-332-2322', 0, '2017-01-15 16:15:57', 0, 0, '2017-01-15 16:15:57'),
(20, 'mikemike', '$2y$10$sgww2xr6x6SkSYVxCw0qWO7dnWgcOwVjvThITsPsZMNLTtC.gtlU6', 'Mike', 'James', 'mj@fakemail.com', '444-555-6666', 2, '2017-01-15 16:25:22', 1, 0, '2017-01-15 16:25:22');

-- --------------------------------------------------------

--
-- Table structure for table `emp_schedule_change`
--

CREATE TABLE `emp_schedule_change` (
  `empid` int(11) UNSIGNED NOT NULL,
  `weeknumber` int(11) NOT NULL,
  `changenumber` int(11) NOT NULL,
  `updatedate` timestamp NOT NULL,
  `approved` tinyint(1) DEFAULT NULL,
  `approvedby` int(11) DEFAULT NULL,
  `manager_initiated` tinyint(1) DEFAULT NULL,
  `delete_indicator` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

--
-- Dumping data for table `emp_schedule_change`
--

INSERT INTO `emp_schedule_change` (`empid`, `weeknumber`, `changenumber`, `updatedate`, `approved`, `approvedby`, `manager_initiated`, `delete_indicator`) VALUES
(1, 0, 3, '2017-01-08 22:12:54', 1, 1, NULL, 1),
(1, 1, 2, '2017-01-12 02:25:10', 1, 1, NULL, 0),
(1, 2, 1, '2017-01-12 02:28:49', 1, 1, NULL, 1),
(1, 3, 3, '2017-01-12 02:32:08', NULL, NULL, NULL, 0),
(1, 4, 2, '2017-01-12 01:12:31', 1, 1, 1, 0),
(4, 2, 1, '2017-01-12 01:13:29', 1, 1, 1, 0),
(4, 3, 2, '2017-01-12 00:59:17', 1, 1, 1, 0),
(6, 0, 3, '2017-01-08 22:53:34', 1, 1, NULL, 0),
(6, 1, 1, '2017-01-08 21:16:44', 1, 1, NULL, 0),
(6, 2, 1, '2017-01-12 01:07:34', 1, 1, NULL, 0),
(6, 3, 0, '2017-01-12 01:00:06', 1, 1, 1, 0),
(7, 3, 2, '2017-01-12 01:02:44', 1, 1, 1, 0),
(7, 4, 3, '2017-01-12 01:02:00', 1, 1, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE `schedule` (
  `dow` varchar(10) NOT NULL,
  `shift1` int(11) NOT NULL,
  `shift2` int(11) NOT NULL,
  `shift3` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf16;

--
-- Dumping data for table `schedule`
--

INSERT INTO `schedule` (`dow`, `shift1`, `shift2`, `shift3`) VALUES
('Monday', 0, 3, 1),
('Tuesday', 0, 2, 3),
('Wednesday', 0, 2, 1),
('Thursday', 0, 3, 1),
('Friday', 0, 2, 3),
('Saturday', 1, 2, 3),
('Sunday', 1, 2, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD UNIQUE KEY `phone_exit` (`custexit`,`custphone`) USING BTREE,
  ADD KEY `custfname` (`custfirstname`),
  ADD KEY `custlname` (`custlastname`),
  ADD KEY `custenter` (`custenter`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `firstname` (`firstname`),
  ADD KEY `lastname` (`lastname`);

--
-- Indexes for table `emp_schedule_change`
--
ALTER TABLE `emp_schedule_change`
  ADD UNIQUE KEY `id_date` (`empid`,`weeknumber`),
  ADD KEY `empid` (`empid`),
  ADD KEY `approvedby` (`approvedby`);

--
-- Indexes for table `schedule`
--
ALTER TABLE `schedule`
  ADD UNIQUE KEY `dow_2` (`dow`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `emp_schedule_change`
--
ALTER TABLE `emp_schedule_change`
  ADD CONSTRAINT `empid_fk` FOREIGN KEY (`empid`) REFERENCES `employee` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
