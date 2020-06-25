--
-- New table to track dependencies
--
CREATE TABLE IF NOT EXISTS `#__coalaweb_common` (
`key` varchar(190) NOT NULL COMMENT 'Primary Key',
`value` longtext NOT NULL,
PRIMARY KEY (`key`)
) DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;