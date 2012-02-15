<?php
/**
 * @package     xortify
 * @subpackage  module
 * @description	Sector Network Security Drone
 * @author	    Simon Roberts WISHCRAFT <simon@chronolabs.coop>
 * @copyright	copyright (c) 2010-2013 ICMS.org
 * @licence		GPL 2.0 - see docs/LICENCE.txt
 */

function icms_module_update_xortify(&$module) {
	
	$sql[] = "CREATE TABLE `".$GLOBALS['db']->prefix('xortify_log')."` (
			  `lid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
			  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  `uname` varchar(64) DEFAULT NULL,
			  `email` varchar(255) DEFAULT NULL,
			  `ip4` varchar(15) NOT NULL DEFAULT '0.0.0.0',
			  `ip6` varchar(128) NOT NULL DEFAULT '0:0:0:0:0:0',
			  `proxy-ip4` varchar(64) NOT NULL DEFAULT '0.0.0.0',
			  `proxy-ip6` varchar(128) NOT NULL DEFAULT '0:0:0:0:0:0',
			  `network-addy` varchar(255) NOT NULL DEFAULT '',
			  `provider` varchar(128) NOT NULL DEFAULT '',
			  `agent` varchar(255) NOT NULL DEFAULT '',
			  `extra` text,
			  `date` int(12) NOT NULL DEFAULT '0',
			  `action` enum('banned','blocked','monitored') NOT NULL DEFAULT 'monitored',
			  PRIMARY KEY (`lid`),
			  KEY `uid` (`uid`),
			  KEY `ip` (`ip4`,`ip6`(16),`proxy-ip4`,`proxy-ip6`(16)),
			  KEY `provider` (`provider`(15)),
			  KEY `date` (`date`),
			  KEY `action` (`action`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8";
	
	foreach($sql as $id => $question)
		if ($GLOBALS['db']->queryF($question))
			icms_error($question, 'SQL Executed Successfully!!!');
			
	icms_load("xoopscache");	
	IcmsCache::delete('xortify_bans_protector');
	return true;				
}

?>