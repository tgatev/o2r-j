--
-- Convert all tables to utf8mb4 character set with utf8mb4_unicode_ci collation
--
ALTER TABLE `#__cwsocial_metafields` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `#__cwsocial_count` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

--
-- Set default character set and collation for all tables
--
ALTER TABLE `#__cwsocial_metafields` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `#__cwsocial_count` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;