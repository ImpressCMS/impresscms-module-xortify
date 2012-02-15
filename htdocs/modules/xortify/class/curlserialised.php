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
	if ($ext=="curl")
		$nativecurl=true;
}

if ($nativecurl==true) {
	if (!defined('ICMS_CURLSERIAL_LIB'))
		define('ICMS_CURLSERIAL_LIB', 'PHPCURLSERIAL');
	if (!defined('XORTIFY_USER_AGENT'))
		define('XORTIFY_USER_AGENT', 'Mozilla/5.0 (X11; U; Linux i686; pl-PL; rv:1.9.0.2) ICMS/20100101 IcmsAuth/1.xx (php)');
}

define('XORTIFY_CURLSERIAL_API', $GLOBALS['xortifyModuleConfig']['xortify_uriserial']);

include_once(ICMS_ROOT_PATH.'/modules/xortify/include/functions.php');

class CURLSERIALISEDXortifyExchange {

	var $curl_client;
	var $serial_icms_username = '';
	var $serial_icms_password = '';
	var $refresh = 600;
	var $json = '';
	
	function __construct()
	{
		$this->CURLSERIALISEDXortifyExchange ();
	}
	
	function CURLSERIALISEDXortifyExchange () {
		
		$module_handler =& icms::handler('icms_module');
		$config_handler =& icms::handler('icms_config');
		if (!isset($GLOBALS['xortifyModule'])) $GLOBALS['xortifyModule'] = $module_handler->getByDirname('xortify');
		if (!isset($GLOBALS['xortifyModuleConfig'])) $GLOBALS['xortifyModuleConfig'] = $config_handler->getConfigList($GLOBALS['xortifyModule']->mid());
		
		$this->serial_icms_username = $GLOBALS['xortifyModuleConfig']['xortify_username'];
		$this->serial_icms_password = md5($GLOBALS['xortifyModuleConfig']['xortify_password']);
		$this->refresh = $GLOBALS['xortifyModuleConfig']['xortify_records'];

		if (!$ch = curl_init(XORTIFY_CURLSERIAL_API)) {
			trigger_error('Could not intialise CURLSERIAL file: '.XORTIFY_CURLSERIAL_API);
			return false;
		}
		$cookies = ICMS_TRUST_PATH.'/cache/icms_cache/authcurl_'.md5(XORTIFY_CURLSERIAL_API).'.cookie'; 

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
			switch (ICMS_CURLSERIAL_LIB){
			case "PHPCURLSERIAL":
				try {
					curl_setopt($this->curl_client, CURLOPT_POSTFIELDS, 'ban='.serialize(array(      "username"	=> 	$this->serial_icms_username, 
									"password"	=> 	$this->serial_icms_password, 
									"bans" 		=> 	array(	0 	=> 	array_merge(
																				$ipData, 
																				array('category_id' => $category_id)
																				)
													),
									"comments" 	=> 	array(	0	=>	array(	'uname'		=>		$this->serial_icms_username, 
																			"comment" 	=> 		$comment
																	)
													 ) )));
					$data = curl_exec($this->curl_client);
					curl_close($this->curl_client);
					$result = (unserialize($data));
				}
				catch (Exception $e) { trigger_error($e); }				
				break;
			}
		return $result;	
	}

	function checkSFSBans($ipdata) {
		if (!empty($this->curl_client))
			switch (ICMS_CURLSERIAL_LIB){
			case "PHPCURLSERIAL":
				try {
					curl_setopt($this->curl_client, CURLOPT_POSTFIELDS, 'checksfsbans='.serialize(array(      "username"	=> 	$this->serial_icms_username, 
									"password"	=> 	$this->serial_icms_password, 
									"ipdata" 	=> 	$ipdata
								)));
					$data = curl_exec($this->curl_client);
					curl_close($this->curl_client);
					$result = (unserialize($data));
				}
				catch (Exception $e) { trigger_error($e); }				
				break;
			}
		return $result;	
	}

	function checkPHPBans($ipdata) {
		if (!empty($this->curl_client))
			switch (ICMS_CURLSERIAL_LIB){
			case "PHPCURLSERIAL":
				try {
					curl_setopt($this->curl_client, CURLOPT_POSTFIELDS, 'checkphpbans='.serialize(array(      "username"	=> 	$this->serial_icms_username, 
									"password"	=> 	$this->serial_icms_password, 
									"ipdata" 	=> 	$ipdata
								)));
					$data = curl_exec($this->curl_client);
					curl_close($this->curl_client);
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
		if (!empty($this->curl_client))
			switch (ICMS_CURLSERIAL_LIB){
			case "PHPCURLSERIAL":
				try {
					curl_setopt($this->curl_client, CURLOPT_POSTFIELDS, 'bans='.serialize(array("username"=> $this->serial_icms_username, "password"=> $this->serial_icms_password,  "records"=> $this->refresh)	 ) );
					$data = curl_exec($this->curl_client);
					curl_close($this->curl_client);				
					$result = (unserialize($data));
				}
				catch (Exception $e) { trigger_error($e); }		
			}
		return $result;
	}

	function checkBanned($ipdata) {
		if (!empty($this->curl_client))
			switch (ICMS_CURLSERIAL_LIB){
			case "PHPCURLSERIAL":
				try {
					curl_setopt($this->curl_client, CURLOPT_POSTFIELDS, 'banned='.serialize(array("username"=> $this->serial_icms_username, "password"=> $this->serial_icms_password,  "ipdata"=> $ipdata)	 ) );
					$data = curl_exec($this->curl_client);
					curl_close($this->curl_client);				
					$result = (unserialize($data));
				}
				catch (Exception $e) { trigger_error($e); }				
			break;
		}		
		return $result;
	}
	
}

?>