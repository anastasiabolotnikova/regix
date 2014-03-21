SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';


-- -----------------------------------------------------
-- Table `controller`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `controller` ;

CREATE TABLE IF NOT EXISTS `controller` (
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
-- Table `controller_property`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `controller_property` ;

CREATE TABLE IF NOT EXISTS `controller_property` (
  `controller_id` INT NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  `value` VARCHAR(200) NULL,
  PRIMARY KEY (`controller_id`, `name`),
  INDEX `fk_controller_property_controller_idx` (`controller_id` ASC),
  CONSTRAINT `fk_controller_property_controller`
    FOREIGN KEY (`controller_id`)
    REFERENCES `controller` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Values of controller properties (settings).';


-- -----------------------------------------------------
-- Table `permission`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `permission` ;

CREATE TABLE IF NOT EXISTS `permission` (
  `controller_id` INT NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`controller_id`, `name`),
  CONSTRAINT `fk_permission_controller1`
    FOREIGN KEY (`controller_id`)
    REFERENCES `controller` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Describes permission required by the controller to perform s' /* comment truncated */ /*pecific activity.*/;


-- -----------------------------------------------------
-- Table `user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `user` ;

CREATE TABLE IF NOT EXISTS `user` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(200) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
COMMENT = 'Users of the system. All components should use this table to' /* comment truncated */ /* check permission and user settings instead of querying different components.*/;


-- -----------------------------------------------------
-- Table `group`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `group` ;

CREATE TABLE IF NOT EXISTS `group` (
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`name`))
ENGINE = InnoDB
COMMENT = 'User groups. Used to manage permissions.';


-- -----------------------------------------------------
-- Table `user_has_group`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `user_has_group` ;

CREATE TABLE IF NOT EXISTS `user_has_group` (
  `user_id` INT NOT NULL,
  `group_name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`user_id`, `group_name`),
  INDEX `fk_user_has_group_group1_idx` (`group_name` ASC),
  INDEX `fk_user_has_group_user1_idx` (`user_id` ASC),
  CONSTRAINT `fk_user_has_group_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_has_group_group1`
    FOREIGN KEY (`group_name`)
    REFERENCES `group` (`name`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Aggregates users into groups. Groups are used primarily for ' /* comment truncated */ /*permission assignment.*/;


-- -----------------------------------------------------
-- Table `group_has_permission`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `group_has_permission` ;

CREATE TABLE IF NOT EXISTS `group_has_permission` (
  `group_name` VARCHAR(45) NOT NULL,
  `permission_controller_id` INT NOT NULL,
  `permission_name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`group_name`, `permission_controller_id`, `permission_name`),
  INDEX `fk_group_has_permission_permission1_idx` (`permission_controller_id` ASC, `permission_name` ASC),
  INDEX `fk_group_has_permission_group1_idx` (`group_name` ASC),
  CONSTRAINT `fk_group_has_permission_group1`
    FOREIGN KEY (`group_name`)
    REFERENCES `group` (`name`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_group_has_permission_permission1`
    FOREIGN KEY (`permission_controller_id` , `permission_name`)
    REFERENCES `permission` (`controller_id` , `name`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Permissions granted to groups.';


-- -----------------------------------------------------
-- Table `local_login`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `local_login` ;

CREATE TABLE IF NOT EXISTS `local_login` (
  `user_id` INT NOT NULL,
  `login` VARCHAR(45) NOT NULL,
  `salt` VARCHAR(45) NOT NULL,
  `hash` CHAR(255) NOT NULL,
  `email` VARCHAR(200) NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE INDEX `login_UNIQUE` (`login` ASC),
  CONSTRAINT `fk_local_login_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Table of LocalLogin component.';


-- -----------------------------------------------------
-- Table `facebook_login`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facebook_login` ;

CREATE TABLE IF NOT EXISTS `facebook_login` (
  `user_id` INT NOT NULL,
  `username` VARCHAR(45) NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  `oauth_uid` VARCHAR(200) NOT NULL,
  `oauth_provider` VARCHAR(200) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE INDEX `oauth_uid_UNIQUE` (`oauth_uid` ASC),
  CONSTRAINT `fk_faceboo_login_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Table of FacebookLogin component.';


-- -----------------------------------------------------
-- Table `calendar`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `calendar` ;

CREATE TABLE IF NOT EXISTS `calendar` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB
COMMENT = 'Virtual calendars.';


-- -----------------------------------------------------
-- Table `event`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `event` ;

CREATE TABLE IF NOT EXISTS `event` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `calendar_id` INT NOT NULL,
  `name` VARCHAR(200) NULL,
  `description` VARCHAR(200) NULL,
  `assigned_user` INT NULL,
  `assigned_group` VARCHAR(45) NULL,
  `from` TIMESTAMP NULL,
  `to` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_event_calendar1_idx` (`calendar_id` ASC),
  INDEX `fk_event_assigned_user1_idx` (`assigned_user` ASC),
  INDEX `fk_event_assigned_group1_idx` (`assigned_group` ASC),
  CONSTRAINT `fk_evant_calendar1`
    FOREIGN KEY (`calendar_id`)
    REFERENCES `calendar` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_event_user1`
    FOREIGN KEY (`assigned_user`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_event_group1`
    FOREIGN KEY (`assigned_group`)
    REFERENCES `group` (`name`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Calendar events';


-- -----------------------------------------------------
-- Table `event_constraint_group`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `event_constraint_group` ;

CREATE TABLE IF NOT EXISTS `event_constraint_group` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB
COMMENT = 'Group of limits for calendars.';


-- -----------------------------------------------------
-- Table `event_constraint`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `event_constraint` ;

CREATE TABLE IF NOT EXISTS `event_constraint` (
  `event_constraint_group_id` INT NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  `value` VARCHAR(200) NOT NULL,
  PRIMARY KEY (`event_constraint_group_id`, `name`),
  CONSTRAINT `fk_event_constraint_event_constraint_group1`
    FOREIGN KEY (`event_constraint_group_id`)
    REFERENCES `event_constraint_group` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Settings limiting addition of events to the calendar.';


-- -----------------------------------------------------
-- Table `calendar_has_event_constraint_group`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `calendar_has_event_constraint_group` ;

CREATE TABLE IF NOT EXISTS `calendar_has_event_constraint_group` (
  `calendar_id` INT NOT NULL,
  `event_constraint_group_id` INT NOT NULL,
  PRIMARY KEY (`calendar_id`, `event_constraint_group_id`),
  INDEX `fk_checg_event_constraint_group1_idx` (`event_constraint_group_id` ASC),
  INDEX `fk_checg_calendar1_idx` (`calendar_id` ASC),
  CONSTRAINT `fk_checg_calendar1`
    FOREIGN KEY (`calendar_id`)
    REFERENCES `calendar` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_checg_event_constraint_group1`
    FOREIGN KEY (`event_constraint_group_id`)
    REFERENCES `event_constraint_group` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Assigns group of event constrants to a calendar.';


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
