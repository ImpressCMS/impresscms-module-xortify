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


define('XORTIFY_JSON_API', $GLOBALS['xortifyModuleConfig']['xortify_urijson']);

include_once(ICMS_ROOT_PATH.'/modules/xortify/include/functions.php');


define('ICMS_JSON_LIB', 'PHPJSON');

class JSONXortifyExchange {

	var $json_client;
	var $json_icms_username = '';
	var $json_icms_password = '';
	var $refresh = 600;
		
	function __construct()
	{
		$this->JSONXortifyExchange ();
	}
	
	function JSONXortifyExchange () {
	
		$module_handler =& icms::handler('icms_module');
		$config_handler =& icms::handler('icms_config');
		if (!isset($GLOBALS['xortifyModule'])) $GLOBALS['xortifyModule'] = $module_handler->getByDirname('xortify');
		if (!isset($GLOBALS['xortifyModuleConfig'])) $GLOBALS['xortifyModuleConfig'] = $config_handler->getConfigList($GLOBALS['xortifyModule']->mid());
		
		$this->json_icms_username = $GLOBALS['xortifyModuleConfig']['xortify_username'];
		$this->json_icms_password = md5($GLOBALS['xortifyModuleConfig']['xortify_password']);
		$this->refresh = $GLOBALS['xortifyModuleConfig']['xortify_records'];
			
	}

	function sendBan($comment, $category_id = 2, $ip=false) {

		$ipData = xortify_getIPData($ip);
		
		switch (ICMS_JSON_LIB){
		default:
		case "PHPJSON":
			try {
				$data = file_get_contents(XORTIFY_JSON_API.'?ban='.urlencode(json_encode( array(      "username"	=> 	$this->json_icms_username, 
								"password"	=> 	$this->json_icms_password, 
								"bans" 		=> 	array(	0 	=> 	array_merge(
																			$ipData, 
																			array('category_id' => $category_id)
																			)
												),
								"comments" 	=> 	array(	0	=>	array(	'uname'		=>		$this->json_icms_username, 
																		"comment" 	=> 		$comment
																)
												 ) 
						))));
				$result = xortify_obj2array(json_decode($data));
			}
			catch (Exception $e) { trigger_error($e); }
			break;
		}			
		return $result;	
	}

	function checkSFSBans($ipdata) {
		
		switch (ICMS_JSON_LIB){
		default:
		case "PHPJSON":
			try {
				$data = file_get_contents(XORTIFY_JSON_API.'?checksfsbans='.urlencode(json_encode( 
						array(  "username"	=> 	$this->json_icms_username, 
								"password"	=> 	$this->json_icms_password, 
								"ipdata" 	=> 	$ipdata
						))));
				$result = xortify_obj2array(json_decode($data));
			}
			catch (Exception $e) { trigger_error($e); }
			break;
		}			
		return $result;	
	}

	function checkPHPBans($ipdata) {
		
		switch (ICMS_JSON_LIB){
		default:
		case "PHPJSON":
			try {
				$data = file_get_contents(XORTIFY_JSON_API.'?checkphpbans='.urlencode(json_encode( 
						array(  "username"	=> 	$this->json_icms_username, 
								"password"	=> 	$this->json_icms_password, 
								"ipdata" 	=> 	$ipdata
						))));
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
		switch (ICMS_JSON_LIB){
		default:
		case "PHPJSON":
			try {
				$data = file_get_contents(XORTIFY_JSON_API.'?bans='.urlencode(json_encode(array("username"=> $this->json_icms_username, "password"=> $this->json_icms_password,  "records"=> $this->refresh))));
				$result = xortify_obj2array(json_decode($data));
			}
			catch (Exception $e) { trigger_error($e); }
			break;
		}
		return $result;
	}

	function checkBanned($ipdata) {
		switch (ICMS_JSON_LIB){
		default:
		case "PHPJSON":
			try {
				$data = file_get_contents(XORTIFY_JSON_API.'?banned='.urlencode(json_encode(array("username"=> $this->json_icms_username, "password"=> $this->json_icms_password,  "ipdata"=> $ipdata))));
				$result = xortify_obj2array(json_decode($data));
			}
			catch (Exception $e) { trigger_error($e); }
			break;
		}
		return $result;
	}
}


?>