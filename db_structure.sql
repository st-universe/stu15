-- phpMyAdmin SQL Dump
-- version 4.9.10
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 21, 2023 at 10:09 PM
-- Server version: 5.5.68-MariaDB
-- PHP Version: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `asdf`
--

-- --------------------------------------------------------

--
-- Table structure for table `stu_allys`
--

CREATE TABLE `stu_allys` (
  `id` int(10) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `pass` varchar(32) NOT NULL DEFAULT '',
  `user_id` int(10) NOT NULL DEFAULT '0',
  `vize` int(10) NOT NULL DEFAULT '0',
  `diplo` int(10) NOT NULL DEFAULT '0',
  `descr` text NOT NULL,
  `hp` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_allys_beziehungen`
--

CREATE TABLE `stu_allys_beziehungen` (
  `id` int(11) NOT NULL,
  `allys_id1` int(11) NOT NULL DEFAULT '0',
  `allys_id2` int(11) NOT NULL DEFAULT '0',
  `type` int(1) NOT NULL DEFAULT '0',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_allys_bez_angebot`
--

CREATE TABLE `stu_allys_bez_angebot` (
  `id` int(11) NOT NULL,
  `allys_id1` int(11) NOT NULL DEFAULT '0',
  `allys_id2` int(11) NOT NULL DEFAULT '0',
  `type` int(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_allys_embassys`
--

CREATE TABLE `stu_allys_embassys` (
  `id` int(11) NOT NULL,
  `allys_id1` int(11) NOT NULL DEFAULT '0',
  `allys_id2` int(11) NOT NULL DEFAULT '0',
  `colonies_id` int(11) NOT NULL DEFAULT '0',
  `field_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_allys_messages`
--

CREATE TABLE `stu_allys_messages` (
  `id` int(10) NOT NULL,
  `subject` varchar(255) NOT NULL DEFAULT '',
  `text` text NOT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_id` int(10) NOT NULL DEFAULT '0',
  `allys_id` int(10) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_buildings`
--

CREATE TABLE `stu_buildings` (
  `id` int(10) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `lager` int(10) NOT NULL DEFAULT '0',
  `eps_cost` int(10) NOT NULL DEFAULT '0',
  `eps` int(10) NOT NULL DEFAULT '0',
  `eps_min` int(3) NOT NULL DEFAULT '0',
  `eps_pro` int(3) NOT NULL DEFAULT '0',
  `bev_pro` int(2) NOT NULL DEFAULT '0',
  `bev_use` int(2) NOT NULL DEFAULT '0',
  `level` int(2) NOT NULL DEFAULT '0',
  `integrity` int(4) NOT NULL DEFAULT '0',
  `research_id` int(10) NOT NULL DEFAULT '0',
  `points` varchar(5) NOT NULL DEFAULT '',
  `view` int(1) NOT NULL DEFAULT '0',
  `schilde` int(3) NOT NULL DEFAULT '0',
  `buildtime` int(10) NOT NULL DEFAULT '0',
  `blimit` tinyint(2) NOT NULL DEFAULT '0',
  `secretimage` varchar(16) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_buildings_cost`
--

CREATE TABLE `stu_buildings_cost` (
  `id` int(10) NOT NULL,
  `buildings_id` int(10) NOT NULL DEFAULT '0',
  `goods_id` int(10) NOT NULL DEFAULT '0',
  `count` int(10) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_buildings_goods`
--

CREATE TABLE `stu_buildings_goods` (
  `id` int(10) NOT NULL,
  `buildings_id` int(3) NOT NULL DEFAULT '0',
  `mode` int(1) NOT NULL DEFAULT '0',
  `goods_id` int(3) NOT NULL DEFAULT '0',
  `count` int(10) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_colonies`
--

CREATE TABLE `stu_colonies` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `colonies_classes_id` int(2) NOT NULL DEFAULT '0',
  `user_id` int(10) NOT NULL DEFAULT '0',
  `coords_x` int(11) NOT NULL DEFAULT '0',
  `coords_y` int(11) NOT NULL DEFAULT '0',
  `energie` int(4) NOT NULL DEFAULT '0',
  `schilde` int(3) NOT NULL DEFAULT '0',
  `bev_used` int(3) NOT NULL DEFAULT '0',
  `bev_free` int(2) NOT NULL DEFAULT '0',
  `max_energie` int(4) NOT NULL DEFAULT '0',
  `max_storage` int(5) NOT NULL DEFAULT '0',
  `max_bev` int(4) NOT NULL DEFAULT '0',
  `max_schilde` int(3) NOT NULL DEFAULT '0',
  `bev_stop_count` int(4) NOT NULL DEFAULT '0',
  `ewopt` int(1) NOT NULL DEFAULT '1',
  `wirtschaft` float NOT NULL DEFAULT '0',
  `sperrung` int(1) NOT NULL DEFAULT '0',
  `schilde_aktiv` tinyint(1) NOT NULL DEFAULT '0',
  `schild_freq1` char(2) NOT NULL DEFAULT '0',
  `schild_freq2` tinyint(1) NOT NULL DEFAULT '0',
  `cloakfield` tinyint(4) NOT NULL DEFAULT '0',
  `temp` int(4) NOT NULL DEFAULT '0',
  `weather` tinyint(1) NOT NULL DEFAULT '1',
  `gravi` float NOT NULL DEFAULT '0',
  `dn_mode` tinyint(1) NOT NULL DEFAULT '0',
  `dn_duration` int(10) NOT NULL DEFAULT '0',
  `dn_nextchange` int(13) NOT NULL DEFAULT '0',
  `mkolz` tinyint(1) NOT NULL DEFAULT '0',
  `wese` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_colonies_classes`
--

CREATE TABLE `stu_colonies_classes` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `mine7` int(1) NOT NULL DEFAULT '0',
  `mine17` int(11) NOT NULL DEFAULT '0',
  `mine33` int(11) NOT NULL DEFAULT '0',
  `mine34` int(11) NOT NULL DEFAULT '0',
  `mine74` int(1) NOT NULL DEFAULT '0',
  `mine75` int(1) NOT NULL DEFAULT '0',
  `mine76` int(1) NOT NULL DEFAULT '0',
  `atmos` tinyint(2) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_colonies_fields`
--

CREATE TABLE `stu_colonies_fields` (
  `id` int(10) NOT NULL,
  `colonies_id` int(10) NOT NULL DEFAULT '0',
  `field_id` int(10) NOT NULL DEFAULT '0',
  `type` int(1) NOT NULL DEFAULT '0',
  `buildings_id` int(10) NOT NULL DEFAULT '0',
  `aktiv` int(1) NOT NULL DEFAULT '0',
  `integrity` int(4) NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL DEFAULT '',
  `buildtime` int(10) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_colonies_orbit`
--

CREATE TABLE `stu_colonies_orbit` (
  `id` int(10) NOT NULL,
  `colonies_id` int(10) NOT NULL DEFAULT '0',
  `field_id` int(10) NOT NULL DEFAULT '0',
  `type` int(2) NOT NULL DEFAULT '0',
  `buildings_id` int(10) NOT NULL DEFAULT '0',
  `aktiv` int(1) NOT NULL DEFAULT '0',
  `integrity` int(4) NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL DEFAULT '',
  `buildtime` int(10) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_colonies_storage`
--

CREATE TABLE `stu_colonies_storage` (
  `colonies_id` int(10) NOT NULL DEFAULT '0',
  `user_id` int(10) NOT NULL DEFAULT '0',
  `goods_id` int(10) NOT NULL DEFAULT '0',
  `count` int(10) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_colonies_underground`
--

CREATE TABLE `stu_colonies_underground` (
  `id` int(10) NOT NULL,
  `colonies_id` int(10) NOT NULL DEFAULT '0',
  `field_id` int(10) NOT NULL DEFAULT '0',
  `type` int(2) NOT NULL DEFAULT '0',
  `buildings_id` int(3) NOT NULL DEFAULT '0',
  `aktiv` int(1) NOT NULL DEFAULT '0',
  `integrity` int(4) NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL DEFAULT '',
  `buildtime` int(10) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_contactlist`
--

CREATE TABLE `stu_contactlist` (
  `id` int(10) NOT NULL,
  `recipient` int(10) NOT NULL DEFAULT '0',
  `user_id` int(10) NOT NULL DEFAULT '0',
  `behaviour` int(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_dock_permissions`
--

CREATE TABLE `stu_dock_permissions` (
  `id` int(11) NOT NULL,
  `ships_id` int(11) NOT NULL DEFAULT '0',
  `type` varchar(20) NOT NULL DEFAULT '',
  `id2` int(11) NOT NULL DEFAULT '0',
  `mode` int(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_event_history`
--

CREATE TABLE `stu_event_history` (
  `id` int(11) NOT NULL,
  `message` text NOT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `type` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_field_build`
--

CREATE TABLE `stu_field_build` (
  `id` int(11) NOT NULL,
  `type` int(3) NOT NULL DEFAULT '0',
  `buildings_id` int(3) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_fleets`
--

CREATE TABLE `stu_fleets` (
  `id` int(11) NOT NULL,
  `ships_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(175) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_game`
--

CREATE TABLE `stu_game` (
  `id` int(5) NOT NULL,
  `fielddescr` varchar(255) NOT NULL DEFAULT '',
  `value` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_game_rounds`
--

CREATE TABLE `stu_game_rounds` (
  `id` int(11) NOT NULL,
  `runde` int(11) NOT NULL DEFAULT '0',
  `start` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ende` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_goods`
--

CREATE TABLE `stu_goods` (
  `id` int(10) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `wfaktor` float NOT NULL DEFAULT '1',
  `hide` tinyint(1) NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL DEFAULT '0',
  `secretimage` varchar(16) DEFAULT '0',
  `maxoffer` int(14) DEFAULT '50000'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_informants`
--

CREATE TABLE `stu_informants` (
  `id` int(2) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT '',
  `pic` tinyint(3) NOT NULL DEFAULT '0',
  `posten` int(2) NOT NULL DEFAULT '0',
  `price` int(5) NOT NULL DEFAULT '0',
  `infoId` int(2) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `map_sectors_id` tinyint(2) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_informants_data`
--

CREATE TABLE `stu_informants_data` (
  `id` int(11) NOT NULL,
  `rasse` varchar(255) NOT NULL DEFAULT '',
  `beruf` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_informants_user`
--

CREATE TABLE `stu_informants_user` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_kn_messages`
--

CREATE TABLE `stu_kn_messages` (
  `id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL DEFAULT '',
  `text` text NOT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_id` int(12) NOT NULL DEFAULT '0',
  `official` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_map_fields`
--

CREATE TABLE `stu_map_fields` (
  `id` int(14) NOT NULL,
  `coords_x` int(5) NOT NULL DEFAULT '0',
  `coords_y` int(5) NOT NULL DEFAULT '0',
  `type` int(2) NOT NULL DEFAULT '0',
  `race` tinyint(3) NOT NULL DEFAULT '0',
  `wese` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_map_sectors`
--

CREATE TABLE `stu_map_sectors` (
  `id` tinyint(2) NOT NULL,
  `coords_x1` int(3) NOT NULL DEFAULT '0',
  `coords_x2` int(3) NOT NULL DEFAULT '0',
  `coords_y1` int(3) NOT NULL DEFAULT '0',
  `coords_y2` int(3) NOT NULL DEFAULT '0',
  `hide` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_map_sectors_user`
--

CREATE TABLE `stu_map_sectors_user` (
  `id` int(10) NOT NULL,
  `map_sectors_id` tinyint(2) NOT NULL DEFAULT '0',
  `user_id` int(10) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_map_special`
--

CREATE TABLE `stu_map_special` (
  `id` int(10) NOT NULL,
  `coords_x` int(4) NOT NULL DEFAULT '0',
  `coords_y` int(4) NOT NULL DEFAULT '0',
  `type` tinyint(3) NOT NULL DEFAULT '0',
  `wese` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_modules_user`
--

CREATE TABLE `stu_modules_user` (
  `id` int(3) NOT NULL,
  `user_id` int(13) NOT NULL DEFAULT '0',
  `modules_id` int(13) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_pms`
--

CREATE TABLE `stu_pms` (
  `id` int(15) NOT NULL,
  `sender` int(10) NOT NULL DEFAULT '0',
  `recipient` int(10) NOT NULL DEFAULT '0',
  `message` text NOT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `new` int(1) NOT NULL DEFAULT '1',
  `cate` tinyint(1) NOT NULL DEFAULT '1',
  `send_del` tinyint(1) NOT NULL DEFAULT '0',
  `recip_del` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_pm_saved`
--

CREATE TABLE `stu_pm_saved` (
  `id` int(10) NOT NULL,
  `recipient` int(10) NOT NULL DEFAULT '0',
  `sender` int(10) NOT NULL DEFAULT '0',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `message` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_research_depencies`
--

CREATE TABLE `stu_research_depencies` (
  `id` int(10) NOT NULL,
  `research_id` int(10) NOT NULL DEFAULT '0',
  `depency_id` int(10) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_research_list`
--

CREATE TABLE `stu_research_list` (
  `id` int(10) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `descr` text NOT NULL,
  `cost` int(10) NOT NULL DEFAULT '0',
  `rasse` int(1) NOT NULL DEFAULT '0',
  `pic` varchar(255) NOT NULL DEFAULT '',
  `sort` int(3) NOT NULL DEFAULT '0',
  `ships_id` int(10) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_research_user`
--

CREATE TABLE `stu_research_user` (
  `id` int(10) NOT NULL,
  `research_id` int(10) NOT NULL DEFAULT '0',
  `user_id` int(10) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_sector_flights`
--

CREATE TABLE `stu_sector_flights` (
  `id` int(11) NOT NULL,
  `ships_rumps_id` int(3) NOT NULL DEFAULT '0',
  `colonies_id` int(10) NOT NULL DEFAULT '0',
  `user_id` int(10) NOT NULL DEFAULT '0',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_sensor_detects`
--

CREATE TABLE `stu_sensor_detects` (
  `phalanx_id` int(10) NOT NULL DEFAULT '0',
  `ships_id` int(10) NOT NULL DEFAULT '0',
  `ships_rumps_id` int(5) NOT NULL DEFAULT '0',
  `user_id` int(10) NOT NULL DEFAULT '0',
  `coords_x` int(5) NOT NULL DEFAULT '0',
  `coords_y` int(5) NOT NULL DEFAULT '0',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_ships`
--

CREATE TABLE `stu_ships` (
  `id` int(14) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `ships_rumps_id` int(10) NOT NULL DEFAULT '0',
  `fleets_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(14) NOT NULL DEFAULT '0',
  `coords_x` int(11) NOT NULL DEFAULT '0',
  `coords_y` int(11) NOT NULL DEFAULT '0',
  `energie` int(5) NOT NULL DEFAULT '0',
  `huelle` int(10) NOT NULL DEFAULT '0',
  `schilde` int(5) NOT NULL DEFAULT '0',
  `schilde_aktiv` int(1) NOT NULL DEFAULT '0',
  `alertlevel` int(1) NOT NULL DEFAULT '1',
  `strb_mode` int(1) NOT NULL DEFAULT '1',
  `cloak` int(1) NOT NULL DEFAULT '0',
  `batt` int(4) NOT NULL DEFAULT '0',
  `replikator` int(1) NOT NULL DEFAULT '0',
  `lss` int(1) NOT NULL DEFAULT '0',
  `kss` int(1) NOT NULL DEFAULT '0',
  `crew` int(5) NOT NULL DEFAULT '0',
  `warpcore` int(4) NOT NULL DEFAULT '0',
  `tachyon` tinyint(1) NOT NULL DEFAULT '0',
  `epsupgrade` tinyint(1) NOT NULL DEFAULT '0',
  `huellmodlvl` tinyint(3) NOT NULL DEFAULT '0',
  `sensormodlvl` tinyint(3) NOT NULL DEFAULT '0',
  `waffenmodlvl` tinyint(3) NOT NULL DEFAULT '0',
  `schildmodlvl` tinyint(3) NOT NULL DEFAULT '0',
  `reaktormodlvl` tinyint(3) NOT NULL DEFAULT '0',
  `antriebmodlvl` tinyint(3) NOT NULL DEFAULT '0',
  `computermodlvl` tinyint(3) NOT NULL DEFAULT '0',
  `epsmodlvl` tinyint(3) NOT NULL DEFAULT '0',
  `points` float NOT NULL DEFAULT '0',
  `dock` int(10) NOT NULL DEFAULT '0',
  `traktor` int(10) NOT NULL DEFAULT '0',
  `traktormode` tinyint(1) NOT NULL DEFAULT '0',
  `trumoldrump` int(10) NOT NULL DEFAULT '3',
  `actscan` tinyint(1) NOT NULL DEFAULT '0',
  `wese` tinyint(1) NOT NULL DEFAULT '1',
  `deact` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_ships_action`
--

CREATE TABLE `stu_ships_action` (
  `id` int(10) NOT NULL,
  `mode` varchar(10) NOT NULL DEFAULT '',
  `ships_id` int(10) NOT NULL DEFAULT '0',
  `ships_id2` varchar(10) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_ships_build`
--

CREATE TABLE `stu_ships_build` (
  `id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL DEFAULT '0',
  `ships_rumps_id` int(10) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_ships_buildprogress`
--

CREATE TABLE `stu_ships_buildprogress` (
  `id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL DEFAULT '0',
  `colonies_id` int(10) NOT NULL DEFAULT '0',
  `ships_id` int(10) NOT NULL DEFAULT '0',
  `ships_rumps_id` int(10) NOT NULL DEFAULT '0',
  `huelle` int(10) NOT NULL DEFAULT '0',
  `huellmodlvl` tinyint(3) NOT NULL DEFAULT '0',
  `sensormodlvl` tinyint(3) NOT NULL DEFAULT '0',
  `waffenmodlvl` tinyint(3) NOT NULL DEFAULT '0',
  `schildmodlvl` tinyint(3) NOT NULL DEFAULT '0',
  `reaktormodlvl` tinyint(3) NOT NULL DEFAULT '0',
  `antriebmodlvl` tinyint(3) NOT NULL DEFAULT '0',
  `computermodlvl` tinyint(3) NOT NULL DEFAULT '0',
  `epsmodlvl` tinyint(3) NOT NULL DEFAULT '0',
  `buildtime` int(12) NOT NULL DEFAULT '0',
  `points` float NOT NULL DEFAULT '0',
  `wese` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_ships_cost`
--

CREATE TABLE `stu_ships_cost` (
  `id` int(10) NOT NULL,
  `ships_rumps_id` int(10) NOT NULL DEFAULT '0',
  `goods_id` int(10) NOT NULL DEFAULT '0',
  `count` int(10) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_ships_goods`
--

CREATE TABLE `stu_ships_goods` (
  `id` int(10) NOT NULL,
  `ships_rumps_id` int(10) NOT NULL DEFAULT '0',
  `goods_id` int(10) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_ships_ki`
--

CREATE TABLE `stu_ships_ki` (
  `ships_id` int(10) NOT NULL DEFAULT '0',
  `endx` int(5) NOT NULL DEFAULT '0',
  `endy` int(5) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_ships_ki_waypoints`
--

CREATE TABLE `stu_ships_ki_waypoints` (
  `id` int(10) NOT NULL,
  `ships_id` varchar(13) NOT NULL DEFAULT '0',
  `coords_x` int(4) NOT NULL DEFAULT '0',
  `coords_y` int(4) NOT NULL DEFAULT '0',
  `aktiv` tinyint(1) NOT NULL DEFAULT '0',
  `nwp` int(10) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_ships_modules`
--

CREATE TABLE `stu_ships_modules` (
  `id` int(11) NOT NULL,
  `type` tinyint(2) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `lvl` tinyint(1) NOT NULL DEFAULT '0',
  `wirt` float NOT NULL DEFAULT '0',
  `buildtime` int(11) NOT NULL DEFAULT '0',
  `huell` tinyint(3) NOT NULL DEFAULT '0',
  `eps` tinyint(3) NOT NULL DEFAULT '0',
  `phaser` tinyint(2) NOT NULL DEFAULT '0',
  `torp_evade` tinyint(2) NOT NULL DEFAULT '0',
  `reaktor` tinyint(2) NOT NULL DEFAULT '0',
  `phaser_chance` tinyint(3) NOT NULL DEFAULT '0',
  `lss_range` tinyint(2) NOT NULL DEFAULT '0',
  `shields` tinyint(3) NOT NULL DEFAULT '0',
  `goods_id` int(2) NOT NULL DEFAULT '0',
  `ecost` tinyint(3) NOT NULL DEFAULT '0',
  `view` tinyint(1) NOT NULL DEFAULT '0',
  `demontchg` tinyint(3) NOT NULL DEFAULT '0',
  `besonder` varchar(255) NOT NULL DEFAULT 'keine'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_ships_modules_cost`
--

CREATE TABLE `stu_ships_modules_cost` (
  `id` int(10) NOT NULL,
  `modules_id` int(10) NOT NULL DEFAULT '0',
  `goods_id` int(10) NOT NULL DEFAULT '0',
  `count` int(10) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_ships_rumps`
--

CREATE TABLE `stu_ships_rumps` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `huellmod` int(3) NOT NULL DEFAULT '0',
  `huellmod_max` tinyint(3) NOT NULL DEFAULT '0',
  `huellmod_min` tinyint(3) NOT NULL DEFAULT '0',
  `schildmod` int(3) NOT NULL DEFAULT '0',
  `schildmod_max` tinyint(3) NOT NULL DEFAULT '0',
  `schildmod_min` tinyint(3) NOT NULL DEFAULT '0',
  `epsmod` int(3) NOT NULL DEFAULT '0',
  `epsmod_max` tinyint(3) NOT NULL DEFAULT '0',
  `epsmod_min` tinyint(3) NOT NULL DEFAULT '0',
  `reaktormod_max` tinyint(1) NOT NULL DEFAULT '0',
  `reaktormod_min` tinyint(1) NOT NULL DEFAULT '0',
  `computermod_max` tinyint(1) NOT NULL DEFAULT '0',
  `computermod_min` tinyint(1) NOT NULL DEFAULT '0',
  `antriebsmod_max` tinyint(1) NOT NULL DEFAULT '0',
  `antriebsmod_min` tinyint(1) NOT NULL DEFAULT '0',
  `waffenmod` smallint(3) DEFAULT '0',
  `waffenmod_max` tinyint(1) NOT NULL DEFAULT '0',
  `waffenmod_min` tinyint(1) NOT NULL DEFAULT '0',
  `sensormod` tinyint(2) NOT NULL DEFAULT '0',
  `sensormod_max` tinyint(1) NOT NULL DEFAULT '0',
  `sensormod_min` tinyint(1) NOT NULL DEFAULT '0',
  `max_batt` int(3) NOT NULL DEFAULT '0',
  `bussard` tinyint(2) NOT NULL DEFAULT '0',
  `erz` tinyint(2) NOT NULL DEFAULT '0',
  `crew` int(5) NOT NULL DEFAULT '0',
  `crew_min` int(5) NOT NULL DEFAULT '0',
  `cloak` tinyint(1) NOT NULL DEFAULT '0',
  `fusion` tinyint(2) NOT NULL DEFAULT '0',
  `slots` tinyint(3) NOT NULL DEFAULT '0',
  `replikator` tinyint(1) NOT NULL DEFAULT '0',
  `storage` int(5) NOT NULL DEFAULT '0',
  `torps` tinyint(3) NOT NULL DEFAULT '0',
  `torp_evade` tinyint(3) NOT NULL DEFAULT '0',
  `sorta` tinyint(3) NOT NULL DEFAULT '0',
  `sortb` tinyint(3) NOT NULL DEFAULT '0',
  `tachyon` tinyint(1) NOT NULL DEFAULT '0',
  `view` tinyint(1) NOT NULL DEFAULT '0',
  `ewerft` tinyint(1) NOT NULL DEFAULT '0',
  `points` float NOT NULL DEFAULT '0',
  `buildtime` int(10) NOT NULL DEFAULT '0',
  `trumfield` tinyint(1) NOT NULL DEFAULT '0',
  `eps_cost` smallint(4) NOT NULL DEFAULT '0',
  `probe` tinyint(1) NOT NULL DEFAULT '0',
  `probe_stor` smallint(3) NOT NULL DEFAULT '0',
  `size` tinyint(1) NOT NULL DEFAULT '3',
  `secretimage` varchar(10) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_ships_storage`
--

CREATE TABLE `stu_ships_storage` (
  `ships_id` int(10) NOT NULL DEFAULT '0',
  `user_id` int(10) NOT NULL DEFAULT '0',
  `goods_id` int(10) NOT NULL DEFAULT '0',
  `count` int(10) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_ships_uncloaked`
--

CREATE TABLE `stu_ships_uncloaked` (
  `id` int(11) NOT NULL,
  `ships_id` int(10) NOT NULL DEFAULT '0',
  `user_id` int(10) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_spy_action`
--

CREATE TABLE `stu_spy_action` (
  `id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL DEFAULT '0',
  `colonies_id` int(11) NOT NULL DEFAULT '0',
  `count` int(10) NOT NULL DEFAULT '0',
  `user_id2` int(11) NOT NULL DEFAULT '0',
  `id2` int(10) NOT NULL DEFAULT '0',
  `action` int(11) NOT NULL DEFAULT '0',
  `value` int(10) NOT NULL DEFAULT '0',
  `start` int(10) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_stats`
--

CREATE TABLE `stu_stats` (
  `user_id` int(12) NOT NULL DEFAULT '0',
  `ship_count` tinyint(3) NOT NULL DEFAULT '0',
  `wirtschaft` varchar(6) NOT NULL DEFAULT '',
  `bev` int(4) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_stats_iptable`
--

CREATE TABLE `stu_stats_iptable` (
  `user_id` int(10) NOT NULL DEFAULT '0',
  `ip` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `agent` varchar(255) NOT NULL DEFAULT '',
  `start_tsp` int(15) NOT NULL DEFAULT '0',
  `ende_tsp` int(15) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_stats_shipstopten`
--

CREATE TABLE `stu_stats_shipstopten` (
  `id` int(10) NOT NULL,
  `count` smallint(4) NOT NULL DEFAULT '0',
  `user_id` int(10) NOT NULL DEFAULT '0',
  `runde` int(10) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_terraform`
--

CREATE TABLE `stu_terraform` (
  `id` int(4) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `ecost` int(3) NOT NULL DEFAULT '0',
  `v_feld` int(3) NOT NULL DEFAULT '0',
  `z_feld` int(3) NOT NULL DEFAULT '0',
  `symp_min` tinyint(3) NOT NULL DEFAULT '0',
  `symp_plus` tinyint(3) NOT NULL DEFAULT '0',
  `research_id` int(10) NOT NULL DEFAULT '0',
  `uglift` tinyint(1) NOT NULL DEFAULT '0',
  `save31` tinyint(1) NOT NULL DEFAULT '0',
  `flimit` tinyint(2) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_terraform_cost`
--

CREATE TABLE `stu_terraform_cost` (
  `terraform_id` int(4) NOT NULL DEFAULT '0',
  `goods_id` int(3) NOT NULL DEFAULT '0',
  `count` int(3) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_torpedo_types`
--

CREATE TABLE `stu_torpedo_types` (
  `id` tinyint(3) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `damage` int(4) NOT NULL DEFAULT '0',
  `research_id` int(10) NOT NULL DEFAULT '0',
  `evade` float NOT NULL DEFAULT '0',
  `goods_id` int(10) NOT NULL DEFAULT '0',
  `size` tinyint(3) NOT NULL DEFAULT '3'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_trade_goods`
--

CREATE TABLE `stu_trade_goods` (
  `id` int(10) NOT NULL,
  `trade_offers_id` int(10) NOT NULL DEFAULT '0',
  `goods_id` int(2) NOT NULL DEFAULT '0',
  `count` int(10) NOT NULL DEFAULT '0',
  `status` int(1) NOT NULL DEFAULT '0',
  `user_id` int(10) NOT NULL DEFAULT '0',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_trade_logs`
--

CREATE TABLE `stu_trade_logs` (
  `id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL DEFAULT '0',
  `aktion` varchar(255) NOT NULL DEFAULT '',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_trade_offers`
--

CREATE TABLE `stu_trade_offers` (
  `id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL DEFAULT '0',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `acount` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_user`
--

CREATE TABLE `stu_user` (
  `id` int(11) NOT NULL,
  `user` varchar(255) NOT NULL DEFAULT '',
  `pass` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `login` varchar(100) NOT NULL DEFAULT '',
  `rasse` int(1) NOT NULL DEFAULT '0',
  `symp` int(7) NOT NULL DEFAULT '0',
  `startrunde` int(11) NOT NULL DEFAULT '0',
  `lastaction` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lastloginround` int(10) NOT NULL DEFAULT '0',
  `level` int(1) NOT NULL DEFAULT '0',
  `aktiv` int(1) NOT NULL DEFAULT '0',
  `act_code` varchar(32) NOT NULL DEFAULT '',
  `allys_id` int(10) NOT NULL DEFAULT '0',
  `status` int(2) NOT NULL DEFAULT '0',
  `kn_lz` int(7) NOT NULL DEFAULT '1',
  `kn_allylz` int(10) NOT NULL DEFAULT '1',
  `grafik` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT 'http://gfx.stuniverse.de',
  `mozilla` tinyint(1) NOT NULL DEFAULT '0',
  `spy` int(1) NOT NULL DEFAULT '0',
  `delmark` int(1) NOT NULL DEFAULT '0',
  `picture` varchar(255) NOT NULL DEFAULT '',
  `wirtmin` float NOT NULL DEFAULT '0',
  `wirtplus` float NOT NULL DEFAULT '0',
  `hasperr` int(1) NOT NULL DEFAULT '0',
  `knsperr` int(1) NOT NULL DEFAULT '0',
  `vac` tinyint(1) NOT NULL DEFAULT '0',
  `pvac` tinyint(1) NOT NULL DEFAULT '2',
  `vactime` int(12) NOT NULL DEFAULT '0',
  `halfnpc` tinyint(1) NOT NULL DEFAULT '0',
  `knanl` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_user_levels`
--

CREATE TABLE `stu_user_levels` (
  `id` int(2) NOT NULL,
  `level` int(2) NOT NULL DEFAULT '0',
  `symp` int(10) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_user_passrec`
--

CREATE TABLE `stu_user_passrec` (
  `id` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_id` int(10) NOT NULL DEFAULT '0',
  `act` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_user_profiles`
--

CREATE TABLE `stu_user_profiles` (
  `id` int(12) NOT NULL,
  `user_id` int(12) NOT NULL DEFAULT '0',
  `rpgtxt` text NOT NULL,
  `icq` int(12) NOT NULL DEFAULT '0',
  `regierung` varchar(150) NOT NULL DEFAULT '0',
  `sl_sorttype` varchar(20) NOT NULL DEFAULT '',
  `sl_sortway` varchar(20) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stu_wormholes`
--

CREATE TABLE `stu_wormholes` (
  `id` int(11) NOT NULL,
  `start_x` int(5) NOT NULL DEFAULT '0',
  `start_y` int(5) NOT NULL DEFAULT '0',
  `end_x` int(5) NOT NULL DEFAULT '0',
  `end_y` int(5) NOT NULL DEFAULT '0',
  `stable` int(1) NOT NULL DEFAULT '0',
  `start_wese` tinyint(1) NOT NULL DEFAULT '1',
  `end_wese` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `stu_allys`
--
ALTER TABLE `stu_allys`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `stu_allys_beziehungen`
--
ALTER TABLE `stu_allys_beziehungen`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `allys_id1` (`allys_id1`),
  ADD KEY `allys_id2` (`allys_id2`),
  ADD KEY `type` (`type`);

--
-- Indexes for table `stu_allys_bez_angebot`
--
ALTER TABLE `stu_allys_bez_angebot`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `allys_id1` (`allys_id1`),
  ADD KEY `allys_id2` (`allys_id2`);

--
-- Indexes for table `stu_allys_embassys`
--
ALTER TABLE `stu_allys_embassys`
  ADD PRIMARY KEY (`id`),
  ADD KEY `allys_id1` (`allys_id1`),
  ADD KEY `allys_id2` (`allys_id2`),
  ADD KEY `colonies_id` (`colonies_id`),
  ADD KEY `field_id` (`field_id`);

--
-- Indexes for table `stu_allys_messages`
--
ALTER TABLE `stu_allys_messages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `date` (`date`),
  ADD KEY `allys_id` (`allys_id`);

--
-- Indexes for table `stu_buildings`
--
ALTER TABLE `stu_buildings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `level` (`level`),
  ADD KEY `research_id` (`research_id`),
  ADD KEY `eps_min` (`eps_min`),
  ADD KEY `bev_use` (`bev_use`),
  ADD KEY `bev_pro` (`bev_pro`);

--
-- Indexes for table `stu_buildings_cost`
--
ALTER TABLE `stu_buildings_cost`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `buildings_id` (`buildings_id`),
  ADD KEY `goods_id` (`goods_id`);

--
-- Indexes for table `stu_buildings_goods`
--
ALTER TABLE `stu_buildings_goods`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `goods_id` (`goods_id`),
  ADD KEY `mode` (`mode`),
  ADD KEY `buildings_id` (`buildings_id`),
  ADD KEY `count` (`count`);

--
-- Indexes for table `stu_colonies`
--
ALTER TABLE `stu_colonies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `coords_x` (`coords_x`),
  ADD KEY `coords_y` (`coords_y`),
  ADD KEY `colonies_classes_id` (`colonies_classes_id`),
  ADD KEY `dn_nextchange` (`dn_nextchange`),
  ADD KEY `weather` (`weather`),
  ADD KEY `temp` (`temp`),
  ADD KEY `wese` (`wese`);

--
-- Indexes for table `stu_colonies_classes`
--
ALTER TABLE `stu_colonies_classes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `stu_colonies_fields`
--
ALTER TABLE `stu_colonies_fields`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `colonies_id` (`colonies_id`),
  ADD KEY `buildings_id` (`buildings_id`),
  ADD KEY `aktiv` (`aktiv`),
  ADD KEY `field_id` (`field_id`),
  ADD KEY `buildtime` (`buildtime`),
  ADD KEY `type` (`type`);

--
-- Indexes for table `stu_colonies_orbit`
--
ALTER TABLE `stu_colonies_orbit`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `colonies_id` (`colonies_id`),
  ADD KEY `aktiv` (`aktiv`),
  ADD KEY `buildings_id` (`buildings_id`),
  ADD KEY `buildtime` (`buildtime`),
  ADD KEY `field_id` (`field_id`);

--
-- Indexes for table `stu_colonies_storage`
--
ALTER TABLE `stu_colonies_storage`
  ADD KEY `colonies_id` (`colonies_id`),
  ADD KEY `goods_id` (`goods_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `count` (`count`);

--
-- Indexes for table `stu_colonies_underground`
--
ALTER TABLE `stu_colonies_underground`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `aktiv` (`aktiv`),
  ADD KEY `buildings_id` (`buildings_id`),
  ADD KEY `buildtime` (`buildtime`),
  ADD KEY `colonies_id` (`colonies_id`),
  ADD KEY `field_id` (`field_id`);

--
-- Indexes for table `stu_contactlist`
--
ALTER TABLE `stu_contactlist`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `recipient` (`recipient`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `behaviour` (`behaviour`);

--
-- Indexes for table `stu_dock_permissions`
--
ALTER TABLE `stu_dock_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `ships_id` (`ships_id`),
  ADD KEY `id2` (`id2`),
  ADD KEY `mode` (`mode`);

--
-- Indexes for table `stu_event_history`
--
ALTER TABLE `stu_event_history`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `date` (`date`),
  ADD KEY `type` (`type`);

--
-- Indexes for table `stu_field_build`
--
ALTER TABLE `stu_field_build`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `type` (`type`),
  ADD KEY `buildings_id` (`buildings_id`);

--
-- Indexes for table `stu_fleets`
--
ALTER TABLE `stu_fleets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `ships_id` (`ships_id`);

--
-- Indexes for table `stu_game`
--
ALTER TABLE `stu_game`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `fielddescr` (`fielddescr`),
  ADD KEY `value` (`value`);

--
-- Indexes for table `stu_game_rounds`
--
ALTER TABLE `stu_game_rounds`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `stu_goods`
--
ALTER TABLE `stu_goods`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `hide` (`hide`),
  ADD KEY `sort` (`sort`);

--
-- Indexes for table `stu_informants`
--
ALTER TABLE `stu_informants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `map_sectors_id` (`map_sectors_id`),
  ADD KEY `posten` (`posten`);

--
-- Indexes for table `stu_informants_data`
--
ALTER TABLE `stu_informants_data`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `stu_informants_user`
--
ALTER TABLE `stu_informants_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `stu_kn_messages`
--
ALTER TABLE `stu_kn_messages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `date` (`date`),
  ADD KEY `official` (`official`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `stu_map_fields`
--
ALTER TABLE `stu_map_fields`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `coords_x` (`coords_x`),
  ADD KEY `coords_y` (`coords_y`),
  ADD KEY `type` (`type`),
  ADD KEY `race` (`race`),
  ADD KEY `wese` (`wese`);

--
-- Indexes for table `stu_map_sectors`
--
ALTER TABLE `stu_map_sectors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `coords_x1` (`coords_x1`),
  ADD KEY `coords_x2` (`coords_x2`),
  ADD KEY `coords_y1` (`coords_y1`),
  ADD KEY `coords_y2` (`coords_y2`);

--
-- Indexes for table `stu_map_sectors_user`
--
ALTER TABLE `stu_map_sectors_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `map_sectors_id` (`map_sectors_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `stu_map_special`
--
ALTER TABLE `stu_map_special`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type` (`type`),
  ADD KEY `coords_x` (`coords_x`),
  ADD KEY `coords_y` (`coords_y`),
  ADD KEY `wese` (`wese`);

--
-- Indexes for table `stu_modules_user`
--
ALTER TABLE `stu_modules_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `modules_id` (`modules_id`);

--
-- Indexes for table `stu_pms`
--
ALTER TABLE `stu_pms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `recipient` (`recipient`),
  ADD KEY `sender` (`sender`),
  ADD KEY `date` (`date`),
  ADD KEY `new` (`new`),
  ADD KEY `cate` (`cate`),
  ADD KEY `send_del` (`send_del`),
  ADD KEY `recip_del` (`recip_del`);

--
-- Indexes for table `stu_pm_saved`
--
ALTER TABLE `stu_pm_saved`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `stu_research_depencies`
--
ALTER TABLE `stu_research_depencies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `depency_id` (`depency_id`),
  ADD KEY `research_id` (`research_id`);

--
-- Indexes for table `stu_research_list`
--
ALTER TABLE `stu_research_list`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `rasse` (`rasse`);

--
-- Indexes for table `stu_research_user`
--
ALTER TABLE `stu_research_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `research_id` (`research_id`);

--
-- Indexes for table `stu_sector_flights`
--
ALTER TABLE `stu_sector_flights`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `colonies_id` (`colonies_id`),
  ADD KEY `date` (`date`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `stu_sensor_detects`
--
ALTER TABLE `stu_sensor_detects`
  ADD KEY `date` (`date`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `phalanx_id` (`phalanx_id`),
  ADD KEY `ships_id` (`ships_id`),
  ADD KEY `coords_x` (`coords_x`),
  ADD KEY `coords_y` (`coords_y`);

--
-- Indexes for table `stu_ships`
--
ALTER TABLE `stu_ships`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `coords_x` (`coords_x`),
  ADD KEY `coords_y` (`coords_y`),
  ADD KEY `crew` (`crew`),
  ADD KEY `fleets_id` (`fleets_id`),
  ADD KEY `energie` (`energie`),
  ADD KEY `alertlevel` (`alertlevel`),
  ADD KEY `cloak` (`cloak`),
  ADD KEY `trumoldrump` (`trumoldrump`),
  ADD KEY `computermodlvl` (`computermodlvl`),
  ADD KEY `sensormodlvl` (`sensormodlvl`),
  ADD KEY `actscan` (`actscan`),
  ADD KEY `wese` (`wese`),
  ADD KEY `lss` (`lss`),
  ADD KEY `ships_rumps_id` (`ships_rumps_id`),
  ADD KEY `kss` (`kss`),
  ADD KEY `tachyon` (`tachyon`);

--
-- Indexes for table `stu_ships_action`
--
ALTER TABLE `stu_ships_action`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mode` (`mode`),
  ADD KEY `ships_id` (`ships_id`),
  ADD KEY `ships_id2` (`ships_id2`);

--
-- Indexes for table `stu_ships_build`
--
ALTER TABLE `stu_ships_build`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `ships_rumps_id` (`ships_rumps_id`);

--
-- Indexes for table `stu_ships_buildprogress`
--
ALTER TABLE `stu_ships_buildprogress`
  ADD PRIMARY KEY (`id`),
  ADD KEY `buildtime` (`buildtime`),
  ADD KEY `colonies_id` (`colonies_id`),
  ADD KEY `ships_id` (`ships_id`),
  ADD KEY `ships_rumps_id` (`ships_rumps_id`);

--
-- Indexes for table `stu_ships_cost`
--
ALTER TABLE `stu_ships_cost`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `ships_rumps_id` (`ships_rumps_id`),
  ADD KEY `goods_id` (`goods_id`);

--
-- Indexes for table `stu_ships_goods`
--
ALTER TABLE `stu_ships_goods`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `ships_rumps_id` (`ships_rumps_id`),
  ADD KEY `goods_id` (`goods_id`);

--
-- Indexes for table `stu_ships_ki`
--
ALTER TABLE `stu_ships_ki`
  ADD UNIQUE KEY `ships_id` (`ships_id`),
  ADD KEY `endx` (`endx`),
  ADD KEY `endy` (`endy`);

--
-- Indexes for table `stu_ships_ki_waypoints`
--
ALTER TABLE `stu_ships_ki_waypoints`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ships_id` (`ships_id`);

--
-- Indexes for table `stu_ships_modules`
--
ALTER TABLE `stu_ships_modules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type` (`type`),
  ADD KEY `lvl` (`lvl`),
  ADD KEY `id` (`id`),
  ADD KEY `view` (`view`),
  ADD KEY `goods_id` (`goods_id`);

--
-- Indexes for table `stu_ships_modules_cost`
--
ALTER TABLE `stu_ships_modules_cost`
  ADD PRIMARY KEY (`id`),
  ADD KEY `modules_id` (`modules_id`),
  ADD KEY `goods_id` (`goods_id`);

--
-- Indexes for table `stu_ships_rumps`
--
ALTER TABLE `stu_ships_rumps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`),
  ADD KEY `sorta` (`sorta`),
  ADD KEY `sortb` (`sortb`),
  ADD KEY `view` (`view`);

--
-- Indexes for table `stu_ships_storage`
--
ALTER TABLE `stu_ships_storage`
  ADD KEY `user_id` (`user_id`),
  ADD KEY `ships_id` (`ships_id`),
  ADD KEY `goods_id` (`goods_id`),
  ADD KEY `count` (`count`);

--
-- Indexes for table `stu_ships_uncloaked`
--
ALTER TABLE `stu_ships_uncloaked`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `ships_id` (`ships_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `stu_spy_action`
--
ALTER TABLE `stu_spy_action`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `colonies_id` (`colonies_id`);

--
-- Indexes for table `stu_stats`
--
ALTER TABLE `stu_stats`
  ADD KEY `user_id` (`user_id`),
  ADD KEY `wirtschaft` (`wirtschaft`),
  ADD KEY `bev` (`bev`),
  ADD KEY `ship_count` (`ship_count`);

--
-- Indexes for table `stu_stats_iptable`
--
ALTER TABLE `stu_stats_iptable`
  ADD KEY `user_id` (`user_id`),
  ADD KEY `ip` (`ip`),
  ADD KEY `start_tsp` (`start_tsp`),
  ADD KEY `ende_tsp` (`ende_tsp`);

--
-- Indexes for table `stu_stats_shipstopten`
--
ALTER TABLE `stu_stats_shipstopten`
  ADD PRIMARY KEY (`id`),
  ADD KEY `runde` (`runde`);

--
-- Indexes for table `stu_terraform`
--
ALTER TABLE `stu_terraform`
  ADD PRIMARY KEY (`id`),
  ADD KEY `v_feld` (`v_feld`),
  ADD KEY `research_id` (`research_id`);

--
-- Indexes for table `stu_terraform_cost`
--
ALTER TABLE `stu_terraform_cost`
  ADD KEY `terraform_id` (`terraform_id`),
  ADD KEY `goods_id` (`goods_id`),
  ADD KEY `count` (`count`);

--
-- Indexes for table `stu_torpedo_types`
--
ALTER TABLE `stu_torpedo_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `goods_id` (`goods_id`);

--
-- Indexes for table `stu_trade_goods`
--
ALTER TABLE `stu_trade_goods`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `trade_offers_id` (`trade_offers_id`),
  ADD KEY `goods_id` (`goods_id`),
  ADD KEY `count` (`count`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `stu_trade_logs`
--
ALTER TABLE `stu_trade_logs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `stu_trade_offers`
--
ALTER TABLE `stu_trade_offers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `date` (`date`);

--
-- Indexes for table `stu_user`
--
ALTER TABLE `stu_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `login` (`login`),
  ADD KEY `user` (`user`),
  ADD KEY `lastloginround` (`lastloginround`),
  ADD KEY `aktiv` (`aktiv`),
  ADD KEY `status` (`status`),
  ADD KEY `allys_id` (`allys_id`);

--
-- Indexes for table `stu_user_levels`
--
ALTER TABLE `stu_user_levels`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `stu_user_passrec`
--
ALTER TABLE `stu_user_passrec`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `stu_user_profiles`
--
ALTER TABLE `stu_user_profiles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `stu_wormholes`
--
ALTER TABLE `stu_wormholes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `start_y` (`start_y`),
  ADD KEY `start_x` (`start_x`),
  ADD KEY `end_x` (`end_x`),
  ADD KEY `end_y` (`end_y`),
  ADD KEY `start_wese` (`start_wese`),
  ADD KEY `end_wese` (`end_wese`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `stu_allys`
--
ALTER TABLE `stu_allys`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_allys_beziehungen`
--
ALTER TABLE `stu_allys_beziehungen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_allys_bez_angebot`
--
ALTER TABLE `stu_allys_bez_angebot`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_allys_embassys`
--
ALTER TABLE `stu_allys_embassys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_allys_messages`
--
ALTER TABLE `stu_allys_messages`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_buildings`
--
ALTER TABLE `stu_buildings`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_buildings_cost`
--
ALTER TABLE `stu_buildings_cost`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_buildings_goods`
--
ALTER TABLE `stu_buildings_goods`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_colonies`
--
ALTER TABLE `stu_colonies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_colonies_classes`
--
ALTER TABLE `stu_colonies_classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_colonies_fields`
--
ALTER TABLE `stu_colonies_fields`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_colonies_orbit`
--
ALTER TABLE `stu_colonies_orbit`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_colonies_underground`
--
ALTER TABLE `stu_colonies_underground`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_contactlist`
--
ALTER TABLE `stu_contactlist`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_dock_permissions`
--
ALTER TABLE `stu_dock_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_event_history`
--
ALTER TABLE `stu_event_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_field_build`
--
ALTER TABLE `stu_field_build`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_fleets`
--
ALTER TABLE `stu_fleets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_game`
--
ALTER TABLE `stu_game`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_game_rounds`
--
ALTER TABLE `stu_game_rounds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_goods`
--
ALTER TABLE `stu_goods`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_informants`
--
ALTER TABLE `stu_informants`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_informants_data`
--
ALTER TABLE `stu_informants_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_informants_user`
--
ALTER TABLE `stu_informants_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_kn_messages`
--
ALTER TABLE `stu_kn_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_map_fields`
--
ALTER TABLE `stu_map_fields`
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_map_sectors`
--
ALTER TABLE `stu_map_sectors`
  MODIFY `id` tinyint(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_map_sectors_user`
--
ALTER TABLE `stu_map_sectors_user`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_map_special`
--
ALTER TABLE `stu_map_special`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_modules_user`
--
ALTER TABLE `stu_modules_user`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_pms`
--
ALTER TABLE `stu_pms`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_pm_saved`
--
ALTER TABLE `stu_pm_saved`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_research_depencies`
--
ALTER TABLE `stu_research_depencies`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_research_list`
--
ALTER TABLE `stu_research_list`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_research_user`
--
ALTER TABLE `stu_research_user`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_sector_flights`
--
ALTER TABLE `stu_sector_flights`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_ships`
--
ALTER TABLE `stu_ships`
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_ships_action`
--
ALTER TABLE `stu_ships_action`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_ships_build`
--
ALTER TABLE `stu_ships_build`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_ships_buildprogress`
--
ALTER TABLE `stu_ships_buildprogress`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_ships_cost`
--
ALTER TABLE `stu_ships_cost`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_ships_goods`
--
ALTER TABLE `stu_ships_goods`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_ships_ki_waypoints`
--
ALTER TABLE `stu_ships_ki_waypoints`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_ships_modules`
--
ALTER TABLE `stu_ships_modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_ships_modules_cost`
--
ALTER TABLE `stu_ships_modules_cost`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_ships_rumps`
--
ALTER TABLE `stu_ships_rumps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_ships_uncloaked`
--
ALTER TABLE `stu_ships_uncloaked`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_spy_action`
--
ALTER TABLE `stu_spy_action`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_stats_shipstopten`
--
ALTER TABLE `stu_stats_shipstopten`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_terraform`
--
ALTER TABLE `stu_terraform`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_torpedo_types`
--
ALTER TABLE `stu_torpedo_types`
  MODIFY `id` tinyint(3) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_trade_goods`
--
ALTER TABLE `stu_trade_goods`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_trade_logs`
--
ALTER TABLE `stu_trade_logs`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_trade_offers`
--
ALTER TABLE `stu_trade_offers`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_user`
--
ALTER TABLE `stu_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_user_levels`
--
ALTER TABLE `stu_user_levels`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_user_passrec`
--
ALTER TABLE `stu_user_passrec`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_user_profiles`
--
ALTER TABLE `stu_user_profiles`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stu_wormholes`
--
ALTER TABLE `stu_wormholes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
