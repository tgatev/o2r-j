ALTER TABLE `#__ofrs_ad_network` ADD `account_created` CHAR(1) NOT NULL DEFAULT '' AFTER `asset_id`;

ALTER TABLE `#__ofrs_ad_network` ADD `account_login` VARCHAR(100) NULL DEFAULT '' AFTER `account_created`;

ALTER TABLE `#__ofrs_ad_network` ADD `account_password` VARCHAR(100) NULL DEFAULT '' AFTER `account_login`;

ALTER TABLE `#__ofrs_ad_network` ADD `join_url` VARCHAR(2048) NOT NULL DEFAULT '' AFTER `import_setup`;

ALTER TABLE `#__ofrs_ad_network` ADD `login_url` VARCHAR(2048) NOT NULL DEFAULT '' AFTER `join_url`;

ALTER TABLE `#__ofrs_ad_network` ADD `min_payment_amt` DECIMAL(10,2) NOT NULL DEFAULT 0 AFTER `login_url`;

ALTER TABLE `#__ofrs_ad_network` ADD `payment_frequency` VARCHAR(100) NOT NULL DEFAULT '' AFTER `name`;

ALTER TABLE `#__ofrs_ad_network` ADD `payment_methods` VARCHAR(10) NOT NULL DEFAULT '' AFTER `payment_frequency`;

ALTER TABLE `#__ofrs_ad_network` ADD `stats_tz` VARCHAR(100) NOT NULL DEFAULT '' AFTER `payment_methods`;
