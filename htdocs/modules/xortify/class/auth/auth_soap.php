<?php
/**
 * @package     xortify
 * @subpackage  module
 * @description	Sector Network Security Drone
 * @author	    Simon Roberts WISHCRAFT <simon@chronolabs.coop>
 * @copyright	copyright (c) 2010-2013 ICMS.org
 * @licence		GPL 2.0 - see docs/LICENCE.txt
 */

if (!defined('XORTIFY_API_LOCAL'))
	define('XORTIFY_API_LOCAL', $GLOBALS['xortifyModuleConfig']['xortify_urisoap']);
	
if (!defined('XORTIFY_API_URI'))
	define('XORTIFY_API_URI', $GLOBALS['xortifyModuleConfig']['xortify_urisoap']);

include_once ICMS_ROOT_PATH . '/modules/xortify/class/auth/auth_soap_provisionning.php';

if (!file_exists(ICMS_ROOT_PATH.'/class/soap/xoopssoap.php')){
	foreach (get_loaded_extensions() as $ext){
		if ($ext=="soap")
			$native=true;
	}
	if ($native!=true) {
		define('ICMS_SOAP_LIB','NUSOAP');
		require_once('../nusoap/nusoap.php');
	} else {
		define('ICMS_SOAP_LIB','PHPSOAP');
	}
} else {
	require_once (ICMS_ROOT_PATH.'/class/soap/xoopssoap.php');
}

class XortifyAuthSoap extends XortifyAuth {
	
	var $soap_client;
	var $soap_icms_username = '';
	var $soap_icms_password = '';
	var $_dao;
	/**
	 * Authentication Service constructor
	 */
	function XortifyAuthSoap (&$dao) {
		switch (ICMS_SOAP_LIB){
		case "NUSOAP":
			$this->soap_client = @new soapclient(XORTIFY_API_URI, 'xsoap.wsdl');
			break;
		case "PHPSOAP":
			$this->soap_client = @new soapclient(NULL, array('location' => XORTIFY_API_LOCAL, 'uri' => XORTIFY_API_URI));
			break;
		}
	}


	/**
	 *  Authenticate  user again SOAP directory (Bind)
	 *
	 * @param string $uname Username
	 * @param string $pwd Password
	 *
	 * @return bool
	 */	
	function authenticate($uname, $pwd = null) {
		$authenticated = false;
	
		if (!$this->soap_client) {
			$this->setErrors(0, _AUTH_SOAP_EXTENSION_NOT_LOAD);
			return $authenticated;
		}

				
		$rnd = rand(-100000, 100000000);
		switch (ICMS_SOAP_LIB){
		case "NUSOAP":
			$result = @$this->soap_client->call('xoops_authentication', array("username"=> $this->soap_icms_username, "password"=> $this->soap_icms_password, "auth" => array('username' => $uname, "password" => $pwd, "time" => time(), "passhash" => sha1((time()-$rnd).$uname.$pwd), "rand"=>$rnd)));
			break;
		case "PHPSOAP":
			$result = @$this->soap_client->__soapCall('xoops_authentication', array("username"=> $this->soap_icms_username, "password"=> $this->soap_icms_password, "auth" => array('username' => $uname, "password" => $pwd, "time" => time(), "passhash" => sha1((time()-$rnd).$uname.$pwd), "rand"=>$rnd)));
			break;
		}
		return $result["RESULT"];		
	}
	
				  
	/**
	 *  validate a user via soap
	 *
	 * @param string $uname
	 * @param string $email
	 * @param string $pass
	 * @param string $vpass
	 *
	 * @return string
	 */		
	function validate($uname, $email, $pass, $vpass){
	
		$rnd = rand(-100000, 100000000);
		switch (ICMS_SOAP_LIB){
		case "NUSOAP":
			$result = @$this->soap_client->call('xoops_user_validate', array("username"=> $this->soap_icms_username, "password"=> $this->soap_icms_password, "validate" => array('uname' => $uname, "pass" => $pass, "vpass" => $vpass, "email" => $email, "time" => time(), "passhash" => sha1((time()-$rnd).$uname.$pass), "rand"=>$rnd)));
			break;
		case "PHPSOAP":
			$result = @$this->soap_client->__soapCall('xoops_user_validate', array("username"=> $this->soap_icms_username, "password"=> $this->soap_icms_password, "validate" => array('uname' => $uname, "pass" => $pass, "vpass" => $vpass, "email" => $email, "time" => time(), "passhash" => sha1((time()-$rnd).$uname.$pass), "rand"=>$rnd)));
			break;
		}
		if ($result['ERRNUM']==1){
			return $result["RESULT"];
		} else {
			return false;
		}
	
	}

	/**
	 *  get the xoops site disclaimer via soap
	 *
	 * @return string
	 */			
	function network_disclaimer(){

		switch (ICMS_SOAP_LIB){
		case "NUSOAP":
			$result = @$this->soap_client->call('xoops_network_disclaimer', array("username"=> $this->soap_icms_username, "password"=> $this->soap_icms_password));
			break;
		case "PHPSOAP":
			$result = @$this->soap_client->__soapCall('xoops_network_disclaimer', array("username"=> $this->soap_icms_username, "password"=> $this->soap_icms_password));
			break;
		}	
		
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
		switch (ICMS_SOAP_LIB){
		case "NUSOAP":
			$result = @$this->soap_client->call('xoops_create_user', array("username"=> $this->soap_icms_username, "password"=> $this->soap_icms_password, "user" => array('user_viewemail' =>$user_viewemail, 'uname' => $uname, 'email' => $email, 'url' => $url, 'actkey' => $actkey, 'pass' => $pass, 'timezone_offset' => $timezone_offset, 'user_mailok' => $user_mailok, "time" => time(), "passhash" => sha1((time()-$rnd).$uname.$pass), "rand"=>$rnd), "siteinfo" => $siteinfo));
			break;
		case "PHPSOAP":
			$result = @$this->soap_client->__soapCall('xoops_create_user', array("username"=> $this->soap_icms_username, "password"=> $this->soap_icms_password, "user" => array('user_viewemail' =>$user_viewemail, 'uname' => $uname, 'email' => $email, 'url' => $url, 'actkey' => $actkey, 'pass' => $pass, 'timezone_offset' => $timezone_offset, 'user_mailok' => $user_mailok, "time" => time(), "passhash" => sha1((time()-$rnd).$uname.$pass), "rand"=>$rnd), "siteinfo" => $siteinfo));
			break;
		}
		
		if ($result['ERRNUM']==1){

			return $result["RESULT"];
			
		} else {
			return false;
		}
	}
		
}
// end class


?>
