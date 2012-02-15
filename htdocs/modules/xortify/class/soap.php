<?php
/**
 * @package     xortify
 * @subpackage  module
 * @description	Sector Network Security Drone
 * @author	    Simon Roberts WISHCRAFT <simon@chronolabs.coop>
 * @copyright	copyright (c) 2010-2013 ICMS.org
 * @licence		GPL 2.0 - see docs/LICENCE.txt
 */

foreach (get_loaded_extensions() as $ext){
	if ($ext=="soap")
		$nativesoap=true;
}

if ($nativesoap==true)
	define('ICMS_SOAP_LIB', 'PHPSOAP');
	
if (!defined('XORTIFY_API_LOCAL'))
	define('XORTIFY_API_LOCAL', $GLOBALS['xortifyModuleConfig']['xortify_urisoap']);
	
if (!defined('XORTIFY_API_URI'))
	define('XORTIFY_API_URI', $GLOBALS['xortifyModuleConfig']['xortify_urisoap']);

class SOAPXortifyExchange {

	var $soap_client;
	var $soap_icms_username = '';
	var $soap_icms_password = '';
	var $refresh = 600;
	
	function SOAPXortifyExchange () {
	
		$module_handler =& icms::handler('icms_module');
		$config_handler =& icms::handler('icms_config');
		if (!isset($GLOBALS['xortifyModule'])) $GLOBALS['xortifyModule'] = $module_handler->getByDirname('xortify');
		if (!isset($GLOBALS['xortifyModuleConfig'])) $GLOBALS['xortifyModuleConfig'] = $config_handler->getConfigList($GLOBALS['xortifyModule']->mid());
		
		$this->soap_icms_username = $GLOBALS['xortifyModuleConfig']['xortify_username'];
		$this->soap_icms_password = md5($GLOBALS['xortifyModuleConfig']['xortify_password']);
		$this->refresh = $GLOBALS['xortifyModuleConfig']['xortify_records'];
			
		switch (ICMS_SOAP_LIB){
		case "PHPSOAP":
			$this->soap_client = new soapclient(NULL, array('location' => XORTIFY_API_LOCAL, 'uri' => XORTIFY_API_URI));
			break;
		}
	}
	
	function sendBan($comment, $category_id = 2, $ip=false) {

		$ipData = xortify_getIPData($ip);
		
		switch (ICMS_SOAP_LIB){
		case "PHPSOAP":
			try {
				$result = $this->soap_client->__soapCall('ban',
	 				array(      "username"	=> 	$this->soap_icms_username, 
								"password"	=> 	$this->soap_icms_password, 
								"bans" 		=> 	array(	0 	=> 	array_merge(
																			$ipData, 
																			array('category_id' => $category_id)
																			)
												),
								"comments" 	=> 	array(	0	=>	array(	'uname'		=>		$this->soap_icms_username, 
																		"comment" 	=> 		$comment
																)
												 ) 
						));
			}
			catch (Exception $e) { trigger_error($e); }
						
			break;
		}
			
		return $result;	
	}

	function checkSFSBans($ipdata) {
		error_reporting(E_ALL);
		switch (ICMS_SOAP_LIB){
		case "PHPSOAP":
			try {
				$result = $this->soap_client->__soapCall('checksfsbans',
	 				array(      "username"	=> 	$this->soap_icms_username, 
								"password"	=> 	$this->soap_icms_password, 
								"ipdata" 	=> 	$ipdata
						));
			}
			catch (Exception $e) { trigger_error($e); }
						
			break;
		}
			
		return $result;	
	}

	function checkPHPBans($ipdata) {
		
		switch (ICMS_SOAP_LIB){
		case "PHPSOAP":
			try {
				$result = $this->soap_client->__soapCall('checkphpbans',
	 				array(      "username"	=> 	$this->soap_icms_username, 
								"password"	=> 	$this->soap_icms_password, 
								"ipdata" 	=> 	$ipdata
						));
			}
			catch (Exception $e) { trigger_error($e); }
			break;
		}
			
		return $result;	
	}
	
	function getBans() {
		if (!class_exists('IcmsCache')) {
			include_once ICMS_ROOT_PATH.'/modules/xortify/class/cache/icmscache.php';
		}
		
        if (! $bans = @IcmsCache::read('xortify_bans_cache')) {
				$bans = @IcmsCache::read('xortify_bans_cache_backup');
				$GLOBALS['xoDoSoap'] = true;
        }
		return $bans;
	}
	
	function retrieveBans() {
		switch (ICMS_SOAP_LIB){
		case "PHPSOAP":
			try {
				$result = $this->soap_client->__soapCall('bans', array("username"=> $this->soap_icms_username, "password"=> $this->soap_icms_password,  "records"=> $this->refresh));
			}
			catch (Exception $e) { trigger_error($e); }
			break;
		}		
		return $result;
	}
	
	function checkBanned($ipdata) {
		switch (ICMS_SOAP_LIB){
		case "PHPSOAP":
			try {
				$result = $this->soap_client->__soapCall('banned', array("username"=> $this->soap_icms_username, "password"=> $this->soap_icms_password,  "ipdata"=> $ipdata));
			}
			catch (Exception $e) { trigger_error($e); }
			break;
		}		
		return $result;
	}
}


?>