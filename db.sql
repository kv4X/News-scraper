-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 02, 2019 at 06:17 PM
-- Server version: 5.7.22-0ubuntu0.16.04.1
-- PHP Version: 5.6.38-3+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `scraper2`
--

-- --------------------------------------------------------

--
-- Table structure for table `scrapedhomepage`
--

CREATE TABLE `scrapedhomepage` (
  `ID` int(11) NOT NULL,
  `website` text NOT NULL,
  `postTitle` text CHARACTER SET utf8mb4 COLLATE utf8mb4_croatian_ci NOT NULL,
  `postLink` text NOT NULL,
  `postImage` text NOT NULL,
  `postCategory` text NOT NULL,
  `dateAdded` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `scrapedhomepage_used` (
  `ID` int(11) NOT NULL,
  `website` text NOT NULL,
  `postTitle` text NOT NULL,
  `postLink` text NOT NULL,
  `postImage` text NOT NULL,
  `postCategory` text NOT NULL,
  `dateAdded` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dateUsed` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `websites` (
  `ID` int(11) NOT NULL,
  `active` int(11) NOT NULL DEFAULT '0',
  `website` text NOT NULL,
  `website_additionalLink` text NOT NULL,
  `homePagePost` text NOT NULL,
  `homePageImage` text NOT NULL,
  `postTitle` text NOT NULL,
  `postContent` text NOT NULL,
  `postImage` text NOT NULL,
  `postIgnoreText` text NOT NULL,
  `postIgnoreDiv` text NOT NULL,
  `category` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
--
-- Indexes for dumped tables
--

--
-- Indexes for table `scrapedhomepage`
--
ALTER TABLE `scrapedhomepage`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `ID` (`ID`);

--
-- Indexes for table `scrapedhomepage_used`
--
ALTER TABLE `scrapedhomepage_used`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `ID` (`ID`);

--
-- Indexes for table `websites`
--
ALTER TABLE `websites`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `scrapedhomepage`
--
ALTER TABLE `scrapedhomepage`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT for table `scrapedhomepage_used`
--
ALTER TABLE `scrapedhomepage_used`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=382;
--
-- AUTO_INCREMENT for table `websites`
--
ALTER TABLE `websites`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
