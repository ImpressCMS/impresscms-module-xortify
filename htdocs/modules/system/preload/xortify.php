<?php
/**
 * @package     xortify
 * @subpackage  module
 * @description	Sector Nexoork Security Drone
 * @author	    Simon Roberts WISHCRAFT <simon@chronolabs.coop>
 * @author	    Richardo Costa TRABIS 
 * @copyright	copyright (c) 2010-2013 ICMS.org
 * @licence		GPL 2.0 - see docs/LICENCE.txt
 */

//defined('ICMS_ROOT_PATH') or die('Restricted access');

class IcmsPreloadXortify extends icms_preload_Item
{
	function eventfinishCoreBoot($args)
	{
		$module_handler = icms::handler('icms_module');
		$config_handler = icms::handler('icms_config');		
		$GLOBALS['xortifyModule'] = $module_handler->getByDirname('xortify');
		if (is_object($GLOBALS['xortifyModule'])) {
			$GLOBALS['xortifyModuleConfig'] = $config_handler->getConfigList($GLOBALS['xortifyModule']->getVar('mid'));
		}
		
		include_once dirname(dirname(dirname(__FILE))).'/xortify/class/cache/icmscache.php';
		$result = IcmsCache::read('xortify_core_include_common_end_cron');
		if ((isset($result['time'])?(float)$result['time']:0)<=microtime(true)) {
			IcmsCache::write('xortify_core_include_common_end_cron', array('time'=>microtime(true)+$GLOBALS['xortifyModuleConfig']['fault_delay']), $GLOBALS['xortifyModuleConfig']['fault_delay']);
			switch ($GLOBALS['xortifyModuleConfig']['crontype']) {
				case 'preloader':
					$read = IcmsCache::read('xortify_pause_preload');
					if ((isset($read['time'])?(float)$read['time']:0)<=microtime(true)) {
						IcmsCache::write('xortify_pause_preload', array('time'=>microtime(true)+$GLOBALS['xortifyModuleConfig']['croninterval']));
						$GLOBALS['xortify_preloader']=true;
						ob_start();
						include(ICMS_ROOT_PATH.'/modules/xortify/cron/serverup.php');
						ob_end_clean();
					}
					break;
			}
			IcmsCache::write('xortify_core_include_common_end_cron', array('time'=>microtime(true)), -1);
		}
	}
}

?>