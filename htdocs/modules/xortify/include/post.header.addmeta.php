<?php
/**
 * @package     xortify
 * @subpackage  module
 * @description	Sector Network Security Drone
 * @author	    Simon Roberts WISHCRAFT <simon@chronolabs.coop>
 * @copyright	copyright (c) 2010-2013 ICMS.org
 * @licence		GPL 2.0 - see docs/LICENCE.txt
 */

	include_once (dirname(dirname(__FILE__)).'/include/functions.php');
	
	error_reporting(E_ALL);
	if (isset($GLOBALS['xortify_pass'])) {
		if ($GLOBALS['xortify_pass'] == true) {
			include_once ICMS_ROOT_PATH.'/modules/xortify/include/functions.php';
			addmeta_googleanalytics(_XOR_MI_ICMS_GOOGLE_ANALYTICS_ACCOUNTID_USERPASSED, $_SERVER['HTTP_HOST']);
			if (defined('_XOR_MI_CLIENT_GOOGLE_ANALYTICS_ACCOUNTID_USERPASSED')&&strlen(constant('_XOR_MI_CLIENT_GOOGLE_ANALYTICS_ACCOUNTID_USERPASSED'))>=13) { 
				addmeta_googleanalytics(_XOR_MI_CLIENT_GOOGLE_ANALYTICS_ACCOUNTID_USERPASSED, $_SERVER['HTTP_HOST']);
			}	
		}
	}

	set_time_limit(1800);
	include_once (ICMS_ROOT_PATH.'/modules/xortify/providers/providers.php');
	$check = new Providers('footerpostcheck');

?>