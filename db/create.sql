SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `webphp` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `webphp` ;

-- -----------------------------------------------------
-- Table `webphp`.`Controller`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `webphp`.`Controller` ;

CREATE TABLE IF NOT EXISTS `webphp`.`Controller` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(200) NOT NULL,
  `description` TEXT NULL,
  `enabled` TINYINT(1) NOT NULL,
  `uri_name` VARCHAR(200) NOT NULL COMMENT 'Name by which router recognizes this controller.',
  `file_path` VARCHAR(200) NOT NULL COMMENT 'Relative file path within controllers directory.',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC),
  UNIQUE INDEX `uri_name_UNIQUE` (`uri_name` ASC))
ENGINE = InnoDB
COMMENT = 'Collection of available controller modules.';


-- -----------------------------------------------------
-- Table `webphp`.`ControllerProperty`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `webphp`.`ControllerProperty` ;

CREATE TABLE IF NOT EXISTS `webphp`.`ControllerProperty` (
  `Controller_id` INT NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  `value` VARCHAR(200) NULL,
  PRIMARY KEY (`Controller_id`, `name`),
  INDEX `fk_ControllerProperty_Controller_idx` (`Controller_id` ASC),
  CONSTRAINT `fk_ControllerProperty_Controller`
    FOREIGN KEY (`Controller_id`)
    REFERENCES `webphp`.`Controller` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Values of controller properties (settings).';


-- -----------------------------------------------------
-- Table `webphp`.`PermissionRequirement`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `webphp`.`PermissionRequirement` ;

CREATE TABLE IF NOT EXISTS `webphp`.`PermissionRequirement` (
  `Controller_id` INT NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`Controller_id`, `name`),
  CONSTRAINT `fk_PermissionRequirement_Controller1`
    FOREIGN KEY (`Controller_id`)
    REFERENCES `webphp`.`Controller` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Describes permission required by the controller to perform s' /* comment truncated */ /*pecific activity.*/;


-- -----------------------------------------------------
-- Table `webphp`.`User`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `webphp`.`User` ;

CREATE TABLE IF NOT EXISTS `webphp`.`User` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(200) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
COMMENT = 'Users of the system. All components should use this table to' /* comment truncated */ /* check permission and user settings instead of querying different components.*/;


-- -----------------------------------------------------
-- Table `webphp`.`Group`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `webphp`.`Group` ;

CREATE TABLE IF NOT EXISTS `webphp`.`Group` (
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`name`))
ENGINE = InnoDB
COMMENT = 'User groups. Used to manage permissions.';


-- -----------------------------------------------------
-- Table `webphp`.`User_has_Group`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `webphp`.`User_has_Group` ;

