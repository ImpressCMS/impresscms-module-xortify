<?php
/**
 * @package     xortify
 * @subpackage  module
 * @description	Sector Network Security Drone
 * @author	    Simon Roberts WISHCRAFT <simon@chronolabs.coop>
 * @copyright	copyright (c) 2010-2013 ICMS.org
 * @licence		GPL 2.0 - see docs/LICENCE.txt
 */

if (!class_exists('XortifyAuthFactory')) {
	class XortifyAuthFactory
	{
	
		/**
		 * Get a reference to the only instance of authentication class
		 * 
		 * if the class has not been instantiated yet, this will also take 
		 * care of that
		 * 
		 * @static
		 * @return      object  Reference to the only instance of authentication class
		 */
		function &getAuthConnection($uname, $xortify_auth_method = 'soap')
		{
					
			static $auth_instance;		
			if (!isset($auth_instance)) {
				require_once ICMS_ROOT_PATH.'/modules/xortify/class/auth/auth.php';
				// Verify if uname allow to bypass LDAP auth 
				$file = ICMS_ROOT_PATH . '/modules/xortify/class/auth/auth_' . $xortify_auth_method . '.php';			
				require_once $file;
				$class = 'XortifyAuth' . ucfirst($xortify_auth_method);
				switch ($xortify_auth_method) {
					case 'soap';
						$dao = null;
						break;
	
				}
				$auth_instance = new $class($dao);
			}
			return $auth_instance;
		}
	
	}
}
?>
