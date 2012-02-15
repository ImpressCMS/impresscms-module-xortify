<?php
/**
 * @package     xortify
 * @subpackage  module
 * @description	Sector Network Security Drone
 * @author	    Simon Roberts WISHCRAFT <simon@chronolabs.coop>
 * @copyright	copyright (c) 2010-2013 ICMS.org
 * @licence		GPL 2.0 - see docs/LICENCE.txt
 */

 
define('XORTIFY_WGETXML_API', $GLOBALS['xortifyModuleConfig']['xortify_urixml']);
include_once ICMS_ROOT_PATH . '/modules/xortify/class/auth/auth_wgetxml_provisionning.php';
include_once ICMS_ROOT_PATH . '/modules/xortify/include/functions.php';

class XortifyAuthWgetxml extends XortifyAuth {
	
	var $xml_icms_username = '';
	var $xml_icms_password = '';
	var $_dao;
	/**
	 * Authentication Service constructor
	 */
	function XortifyAuthWgetxml (&$dao) {
	
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
		$data = file_get_contents(XORTIFY_WGETXML_API.'?icms_authentication='.urlencode(xortify_toXml(array("username"=> $this->xml_icms_username, "password"=> $this->xml_icms_password, "auth" => array('username' => $uname, "password" => $pwd, "time" => time(), "passhash" => sha1((time()-$rnd).$uname.$pwd), "rand"=>$rnd)), 'xoops_authentication')));
		$result = xortify_elekey2numeric(xortify_xml2array($data), 'xoops_authentication');
		return $result['xoops_authentication']["RESULT"];		
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
		$data = file_get_contents(XORTIFY_WGETXML_API.'?icms_user_validate='.urlencode(xortify_toXml(array("username"=> $this->xml_icms_username, "password"=> $this->xml_icms_password, "validate" => array('uname' => $uname, "pass" => $pass, "vpass" => $vpass, "email" => $email, "time" => time(), "passhash" => sha1((time()-$rnd).$uname.$pass), "rand"=>$rnd)), 'xoops_user_validate')));
		$result = xortify_elekey2numeric(xortify_xml2array($data), 'xoops_user_validate');
		if ($result['xoops_user_validate']['ERRNUM']==1){
			return $result['xoops_user_validate']["RESULT"];
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

		$data = file_get_contents(XORTIFY_WGETXML_API.'?icms_network_disclaimer='.urlencode(xortify_toXml(array("username"=> $this->xml_icms_username, "password"=> $this->xml_icms_password), 'xoops_network_disclaimer')));
		$result = xortify_elekey2numeric(xortify_xml2array($data), 'xoops_network_disclaimer');

		if ($result['xoops_network_disclaimer']['ERRNUM']==1){
			return $result['xoops_network_disclaimer']["RESULT"];
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
		$data = file_get_contents(XORTIFY_WGETXML_API.'?icms_create_user='.urlencode(xortify_toXml(array("username"=> $this->xml_icms_username, "password"=> $this->xml_icms_password, "user" => array('user_viewemail' =>$user_viewemail, 'uname' => $uname, 'email' => $email, 'url' => $url, 'actkey' => $actkey, 'pass' => $pass, 'timezone_offset' => $timezone_offset, 'user_mailok' => $user_mailok, "time" => time(), "passhash" => sha1((time()-$rnd).$uname.$pass), "rand"=>$rnd), "siteinfo" => $siteinfo), 'xoops_create_user')));
		$result = xortify_elekey2numeric(xortify_xml2array($data), 'xoops_create_user');
		if ($result['xoops_create_user']['ERRNUM']==1){
			return $result['xoops_create_user']["RESULT"];
		} else {
			return false;
		}
	}
		
}
// end class


?>
