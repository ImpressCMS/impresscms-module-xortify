<?php
/**
 * @package     xortify
 * @subpackage  module
 * @description	Sector Network Security Drone
 * @author	    Simon Roberts WISHCRAFT <simon@chronolabs.coop>
 * @copyright	copyright (c) 2010-2013 ICMS.org
 * @licence		GPL 2.0 - see docs/LICENCE.txt
 */

define('XORTIFY_SERIAL_API', $GLOBALS['xortifyModuleConfig']['xortify_uriserial']);

include_once(ICMS_ROOT_PATH.'/modules/xortify/include/functions.php');

define('ICMS_SERIAL_LIB', 'PHPSERIAL');

class WGETSERIALISEDXortifyExchange {

	var $serial_client;
	var $serial_icms_username = '';
	var $serial_icms_password = '';
	var $refresh = 600;
	var $serial = '';
	
	function __construct()
	{
		$this->WGETSERIALISEDXortifyExchange ();
	}
	
	function WGETSERIALISEDXortifyExchange () {
	
		$module_handler =& icms::handler('icms_module');
		$config_handler =& icms::handler('icms_config');
		if (!isset($GLOBALS['xortifyModule'])) $GLOBALS['xortifyModule'] = $module_handler->getByDirname('xortify');
		if (!isset($GLOBALS['xortifyModuleConfig'])) $GLOBALS['xortifyModuleConfig'] = $config_handler->getConfigList($GLOBALS['xortifyModule']->mid());
		
		$this->serial_icms_username = $GLOBALS['xortifyModuleConfig']['xortify_username'];
		$this->serial_icms_password = md5($GLOBALS['xortifyModuleConfig']['xortify_password']);
		$this->refresh = $GLOBALS['xortifyModuleConfig']['xortify_records'];
			
	}

	function sendBan($comment, $category_id = 2, $ip=false) {

		$ipData = xortify_getIPData($ip);
		
		switch (ICMS_SERIAL_LIB){
		default:
		case "PHPSERIAL":
			try {
				$data = file_get_contents(XORTIFY_SERIAL_API.'?ban='.urlencode(serialize( array(      "username"	=> 	$this->serial_icms_username, 
								"password"	=> 	$this->serial_icms_password, 
								"bans" 		=> 	array(	0 	=> 	array_merge(
																			$ipData, 
																			array('category_id' => $category_id)
																			)
												),
								"comments" 	=> 	array(	0	=>	array(	'uname'		=>		$this->serial_icms_username, 
																		"comment" 	=> 		$comment
																)
												 ) 
						))));
				$result = (unserialize($data));
			}
			catch (Exception $e) { trigger_error($e); }
							
			break;
		}			
		return $result;	
	}

	function checkSFSBans($ipdata) {
		
		switch (ICMS_SERIAL_LIB){
		default:
		case "PHPSERIAL":
			try {
				$data = file_get_contents(XORTIFY_SERIAL_API.'?checksfsbans='.urlencode(serialize( 
						array(  "username"	=> 	$this->serial_icms_username, 
								"password"	=> 	$this->serial_icms_password, 
								"ipdata" 	=> 	$ipdata
						))));
				$result = (unserialize($data));
			}
			catch (Exception $e) { trigger_error($e); }
			
			break;
		}			
		return $result;	
	}

	function checkPHPBans($ipdata) {
		
		switch (ICMS_SERIAL_LIB){
		default:
		case "PHPSERIAL":
			try {
				$data = file_get_contents(XORTIFY_SERIAL_API.'?checkphpbans='.urlencode(serialize( 
						array(  "username"	=> 	$this->serial_icms_username, 
								"password"	=> 	$this->serial_icms_password, 
								"ipdata" 	=> 	$ipdata
						))));
				$result = (unserialize($data));
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
		switch (ICMS_SERIAL_LIB){
		default:
		case "PHPSERIAL":
			try {
				$data = file_get_contents(XORTIFY_SERIAL_API.'?bans='.urlencode(serialize(array("username"=> $this->serial_icms_username, "password"=> $this->serial_icms_password,  "records"=> $this->refresh))));
				$result = (unserialize($data));
			}
			catch (Exception $e) { trigger_error($e); }
			break;
		}
		return $result;
	}

	function checkBanned($ipdata) {
		switch (ICMS_SERIAL_LIB){
		default:
		case "PHPSERIAL":
			try {
				$data = file_get_contents(XORTIFY_SERIAL_API.'?banned='.urlencode(serialize(array("username"=> $this->serial_icms_username, "password"=> $this->serial_icms_password,  "ipdata"=> $ipdata))));
				$result = (unserialize($data));
			}
			catch (Exception $e) { trigger_error($e); }
			break;
		}
		return $result;
	}
}


?>