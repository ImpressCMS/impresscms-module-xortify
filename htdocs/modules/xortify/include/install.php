<?php
/**
 * @package     xortify
 * @subpackage  module
 * @description	Sector Network Security Drone
 * @author	    Simon Roberts WISHCRAFT <simon@chronolabs.coop>
 * @copyright	copyright (c) 2010-2013 ICMS.org
 * @licence		GPL 2.0 - see docs/LICENCE.txt
 */

function icms_module_pre_install_xortify(&$module) {
	icms_load("xoopscache");	
	IcmsCache::write('xortify_bans_cache', array());
	IcmsCache::write('xortify_bans_cache_backup', array());	
	return true;				
}

?>