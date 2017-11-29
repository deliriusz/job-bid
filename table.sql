SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `pai`
--
DROP DATABASE IF EXISTS pai;
CREATE DATABASE  IF NOT EXISTS pai;
USE pai;
-- --------------------------------------------------------

--
-- Table structure for table `bid`
--

CREATE TABLE IF NOT EXISTS `bid` (
  `id` int(11) AUTO_INCREMENT PRIMARY KEY,
  `user_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `time` datetime NOT NULL ,
  `value` double UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `job`
--
CREATE TABLE `job_type` (
  `id` int(11) NOT NULL,
  `name` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `job_type` (`name`) VALUES ('Accounting');
INSERT INTO `job_type` (`name`) VALUES ('Programming');
INSERT INTO `job_type` (`name`) VALUES ('Copywriting');

CREATE TABLE IF NOT EXISTS `job` (
  `id` int(11) AUTO_INCREMENT PRIMARY KEY,
  `userid` int(11) NOT NULL,
  `name` varchar(180) NOT NULL,
  `description` text NOT NULL,
  `initial_price` double UNSIGNED NOT NULL,
  `creation_time` datetime NOT NULL ,
  `job_start_time` datetime NOT NULL,
  `job_end_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) AUTO_INCREMENT PRIMARY KEY,
  `username` varchar(30) NOT NULL,
  `password` text NOT NULL,
  `email` varchar(50) NOT NULL,
  `info` text NULL,
  `salt` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `pai`.`event_type` (
  `id` INT AUTO_INCREMENT PRIMARY KEY ,
  `name` VARCHAR(30) NOT NULL
) ENGINE = InnoDB;

INSERT INTO `event_type` (`name`) VALUES ('job created');
INSERT INTO `event_type` (`name`) VALUES ('job changed');
INSERT INTO `event_type` (`name`) VALUES ('job finished');
INSERT INTO `event_type` (`name`) VALUES ('lower bid');
INSERT INTO `event_type` (`name`) VALUES ('job winner choosen');

CREATE TABLE IF NOT EXISTS `pai`.`event` ( `id` INT AUTO_INCREMENT PRIMARY KEY , `source_id` INT NOT NULL , `type` VARCHAR(10) NOT NULL ) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `pai`.`event_subscriber` ( `id` INT AUTO_INCREMENT PRIMARY KEY , `event_id` INT NOT NULL, `user_id` INT NOT NULL) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `pai`.`notification` ( `id` INT AUTO_INCREMENT PRIMARY KEY , `event_id` INT NOT NULL, `user_id` INT NOT NULL , `is_read` INT NOT NULL) ENGINE = InnoDB;

ALTER TABLE `notification` ADD `event_type` INT NOT NULL AFTER `user_id`;
--
-- Indexes for dumped tables
--

--
-- Indexes for table `bid`
--
ALTER TABLE `bid`
  ADD KEY `job_id` (`job_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `job`
--
ALTER TABLE `job`
  ADD KEY `userid` (`userid`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

ALTER TABLE `user` ADD `first_name` VARCHAR(40) NOT NULL AFTER `username`, ADD `last_name` VARCHAR(40) NOT NULL AFTER `first_name`, ADD `birth_date` DATE NOT NULL AFTER `last_name`;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `bid`
--
ALTER TABLE `bid`
  ADD CONSTRAINT `bid_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `job` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bid_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `job`
--
ALTER TABLE `job`
  ADD CONSTRAINT `job_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;


ALTER TABLE `job` CHANGE `initial_price` `initial_price` DOUBLE UNSIGNED NOT NULL;
ALTER TABLE `job` ADD `finished` BOOLEAN NOT NULL DEFAULT FALSE AFTER `job_end_time`;
ALTER TABLE `user` ADD `picture_path` VARCHAR(200) NULL DEFAULT NULL AFTER `info`;
ALTER TABLE `job` ADD `job_type` INT NOT NULL AFTER `finished`;

COMMIT;
