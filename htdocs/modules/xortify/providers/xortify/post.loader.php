<?php
	error_reporting(E_ALL);
	$checkfields = array('uname', 'email', 'ip4', 'ip6', 'network-addy');
	
	$module_handler = icms::handler('icms_module');
	$config_handler = icms::handler('icms_config');
	$GLOBALS['xortifyModule'] = $module_handler->getByDirname('xortify');
	$GLOBALS['xortifyModuleConfig'] = $config_handler->getConfigList($GLOBALS['xortifyModule']->getVar('mid'));
	
	require_once( ICMS_ROOT_PATH.'/modules/xortify/class/'.$GLOBALS['xortifyModuleConfig']['protocol'].'.php' );
	$func = strtoupper($GLOBALS['xortifyModuleConfig']['protocol']).'XortifyExchange';
	$soapExchg = new $func;
	$bans = $soapExchg->getBans();
	
	if (is_object(icms::$user)) {
		$uid = icms::$user->getVar('uid');
		$uname = icms::$user->getVar('uname');
		$email = icms::$user->getVar('email');
	} else {
		$uid = 0;
		$uname = (isset($_REQUEST['uname'])?$_REQUEST['uname']:'');
		$email = (isset($_REQUEST['email'])?$_REQUEST['email']:'');
	}
	
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
	
	if (is_array($bans['data'])&&count($bans['data'])>0) {
		foreach ($bans['data'] as $id => $ban) {
			foreach($ipdata as $key => $ip) {
				if (isset($ban[$key])&&!empty($ban[$key])&&!empty($ip)) {
					if (in_array($key, $checkfields)) {
						if ($ban[$key] == $ip) {
							icms_loadLanguage('ban', 'xortify');
							
							$log_handler = icms_getModuleHandler('log', 'xortify');
							$log = $log_handler->create();
							$log->setVars($ipdata);
							$log->setVar('provider', basename(dirname(__FILE__)));
							$log->setVar('action', 'blocked');
							$log->setVar('extra', _XOR_BAN_XORT_KEY.' '.$key.'<br/>'.
												  _XOR_BAN_XORT_MATCH.' ('.$key.') '.$ban[$key].' == '.$ip.'<br/>'.
												  _XOR_BAN_XORT_LENGTH.' '.strlen($ban[$key]).' == '.strlen($ip));
							
							$lid = $log_handler->insert($log, true);
							IcmsCache::write('xortify_core_include_common_end', array('time'=>microtime(true)), $GLOBALS['xortifyModuleConfig']['fault_delay']);
							$_SESSION['xortify']['lid'] = $lid;
							setcookie('xortify', array('lid' => $lid), time()+3600*24*7*4*3);
							header('Location: '.ICMS_URL.'/banned.php');
							exit(0);
						}
					}
				}
			}
		}
		$GLOBALS['xortify_pass'] = true;
	}
	
	if (!$checked = IcmsCache::read('xortify_xrt_'.sha1($ipdata['uname'].$ipdata['email'].(isset($ipdata['ip4'])?$ipdata['ip4']:"").(isset($ipdata['ip6'])?$ipdata['ip6']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip4']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip6']:"").$ipdata['network-addy'])))
	{
		$checked = $soapExchg->checkBanned($ipdata);
		IcmsCache::write('xortify_xrt_'.sha1($ipdata['uname'].$ipdata['email'].(isset($ipdata['ip4'])?$ipdata['ip4']:"").(isset($ipdata['ip6'])?$ipdata['ip6']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip4']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip6']:"").$ipdata['network-addy']), array_merge($checked, array('ipdata' => $ipdata)), $GLOBALS['xortifyModuleConfig']['xortify_ip_cache']);
	}
	
	if (isset($checked['count'])) {
		if ($checked['count']>0) {
			foreach ($checked['bans'] as $id => $ban)
				foreach($ipdata as $key => $ip)
					if (in_array($key, $checkfields))
						if (isset($ban[$key])&&!empty($ban[$key])&&!empty($ip)) 
							if ($ban[$key] == $ip) {
								icms_loadLanguage('ban', 'xortify');
								
								$log_handler = icms_getModuleHandler('log', 'xortify');
								$log = $log_handler->create();
								$log->setVars($ipdata);
								$log->setVar('provider', basename(dirname(__FILE__)));
								$log->setVar('action', 'blocked');
								$log->setVar('extra', _XOR_BAN_XORT_KEY.' '.$key.'<br/>'.
													  _XOR_BAN_XORT_MATCH.' '.$ban[$key].' == '.$ip.'<br/>'.
													  _XOR_BAN_XORT_LENGTH.' '.strlen($ban[$key]).' == '.strlen($ip));
								
								include_once ICMS_ROOT_PATH."/include/common.php";
						
								$lid = $log_handler->insert($log, true);
								IcmsCache::write('xortify_core_include_common_end', array('time'=>microtime(true)), $GLOBALS['xortifyModuleConfig']['fault_delay']);
								$_SESSION['xortify']['lid'] = $lid;
								setcookie('xortify', array('lid' => $lid), time()+3600*24*7*4*3);
								header('Location: '.ICMS_URL.'/modules/xortify/banned.php');
								exit(0);
							
							}		
	}
		$GLOBALS['xortify_pass'] = true;
	}
	

?>