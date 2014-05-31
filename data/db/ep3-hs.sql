-- phpMyAdmin SQL Dump
-- version 4.1.8
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Erstellungszeit: 30. Mai 2014 um 18:10
-- Server Version: 5.5.27
-- PHP-Version: 5.4.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Datenbank: `ep3-hs`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `hs_bills`
--

CREATE TABLE IF NOT EXISTS `hs_bills` (
  `bid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bnr` varchar(64) DEFAULT NULL,
  `status` varchar(64) NOT NULL,
  `booking` int(10) unsigned DEFAULT NULL,
  `bundle` int(10) unsigned DEFAULT NULL,
  `bundle_name` varchar(512) DEFAULT NULL,
  `user` int(10) unsigned NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`bid`),
  KEY `bnr` (`bnr`),
  KEY `booking` (`booking`),
  KEY `user` (`user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `hs_bills_items`
--

CREATE TABLE IF NOT EXISTS `hs_bills_items` (
  `biid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bid` int(10) unsigned NOT NULL,
  `pid` int(10) unsigned DEFAULT NULL,
  `pid_name` varchar(512) NOT NULL,
  `priority` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `price` int(10) unsigned NOT NULL,
  `rate` int(10) unsigned NOT NULL,
  `gross` tinyint(1) NOT NULL,
  PRIMARY KEY (`biid`),
  KEY `bid` (`bid`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `hs_bills_meta`
--

CREATE TABLE IF NOT EXISTS `hs_bills_meta` (
  `bmid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bid` int(10) unsigned NOT NULL,
  `key` varchar(64) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`bmid`),
  KEY `bid` (`bid`),
  KEY `key` (`key`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `hs_bills_nights`
--

CREATE TABLE IF NOT EXISTS `hs_bills_nights` (
  `bnid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bid` int(10) unsigned NOT NULL,
  `rid` int(10) unsigned DEFAULT NULL,
  `date_arrival` datetime NOT NULL,
  `date_departure` datetime NOT NULL,
  `date_repeat` int(10) unsigned DEFAULT NULL,
  `quantity` int(10) unsigned NOT NULL,
  `price` int(10) unsigned NOT NULL,
  `rate` int(10) unsigned NOT NULL,
  `gross` tinyint(1) NOT NULL,
  PRIMARY KEY (`bnid`),
  KEY `bid` (`bid`),
  KEY `rid` (`rid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `hs_bookings`
--

CREATE TABLE IF NOT EXISTS `hs_bookings` (
  `bid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned DEFAULT NULL,
  `uid` int(10) unsigned DEFAULT NULL,
  `status` varchar(64) NOT NULL,
  `date_arrival` datetime DEFAULT NULL,
  `date_departure` datetime DEFAULT NULL,
  `date_repeat` int(10) unsigned DEFAULT NULL,
  `quantity` int(10) unsigned NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`bid`),
  KEY `rid` (`rid`),
  KEY `uid` (`uid`),
  KEY `date_arrival` (`date_arrival`),
  KEY `date_departure` (`date_departure`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `hs_bookings_exceptions`
--

CREATE TABLE IF NOT EXISTS `hs_bookings_exceptions` (
  `beid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bid` int(10) unsigned NOT NULL,
  `date_start` datetime NOT NULL,
  `date_end` datetime NOT NULL,
  `notes` text,
  `created` datetime NOT NULL,
  PRIMARY KEY (`beid`),
  KEY `bid` (`bid`),
  KEY `date_start` (`date_start`),
  KEY `date_end` (`date_end`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `hs_bookings_meta`
--

CREATE TABLE IF NOT EXISTS `hs_bookings_meta` (
  `bmid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bid` int(10) unsigned NOT NULL,
  `key` varchar(64) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`bmid`),
  KEY `bid` (`bid`),
  KEY `key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `hs_bundles`
--

CREATE TABLE IF NOT EXISTS `hs_bundles` (
  `bid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned DEFAULT NULL,
  `rid_group` int(10) unsigned DEFAULT NULL,
  `status` varchar(64) NOT NULL,
  `code` varchar(64) DEFAULT NULL,
  `priority` int(11) DEFAULT NULL,
  `date_start` datetime DEFAULT NULL,
  `date_end` datetime DEFAULT NULL,
  `date_repeat` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`bid`),
  KEY `rid` (`rid`),
  KEY `rid_group` (`rid_group`),
  KEY `date_start` (`date_start`),
  KEY `date_end` (`date_end`),
  KEY `code` (`code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `hs_bundles_exceptions`
--

CREATE TABLE IF NOT EXISTS `hs_bundles_exceptions` (
  `beid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bid` int(10) unsigned NOT NULL,
  `date_start` datetime NOT NULL,
  `date_end` datetime NOT NULL,
  `notes` text,
  PRIMARY KEY (`beid`),
  KEY `bid` (`bid`),
  KEY `date_start` (`date_start`),
  KEY `date_end` (`date_end`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `hs_bundles_items`
--

CREATE TABLE IF NOT EXISTS `hs_bundles_items` (
  `biid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bid` int(10) unsigned NOT NULL,
  `pid` int(10) unsigned NOT NULL,
  `priority` int(11) NOT NULL,
  `due` varchar(64) NOT NULL,
  `amount_min` int(10) unsigned NOT NULL,
  `amount_max` int(10) unsigned NOT NULL,
  `price` int(10) unsigned NOT NULL,
  `price_fixed` tinyint(1) NOT NULL,
  `rate` int(10) unsigned NOT NULL,
  `gross` tinyint(1) NOT NULL,
  PRIMARY KEY (`biid`),
  KEY `bid` (`bid`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `hs_bundles_meta`
--

CREATE TABLE IF NOT EXISTS `hs_bundles_meta` (
  `bmid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bid` int(10) unsigned NOT NULL,
  `key` varchar(64) NOT NULL,
  `value` text NOT NULL,
  `locale` varchar(8) DEFAULT NULL,
  PRIMARY KEY (`bmid`),
  KEY `bid` (`bid`),
  KEY `key` (`key`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `hs_bundles_nights`
--

CREATE TABLE IF NOT EXISTS `hs_bundles_nights` (
  `bnid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bid` int(10) unsigned NOT NULL,
  `nights_min` int(10) unsigned NOT NULL,
  `nights_max` int(10) unsigned NOT NULL,
  `price` int(10) unsigned NOT NULL,
  `price_fixed` tinyint(1) NOT NULL,
  `rate` int(10) unsigned NOT NULL,
  `gross` tinyint(1) NOT NULL,
  PRIMARY KEY (`bnid`),
  KEY `bid` (`bid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `hs_options`
--

CREATE TABLE IF NOT EXISTS `hs_options` (
  `oid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(64) NOT NULL,
  `value` text NOT NULL,
  `locale` varchar(8) DEFAULT NULL,
  PRIMARY KEY (`oid`),
  KEY `key` (`key`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `hs_products`
--

CREATE TABLE IF NOT EXISTS `hs_products` (
  `pid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `status` varchar(64) NOT NULL,
  PRIMARY KEY (`pid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `hs_products_meta`
--

CREATE TABLE IF NOT EXISTS `hs_products_meta` (
  `pmid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL,
  `key` varchar(64) NOT NULL,
  `value` text NOT NULL,
  `locale` varchar(8) DEFAULT NULL,
  PRIMARY KEY (`pmid`),
  KEY `pid` (`pid`),
  KEY `key` (`key`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `hs_rooms`
--

CREATE TABLE IF NOT EXISTS `hs_rooms` (
  `rid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid_prototype` int(10) unsigned DEFAULT NULL,
  `rnr` varchar(64) NOT NULL,
  `status` varchar(64) NOT NULL,
  `capacity` int(10) unsigned NOT NULL,
  PRIMARY KEY (`rid`),
  KEY `rid_prototype` (`rid_prototype`),
  KEY `rnr` (`rnr`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `hs_rooms_meta`
--

CREATE TABLE IF NOT EXISTS `hs_rooms_meta` (
  `rmid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `key` varchar(64) NOT NULL,
  `value` text NOT NULL,
  `locale` varchar(8) DEFAULT NULL,
  PRIMARY KEY (`rmid`),
  KEY `rid` (`rid`),
  KEY `key` (`key`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `hs_users`
--

CREATE TABLE IF NOT EXISTS `hs_users` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `alias` varchar(256) NOT NULL,
  `status` varchar(64) NOT NULL COMMENT 'placeholder|guest|deleted|blocked|disabled|enabled|assist|admin',
  `email` varchar(128) DEFAULT NULL,
  `pw` varchar(256) DEFAULT NULL,
  `login_attempts` tinyint(3) unsigned DEFAULT NULL,
  `login_detent` datetime DEFAULT NULL,
  `last_activity` datetime DEFAULT NULL,
  `last_ip` varchar(64) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `alias` (`alias`(255)),
  KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `hs_users_meta`
--

CREATE TABLE IF NOT EXISTS `hs_users_meta` (
  `umid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `key` varchar(64) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`umid`),
  KEY `key` (`key`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `hs_bills`
--
ALTER TABLE `hs_bills`
  ADD CONSTRAINT `hs_bills_ibfk_1` FOREIGN KEY (`booking`) REFERENCES `hs_bookings` (`bid`),
  ADD CONSTRAINT `hs_bills_ibfk_2` FOREIGN KEY (`user`) REFERENCES `hs_users` (`uid`);

--
-- Constraints der Tabelle `hs_bills_items`
--
ALTER TABLE `hs_bills_items`
  ADD CONSTRAINT `hs_bills_items_ibfk_1` FOREIGN KEY (`bid`) REFERENCES `hs_bills` (`bid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `hs_bills_items_ibfk_2` FOREIGN KEY (`pid`) REFERENCES `hs_products` (`pid`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints der Tabelle `hs_bills_meta`
--
ALTER TABLE `hs_bills_meta`
  ADD CONSTRAINT `hs_bills_meta_ibfk_1` FOREIGN KEY (`bid`) REFERENCES `hs_bills` (`bid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `hs_bills_nights`
--
ALTER TABLE `hs_bills_nights`
  ADD CONSTRAINT `hs_bills_nights_ibfk_1` FOREIGN KEY (`bid`) REFERENCES `hs_bills` (`bid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `hs_bills_nights_ibfk_2` FOREIGN KEY (`rid`) REFERENCES `hs_rooms` (`rid`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints der Tabelle `hs_bookings`
--
ALTER TABLE `hs_bookings`
  ADD CONSTRAINT `hs_bookings_ibfk_1` FOREIGN KEY (`rid`) REFERENCES `hs_rooms` (`rid`),
  ADD CONSTRAINT `hs_bookings_ibfk_2` FOREIGN KEY (`uid`) REFERENCES `hs_users` (`uid`);

--
-- Constraints der Tabelle `hs_bookings_exceptions`
--
ALTER TABLE `hs_bookings_exceptions`
  ADD CONSTRAINT `hs_bookings_exceptions_ibfk_1` FOREIGN KEY (`bid`) REFERENCES `hs_bookings` (`bid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `hs_bookings_meta`
--
ALTER TABLE `hs_bookings_meta`
  ADD CONSTRAINT `hs_bookings_meta_ibfk_1` FOREIGN KEY (`bid`) REFERENCES `hs_bookings` (`bid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `hs_bundles`
--
ALTER TABLE `hs_bundles`
  ADD CONSTRAINT `hs_bundles_ibfk_1` FOREIGN KEY (`rid`) REFERENCES `hs_rooms` (`rid`),
  ADD CONSTRAINT `hs_bundles_ibfk_2` FOREIGN KEY (`rid_group`) REFERENCES `hs_rooms` (`rid`);

--
-- Constraints der Tabelle `hs_bundles_exceptions`
--
ALTER TABLE `hs_bundles_exceptions`
  ADD CONSTRAINT `hs_bundles_exceptions_ibfk_1` FOREIGN KEY (`bid`) REFERENCES `hs_bundles` (`bid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `hs_bundles_items`
--
ALTER TABLE `hs_bundles_items`
  ADD CONSTRAINT `hs_bundles_items_ibfk_1` FOREIGN KEY (`bid`) REFERENCES `hs_bundles` (`bid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `hs_bundles_items_ibfk_2` FOREIGN KEY (`pid`) REFERENCES `hs_products` (`pid`);

--
-- Constraints der Tabelle `hs_bundles_meta`
--
ALTER TABLE `hs_bundles_meta`
  ADD CONSTRAINT `hs_bundles_meta_ibfk_1` FOREIGN KEY (`bid`) REFERENCES `hs_bundles` (`bid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `hs_bundles_nights`
--
ALTER TABLE `hs_bundles_nights`
  ADD CONSTRAINT `hs_bundles_nights_ibfk_1` FOREIGN KEY (`bid`) REFERENCES `hs_bundles` (`bid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `hs_products_meta`
--
ALTER TABLE `hs_products_meta`
  ADD CONSTRAINT `hs_products_meta_ibfk_1` FOREIGN KEY (`pid`) REFERENCES `hs_products` (`pid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `hs_rooms_meta`
--
ALTER TABLE `hs_rooms_meta`
  ADD CONSTRAINT `hs_rooms_meta_ibfk_1` FOREIGN KEY (`rid`) REFERENCES `hs_rooms` (`rid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `hs_users_meta`
--
ALTER TABLE `hs_users_meta`
  ADD CONSTRAINT `hs_users_meta_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `hs_users` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE;
