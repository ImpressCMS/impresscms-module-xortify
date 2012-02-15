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
	
	include_once((ICMS_ROOT_PATH.'/modules/xortify/include/functions.php'));
	
	if (is_object(icms::$user)) {
		$uid = icms::$user->getVar('uid');
		$uname = icms::$user->getVar('uname');
		$email = icms::$user->getVar('email');
	} else {
		$uid = 0;
		$uname = (isset($_REQUEST['uname'])?$_REQUEST['uname']:'');
		$email = (isset($_REQUEST['email'])?$_REQUEST['email']:'');
	}
	
	$module_handler = icms::handler('icms_module');
	$config_handler = icms::handler('icms_config');
	if (!isset($GLOBALS['xortifyModule'])) $GLOBALS['xortifyModule'] = $module_handler->getByDirname('xortify');
	if (!isset($GLOBALS['xortifyModuleConfig'])) $GLOBALS['xortifyModuleConfig'] = $config_handler->getConfigList($GLOBALS['xortifyModule']->getVar('mid'));
	
	icms_load('xoopscache');
	if (!class_exists('IcmsCache')) {
		// ICMS 2.4 Compliance
		icms_load('cache');
		if (!class_exists('IcmsCache')) {
			include_once ICMS_ROOT_PATH.'/class/cache/xoopscache.php';
		}
	}
	
	if (!$ipdata = IcmsCache::read('xortify_php_'.sha1($_SERVER['REMOTE_ADDR'].(isset($_SERVER['HTTP_X_FORWARDED_FOR'])?$_SERVER['HTTP_X_FORWARDED_FOR']:'').$uid.$uname.$email))) {
		$ipdata = xortify_getIPData(false);
		IcmsCache::write('xortify_php_'.sha1($_SERVER['REMOTE_ADDR'].(isset($_SERVER['HTTP_X_FORWARDED_FOR'])?$_SERVER['HTTP_X_FORWARDED_FOR']:'').$uid.$uname.$email), $ipdata, $GLOBALS['xortifyModuleConfig']['xortify_ip_cache']);
	}
	
	if (isset($ipdata['ip4']))
		if ($ipdata['ip4']==$GLOBALS['xoopsConfig']['my_ip'])
			return false;
			
	if (isset($ipdata['ip6']))
		if ($ipdata['ip6']==$GLOBALS['xoopsConfig']['my_ip']) 
			return false;
	$checked = IcmsCache::read('xortify_php_'.sha1($ipdata['uname'].$ipdata['email'].(isset($ipdata['ip4'])?$ipdata['ip4']:"").(isset($ipdata['ip6'])?$ipdata['ip6']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip4']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip6']:"").$ipdata['network-addy']));
		
  	
	if (!is_array($checked))
	{
		require_once( ICMS_ROOT_PATH.'/modules/xortify/class/'.$GLOBALS['xortifyModuleConfig']['protocol'].'.php' );
		$func = strtoupper($GLOBALS['xortifyModuleConfig']['protocol']).'XortifyExchange';
		ob_start();
		$soapExchg = new $func;
		$result = $soapExchg->checkPHPBans($ipdata);
		ob_end_flush();
		
		if (is_array($result)) {
			if ($result['success']==true)
				if (($result['octeta']<=$GLOBALS['xortifyModuleConfig']['octeta']&&$result['octetb']>$GLOBALS['xortifyModuleConfig']['octetb']&&$result['octetc']>=$GLOBALS['xortifyModuleConfig']['octetc'])) {
					$module_handler =& icms::handler('icms_module');
					$config_handler =& icms::handler('icms_config');
					if (!isset($GLOBALS['xortifyModule'])) $GLOBALS['xortifyModule'] = $module_handler->getByDirname('xortify');
					$configs = $config_handler->getConfigList($GLOBALS['xortifyModule']->mid());
					
					IcmsCache::write('xortify_php_'.sha1($ipdata['uname'].$ipdata['email'].(isset($ipdata['ip4'])?$ipdata['ip4']:"").(isset($ipdata['ip6'])?$ipdata['ip6']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip4']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip6']:"").$ipdata['network-addy']), array_merge($result, array('ipdata' => $ipdata)), $GLOBALS['xortifyModuleConfig']['xortify_ip_cache']);					
				
					icms_loadLanguage('ban', 'xortify');
					
					$result = $soapExchg->sendBan(_XOR_BAN_PHP_TYPE."\n".
												  _XOR_BAN_PHP_OCTETA.' '.$result['octeta'].' <= ' . $GLOBALS['xortifyModuleConfig']['octeta']."\n".
												  _XOR_BAN_PHP_OCTETB.' '.$result['octetb'].' > ' . $GLOBALS['xortifyModuleConfig']['octetb']."\n".
												  _XOR_BAN_PHP_OCTETC.' '.$result['octetc'].' >= ' . $GLOBALS['xortifyModuleConfig']['octetc']."\n", 5, $ipdata);
												  
					$log_handler = icms_getModuleHandler('log', 'xortify');
					$log = $log_handler->create();
					$log->setVars($ipdata);
					$log->setVar('provider', basename(dirname(__FILE__)));
					$log->setVar('action', 'banned');
					$log->setVar('extra', _XOR_BAN_PHP_OCTETA.' '.$result['octeta'].' <= ' . $GLOBALS['xortifyModuleConfig']['octeta']."\n".
										  _XOR_BAN_PHP_OCTETB.' '.$result['octetb'].' > ' . $GLOBALS['xortifyModuleConfig']['octetb']."\n".
										  _XOR_BAN_PHP_OCTETC.' '.$result['octetc'].' >= ' . $GLOBALS['xortifyModuleConfig']['octetc']);
					
					$_SESSION['xortify']['lid'] = $log_handler->insert($log, true);
					IcmsCache::write('xortify_core_include_common_end', array('time'=>microtime(true)), $GLOBALS['xortifyModuleConfig']['fault_delay']);
					$_SESSION['xortify']['lid'] = $lid;
					setcookie('xortify', array('lid' => $lid), time()+3600*24*7*4*3);
					header('Location: '.ICMS_URL.'/banned.php');
					exit(0);
				
				}
			
		}
		$GLOBALS['xortify_pass'] = true;
		IcmsCache::write('xortify_php_'.sha1($ipdata['uname'].$ipdata['email'].(isset($ipdata['ip4'])?$ipdata['ip4']:"").(isset($ipdata['ip6'])?$ipdata['ip6']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip4']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip6']:"").$ipdata['network-addy']), array_merge($result, array('ipdata' => $ipdata)), $GLOBALS['xortifyModuleConfig']['xortify_seconds']);
		
	} elseif (isset($checked['success'])) {
		if ($checked['success']==true) {
			if (($checked['octeta']<=$GLOBALS['xortifyModuleConfig']['octeta']&&$checked['octetb']>$GLOBALS['xortifyModuleConfig']['octetb']&&$checked['octetc']>=$GLOBALS['xortifyModuleConfig']['octetc'])) {
				
				icms_loadLanguage('ban', 'xortify');
				
				$log_handler = icms_getModuleHandler('log', 'xortify');
				$log = $log_handler->create();
				$log->setVars($ipdata);
				$log->setVar('provider', basename(dirname(__FILE__)));
				$log->setVar('action', 'blocked');
				$log->setVar('extra', _XOR_BAN_PHP_OCTETA.' '.$checked['octeta'].' <= ' . $GLOBALS['xortifyModuleConfig']['octeta']."\n".
									  _XOR_BAN_PHP_OCTETB.' '.$checked['octetb'].' > ' . $GLOBALS['xortifyModuleConfig']['octetb']."\n".
									  _XOR_BAN_PHP_OCTETC.' '.$checked['octetc'].' >= ' . $GLOBALS['xortifyModuleConfig']['octetc']);
				
				$_SESSION['xortify']['lid'] = $log_handler->insert($log, true);
				IcmsCache::write('xortify_core_include_common_end', array('time'=>microtime(true)), $GLOBALS['xortifyModuleConfig']['fault_delay']);
				$_SESSION['xortify']['lid'] = $lid;
				setcookie('xortify', array('lid' => $lid), time()+3600*24*7*4*3);
				header('Location: '.ICMS_URL.'/banned.php');
				exit(0);			
			}
		}
	}
	
?>