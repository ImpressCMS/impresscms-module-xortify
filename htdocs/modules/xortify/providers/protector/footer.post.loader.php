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
	if (class_exists('Protector')) {
		
		icms_load('xoopscache');
		if (!class_exists('IcmsCache')) {
			// ICMS 2.4 Compliance
			icms_load('cache');
			if (!class_exists('IcmsCache')) {
				include_once ICMS_ROOT_PATH.'/class/cache/xoopscache.php';
			}
		}
			
		
		$bad_ips = Protector::get_bad_ips(false);
		$cache_bad_ips = IcmsCache::read('xortify_bans_protector');
		if (empty($cache_bad_ips))
			$cache_bad_ips = array();
	
		$module_handler = icms::handler('icms_module');
		$config_handler = icms::handler('icms_config');
		if (!isset($GLOBALS['xortifyModule'])) $GLOBALS['xortifyModule'] = $module_handler->getByDirname('xortify');
		if (!isset($GLOBALS['xortifyModuleConfig'])) $GLOBALS['xortifyModuleConfig'] = $config_handler->getConfigList($GLOBALS['xortifyModule']->getVar('mid'));
	
		require_once( ICMS_ROOT_PATH.'/modules/xortify/class/'.$GLOBALS['xortifyModuleConfig']['protocol'].'.php' ); 	
		$func = strtoupper($GLOBALS['xortifyModuleConfig']['protocol']).'XortifyExchange';
		$soapExchg = new $func;
		
		if (is_array($cache_bad_ips)) {
			foreach($bad_ips as $id => $ip) {
				if (!in_array($ip, $cache_bad_ips)) { 
					if ($ip!=$GLOBALS['xoopsConfig']['my_ip']) {    
						$sql = 'SELECT `timestamp`, `type`, `agent`, `description` FROM '.$GLOBALS['db']->prefix('protector_log').' WHERE ip = "'.$ip.'" ORDER BY `timestamp`';
						$result = $GLOBALS['db']->queryF($sql);
						$comment = '';
						while($row = $GLOBALS['db']->fetchArray($result)) {
							$comment .= (strlen($comment)>0?"\n":'').$row['timestamp']. ' - ' . $row['type'] . ' - ' . $row['agent'] . ' - ' . $row['description'];
							$agent[] = $row['agent'];
						} 
						$results[] = $soapExchg->sendBan($comment, 1, $ip);
						
						$log_handler = icms_getModuleHandler('log', 'xortify');
						$log = $log_handler->create();
						$log->setVars(xortify_getIPData($ip));
						$log->setVar('provider', basename(dirname(__FILE__)));
						$log->setVar('action', 'banned');
						$log->setVar('extra', $comment);
						$log->setVar('agent', implode("\n", array_unique($agent)));
						$log->setVar('email', '');
						$log->setVar('uname', '');
						$log_handler->insert($log, true);
						
					}
				}
			}
		}		
		IcmsCache::delete('xortify_bans_protector');
		IcmsCache::write('xortify_bans_protector', $bad_ips);			
	}
?>