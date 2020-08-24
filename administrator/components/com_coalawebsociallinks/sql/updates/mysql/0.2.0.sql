CREATE TABLE IF NOT EXISTS `#__cwsocial_metafields` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `article_id` int(11) unsigned NULL COMMENT 'FK to the #__content table.',
  `cat_id` int(11) unsigned NULL COMMENT 'FK to the #__categories table.',
  `attribs` longtext NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;