ALTER TABLE `#__ofrs_imp_session` ADD `tracking_platform_id` INT NOT NULL DEFAULT 0 AFTER `status`;

ALTER TABLE `#__ofrs_imp_message` ADD `imp_session_id` INT NOT NULL DEFAULT 0 AFTER `imp_offer_id`;

ALTER TABLE `#__ofrs_ad_network` ADD `display_properties` VARCHAR NULL DEFAULT '' AFTER `description`;
