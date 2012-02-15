<?php
/**
 * @package     xortify
 * @subpackage  module
 * @description	Sector Network Security Drone
 * @author	    Simon Roberts WISHCRAFT <simon@chronolabs.coop>
 * @copyright	copyright (c) 2010-2013 ICMS.org
 * @licence		GPL 2.0 - see docs/LICENCE.txt
 */

if (!function_exists('json_encode')){
	function json_encode($data) {
		static $json = NULL;
		if (!class_exists('Services_JSON') ) { include_once (ICMS_ROOT_PATH.'/modules/xortify/include/JSON.php'); }
		$json = new Services_JSON();
		return $json->encode($data);
	}
}

if (!function_exists('json_decode')){
	function json_decode($data) {
		static $json = NULL;
		if (!class_exists('Services_JSON') ) { include_once (ICMS_ROOT_PATH.'/modules/xortify/include/JSON.php'); }
		$json = new Services_JSON();
		return $json->decode($data);
	}
}

foreach (get_loaded_extensions() as $ext){
	if ($ext=="curl")
		$nativecurl=true;
}

if ($nativecurl==true) {
	define('ICMS_CURL_LIB', 'PHPCURL');
	if (!defined('XORTIFY_USER_AGENT'))
		define('XORTIFY_USER_AGENT', 'Mozilla/5.0 (X11; U; Linux i686; pl-PL; rv:1.9.0.2) ICMS/20100101 IcmsAuth/1.xx (php)');
}

define('XORTIFY_CURL_API', $GLOBALS['xortifyModuleConfig']['xortify_uricurl']);

include_once(ICMS_ROOT_PATH.'/modules/xortify/include/functions.php');

class CURLXortifyExchange {

	var $curl_client;
	var $curl_icms_username = '';
	var $curl_icms_password = '';
	var $refresh = 600;
		
	function __construct()
	{
		$this->CURLXortifyExchange ();
	}
	
	function CURLXortifyExchange () {
		
		$module_handler =& icms::handler('icms_module');
		$config_handler =& icms::handler('icms_config');
		if (!isset($GLOBALS['xortifyModule'])) $GLOBALS['xortifyModule'] = $module_handler->getByDirname('xortify');
		if (!isset($GLOBALS['xortifyModuleConfig'])) $GLOBALS['xortifyModuleConfig'] = $config_handler->getConfigList($GLOBALS['xortifyModule']->mid());
		
		$this->curl_icms_username = $GLOBALS['xortifyModuleConfig']['xortify_username'];
		$this->curl_icms_password = md5($GLOBALS['xortifyModuleConfig']['xortify_password']);
		$this->refresh = $GLOBALS['xortifyModuleConfig']['xortify_records'];

		if (!$ch = curl_init(XORTIFY_CURL_API)) {
			trigger_error('Could not intialise CURL file: '.XORTIFY_CURL_API);
			return false;
		}
		$cookies = ICMS_TRUST_PATH.'/cache/icms_cache/authcurl_'.md5(XORTIFY_CURL_API).'.cookie'; 

		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $GLOBALS['xortifyModuleConfig']['curl_connecttimeout']);
		curl_setopt($ch, CURLOPT_TIMEOUT, $GLOBALS['xortifyModuleConfig']['curl_timeout']);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookies); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_USERAGENT, XORTIFY_USER_AGENT); 
		$this->curl_client =& $ch;			
	}
	

	function sendBan($comment, $category_id = 2, $ip=false) {
		$ipData = xortify_getIPData($ip);
		if (!empty($this->curl_client)) 
			switch (ICMS_CURL_LIB){
			case "PHPCURL":
				try {
					curl_setopt($this->curl_client, CURLOPT_POSTFIELDS, 'ban='.json_encode(array(      "username"	=> 	$this->curl_icms_username, 
									"password"	=> 	$this->curl_icms_password, 
									"bans" 		=> 	array(	0 	=> 	array_merge(
																				$ipData, 
																				array('category_id' => $category_id)
																				)
													),
									"comments" 	=> 	array(	0	=>	array(	'uname'		=>		$this->curl_icms_username, 
																			"comment" 	=> 		$comment
																	)
													 ) )));
					$data = curl_exec($this->curl_client);
					curl_close($this->curl_client);
					$result = xortify_obj2array(json_decode($data));
				}
				catch (Exception $e) { trigger_error($e); }		
				break;
			}
		return $result;	
	}

	function checkSFSBans($ipdata) {
		if (!empty($this->curl_client))
			switch (ICMS_CURL_LIB){
			case "PHPCURL":
				try {
					curl_setopt($this->curl_client, CURLOPT_POSTFIELDS, 'checksfsbans='.json_encode(array(      "username"	=> 	$this->curl_icms_username, 
									"password"	=> 	$this->curl_icms_password, 
									"ipdata" 	=> 	$ipdata
								)));
					$data = curl_exec($this->curl_client);
					curl_close($this->curl_client);
					$result = xortify_obj2array(json_decode($data));
				}		
				catch (Exception $e) { trigger_error($e); }
				break;
			}
		return $result;	
	}

	function checkPHPBans($ipdata) {
		if (!empty($this->curl_client))
			switch (ICMS_CURL_LIB){
			case "PHPCURL":
				try {
					curl_setopt($this->curl_client, CURLOPT_POSTFIELDS, 'checkphpbans='.json_encode(array(      "username"	=> 	$this->curl_icms_username, 
									"password"	=> 	$this->curl_icms_password, 
									"ipdata" 	=> 	$ipdata
								)));
					$data = curl_exec($this->curl_client);
					curl_close($this->curl_client);
					$result = xortify_obj2array(json_decode($data));
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
		if (!empty($this->curl_client))
			switch (ICMS_CURL_LIB){
			case "PHPCURL":
				try {
					curl_setopt($this->curl_client, CURLOPT_POSTFIELDS, 'bans='.json_encode(array("username"=> $this->curl_icms_username, "password"=> $this->curl_icms_password,  "records"=> $this->refresh)	 ) );
					$data = curl_exec($this->curl_client);
					curl_close($this->curl_client);				
					$result = xortify_obj2array(json_decode($data));
				}		
				catch (Exception $e) { trigger_error($e); }
			}
		return $result;
	}

	function checkBanned($ipdata) {
		switch (ICMS_CURL_LIB){
			case "PHPCURL":
				try {
					curl_setopt($this->curl_client, CURLOPT_POSTFIELDS, 'banned='.json_encode(array("username"=> $this->curl_icms_username, "password"=> $this->curl_icms_password,  "ipdata"=> $ipdata)	 ) );
					$data = curl_exec($this->curl_client);
					curl_close($this->curl_client);				
					$result = xortify_obj2array(json_decode($data));
				}
				catch (Exception $e) { trigger_error($e); }				
			break;
		}		
		return $result;
	}
	
	
}

?>