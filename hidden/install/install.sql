-- phpMyAdmin SQL Dump
-- version 3.4.5deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 08, 2012 at 06:08 AM
-- Server version: 5.1.61
-- PHP Version: 5.3.6-13ubuntu3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `db_clearbugs`
--

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

CREATE TABLE IF NOT EXISTS `log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(35) NOT NULL,
  `loggedin_user_id` int(11) NOT NULL,
  `loggedin_username` varchar(90) NOT NULL,
  `loggedin_email` varchar(255) NOT NULL,
  `date_created` datetime NOT NULL,
  `type` varchar(50) NOT NULL,
  `details` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `organizations`
--

CREATE TABLE IF NOT EXISTS `organizations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_expires` datetime DEFAULT NULL,
  `plan_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

-- --------------------------------------------------------

--
-- Table structure for table `plans`
--

CREATE TABLE IF NOT EXISTS `plans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shortname` varchar(30) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `cost` float NOT NULL,
  `charge_cycle` enum('yearly') NOT NULL,
  `max_users` int(11) NOT NULL,
  `max_projects` int(11) NOT NULL,
  `allow_email_bug_submissions` tinyint(1) NOT NULL DEFAULT '0',
  `allow_file_attachments` tinyint(1) NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `shortname` (`shortname`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) NOT NULL,
  `username` varchar(90) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified` tinyint(1) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `name` varchar(255) NOT NULL,
  `street1` varchar(255) NOT NULL,
  `street2` varchar(255) NOT NULL,
  `city` varchar(90) NOT NULL,
  `state` varchar(90) NOT NULL,
  `zip` int(25) NOT NULL,
  `country` int(255) NOT NULL,
  `date_added` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `verify_code` varchar(255) DEFAULT NULL,
  `verify_action` enum('initial_email_verification','change_email') DEFAULT NULL,
  `verify_param` varchar(255) NOT NULL,
  `verify_date_expires` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;
