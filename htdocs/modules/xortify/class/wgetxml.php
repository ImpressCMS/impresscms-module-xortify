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
	if ($ext=="SimpleXML")
		$nativexml=true;
		
}

if ($nativexml==true)
	define('ICMS_WGETXML_LIB', 'PHPXML');
	
define('XORTIFY_WGETXML_API', $GLOBALS['xortifyModuleConfig']['xortify_urixml']);

include_once(ICMS_ROOT_PATH.'/modules/xortify/include/functions.php');

class WGETXMLXortifyExchange {

	var $xml_icms_username = '';
	var $xml_icms_password = '';
	var $refresh = 600;
		
	function __construct()
	{
		$this->WGETXMLXortifyExchange ();
	}
	
	function WGETXMLXortifyExchange () {
	
		$module_handler =& icms::handler('icms_module');
		$config_handler =& icms::handler('icms_config');
		if (!isset($GLOBALS['xortifyModule'])) $GLOBALS['xortifyModule'] = $module_handler->getByDirname('xortify');
		if (!isset($GLOBALS['xortifyModuleConfig'])) $GLOBALS['xortifyModuleConfig'] = $config_handler->getConfigList($GLOBALS['xortifyModule']->mid());
		
		$this->xml_icms_username = $GLOBALS['xortifyModuleConfig']['xortify_username'];
		$this->xml_icms_password = md5($GLOBALS['xortifyModuleConfig']['xortify_password']);
		$this->refresh = $GLOBALS['xortifyModuleConfig']['xortify_records'];
			
	}

	function sendBan($comment, $category_id = 2, $ip=false) {

		$ipData = xortify_getIPData($ip);
		
		switch (ICMS_WGETXML_LIB){
		default:
		case "PHPXML":
			try {
				$data = file_get_contents(XORTIFY_WGETXML_API.'?ban='.urlencode(xortify_toXml( array(      "username"	=> 	$this->xml_icms_username, 
								"password"	=> 	$this->xml_icms_password, 
								"bans" 		=> 	array(	0 	=> 	array_merge(
																			$ipData, 
																			array('category_id' => $category_id)
																			)
												),
								"comments" 	=> 	array(	0	=>	array(	'uname'		=>		$this->xml_icms_username, 
																		"comment" 	=> 		$comment
																)
												 ) 
						), 'ban')));
				$result = xortify_elekey2numeric(xortify_xml2array($data), 'ban');
			}
			catch (Exception $e) { trigger_error($e); }
			break;
		}			
		return $result['ban'];	
	}

	function checkSFSBans($ipdata) {
		switch (ICMS_WGETXML_LIB){
		default:
		case "PHPXML":
			try {
				$data = file_get_contents(XORTIFY_WGETXML_API.'?checksfsbans='.urlencode(xortify_toXml( 
						array(  "username"	=> 	$this->xml_icms_username, 
								"password"	=> 	$this->xml_icms_password, 
								"ipdata" 	=> 	$ipdata
						), 'checksfsbans')));
				$result = xortify_elekey2numeric(xortify_xml2array($data), 'checksfsbans');
			}
			catch (Exception $e) { trigger_error($e); }
			break;
		}			
		return $result['checksfsbans'];	
	}

	function checkPHPBans($ipdata) {
		
		switch (ICMS_WGETXML_LIB){
		default:
		case "PHPXML":
			try {
				$data = file_get_contents(XORTIFY_WGETXML_API.'?checkphpbans='.urlencode(xortify_toXml( 
						array(  "username"	=> 	$this->xml_icms_username, 
								"password"	=> 	$this->xml_icms_password, 
								"ipdata" 	=> 	$ipdata
						), 'checkphpbans')));
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
			
		switch (ICMS_WGETXML_LIB){
		default:
		case "PHPXML":
			try {
				$data = file_get_contents(XORTIFY_WGETXML_API.'?bans='.urlencode(xortify_toXml(array("username"=> $this->xml_icms_username, "password"=> $this->xml_icms_password,  "records"=> $this->refresh), 'bans')));
				$result = xortify_elekey2numeric(xortify_xml2array($data), 'bans');
			}
			catch (Exception $e) { trigger_error($e); }
			break;
		}
		return $result['bans'];
	}

	function checkBanned($ipdata) {
		switch (ICMS_WGETXML_LIB){
		default:
		case "PHPXML":
			try {
				$data = file_get_contents(XORTIFY_WGETXML_API.'?banned='.urlencode(xortify_toXml(array("username"=> $this->xml_icms_username, "password"=> $this->xml_icms_password,  "ipdata"=> $ipdata), 'banned')));
				$result = xortify_elekey2numeric(xortify_xml2array($data), 'banned');
			}
			catch (Exception $e) { trigger_error($e); }
			break;
		}
		return $result;
	}
}


?>