<?php
/**
 * @package     xortify
 * @subpackage  module
 * @description	Sector Network Security Drone
 * @author	    Simon Roberts WISHCRAFT <simon@chronolabs.coop>
 * @copyright	copyright (c) 2010-2013 ICMS.org
 * @licence		GPL 2.0 - see docs/LICENCE.txt
 */

 
define('XORTIFY_WGETSERIAL_API', $GLOBALS['xortifyModuleConfig']['xortify_uriserial']);
include_once ICMS_ROOT_PATH . '/modules/xortify/class/auth/auth_wgetserialised_provisionning.php';

class XortifyAuthWgetserialised extends XortifyAuth {
	
	var $wget_icms_username = '';
	var $wget_icms_password = '';
	var $_dao;
	/**
	 * Authentication Service constructor
	 */
	function XortifyAuthWgetserialised (&$dao) {
	}


	/**
	 *  Authenticate  user again json directory (Bind)
	 *
	 * @param string $uname Username
	 * @param string $pwd Password
	 *
	 * @return bool
	 */	
	function authenticate($uname, $pwd = null) {
		$authenticated = false;
		$rnd = rand(-100000, 100000000);		
		$data = file_get_contents(XORTIFY_WGETSERIAL_API.'?icms_authentication='.urlencode(serialize(array("username"=> $this->wget_icms_username, "password"=> $this->wget_icms_password, "auth" => array('username' => $uname, "password" => $pwd, "time" => time(), "passhash" => sha1((time()-$rnd).$uname.$pwd), "rand"=>$rnd)))));
		$result = unserialize($data);
		return $result["RESULT"];		
	}
	
				  
	/**
	 *  validate a user via json
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
		$data = file_get_contents(XORTIFY_WGETSERIAL_API.'?icms_user_validate='.urlencode(serialize(array("username"=> $this->wget_icms_username, "password"=> $this->wget_icms_password, "validate" => array('uname' => $uname, "pass" => $pass, "vpass" => $vpass, "email" => $email, "time" => time(), "passhash" => sha1((time()-$rnd).$uname.$pass), "rand"=>$rnd)))));
		$result = unserialize($data);
		if ($result['ERRNUM']==1){
			return $result["RESULT"];
		} else {
			return false;
		}
	
	}

    function reduce_string($str)
    {
        $str = preg_replace(array(

                // eliminate single line comments in '// ...' form
                '#^\s*//(.+)$#m',

                // eliminate multi-line comments in '/* ... */' form, at start of string
                '#^\s*/\*(.+)\*/#Us',

                // eliminate multi-line comments in '/* ... */' form, at end of string
                '#/\*(.+)\*/\s*$#Us'

            ), '', $str);

        // eliminate extraneous space
        return trim($str);
    }
    
	/**
	 *  get the xoops site disclaimer via json
	 *
	 * @return string
	 */			
	function network_disclaimer(){

		$data = file_get_contents(XORTIFY_WGETSERIAL_API.'?icms_network_disclaimer='.urlencode(serialize(array("username"=> $this->wget_icms_username, "password"=> $this->wget_icms_password))));
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
		$data = file_get_contents(XORTIFY_WGETSERIAL_API.'?icms_create_user='.urlencode(serialize(array("username"=> $this->wget_icms_username, "password"=> $this->wget_icms_password, "user" => array('user_viewemail' =>$user_viewemail, 'uname' => $uname, 'email' => $email, 'url' => $url, 'actkey' => $actkey, 'pass' => $pass, 'timezone_offset' => $timezone_offset, 'user_mailok' => $user_mailok, "time" => time(), "passhash" => sha1((time()-$rnd).$uname.$pass), "rand"=>$rnd), "siteinfo" => $siteinfo))));
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
