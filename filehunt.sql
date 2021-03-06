-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 21, 2012 at 08:07 PM
-- Server version: 5.5.16
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `filehunt`
--

-- --------------------------------------------------------

--
-- Table structure for table `abuse`
--

CREATE TABLE IF NOT EXISTS `abuse` (
  `rowID` int(11) NOT NULL AUTO_INCREMENT,
  `fileID` int(11) NOT NULL,
  `report_by` int(11) NOT NULL,
  `date_reported` bigint(20) NOT NULL,
  PRIMARY KEY (`rowID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `rowID` int(11) NOT NULL AUTO_INCREMENT,
  `fileID` int(11) NOT NULL,
  `comment_by` int(11) NOT NULL,
  `date_commented` bigint(20) NOT NULL,
  `comment` text NOT NULL,
  PRIMARY KEY (`rowID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `comments`
--



-- --------------------------------------------------------

--
-- Table structure for table `downloads`
--

CREATE TABLE IF NOT EXISTS `downloads` (
  `rowID` int(11) NOT NULL AUTO_INCREMENT,
  `downloaded_by` varchar(11) NOT NULL,
  `fileID` int(11) NOT NULL,
  `downloaded_date` text NOT NULL,
  PRIMARY KEY (`rowID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `downloads`
--



-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `rowID` int(11) NOT NULL AUTO_INCREMENT,
  `file` text NOT NULL,
  `mimetype` text NOT NULL,
  `data` longblob NOT NULL,
  `uploaded_by` int(11) NOT NULL,
  `uploaded_date` bigint(20) NOT NULL,
  `size` int(11) NOT NULL,
  `times_downloaded` int(11) NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  PRIMARY KEY (`rowID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `files`
--

--
-- Table structure for table `subs`
--

CREATE TABLE IF NOT EXISTS `subs` (
  `rowID` int(11) NOT NULL AUTO_INCREMENT,
  `subscriber` int(11) NOT NULL,
  `subscribed` int(11) NOT NULL,
  PRIMARY KEY (`rowID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `subs`
--



-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `rowID` int(11) NOT NULL AUTO_INCREMENT,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `email` text NOT NULL,
  `security_code` int(11) NOT NULL,
  `last_sub_check` bigint(20) NOT NULL,
  PRIMARY KEY (`rowID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`rowID`, `username`, `password`, `email`, `security_code`, `last_sub_check`) VALUES
(1, 'theadamlt', 'bdef23997404ae3a0a653cf369610e85', 'lilienfeldt.adam@gmail.com', 29264480, 1331827150),
(2, 'ChristianWismann', 'a407a87802cb98535d3ef3ca8d62c634', 'wismann.christian@gmail.com', 1404729028, 1333473540),
(3, 'sebastianBeen', 'c23aae0f542e158d40a232617598a6f4', 'donau12@gmail.com', 2147483647, 1336025220),


-- --------------------------------------------------------

--
-- Table structure for table `user_pref`
--

CREATE TABLE IF NOT EXISTS `user_pref` (
  `rowID` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `real_name` text NOT NULL,
  `show_real_name` tinyint(1) NOT NULL,
  `show_mail` tinyint(1) NOT NULL,
  `admin` tinyint(1) NOT NULL,
  PRIMARY KEY (`rowID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `user_pref`
--

INSERT INTO `user_pref` (`rowID`, `userID`, `real_name`, `show_real_name`, `show_mail`, `admin`) VALUES
(4, 1, 'Adam Lilienfeldt', 1, 1, 1),
(6, 2, 'Christian Wismann', 0, 0, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
