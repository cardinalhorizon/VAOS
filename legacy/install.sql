/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `phpvms_acarsdata` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pilotid` varchar(11) NOT NULL DEFAULT '0',
  `flightnum` varchar(11) NOT NULL DEFAULT '0',
  `pilotname` varchar(100) NOT NULL DEFAULT '',
  `aircraft` varchar(12) NOT NULL DEFAULT '',
  `lat` varchar(15) NOT NULL DEFAULT '',
  `lng` varchar(15) NOT NULL DEFAULT '',
  `heading` smallint(6) NOT NULL DEFAULT '0',
  `alt` varchar(6) NOT NULL DEFAULT '',
  `gs` int(11) NOT NULL DEFAULT '0',
  `depicao` varchar(4) NOT NULL DEFAULT '',
  `depapt` varchar(255) NOT NULL DEFAULT '',
  `arricao` varchar(4) NOT NULL DEFAULT '',
  `arrapt` text NOT NULL,
  `deptime` time NOT NULL DEFAULT CURRENT_TIME,
  `timeremaining` varchar(6) NOT NULL DEFAULT '0',
  `arrtime` time NOT NULL DEFAULT CURRENT_TIME,
  `route` text NOT NULL,
  `route_details` text NOT NULL,
  `distremain` varchar(6) NOT NULL DEFAULT '',
  `phasedetail` varchar(255) NOT NULL DEFAULT '',
  `online` varchar(10) NOT NULL DEFAULT '',
  `messagelog` text NOT NULL,
  `lastupdate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `client` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `pilotid` (`pilotid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `phpvms_activityfeed` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pilotid` int(11) NOT NULL DEFAULT '0',
  `refid` bigint(20) NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  `message` varchar(100) NOT NULL,
  `submitdate` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pilotid` (`pilotid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `phpvms_adminlog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pilotid` int(11) NOT NULL,
  `datestamp` datetime NOT NULL,
  `message` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `phpvms_aircraft` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `icao` varchar(4) NOT NULL DEFAULT '',
  `name` varchar(12) NOT NULL DEFAULT '',
  `fullname` varchar(50) NOT NULL DEFAULT '',
  `registration` varchar(30) NOT NULL,
  `downloadlink` text NOT NULL,
  `imagelink` text NOT NULL,
  `range` varchar(15) NOT NULL DEFAULT '0',
  `weight` varchar(15) NOT NULL DEFAULT '0',
  `cruise` varchar(15) NOT NULL DEFAULT '0',
  `maxpax` float NOT NULL DEFAULT '0',
  `maxcargo` float NOT NULL DEFAULT '0',
  `minrank` int(11) NOT NULL DEFAULT '0',
  `ranklevel` int(11) NOT NULL DEFAULT '0',
  `enabled` smallint(6) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `phpvms_airlines` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` char(3) NOT NULL DEFAULT '',
  `name` varchar(30) NOT NULL DEFAULT '',
  `enabled` smallint(6) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `phpvms_airports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `icao` varchar(5) NOT NULL DEFAULT '',
  `name` text NOT NULL,
  `country` varchar(50) NOT NULL DEFAULT '',
  `lat` float NOT NULL DEFAULT '0',
  `lng` float NOT NULL DEFAULT '0',
  `hub` smallint(6) NOT NULL DEFAULT '0',
  `fuelprice` float NOT NULL DEFAULT '0',
  `chartlink` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `icao` (`icao`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `phpvms_awards` (
  `awardid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  `descrip` varchar(100) NOT NULL,
  `image` text NOT NULL,
  PRIMARY KEY (`awardid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `phpvms_awardsgranted` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `awardid` int(11) NOT NULL,
  `pilotid` int(11) NOT NULL,
  `dateissued` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `phpvms_bids` (
  `bidid` int(11) NOT NULL AUTO_INCREMENT,
  `pilotid` int(11) NOT NULL DEFAULT '0',
  `routeid` int(11) NOT NULL DEFAULT '0',
  `dateadded` date NOT NULL,
  PRIMARY KEY (`bidid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `phpvms_customfields` (
  `fieldid` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(75) NOT NULL,
  `fieldname` varchar(75) NOT NULL,
  `value` text NOT NULL,
  `type` varchar(25) NOT NULL DEFAULT 'text',
  `public` smallint(6) NOT NULL DEFAULT '0',
  `showonregister` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`fieldid`),
  UNIQUE KEY `fieldname` (`fieldname`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `phpvms_downloads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` text,
  `link` text,
  `image` text,
  `hits` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `phpvms_expenselog` (
  `dateadded` int(11) NOT NULL,
  `name` varchar(25) NOT NULL,
  `type` varchar(2) NOT NULL,
  `cost` float NOT NULL,
  KEY `dateadded` (`dateadded`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `phpvms_expenses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  `cost` float NOT NULL,
  `fixed` int(11) NOT NULL DEFAULT '0',
  `type` varchar(1) NOT NULL DEFAULT 'M',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `phpvms_fieldvalues` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fieldid` int(11) NOT NULL,
  `pilotid` int(11) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `phpvms_fieldvalues_ibfk_1` (`fieldid`),
  KEY `phpvms_fieldvalues_ibfk_2` (`pilotid`),
  CONSTRAINT `phpvms_fieldvalues_ibfk_1` FOREIGN KEY (`fieldid`) REFERENCES `phpvms_customfields` (`fieldid`) ON DELETE CASCADE,
  CONSTRAINT `phpvms_fieldvalues_ibfk_2` FOREIGN KEY (`pilotid`) REFERENCES `phpvms_pilots` (`pilotid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `phpvms_financedata` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `month` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `data` text NOT NULL,
  `total` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `phpvms_fuelprices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `icao` varchar(4) NOT NULL,
  `lowlead` float NOT NULL,
  `jeta` float NOT NULL,
  `dateupdated` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `phpvms_groupmembers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `groupid` int(11) NOT NULL DEFAULT '0',
  `pilotid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `phpvms_groupmembers_ibfk_1` (`groupid`),
  KEY `phpvms_groupmembers_ibfk_2` (`pilotid`),
  CONSTRAINT `phpvms_groupmembers_ibfk_1` FOREIGN KEY (`groupid`) REFERENCES `phpvms_groups` (`groupid`) ON DELETE CASCADE,
  CONSTRAINT `phpvms_groupmembers_ibfk_2` FOREIGN KEY (`pilotid`) REFERENCES `phpvms_pilots` (`pilotid`) ON DELETE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `phpvms_groups` (
  `groupid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL DEFAULT '',
  `permissions` varchar(25) NOT NULL DEFAULT '',
  `core` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`groupid`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `phpvms_ledger` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pilotid` int(11) NOT NULL,
  `pirepid` int(11) NOT NULL DEFAULT '0',
  `paysource` tinyint(4) NOT NULL,
  `paytype` int(11) NOT NULL DEFAULT '3',
  `amount` float(7,2) NOT NULL DEFAULT '0.00',
  `submitdate` datetime NOT NULL,
  `modifieddate` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pilot_id` (`pilotid`),
  KEY `pirepid` (`pirepid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `phpvms_navdata` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(7) NOT NULL,
  `title` varchar(25) NOT NULL,
  `airway` varchar(7) DEFAULT NULL,
  `airway_type` varchar(1) DEFAULT NULL,
  `seq` int(11) NOT NULL,
  `loc` varchar(4) NOT NULL,
  `lat` float(8,6) NOT NULL,
  `lng` float(9,6) NOT NULL,
  `freq` varchar(7) NOT NULL,
  `type` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `airway` (`airway`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `phpvms_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(30) NOT NULL DEFAULT '',
  `body` text NOT NULL,
  `postdate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `postedby` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `phpvms_pages` (
  `pageid` int(11) NOT NULL AUTO_INCREMENT,
  `pagename` varchar(30) NOT NULL DEFAULT '',
  `filename` varchar(30) NOT NULL DEFAULT '',
  `order` smallint(6) NOT NULL DEFAULT '0',
  `postedby` varchar(50) NOT NULL DEFAULT '',
  `postdate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `public` smallint(6) NOT NULL DEFAULT '0',
  `enabled` smallint(6) NOT NULL DEFAULT '1',
  PRIMARY KEY (`pageid`),
  UNIQUE KEY `pagename` (`pagename`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `phpvms_pilots` (
  `pilotid` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(25) NOT NULL DEFAULT '',
  `lastname` varchar(25) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `code` char(3) NOT NULL DEFAULT '',
  `location` varchar(32) NOT NULL DEFAULT '',
  `hub` varchar(4) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `salt` varchar(32) NOT NULL DEFAULT '',
  `bgimage` varchar(30) NOT NULL DEFAULT '',
  `lastlogin` date NOT NULL DEFAULT '0000-00-00',
  `totalflights` int(11) NOT NULL DEFAULT '0',
  `totalhours` float NOT NULL DEFAULT '0',
  `totalpay` float NOT NULL DEFAULT '0',
  `payadjust` float DEFAULT '0',
  `transferhours` float NOT NULL DEFAULT '0',
  `rankid` int(11) NOT NULL DEFAULT '0',
  `rank` varchar(32) NOT NULL DEFAULT 'New Hire',
  `ranklevel` int(11) NOT NULL DEFAULT '0',
  `confirmed` smallint(5) unsigned NOT NULL DEFAULT '0',
  `retired` smallint(6) NOT NULL DEFAULT '0',
  `joindate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastpirep` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastip` varchar(25) DEFAULT '',
  `comment` text,
  PRIMARY KEY (`pilotid`),
  KEY `code` (`code`),
  KEY `rank` (`rank`),
  CONSTRAINT `phpvms_pilots_ibfk_1` FOREIGN KEY (`code`) REFERENCES `phpvms_airlines` (`code`) ON UPDATE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `phpvms_pirepcomments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pirepid` int(11) NOT NULL,
  `pilotid` int(11) NOT NULL,
  `comment` text NOT NULL,
  `postdate` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `phpvms_pirepcomments_ibfk_1` (`pirepid`),
  CONSTRAINT `phpvms_pirepcomments_ibfk_1` FOREIGN KEY (`pirepid`) REFERENCES `phpvms_pireps` (`pirepid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `phpvms_pirepfields` (
  `fieldid` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(25) NOT NULL,
  `name` varchar(25) NOT NULL,
  `type` varchar(25) NOT NULL,
  `options` text NOT NULL,
  PRIMARY KEY (`fieldid`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `phpvms_pireps` (
  `pirepid` int(11) NOT NULL AUTO_INCREMENT,
  `pilotid` int(11) NOT NULL DEFAULT '0',
  `code` char(3) NOT NULL DEFAULT '',
  `flightnum` varchar(10) NOT NULL DEFAULT '0',
  `depicao` varchar(4) NOT NULL DEFAULT '',
  `arricao` varchar(4) NOT NULL DEFAULT '',
  `route` text NOT NULL,
  `route_details` text NOT NULL,
  `aircraft` varchar(12) NOT NULL DEFAULT '',
  `flighttime` varchar(10) NOT NULL DEFAULT '',
  `flighttime_stamp` time NOT NULL,
  `distance` smallint(6) NOT NULL DEFAULT '0',
  `landingrate` float NOT NULL DEFAULT '0',
  `submitdate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modifieddate` datetime NOT NULL,
  `accepted` smallint(6) NOT NULL DEFAULT '0',
  `log` text NOT NULL,
  `load` int(11) NOT NULL,
  `fuelused` float NOT NULL DEFAULT '0',
  `fuelunitcost` float NOT NULL DEFAULT '0',
  `fuelprice` float NOT NULL DEFAULT '5.1',
  `price` float NOT NULL,
  `flighttype` varchar(1) NOT NULL DEFAULT 'P',
  `gross` float NOT NULL DEFAULT '0',
  `pilotpay` float NOT NULL,
  `paytype` tinyint(1) NOT NULL DEFAULT '1',
  `expenses` float NOT NULL,
  `expenselist` blob NOT NULL,
  `revenue` float NOT NULL,
  `source` varchar(25) NOT NULL,
  `exported` tinyint(4) NOT NULL,
  `rawdata` text NOT NULL,
  PRIMARY KEY (`pirepid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `phpvms_pirepvalues` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fieldid` int(11) NOT NULL,
  `pirepid` int(11) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `phpvms_ranks` (
  `rankid` int(11) NOT NULL AUTO_INCREMENT,
  `rank` varchar(32) NOT NULL DEFAULT '',
  `rankimage` text NOT NULL,
  `minhours` smallint(6) NOT NULL DEFAULT '0',
  `payrate` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`rankid`),
  UNIQUE KEY `rank` (`rank`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `phpvms_schedules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` char(3) NOT NULL DEFAULT '',
  `flightnum` varchar(10) NOT NULL DEFAULT '0',
  `depicao` varchar(4) NOT NULL DEFAULT '',
  `arricao` varchar(4) NOT NULL DEFAULT '',
  `route` text NOT NULL,
  `route_details` text NOT NULL,
  `aircraft` text NOT NULL,
  `flightlevel` varchar(6) NOT NULL,
  `distance` float NOT NULL DEFAULT '0',
  `deptime` varchar(15) NOT NULL DEFAULT '',
  `arrtime` varchar(15) NOT NULL DEFAULT '',
  `flighttime` float NOT NULL DEFAULT '0',
  `daysofweek` varchar(7) NOT NULL DEFAULT '0123456',
  `week1` varchar(7) NOT NULL DEFAULT '0123456',
  `week2` varchar(7) NOT NULL DEFAULT '0123456',
  `week3` varchar(7) NOT NULL DEFAULT '0123456',
  `week4` varchar(7) NOT NULL DEFAULT '0123456',
  `price` float NOT NULL,
  `payforflight` float NOT NULL DEFAULT '0',
  `flighttype` varchar(1) NOT NULL DEFAULT 'P',
  `timesflown` int(11) NOT NULL DEFAULT '0',
  `notes` text NOT NULL,
  `enabled` int(11) NOT NULL DEFAULT '1',
  `bidid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `depicao` (`depicao`),
  KEY `flightnum` (`flightnum`),
  KEY `depicao_arricao` (`depicao`,`arricao`),
  KEY `code` (`code`),
  KEY `idx_code_flightnum` (`code`,`flightnum`),
  CONSTRAINT `phpvms_schedules_ibfk_1` FOREIGN KEY (`code`) REFERENCES `phpvms_airlines` (`code`) ON UPDATE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `phpvms_sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pilotid` int(11) NOT NULL,
  `ipaddress` varchar(25) NOT NULL,
  `logintime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `phpvms_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `friendlyname` varchar(25) NOT NULL DEFAULT '',
  `name` varchar(25) NOT NULL DEFAULT '',
  `value` varchar(150) NOT NULL DEFAULT '',
  `descrip` varchar(150) NOT NULL DEFAULT '',
  `core` smallint(6) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `phpvms_updates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  `lastupdate` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
