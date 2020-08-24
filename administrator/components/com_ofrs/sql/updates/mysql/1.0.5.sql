ALTER TABLE `#__ofrs_imp_offer` ADD `payout` VARCHAR(10) NULL DEFAULT '' AFTER `name`;

ALTER TABLE `#__ofrs_imp_offer` ADD `payout_type` VARCHAR(50) NULL DEFAULT '' AFTER `payout`;
