<?php
/**
 * @package     xortify
 * @subpackage  module
 * @description	Sector Network Security Drone
 * @author	    Simon Roberts WISHCRAFT <simon@chronolabs.coop>
 * @copyright	copyright (c) 2010-2013 ICMS.org
 * @licence		GPL 2.0 - see docs/LICENCE.txt
 */

 
define('XORTIFY_CURLSERIAL_API', $GLOBALS['xortifyModuleConfig']['xortify_uriserial']);
define('XORTIFY_USER_AGENT', 'Mozilla/5.0 (X11; U; Linux i686; pl-PL; rv:1.9.0.2) ICMS/20100101 IcmsAuth/1.xx (php)');
include_once ICMS_ROOT_PATH . '/modules/xortify/class/auth/auth_curlserialised_provisionning.php';

class XortifyAuthCurlserialised extends XortifyAuth {
	
	var $curl_client;
	var $curl_icms_username = '';
	var $curl_icms_password = '';
	var $_dao;
	/**
	 * Authentication Service constructor
	 */
	function XortifyAuthCurlserialised (&$dao) {
		
		if (!$ch = curl_init(XORTIFY_CURLSERIAL_API)) {
			trigger_error('Could not intialise CURL file: '.$url);
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


	/**
	 *  Authenticate  user again curl directory (Bind)
	 *
	 * @param string $uname Username
	 * @param string $pwd Password
	 *
	 * @return bool
	 */	
	function authenticate($uname, $pwd = null) {
		$authenticated = false;
		$this->XortifyAuthCurlserialised($GLOBALS['db']);
		
		if (!$this->curl_client) {
			$this->setErrors(0, _AUTH_CURL_EXTENSION_NOT_LOAD);
			return $authenticated;
		}

				
		$rnd = rand(-100000, 100000000);
		curl_setopt($this->curl_client, CURLOPT_POSTFIELDS, array('xoops_authentication' => serialize(array("username"=> $this->curl_icms_username, "password"=> $this->curl_icms_password, "auth" => array('username' => $uname, "password" => $pwd, "time" => time(), "passhash" => sha1((time()-$rnd).$uname.$pwd), "rand"=>$rnd)))));
		$data = curl_exec($this->curl_client);
		curl_close($this->curl_client);
		$result = unserialize($data);
		return $result["RESULT"];		
	}
	
				  
	/**
	 *  validate a user via curl
	 *
	 * @param string $uname
	 * @param string $email
	 * @param string $pass
	 * @param string $vpass
	 *
	 * @return string
	 */		
	function validate($uname, $email, $pass, $vpass){
		$this->XortifyAuthCurlserialised($GLOBALS['db']);
		$rnd = rand(-100000, 100000000);
		curl_setopt($this->curl_client, CURLOPT_POSTFIELDS, array('xoops_user_validate' => serialize(array("username"=> $this->curl_icms_username, "password"=> $this->curl_icms_password, "validate" => array('uname' => $uname, "pass" => $pass, "vpass" => $vpass, "email" => $email, "time" => time(), "passhash" => sha1((time()-$rnd).$uname.$pass), "rand"=>$rnd)))));
		$data = curl_exec($this->curl_client);
		curl_close($this->curl_client);
		$result = unserialize($data);
		if ($result['ERRNUM']==1){
			return $result["RESULT"];
		} else {
			return false;
		}
	
	}

	
	/**
	 *  get the xoops site disclaimer via curl
	 *
	 * @return string
	 */			
	function network_disclaimer(){
		$this->XortifyAuthCurlserialised($GLOBALS['db']);
		curl_setopt($this->curl_client, CURLOPT_POSTFIELDS, array('xoops_network_disclaimer' => serialize(array("username"=> $this->curl_icms_username, "password"=> $this->curl_icms_password))));
		$data = curl_exec($this->curl_client);
		curl_close($this->curl_client);
		$result = unserialize($data);	
		if ($result['ERRNUM']==1){
			return $result["RESULT"];
		} else {
			return false;
		}

	}
	
	/**
	 *  create a user
	 *
	 * @param bool $user_viewemail
	 * @param string $uname
	 * @param string $email
	 * @param string $url
	 * @param string $actkey
	 * @param string $pass
	 * @param integer $timezone_offset
	 * @param bool $user_mailok		 
	 * @param array $siteinfo
	 *
	 * @return array
	 */	
	function create_user($user_viewemail, $uname, $email, $url, $actkey, 
						 $pass, $timezone_offset, $user_mailok, $siteinfo){
						 
		$siteinfo = $this->check_siteinfo($siteinfo);

		$rnd = rand(-100000, 100000000);
		$this->XortifyAuthCurlserialised($GLOBALS['db']);
		curl_setopt($this->curl_client, CURLOPT_POSTFIELDS, 'xoops_create_user='.serialize(array("username"=> $this->curl_icms_username, "password"=> $this->curl_icms_password, "user" => array('user_viewemail' =>$user_viewemail, 'uname' => $uname, 'email' => $email, 'url' => $url, 'actkey' => $actkey, 'pass' => $pass, 'timezone_offset' => $timezone_offset, 'user_mailok' => $user_mailok, "time" => time(), "passhash" => sha1((time()-$rnd).$uname.$pass), "rand"=>$rnd), "siteinfo" => $siteinfo)));
		$data = curl_exec($this->curl_client);
		curl_close($this->curl_client);	
		$result = unserialize($data);	
		if ($result['ERRNUM']==1){
			return $result["RESULT"];		
		} else {
			return false;
		}
	}
	
	function obj2array($objects) {
		$ret = array();
		foreach($objects as $key => $value) {
			if (is_a($value, 'stdClass')) {
				$ret[$key] = (array)$value;
			} elseif (is_array($value)) {
				$ret[$key] = $this->obj2array($value);
			} else {
				$ret[$key] = $value;
			}
		}
		return $ret;
	}
		
}
// end class


?>
