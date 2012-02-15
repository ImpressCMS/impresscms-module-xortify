<?php
/**
 * @package     xortify
 * @subpackage  module
 * @description	Sector Network Security Drone
 * @author	    Simon Roberts WISHCRAFT <simon@chronolabs.coop>
 * @copyright	copyright (c) 2010-2013 ICMS.org
 * @licence		GPL 2.0 - see docs/LICENCE.txt
 */
	error_reporting(E_ALL);
	if (isset($GLOBALS['xoDoSoap']))
	{
		
		$module_handler = icms::handler('icms_module');
		$config_handler = icms::handler('icms_config');
		if (!isset($GLOBALS['xortifyModule'])) $GLOBALS['xortifyModule'] = $module_handler->getByDirname('xortify');
		if (!isset($GLOBALS['xortifyModuleConfig'])) $GLOBALS['xortifyModuleConfig'] = $config_handler->getConfigList($GLOBALS['xortifyModule']->getVar('mid'));
		
		require_once( ICMS_ROOT_PATH.'/modules/xortify/class/'.$GLOBALS['xortifyModuleConfig']['protocol'].'.php' );
		$func = strtoupper($GLOBALS['xortifyModuleConfig']['protocol']).'XortifyExchange';
		$soapExchg = new $func;
		$result = $soapExchg->retrieveBans();
				
		if (is_array($result)) {
		
			icms_load('xoopscache');
			if (!class_exists('IcmsCache')) {
				// ICMS 2.4 Compliance
				icms_load('cache');
				if (!class_exists('IcmsCache')) {
					include_once ICMS_ROOT_PATH.'/class/cache/xoopscache.php';
				}
			}
					
			IcmsCache::delete('xortify_bans_cache');
			IcmsCache::delete('xortify_bans_cache_backup');			
			IcmsCache::write('xortify_bans_cache', $result, $GLOBALS['xortifyModuleConfig']['xortify_seconds']);
			IcmsCache::write('xortify_bans_cache_backup', $result, ($GLOBALS['xortifyModuleConfig']['xortify_seconds'] * 1.45));			
		}		
	}
	
?>