<?php
/**
 * @package     Xortify
 * @subpackage  module
 * @description	Sector Network Security Drone for Robots
 * @author	    Simon Roberts WISHCRAFT <simon@chronolabs.coop>
 * @copyright	copyright (c) 2010-2013 ICMS.org
 * @licence		GPL 2.0 - see docs/LICENCE.txt
 * @cron		Run at Least Once an Hour to five minutes!
 */

	function xortify_getURLData($URI, $curl=false) {
		$ret = '';
		try {
			switch ($curl) {
				case true:
					if (!$ch = curl_init($URI)) {
						trigger_error('Could not intialise CURL file: '.$url);
						return false;
					}
					$cookies = ICMS_TRUST_PATH.'/cache/icms_cache/croncurl_'.md5($URI).'.cookie'; 
			
					curl_setopt($ch, CURLOPT_HEADER, 0);
					curl_setopt($ch, CURLOPT_COOKIEJAR, $cookies);
					curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $GLOBALS['xortifyModuleConfig']['curl_connecttimeout']);
					curl_setopt($ch, CURLOPT_TIMEOUT, $GLOBALS['xortifyModuleConfig']['curl_timeout']);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
					curl_setopt($ch, CURLOPT_USERAGENT, XORTIFY_USER_AGENT);
					$ret = curl_exec($ch);
					curl_close($ch);
					break;
				case false:
					$ret = file_get_contents($uri);
					break;
				
			}
		}
		catch(Exception $e) {
			echo 'Exception: "'.$e."\n";
		}	
		return $ret;
	}
	
	define('XORTIFY_USER_AGENT', 'Mozilla/5.0 (PHP) Xortify 2.5.x ICMS/20100101');
	define("SERVER1", 'http://xortify.com/unban/?op=unban');
	define("SERVER2", 'http://xortify.chronolabs.coop/unban/?op=unban');
	define("SERVER3", 'http://xortify.xoops.org/unban/?op=unban');
	define("REPLACE", 'unban/?op=unban');
	define("SOAP", 'soap/');
	define("CURL", 'curl/');
	define("JSON", 'json/');
	define("SERIAL", 'serial/');
	define("XML", 'xml/');
	define("SEARCHFOR", 'Solve Puzzel:');
	
	foreach (get_loaded_extensions() as $ext){
		if ($ext=="curl")
			$nativecurl=true;
	}

	if (!isset($GLOBALS['xortify_preloader'])) {
		require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/mainfile.php');
	}
		
	$module_handler =& icms::handler('icms_module');
	$config_handler =& icms::handler('icms_config');
	$configitem_handler =& icms::handler('icms_configitem');
	$GLOBALS['xortifyModule'] = $module_handler->getByDirname('xortify');
	if (is_object($GLOBALS['xortifyModule'])) {
		$GLOBALS['xortifyModuleConfig'] = $config_handler->getConfigList($GLOBALS['xortifyModule']->getVar('mid'));
		$source = xortify_getURLData(SERVER1, $nativecurl);
		if (strpos(strtolower($source), strtolower(SEARCHFOR))>0) {
			
			echo 'Server 1 is UP - check @ '.SERVER1;
			
			$GLOBALS['xortifyModule']->setVar('isactive', true);
			
			$criteria = new icms_db_criteria_Compo(new icms_db_criteria_Item('conf_modid', $GLOBALS['xortifyModule']->getVar('mid')));
			$criteria->add(new icms_db_criteria_Item('conf_name', 'xortify_urisoap'));
			$xoConfig = $configitem_handler->getObjects($criteria);
			if (is_object($xoConfig[0])) {
				$xoConfig[0]->setVar('conf_value', str_replace(REPLACE, SOAP, SERVER1));
				$configitem_handler->insert($xoConfig[0], true);
			}
			
			$criteria = new icms_db_criteria_Compo(new icms_db_criteria_Item('conf_modid', $GLOBALS['xortifyModule']->getVar('mid')));
			$criteria->add(new icms_db_criteria_Item('conf_name', 'xortify_uricurl'));
			$xoConfig = $configitem_handler->getObjects($criteria);
			if (is_object($xoConfig[0])) {
				$xoConfig[0]->setVar('conf_value', str_replace(REPLACE, CURL, SERVER1));
				$configitem_handler->insert($xoConfig[0], true);
			}
			
			$criteria = new icms_db_criteria_Compo(new icms_db_criteria_Item('conf_modid', $GLOBALS['xortifyModule']->getVar('mid')));
			$criteria->add(new icms_db_criteria_Item('conf_name', 'xortify_urijson'));
			$xoConfig = $configitem_handler->getObjects($criteria);
			if (is_object($xoConfig[0])) {
				$xoConfig[0]->setVar('conf_value', str_replace(REPLACE, JSON, SERVER1));
				$configitem_handler->insert($xoConfig[0], true);
			}
			
			$criteria = new icms_db_criteria_Compo(new icms_db_criteria_Item('conf_modid', $GLOBALS['xortifyModule']->getVar('mid')));
			$criteria->add(new icms_db_criteria_Item('conf_name', 'xortify_uriserial'));
			$xoConfig = $configitem_handler->getObjects($criteria);
			if (is_object($xoConfig[0])) {
				$xoConfig[0]->setVar('conf_value', str_replace(REPLACE, SERIAL, SERVER1));
				$configitem_handler->insert($xoConfig[0], true);
			}
			
			$criteria = new icms_db_criteria_Compo(new icms_db_criteria_Item('conf_modid', $GLOBALS['xortifyModule']->getVar('mid')));
			$criteria->add(new icms_db_criteria_Item('conf_name', 'xortify_urixml'));
			$xoConfig = $configitem_handler->getObjects($criteria);
			if (is_object($xoConfig[0])) {
				$xoConfig[0]->setVar('conf_value', str_replace(REPLACE, XML, SERVER1));
				$configitem_handler->insert($xoConfig[0], true);
			}
			
		} else {
			$source = xortify_getURLData(SERVER2, $nativecurl);;
			if (strpos(strtolower($source), strtolower(SEARCHFOR))>0) {
				
				echo 'Server 2 is UP - check @ '.SERVER2;
				
				$GLOBALS['xortifyModule']->setVar('isactive', true);

				$criteria = new icms_db_criteria_Compo(new icms_db_criteria_Item('conf_modid', $GLOBALS['xortifyModule']->getVar('mid')));
				$criteria->add(new icms_db_criteria_Item('conf_name', 'xortify_urisoap'));
				$xoConfig = $configitem_handler->getObjects($criteria);
				if (is_object($xoConfig[0])) {
					$xoConfig[0]->setVar('conf_value', str_replace(REPLACE, SOAP, SERVER2));
					$configitem_handler->insert($xoConfig[0], true);
				}
				
				$criteria = new icms_db_criteria_Compo(new icms_db_criteria_Item('conf_modid', $GLOBALS['xortifyModule']->getVar('mid')));
				$criteria->add(new icms_db_criteria_Item('conf_name', 'xortify_uricurl'));
				$xoConfig = $configitem_handler->getObjects($criteria);
				if (is_object($xoConfig[0])) {
					$xoConfig[0]->setVar('conf_value', str_replace(REPLACE, CURL, SERVER2));
					$configitem_handler->insert($xoConfig[0], true);
				}
				
				$criteria = new icms_db_criteria_Compo(new icms_db_criteria_Item('conf_modid', $GLOBALS['xortifyModule']->getVar('mid')));
				$criteria->add(new icms_db_criteria_Item('conf_name', 'xortify_urijson'));
				$xoConfig = $configitem_handler->getObjects($criteria);
				if (is_object($xoConfig[0])) {
					$xoConfig[0]->setVar('conf_value', str_replace(REPLACE, JSON, SERVER2));
					$configitem_handler->insert($xoConfig[0], true);
				}
				
				$criteria = new icms_db_criteria_Compo(new icms_db_criteria_Item('conf_modid', $GLOBALS['xortifyModule']->getVar('mid')));
				$criteria->add(new icms_db_criteria_Item('conf_name', 'xortify_uriserial'));
				$xoConfig = $configitem_handler->getObjects($criteria);
				if (is_object($xoConfig[0])) {
					$xoConfig[0]->setVar('conf_value', str_replace(REPLACE, SERIAL, SERVER2));
					$configitem_handler->insert($xoConfig[0], true);
				}
				
				$criteria = new icms_db_criteria_Compo(new icms_db_criteria_Item('conf_modid', $GLOBALS['xortifyModule']->getVar('mid')));
				$criteria->add(new icms_db_criteria_Item('conf_name', 'xortify_urixml'));
				$xoConfig = $configitem_handler->getObjects($criteria);
				if (is_object($xoConfig[0])) {
					$xoConfig[0]->setVar('conf_value', str_replace(REPLACE, XML, SERVER2));
					$configitem_handler->insert($xoConfig[0], true);
				}
				
			} else {
				$source = xortify_getURLData(SERVER3, $nativecurl);;
				if (strpos(strtolower($source), strtolower(SEARCHFOR))>0) {
					
					echo 'Server 3 is UP - check @ '.SERVER3;
					
					$GLOBALS['xortifyModule']->setVar('isactive', true);
	
					$criteria = new icms_db_criteria_Compo(new icms_db_criteria_Item('conf_modid', $GLOBALS['xortifyModule']->getVar('mid')));
					$criteria->add(new icms_db_criteria_Item('conf_name', 'xortify_urisoap'));
					$xoConfig = $configitem_handler->getObjects($criteria);
					if (is_object($xoConfig[0])) {
						$xoConfig[0]->setVar('conf_value', str_replace(REPLACE, SOAP, SERVER3));
						$configitem_handler->insert($xoConfig[0], true);
					}
					
					$criteria = new icms_db_criteria_Compo(new icms_db_criteria_Item('conf_modid', $GLOBALS['xortifyModule']->getVar('mid')));
					$criteria->add(new icms_db_criteria_Item('conf_name', 'xortify_uricurl'));
					$xoConfig = $configitem_handler->getObjects($criteria);
					if (is_object($xoConfig[0])) {
						$xoConfig[0]->setVar('conf_value', str_replace(REPLACE, CURL, SERVER3));
						$configitem_handler->insert($xoConfig[0], true);
					}
					
					$criteria = new icms_db_criteria_Compo(new icms_db_criteria_Item('conf_modid', $GLOBALS['xortifyModule']->getVar('mid')));
					$criteria->add(new icms_db_criteria_Item('conf_name', 'xortify_urijson'));
					$xoConfig = $configitem_handler->getObjects($criteria);
					if (is_object($xoConfig[0])) {
						$xoConfig[0]->setVar('conf_value', str_replace(REPLACE, JSON, SERVER3));
						$configitem_handler->insert($xoConfig[0], true);
					}
					
					$criteria = new icms_db_criteria_Compo(new icms_db_criteria_Item('conf_modid', $GLOBALS['xortifyModule']->getVar('mid')));
					$criteria->add(new icms_db_criteria_Item('conf_name', 'xortify_uriserial'));
					$xoConfig = $configitem_handler->getObjects($criteria);
					if (is_object($xoConfig[0])) {
						$xoConfig[0]->setVar('conf_value', str_replace(REPLACE, SERIAL, SERVER3));
						$configitem_handler->insert($xoConfig[0], true);
					}
					
					$criteria = new icms_db_criteria_Compo(new icms_db_criteria_Item('conf_modid', $GLOBALS['xortifyModule']->getVar('mid')));
					$criteria->add(new icms_db_criteria_Item('conf_name', 'xortify_urixml'));
					$xoConfig = $configitem_handler->getObjects($criteria);
					if (is_object($xoConfig[0])) {
						$xoConfig[0]->setVar('conf_value', str_replace(REPLACE, XML, SERVER3));
						$configitem_handler->insert($xoConfig[0], true);
					}
					
				} else {
					$GLOBALS['xortifyModule']->setVar('isactive', false);
				}
			}
		}	
		$module_handler->insert($GLOBALS['xortifyModule'], true);
	}
?>