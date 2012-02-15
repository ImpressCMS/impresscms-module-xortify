<?php
/**
 * @package     xortify
 * @subpackage  module
 * @description	Sector Network Security Drone
 * @author	    Simon Roberts WISHCRAFT <simon@chronolabs.coop>
 * @copyright	copyright (c) 2010-2013 ICMS.org
 * @licence		GPL 2.0 - see docs/LICENCE.txt
 */

if (!defined('ICMS_ROOT_PATH')) die ('Restricted Access');

error_reporting(E_ALL);

include_once( ICMS_ROOT_PATH.'/modules/xortify/include/functions.php' );

class Providers 
{
	var $providers = array();
	
	function init($check) {
		error_reporting(E_ALL);
		defined('DS') or define('DS', DIRECTORY_SEPARATOR);
		defined('NWLINE')or define('NWLINE', "\n");
		
		global $xoops, $xoopsPreload, $xoopsLogger, $xoopsErrorHandler, $xoopsSecurity, $sess_handler;
		
		include_once ICMS_ROOT_PATH . DS . 'include' . DS . 'defines.php';
		include_once ICMS_ROOT_PATH . DS . 'include' . DS . 'version.php';
		include_once ICMS_ROOT_PATH . DS . 'include' . DS . 'license.php';
		
		require_once ICMS_ROOT_PATH . DS . 'class' . DS . 'xoopsload.php';
		
		IcmsLoad::load('preload');
		$xoopsPreload =& IcmsPreload::getInstance();
		
		IcmsLoad::load('xoopskernel');
		$xoops = new xos_kernel_Icms2();
		$xoops->pathTranslation();
		$xoopsRequestUri =& $_SERVER['REQUEST_URI'];// Deprecated (use the corrected $_SERVER variable now)
		
		IcmsLoad::load('xoopssecurity');
		$xoopsSecurity = new IcmsSecurity();
		$xoopsSecurity->checkSuperglobals();
		
		IcmsLoad::load('xoopslogger');
		$xoopsLogger =& IcmsLogger::getInstance();
		$xoopsErrorHandler =& IcmsLogger::getInstance();
		
		include_once $xoops->path('kernel/object.php');
		include_once $xoops->path('class/criteria.php');
		include_once $xoops->path('class/module.textsanitizer.php');
		include_once $xoops->path('include/functions.php');
		
		include_once $xoops->path('class/database/databasefactory.php');
		$GLOBALS['db'] =& icms_db_Factory::instance();
		
		$module_handler = icms::handler('icms_module');
		$config_handler = icms::handler('icms_config');		
		$GLOBALS['xortifyModule'] = $module_handler->getByDirname('xortify');
		if (is_object($GLOBALS['xortifyModule'])) {
			$GLOBALS['xortifyModuleConfig'] = $config_handler->getConfigList($GLOBALS['xortifyModule']->getVar('mid'));
		}
		
		global $xoopsConfig; 
		$xoopsConfig = $config_handler->getConfigsByCat(ICMS_CONF);
	}
		
	function __construct($check = 'precheck')
	{	 
				
		if (strpos($_SERVER["PHP_SELF"], '/banned.php')>0) {
			return false;
		}
		
		$this->init($check);	
		$this->providers = $GLOBALS['xortifyModuleConfig']['xortify_providers'];
		
		$this->$check();
	}
	
	private function precheck()
	{
		error_reporting(E_ALL);
		
		if ($GLOBALS['xortifyModule']->getVar('version')<300)
			return false;
		foreach($this->providers as $id => $key)
		switch ($key) {
		default:
			
			if (file_exists(ICMS_ROOT_PATH.'/modules/xortify/providers/'.$key.'/precheck.inc.php')) 
				include_once(ICMS_ROOT_PATH.'/modules/xortify/providers/'.$key.'/precheck.inc.php');
			if (file_exists(ICMS_ROOT_PATH.'/modules/xortify/providers/'.$key.'/pre.loader.php')) 
				include_once(ICMS_ROOT_PATH.'/modules/xortify/providers/'.$key.'/pre.loader.php');
			
		}
		
	}
	
	private function postcheck()
	{
		error_reporting(E_ALL);
		
		if ($GLOBALS['xortifyModule']->getVar('version')<300)
			return false;
		foreach($this->providers as $id => $key)
		switch ($key) {
		default:
			if (file_exists(ICMS_ROOT_PATH.'/modules/xortify/providers/'.$key.'/postcheck.inc.php'))
				include_once(ICMS_ROOT_PATH.'/modules/xortify/providers/'.$key.'/postcheck.inc.php');
			if (file_exists(ICMS_ROOT_PATH.'/modules/xortify/providers/'.$key.'/post.loader.php'))
				include_once(ICMS_ROOT_PATH.'/modules/xortify/providers/'.$key.'/post.loader.php');
		}
		
	}
	
	private function headerpostcheck()
	{
		
		error_reporting(E_ALL);
		if ($GLOBALS['xortifyModule']->getVar('version')<300)
			return false;
		foreach($this->providers as $id => $key)
		switch ($key) {
		default:
			
			if (file_exists(ICMS_ROOT_PATH.'/modules/xortify/providers/'.$key.'/headerpostcheck.inc.php'))
				include_once(ICMS_ROOT_PATH.'/modules/xortify/providers/'.$key.'/headerpostcheck.inc.php');
			if (file_exists(ICMS_ROOT_PATH.'/modules/xortify/providers/'.$key.'/header.post.loader.php'))
				include_once(ICMS_ROOT_PATH.'/modules/xortify/providers/'.$key.'/header.post.loader.php');
			
		}
		
	}
	
	private function footerpostcheck()
	{
		
		error_reporting(E_ALL);
		if ($GLOBALS['xortifyModule']->getVar('version')<300)
			return false;
		foreach($this->providers as $id => $key)
		switch ($key) {
		default:
			
			if (file_exists(ICMS_ROOT_PATH.'/modules/xortify/providers/'.$key.'/footerpostcheck.inc.php'))
				include_once(ICMS_ROOT_PATH.'/modules/xortify/providers/'.$key.'/footerpostcheck.inc.php');
			if (file_exists(ICMS_ROOT_PATH.'/modules/xortify/providers/'.$key.'/footer.post.loader.php'))
				include_once(ICMS_ROOT_PATH.'/modules/xortify/providers/'.$key.'/footer.post.loader.php');
			
		}
		
	}
}

?>