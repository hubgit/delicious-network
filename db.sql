SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

CREATE TABLE IF NOT EXISTS `bookmarks` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `user` varchar(255) NOT NULL,
  `hash` varchar(32) NOT NULL,
  `date` int(11) NOT NULL,
  `url` text NOT NULL,
  `title` text,
  `description` text,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `user` (`user`,`hash`),
  KEY `hash` (`hash`),
  KEY `date` (`date`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(11) unsigned NOT NULL,
  `tag` varchar(255) NOT NULL,
  UNIQUE KEY `id` (`id`,`tag`),
  KEY `tag` (`tag`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

