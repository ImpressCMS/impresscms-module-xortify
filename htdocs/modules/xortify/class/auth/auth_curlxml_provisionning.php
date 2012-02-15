<?php
/**
 * @package     xortify
 * @subpackage  module
 * @description	Sector Network Security Drone
 * @author	    Simon Roberts WISHCRAFT <simon@chronolabs.coop>
 * @copyright	copyright (c) 2010-2013 ICMS.org
 * @licence		GPL 2.0 - see docs/LICENCE.txt
 */


class XortifyAuthCurlxmlProvisionning {

	var $_auth_instance;
	
	function &getInstance(&$auth_instance)
	{
		static $provis_instance;				
		if (!isset($provis_instance)) {
			$provis_instance = new XortifyAuthCurlxmlProvisionning($auth_instance);
		}
		return $provis_instance;
	}

	/**
	 * Authentication Service constructor
	 */
	function XortifyAuthCurlxmlProvisionning (&$auth_instance) {
		$this->_auth_instance = &$auth_instance;        
		$config_handler =& icms::handler('icms_config');    
		$config =& $config_handler->getConfigsByCat(ICMS_CONF_AUTH);
		foreach ($config as $key => $val) {
			$this->$key = $val;
		}
		$config_gen =& $config_handler->getConfigsByCat(ICMS_CONF);
		$this->default_TZ = $config_gen['default_TZ'];
		$this->theme_set = $config_gen['theme_set'];
		$this->com_mode = $config_gen['com_mode'];
		$this->com_order = $config_gen['com_order'];        
	}

	/**
	 *  Return a Xortify User Object 
	 *
	 * @return XortifyUser or false
	 */	
	function getXortifyUser($uname) {
		$member_handler =& icms::handler('icms_member');
		$criteria = new icms_db_criteria_Item('uname', $uname);
		$getuser = $member_handler->getUsers($criteria);
		if (count($getuser) == 1)
			return $getuser[0];
		else return false;		
	}
	
	/**
	 *  Launch the synchronisation process 
	 *
	 * @return bool
	 */		
	function sync($datas, $uname, $pwd = null) {
		$xoopsUser = $this->getXortifyUser($uname);		
		if (!$xoopsUser) { // Xortify User Database not exists
			if ($this->curl_provisionning) { 
				$xoopsUser = $this->add($datas, $uname, $pwd);
			} else $this->_auth_instance->setErrors(0, sprintf(_AUTH_LDAP_ICMS_USER_NOTFOUND, $uname));
		} else { // Xortify User Database exists
			
		}
		return $xoopsUser;
	}

	/**
	 *  Add a new user to the system
	 *
	 * @return bool
	 */		
	function add($datas, $uname, $pwd = null) {
		$ret = false;
		$member_handler =& icms::handler('icms_member');
		// Create ICMS Database User
		$newuser = $member_handler->createUser();
		$newuser->setVar('uname', $uname);
		$newuser->setVar('pass', md5(stripslashes($pwd)));
		$newuser->setVar('email', $datas['email']);
		$newuser->setVar('rank', 0);
		$newuser->setVar('level', 1);
		$newuser->setVar('timezone_offset', $this->default_TZ);
		$newuser->setVar('theme', 	$this->theme_set);
		$newuser->setVar('umode', 	$this->com_mode);
		$newuser->setVar('uorder', 	$this->com_order);
		if ($this->curl_provisionning)
			$tab_mapping = explode('|', $this->curl_field_mapping);
		else 
			$tab_mapping = explode('|', $this->ldap_field_mapping);
			
		foreach ($tab_mapping as $mapping) {
			$fields = explode('=', trim($mapping));
			if ($fields[0] && $fields[1])
				$newuser->setVar(trim($fields[0]), utf8_decode($datas[trim($fields[1])]));
		}        
		if ($member_handler->insertUser($newuser)) {
		} 
		if ($member_handler->insertUser($newuser)) {
			foreach ($this->curl_provisionning_group as $groupid)
				$member_handler->addUserToGroup($groupid, $newuser->getVar('uid'));
			$newuser->unsetNew();
			return $newuser;
		} else redirect_header(ICMS_URL.'/user.php', 5, $newuser->getHtmlErrors());      
		
		$newuser->unsetNew();
		return $newuser;
		//else redirect_header(ICMS_URL.'/user.php', 5, $newuser->getHtmlErrors());         
		return $ret;	
	}
	
	/**
	 *  Modify user information
	 *
	 * @return bool
	 */		
	function change(&$xoopsUser, $datas, $uname, $pwd = null) {	
		$ret = false;
		$member_handler =& icms::handler('icms_member');
		$xoopsUser->setVar('pass', md5(stripslashes($pwd)));
		$tab_mapping = explode('|', $this->ldap_field_mapping);
		foreach ($tab_mapping as $mapping) {
			$fields = explode('=', trim($mapping));
			if ($fields[0] && $fields[1])
				$xoopsUser->setVar(trim($fields[0]), utf8_decode($datas[trim($fields[1])][0]));
		}
		if ($member_handler->insertUser($xoopsUser)) {
			return $xoopsUser;
		} else redirect_header(ICMS_URL.'/user.php', 5, $xoopsUser->getHtmlErrors());         
		return $ret;
	}
	
	function change_curl(&$xoopsUser, $datas, $uname, $pwd = null) {	
		$ret = false;
		$member_handler =& icms::handler('icms_member');
		$xoopsUser->setVar('pass', md5(stripslashes($pwd)));
		$tab_mapping = explode('|', $this->curl_field_mapping);
		foreach ($tab_mapping as $mapping) {
			$fields = explode('=', trim($mapping));
			if ($fields[0] && $fields[1])
				$xoopsUser->setVar(trim($fields[0]), utf8_decode($datas[trim($fields[1])][0]));
		}
		if ($member_handler->insertUser($xoopsUser)) {
			return $xoopsUser;
		} else redirect_header(ICMS_URL.'/user.php', 5, $xoopsUser->getHtmlErrors());         
		return $ret;
	}

	/**
	 *  Modify a user
	 *
	 * @return bool
	 */		
	function delete() {
	}

	/**
	 *  Suspend a user
	 *
	 * @return bool
	 */		
	function suspend() {
	}

	/**
	 *  Restore a user
	 *
	 * @return bool
	 */		
	function restore() {
	}

	/**
	 *  Add a new user to the system
	 *
	 * @return bool
	 */		
	function resetpwd() {
	}
	
	
}
// end class
 
?>
