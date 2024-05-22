-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 03, 2024 at 06:50 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `libralogdatabase`
--

-- --------------------------------------------------------

--
-- Table structure for table `book`
--

CREATE TABLE `book` (
  `isbn` varchar(100) NOT NULL,
  `bookName` varchar(100) NOT NULL,
  `bookAuthor` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `book`
--

INSERT INTO `book` (`isbn`, `bookName`, `bookAuthor`) VALUES
('0002160858', 'The Brothers Karamazov', 'Fyodor Dostoevsky'),
('0002219194', 'Crime and Punishment', 'Fyodor Dostoevsky'),
('0010726721', 'The Idiot', 'Fyodor Dostoevsky');

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

CREATE TABLE `log` (
  `logID` int(100) NOT NULL,
  `uid` varchar(100) NOT NULL,
  `dateOfBorrowing` datetime NOT NULL,
  `dueDate` date NOT NULL,
  `dateReturned` datetime NOT NULL,
  `isbn` varchar(100) NOT NULL,
  `isReturned` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `log`
--

INSERT INTO `log` (`logID`, `uid`, `dateOfBorrowing`, `dueDate`, `dateReturned`, `isbn`, `isReturned`) VALUES
(3, '0002160672', '2024-03-03 22:27:01', '2024-03-29', '2024-03-03 23:29:14', '0010726721', 1),
(4, '0002160672', '2024-03-03 22:27:19', '2024-03-28', '2024-03-03 23:26:50', '0002219194', 1),
(11, '0002160672', '2024-03-03 22:39:33', '2024-03-29', '2024-03-03 23:29:07', '0002160858', 1),
(14, '0002159480', '2024-03-03 22:48:26', '2024-03-27', '2024-03-03 22:55:58', '0002160858', 1),
(15, '0002159480', '2024-03-03 23:42:47', '2024-03-15', '0000-00-00 00:00:00', '0002219194', 0),
(16, '0002159480', '2024-03-03 23:48:56', '2024-03-13', '0000-00-00 00:00:00', '0002219194', 0),
(19, '0002160672', '2024-03-04 00:04:30', '2024-03-29', '0000-00-00 00:00:00', '0002219194', 0),
(20, '0002159480', '2024-03-04 00:04:55', '2024-03-19', '0000-00-00 00:00:00', '0002219194', 0),
(24, '0010726721', '2024-03-04 00:13:27', '2024-04-05', '2024-03-04 00:14:32', '0002219194', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `uid` varchar(100) NOT NULL,
  `firstName` varchar(100) NOT NULL,
  `middleName` varchar(100) NOT NULL,
  `lastName` varchar(100) NOT NULL,
  `sex` varchar(100) NOT NULL,
  `studentID` int(100) NOT NULL,
  `dep` varchar(100) NOT NULL,
  `gradeYear` int(100) NOT NULL,
  `section` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`uid`, `firstName`, `middleName`, `lastName`, `sex`, `studentID`, `dep`, `gradeYear`, `section`) VALUES
('0002159480', 'Larah Jane', 'Panuncio', 'Cabrera', 'Female', 2200641, 'Senior High School', 12, 'EABM-1'),
('0002160672', 'Clarence', 'Ungos', 'Lubrin', 'Male', 2200512, 'Senior High School', 12, 'ESTEM-C6'),
('0010726721', 'John Daniel', 'Generalao', 'Alvarez', 'Male', 2200510, 'Senior High School', 12, 'ESTEM-C1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`isbn`);

--
-- Indexes for table `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`logID`),
  ADD KEY `isbnINDEX` (`isbn`),
  ADD KEY `uidINDEX` (`uid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`uid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `log`
--
ALTER TABLE `log`
  MODIFY `logID` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `log`
--
ALTER TABLE `log`
  ADD CONSTRAINT `log_ibfk_2` FOREIGN KEY (`isbn`) REFERENCES `book` (`isbn`),
  ADD CONSTRAINT `log_ibfk_3` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
