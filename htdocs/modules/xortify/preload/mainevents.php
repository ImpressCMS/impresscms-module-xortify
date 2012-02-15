<?php
/**
 * @package     xortify
 * @subpackage  module
 * @description	Sector Nexoork Security Drone
 * @author	    Simon Roberts WISHCRAFT <simon@chronolabs.coop>
 * @author	    Richardo Costa TRABIS 
 * @copyright	copyright (c) 2010-2013 ICMS.org
 * @licence		GPL 2.0 - see docs/LICENCE.txt
 */

//defined('ICMS_ROOT_PATH') or die('Restricted access');

class IcmsPreloadMainevents extends icms_preload_Item
{
	
	function eventstartCoreBoot($args)
	{
		error_reporting(E_ALL);
		include_once dirname(dirname(dirname(__FILE))).'/xortify/class/cache/icmscache.php';
		$result = IcmsCache::read('xortify_core_include_common_start');
		if ((isset($result['time'])?(float)$result['time']:0)<=microtime(true)) {
			IcmsCache::write('xortify_core_include_common_start', array('time'=>microtime(true)+600), 600);
			include_once ICMS_ROOT_PATH . ( '/modules/xortify/include/pre.loader.mainfile.php' );
			IcmsCache::write('xortify_core_include_common_start', array('time'=>microtime(true)), -1);
		}
		
	}

	function eventfinishCoreBoot($args)
	{
		$language = $GLOBALS['icmsConfig']['language'];
	    if ( !file_exists($fileinc = ICMS_ROOT_PATH . "/modules/xortify/language/{$language}/modinfo.php" )){
	    	if ( file_exists($fileinc = ICMS_ROOT_PATH . "/modules/xortify/language/english/modinfo.php" )){
	        	include_once $fileinc;
	        }
	    } else {
	    	include_once $fileinc;
	    }
		$module_handler = icms::handler('icms_module');
		$config_handler = icms::handler('icms_config');		
		$GLOBALS['xortifyModule'] = $module_handler->getByDirname('xortify');
		if (is_object($GLOBALS['xortifyModule'])) {
			$GLOBALS['xortifyModuleConfig'] = $config_handler->getConfigList($GLOBALS['xortifyModule']->getVar('mid'));
		}
		
		include_once dirname(dirname(dirname(__FILE))).'/xortify/class/cache/icmscache.php';		
		$result = IcmsCache::read('xortify_cleanup_last');
		if ((isset($result['when'])?(float)$result['when']:-microtime(true))+$GLOBALS['xortifyModuleConfig']['xortify_ip_cache']<=microtime(true)) {
			$result = array();
			$result['when'] = microtime(true);
			$result['files'] = 0;
			$result['size'] = 0;
			foreach(IcmsPreloadMainevents::getFileListAsArray(ICMS_TRUST_PATH.'/caches/icms_cache/', 'xortify') as $id => $file) {
				if (file_exists(ICMS_TRUST_PATH.'/caches/icms_cache/'.$file)&&!empty($file)) {
					if (@filectime(ICMS_TRUST_PATH.'/caches/icms_cache/'.$file)<time()-$GLOBALS['xortifyModuleConfig']['xortify_ip_cache']) {
						$result['files']++;
						$result['size'] = $result['size'] + filesize(ICMS_TRUST_PATH.'/caches/icms_cache/'.$file);
						@unlink(ICMS_TRUST_PATH.'/caches/icms_cache/'.$file);
					}
				}
			}
			$result['took'] = microtime(true)-$result['when'];
			IcmsCache::write('xortify_cleanup_last', $result, $GLOBALS['xortifyModuleConfig']['xortify_ip_cache']*2);
		}
		
		if (isset($_SESSION['xortify']['lid']))
			if ($_SESSION['xortify']['lid']==0)
				unset($_SESSION['xortify']);
				
		if (strpos($_SERVER["PHP_SELF"], '/banned.php')>0) {
			return false;
		}
		
		if ((isset($_COOKIE['xortify']['lid'])&&$_COOKIE['xortify']['lid']!=0)||(isset($_SESSION['xortify']['lid'])&&$_SESSION['xortify']['lid']!=0)&&!strpos($_SERVER["PHP_SELF"], '/banned.php')) {
			header('Location: '.ICMS_URL.'/banned.php');
			exit;
		} 
		
	    $result = IcmsCache::read('xortify_core_include_common_end');
	    if ((isset($result['time'])?(float)$result['time']:0)<=microtime(true)) {
			IcmsCache::write('xortify_core_include_common_end', array('time'=>microtime(true)+$GLOBALS['xortifyModuleConfig']['fault_delay']), $GLOBALS['xortifyModuleConfig']['fault_delay']);
			if (XortifyCorePreload::hasAPIUserPass()) {
				include_once ICMS_ROOT_PATH . ( '/modules/xortify/include/post.loader.mainfile.php' );
			}
			IcmsCache::write('xortify_core_include_common_end', array('time'=>microtime(true)), $GLOBALS['xortifyModuleConfig']['fault_delay']);
		}
		
		
	}

	
	function eventstartOutputInit($args)
	{
		
		include_once dirname(dirname(dirname(__FILE))).'/xortify/class/cache/icmscache.php';
		$result = IcmsCache::read('xortify_core_header_add_meta');
		if ((isset($result['time'])?(float)$result['time']:0)<=microtime(true)) {
			IcmsCache::write('xortify_core_header_add_meta', array('time'=>microtime(true)+$GLOBALS['xortifyModuleConfig']['fault_delay']), $GLOBALS['xortifyModuleConfig']['fault_delay']);
			if (XortifyCorePreload::hasAPIUserPass()) {	
				include_once ICMS_ROOT_PATH . ( '/modules/xortify/include/post.header.addmeta.php' );
			}
			IcmsCache::write('xortify_core_header_add_meta', array('time'=>microtime(true)), -1);
		}
		
	}
	
	function hasAPIUserPass()
	{
		if ($GLOBALS['xortifyModuleConfig']['xortify_username']!=''&&$GLOBALS['xortifyModuleConfig']['xortify_password']!='')
			return true;
		else
			return false;
	}		
	
	public function getFileListAsArray($dirname, $prefix="xortify")
	{
		error_reporting(E_ALL);
		
		$filelist = array();
		if (substr($dirname, -1) == '/') {
			$dirname = substr($dirname, 0, -1);
		}
		if (is_dir($dirname) && $handle = opendir($dirname)) {
			while (false !== ($file = readdir($handle))) {
				if (!preg_match("/^[\.]{1,2}$/",$file) && is_file($dirname.'/'.$file)) {
					if (!empty($prefix)&&strpos(' '.$file, $prefix)>0) {
						$filelist[$file] = $file;
					} elseif (empty($prefix)) {
						$filelist[$file] = $file;
					}
				}
			}
			closedir($handle);
			asort($filelist);
			reset($filelist);
		}
		return $filelist;
	}
}

?>