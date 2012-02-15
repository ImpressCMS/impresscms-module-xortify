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

$module_handler =& icms::handler('icms_module');
$config_handler =& icms::handler('icms_config');
$GLOBALS['spiderModule'] = $module_handler->getByDirname('spiders');
if (!isset($GLOBALS['xortifyModule'])) $GLOBALS['xortifyModule'] = $module_handler->getByDirname('xortify');
if (is_object($GLOBALS['spiderModule'])) {
	$GLOBALS['spidersModuleConfig'] = $config_handler->getConfigList($GLOBALS['spiderModule']->mid());

	if (is_object(icms::$user)) {
		if (in_array($GLOBALS['spidersModuleConfig']['bot_group'], icms::$user->getGroups())&&!empty($_POST)) {

			include_once ICMS_ROOT_PATH."/include/common.php";
			
			icms_loadLanguage('ban', 'xortify');
			
			$log_handler = icms_getModuleHandler('log', 'xortify');
			$log = $log_handler->create();
			$log->setVars(xortify_getIPData(false));
			$log->setVar('provider', basename(dirname(__FILE__)));
			$log->setVar('action', 'monitored');
			$log->setVar('extra', _XOR_BAN_SPIDER_TYPE.': '.print_r($_POST, true));
			$lid = $log_handler->insert($log, true);
			IcmsCache::write('xortify_core_include_common_end', array('time'=>microtime(true)), $GLOBALS['xortifyModuleConfig']['fault_delay']);
			$_SESSION['xortify']['lid'] = $lid;
			setcookie('xortify', array('lid' => $lid), time()+3600*24*7*4*3);
			header('Location: '.ICMS_URL.'/banned.php');
			exit(0);
		}
		$GLOBALS['xortify_pass'] = true;
	}	
}

?>