CREATE TABLE IF NOT EXISTS `webphp`.`User_has_Group` (
  `User_id` INT NOT NULL,
  `Group_name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`User_id`, `Group_name`),
  INDEX `fk_User_has_Group_Group1_idx` (`Group_name` ASC),
  INDEX `fk_User_has_Group_User1_idx` (`User_id` ASC),
  CONSTRAINT `fk_User_has_Group_User1`
    FOREIGN KEY (`User_id`)
    REFERENCES `webphp`.`User` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_User_has_Group_Group1`
    FOREIGN KEY (`Group_name`)
    REFERENCES `webphp`.`Group` (`name`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Aggregates users into groups. Groups are used primarily for ' /* comment truncated */ /*permission assignment.*/;


-- -----------------------------------------------------
-- Table `webphp`.`Group_has_Permission`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `webphp`.`Group_has_Permission` ;

CREATE TABLE IF NOT EXISTS `webphp`.`Group_has_Permission` (
  `Group_name` VARCHAR(45) NOT NULL,
  `PermissionRequirement_Controller_id` INT NOT NULL,
  `PermissionRequirement_name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`Group_name`, `PermissionRequirement_Controller_id`, `PermissionRequirement_name`),
  INDEX `fk_Group_has_PermissionRequirement_PermissionRequirement1_idx` (`PermissionRequirement_Controller_id` ASC, `PermissionRequirement_name` ASC),
  INDEX `fk_Group_has_PermissionRequirement_Group1_idx` (`Group_name` ASC),
  CONSTRAINT `fk_Group_has_PermissionRequirement_Group1`
    FOREIGN KEY (`Group_name`)
    REFERENCES `webphp`.`Group` (`name`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Group_has_PermissionRequirement_PermissionRequirement1`
    FOREIGN KEY (`PermissionRequirement_Controller_id` , `PermissionRequirement_name`)
    REFERENCES `webphp`.`PermissionRequirement` (`Controller_id` , `name`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Permissions granted to groups.';


-- -----------------------------------------------------
-- Table `webphp`.`LocalLogin`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `webphp`.`LocalLogin` ;

CREATE TABLE IF NOT EXISTS `webphp`.`LocalLogin` (
  `User_id` INT NOT NULL,
  `username` VARCHAR(45) NOT NULL,
  `salt` VARCHAR(45) NOT NULL,
  `hash` CHAR(128) NOT NULL,
  `email` VARCHAR(200) NULL,
  PRIMARY KEY (`User_id`),
  UNIQUE INDEX `username_UNIQUE` (`username` ASC),
  CONSTRAINT `fk_LocalLogin_User1`
    FOREIGN KEY (`User_id`)
    REFERENCES `webphp`.`User` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Table of LocalLogin component.';


-- -----------------------------------------------------
-- Table `webphp`.`FacebookLogin`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `webphp`.`FacebookLogin` ;

CREATE TABLE IF NOT EXISTS `webphp`.`FacebookLogin` (
  `User_id` INT NOT NULL,
  `username` VARCHAR(45) NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  `oauth_uid` VARCHAR(200) NOT NULL,
  `oauth_provider` VARCHAR(200) NOT NULL,
  PRIMARY KEY (`User_id`),
  UNIQUE INDEX `oauth_uid_UNIQUE` (`oauth_uid` ASC),
  CONSTRAINT `fk_FacebookLogin_User1`
    FOREIGN KEY (`User_id`)
    REFERENCES `webphp`.`User` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Table of FacebookLogin component.';


-- -----------------------------------------------------
-- Table `webphp`.`Calendar`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `webphp`.`Calendar` ;

CREATE TABLE IF NOT EXISTS `webphp`.`Calendar` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB
COMMENT = 'Virtual calendars.';


-- -----------------------------------------------------
-- Table `webphp`.`Event`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `webphp`.`Event` ;

CREATE TABLE IF NOT EXISTS `webphp`.`Event` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `Calendar_id` INT NOT NULL,
  `name` VARCHAR(200) NULL,
  `description` VARCHAR(200) NULL,
  `assigned_user` INT NULL,
  `assigned_group` VARCHAR(45) NULL,
  `from` TIMESTAMP NULL,
  `to` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_table1_Calendar1_idx` (`Calendar_id` ASC),
  INDEX `fk_Event_User1_idx` (`assigned_user` ASC),
  INDEX `fk_Event_Group1_idx` (`assigned_group` ASC),
  CONSTRAINT `fk_table1_Calendar1`
    FOREIGN KEY (`Calendar_id`)
    REFERENCES `webphp`.`Calendar` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Event_User1`
    FOREIGN KEY (`assigned_user`)
    REFERENCES `webphp`.`User` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Event_Group1`
    FOREIGN KEY (`assigned_group`)
    REFERENCES `webphp`.`Group` (`name`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Calendar events';


-- -----------------------------------------------------
-- Table `webphp`.`EventConstraintGroup`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `webphp`.`EventConstraintGroup` ;

CREATE TABLE IF NOT EXISTS `webphp`.`EventConstraintGroup` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB
COMMENT = 'Group of limits for calendars.';


-- -----------------------------------------------------
-- Table `webphp`.`EventConstraint`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `webphp`.`EventConstraint` ;

CREATE TABLE IF NOT EXISTS `webphp`.`EventConstraint` (
  `EventConstraintGroup_id` INT NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  `value` VARCHAR(200) NOT NULL,
  PRIMARY KEY (`EventConstraintGroup_id`, `name`),
  CONSTRAINT `fk_EventConstraint_EventConstraintGroup1`
    FOREIGN KEY (`EventConstraintGroup_id`)
    REFERENCES `webphp`.`EventConstraintGroup` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Settings limiting addition of events to the calendar.';


-- -----------------------------------------------------
-- Table `webphp`.`Calendar_has_EventConstraintGroup`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `webphp`.`Calendar_has_EventConstraintGroup` ;

CREATE TABLE IF NOT EXISTS `webphp`.`Calendar_has_EventConstraintGroup` (
  `Calendar_id` INT NOT NULL,
  `EventConstraintGroup_id` INT NOT NULL,
  PRIMARY KEY (`Calendar_id`, `EventConstraintGroup_id`),
  INDEX `fk_Calendar_has_EventConstraintGroup_EventConstraintGroup1_idx` (`EventConstraintGroup_id` ASC),
  INDEX `fk_Calendar_has_EventConstraintGroup_Calendar1_idx` (`Calendar_id` ASC),
  CONSTRAINT `fk_Calendar_has_EventConstraintGroup_Calendar1`
    FOREIGN KEY (`Calendar_id`)
    REFERENCES `webphp`.`Calendar` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Calendar_has_EventConstraintGroup_EventConstraintGroup1`
    FOREIGN KEY (`EventConstraintGroup_id`)
    REFERENCES `webphp`.`EventConstraintGroup` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Assigns group of event constrants to a calendar.';


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
