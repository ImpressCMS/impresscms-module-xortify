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
	if ($ext=="SimpleXML")
		$nativexml=true;
		
}

if ($nativecurl==true&&$nativexml==true) {
	define('ICMS_CURLXML_LIB', 'PHPCURLXML');
	if (!defined('XORTIFY_USER_AGENT'))
		define('XORTIFY_USER_AGENT', 'Mozilla/5.0 (X11; U; Linux i686; pl-PL; rv:1.9.0.2) ICMS/20100101 IcmsAuth/1.xx (php)');
}

define('XORTIFY_CURLXML_API', $GLOBALS['xortifyModuleConfig']['xortify_urixml']);

include_once(ICMS_ROOT_PATH.'/modules/xortify/include/functions.php');

class CURLXMLXortifyExchange {

	var $curl_client;
	var $xml_icms_username = '';
	var $xml_icms_password = '';
	var $refresh = 600;
		
	function __construct()
	{
		$this->CURLXMLXortifyExchange ();
	}
	
	function CURLXMLXortifyExchange () {
		
		$module_handler =& icms::handler('icms_module');
		$config_handler =& icms::handler('icms_config');
		if (!isset($GLOBALS['xortifyModule'])) $GLOBALS['xortifyModule'] = $module_handler->getByDirname('xortify');
		if (!isset($GLOBALS['xortifyModuleConfig'])) $GLOBALS['xortifyModuleConfig'] = $config_handler->getConfigList($GLOBALS['xortifyModule']->mid());
		
		$this->xml_icms_username = $GLOBALS['xortifyModuleConfig']['xortify_username'];
		$this->xml_icms_password = md5($GLOBALS['xortifyModuleConfig']['xortify_password']);
		$this->refresh = $GLOBALS['xortifyModuleConfig']['xortify_records'];

		if (!$ch = curl_init(XORTIFY_CURLXML_API)) {
			trigger_error('Could not intialise CURL file: '.XORTIFY_CURLXML_API);
			return false;
		}
		$cookies = ICMS_TRUST_PATH.'/cache/icms_cache/authcurl_'.md5(XORTIFY_CURLXML_API).'.cookie'; 

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
			switch (ICMS_CURLXML_LIB){
			case "PHPCURLXML":
				try {
					curl_setopt($this->curl_client, CURLOPT_POSTFIELDS, 'ban='.xortify_toXml(array(      "username"	=> 	$this->xml_icms_username, 
									"password"	=> 	$this->xml_icms_password, 
									"bans" 		=> 	array(	0 	=> 	array_merge(
																				$ipData, 
																				array('category_id' => $category_id)
																				)
													),
									"comments" 	=> 	array(	0	=>	array(	'uname'		=>		$this->xml_icms_username, 
																			"comment" 	=> 		$comment
																	)
													 ) ), 'ban'));
					$data = curl_exec($this->curl_client);
					curl_close($this->curl_client);
					$result = xortify_elekey2numeric(xortify_xml2array($data), 'ban');
				}
				catch (Exception $e) { trigger_error($e); }		
				break;
			}
		return $result['ban'];	
	}

	function checkSFSBans($ipdata) {
		if (!empty($this->curl_client))
			switch (ICMS_CURLXML_LIB){
			case "PHPCURLXML":
				try {
					curl_setopt($this->curl_client, CURLOPT_POSTFIELDS, 'checksfsbans='.xortify_toXml(array(      "username"	=> 	$this->xml_icms_username, 
									"password"	=> 	$this->xml_icms_password, 
									"ipdata" 	=> 	$ipdata
								), 'checksfsbans'));
					$data = curl_exec($this->curl_client);
					curl_close($this->curl_client);
					$result = xortify_elekey2numeric(xortify_xml2array($data), 'checksfsbans');
				}
				catch (Exception $e) { trigger_error($e); }		
				break;
			}
		return $result['checksfsbans'];	
	}

	function checkPHPBans($ipdata) {
		if (!empty($this->curl_client))
			switch (ICMS_CURLXML_LIB){
			case "PHPCURLXML":
				try {
					curl_setopt($this->curl_client, CURLOPT_POSTFIELDS, 'checkphpbans='.xortify_toXml(array(      "username"	=> 	$this->xml_icms_username, 
									"password"	=> 	$this->xml_icms_password, 
									"ipdata" 	=> 	$ipdata
								), 'checkphpbans'));
					$data = curl_exec($this->curl_client);
					curl_close($this->curl_client);
					$result = xortify_elekey2numeric(xortify_xml2array($data), 'checkphpbans');
				}
				catch (Exception $e) { trigger_error($e); }		
				break;
			}
		return $result['checkphpbans'];	
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
			switch (ICMS_CURLXML_LIB){
			case "PHPCURLXML":
				try {
					curl_setopt($this->curl_client, CURLOPT_POSTFIELDS, 'bans='.xortify_toXml(array("username"=> $this->xml_icms_username, "password"=> $this->xml_icms_password,  "records"=> $this->refresh), 'bans'));
					$data = curl_exec($this->curl_client);
					curl_close($this->curl_client);				
					$result = xortify_elekey2numeric(xortify_xml2array($data),'bans');
				}
				catch (Exception $e) { trigger_error($e); }		
			}
		return $result['bans'];
	}

	function checkBanned($ipdata) {
		if (!empty($this->curl_client))
			switch (ICMS_CURLXML_LIB){
				case "PHPCURLXML":
					try {
						curl_setopt($this->curl_client, CURLOPT_POSTFIELDS, 'banned='.xortify_toXml(array("username"=> $this->xml_icms_username, "password"=> $this->xml_icms_password,  "ipdata"=> $ipdata), 'banned'));
						$data = curl_exec($this->curl_client);
						curl_close($this->curl_client);				
						$result = xortify_elekey2numeric(xortify_xml2array($data),'banned');
					}
					
					catch (Exception $e) { trigger_error($e); }				
				break;
			}		
		return $result;
	}
	
}

?>