CREATE TABLE IF NOT EXISTS `#__uu_fields` (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`type` VARCHAR(255) NOT NULL,
	`name` VARCHAR(50) NOT NULL,
	`description` VARCHAR(255) NOT NULL,
	`core` TINYINT(1) NOT NULL DEFAULT '0',
	`ordering` INT(11) NOT NULL DEFAULT '0',
	`published` TINYINT(1) NOT NULL DEFAULT '0',
	`required` TINYINT(1) NOT NULL DEFAULT '0',
	`registration` TINYINT(1) NOT NULL DEFAULT '1',
	`editable` TINYINT(1) NOT NULL DEFAULT '1',
	`fieldcode` VARCHAR(255) NOT NULL,
	`params` TEXT NOT NULL,
	PRIMARY KEY (`id`)
)
DEFAULT CHARACTER SET utf8;

CREATE TABLE IF NOT EXISTS `#__uu_fields_values` (
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`id_field` INT(11) UNSIGNED NOT NULL,
	`value` VARCHAR(255) NOT NULL DEFAULT '',
	`title` VARCHAR(255) NOT NULL DEFAULT '',
	`published` TINYINT(2) NOT NULL DEFAULT '0',
	`ordering` INT(4) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`),
	UNIQUE INDEX `id_field` (`id_field`, `value`)
)
DEFAULT CHARACTER SET utf8;

CREATE TABLE IF NOT EXISTS `#__uu_users` (
	`user_id` INT(11) NOT NULL,
	`ip_address` VARCHAR(39) NULL DEFAULT NULL,
	`accepted_terms` TINYINT(4) NOT NULL DEFAULT '0',
	PRIMARY KEY (`user_id`)
)
DEFAULT CHARACTER SET utf8;

CREATE TABLE IF NOT EXISTS `#__uu_configuration` (
	`key` VARCHAR(200) NOT NULL,
	`value` TEXT NOT NULL,
	PRIMARY KEY (`key`)
)
DEFAULT CHARACTER SET utf8;




