-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 18, 2014 at 07:09 AM
-- Server version: 5.6.20
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `timeline`
--

-- --------------------------------------------------------

--
-- Table structure for table `timeline`
--

CREATE TABLE IF NOT EXISTS `timeline` (
`id` int(11) NOT NULL,
  `title` varchar(200) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=16 ;

--
-- Dumping data for table `timeline`
--

INSERT INTO `timeline` (`id`, `title`) VALUES
(14, 'Blizzard Entertainment'),
(11, 'Dro Soft'),
(8, 'Electronic Arts'),
(12, 'Fox Interactive'),
(10, 'Gremlin Interactive Ltd.'),
(13, 'Illusion Software'),
(15, 'PopCap Games, Inc.'),
(9, 'The Hit Squad');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `timeline`
--
ALTER TABLE `timeline`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `title` (`title`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `timeline`
--
ALTER TABLE `timeline`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
