<?php
/**
 * @package     xortify
 * @subpackage  module
 * @description	Sector Network Security Drone
 * @author	    Simon Roberts WISHCRAFT <simon@chronolabs.coop>
 * @copyright	copyright (c) 2010-2013 ICMS.org
 * @licence		GPL 2.0 - see docs/LICENCE.txt
 */


class XortifyAuth {

	var	$_dao;

	var	$_errors;
	/**
	 * Authentication Service constructor
	 */
	function XortifyAuth (&$dao){
		$this->_dao = $dao;
	}

	/**
	 * @abstract need to be write in the dervied class
	 */	
	function authenticate() {
		$authenticated = false;
				
		return $authenticated;
	}		
	
	/**
	 * add an error 
	 * 
	 * @param string $value error to add
	 * @access public
	 */
	function setErrors($err_no, $err_str)
	{
		$this->_errors[$err_no] = trim($err_str);
	}

	/**
	 * return the errors for this object as an array
	 * 
	 * @return array an array of errors
	 * @access public
	 */
	function getErrors()
	{
		return $this->_errors;
	}

	/**
	 * return the errors for this object as html
	 * 
	 * @return string html listing the errors
	 * @access public
	 */
	function getHtmlErrors()
	{
		global $xoopsConfig;
		$ret = '<br>';
		if ( $xoopsConfig['debug_mode'] == 1 || $xoopsConfig['debug_mode'] == 2 ) 
		{	       
			if (!empty($this->_errors)) {
				foreach ($this->_errors as $errno => $errstr) {            	
					$ret .=  $errstr . '<br/>';
				}
			} else {
				$ret .= _NONE.'<br />';
			}        
			$ret .= sprintf(_AUTH_MSG_AUTH_METHOD, $this->auth_method);
		}
		else {
			$ret .= _US_INCORRECTLOGIN;
		}
		return $ret;
	}	

	/**
	 * checks for variables require in siteinfo package in the auth library
	 * 
	 * @param array $siteinfo 
	 *
	 * @return array $siteinfo
	 * @access public
	 */
	function check_siteinfo($siteinfo){

		global $xoopsConfig;
		if (!isset($siteinfo)||empty($siteinfo)||!is_array($siteinfo)){
			$siteinfo = array();
			$siteinfo['sitename'] = $xoopsConfig['sitename'];
			$siteinfo['adminmail'] = $xoopsConfig['adminmail'];
			$siteinfo['systemkey'] = ICMS_LICENSE_KEY;
			$siteinfo['xoops_url'] = ICMS_URL;
		}
		
		if (!isset($siteinfo['sitename'])||empty($siteinfo['sitename']))
			$siteinfo['sitename'] = $xoopsConfig['sitename'];

		if (!isset($siteinfo['adminmail'])||empty($siteinfo['adminmail']))
			$siteinfo['adminmail'] = $xoopsConfig['adminmail'];
		
		if (!isset($siteinfo['xoops_url'])||empty($siteinfo['xoops_url']))
			$siteinfo['xoops_url'] = ICMS_URL;

		if (!isset($siteinfo['systemkey'])||empty($siteinfo['systemkey']))
			$siteinfo['systemkey'] = $xoopsConfig['systemkey'];
		
		return $siteinfo;	
	}
}


?>
