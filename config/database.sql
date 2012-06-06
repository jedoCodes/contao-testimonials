-- ********************************************************
-- *                                                      *
-- * IMPORTANT NOTE                                       *
-- *                                                      *
-- * Do not import this file manually but use the Contao  *
-- * install tool to create and maintain database tables! *
-- *                                                      *
-- ********************************************************

-- 
-- Table `tl_jedoTestimonials`
-- 

CREATE TABLE `tl_jedoTestimonials` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `name` varchar(64) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `company` varchar(255) NOT NULL default '',
  `url` varchar(128) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `testimonial` text NULL,
  `vote` varchar(64) NOT NULL default '',
  `published` char(1) NOT NULL default '',
  `date` int(10) unsigned NOT NULL default '0',
  `ip` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

-- 
-- Table `tl_content`
-- 

CREATE TABLE `tl_content` (
  `jedoTM_order` varchar(32) NOT NULL default '',
  `jedoTM_perPage` smallint(5) unsigned NOT NULL default '0',
  `jedoTM_moderate` char(1) NOT NULL default '',
  `jedoTM_bbcode` char(1) NOT NULL default '',
  `jedoTM_requireLogin` char(1) NOT NULL default '',
  `jedoTM_disableCaptcha` char(1) NOT NULL default '',
  `jedoTM_disableVote` char(1) NOT NULL default '',
  `jedoTM_template` varchar(32) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

-- 
-- Table `tl_module`
-- 

CREATE TABLE `tl_module` (
  `jedoTM_order` varchar(32) NOT NULL default '',
  `jedoTM_perPage` smallint(5) unsigned NOT NULL default '0',
  `jedoTM_moderate` char(1) NOT NULL default '',
  `jedoTM_bbcode` char(1) NOT NULL default '',
  `jedoTM_requireLogin` char(1) NOT NULL default '',
  `jedoTM_disableCaptcha` char(1) NOT NULL default '',
  `jedoTM_disableVote` char(1) NOT NULL default '',
  `jedoTM_template` varchar(32) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
