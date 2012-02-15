<?php

/*
Module: Xcenter

Version: 2.01

Description: Multilingual Content Module with tags and lists with search functions

Author: Written by Simon Roberts aka. Wishcraft (simon@chronolabs.coop)

Owner: Chronolabs

License: See /docs - GPL 2.0
*/



if (!defined('ICMS_ROOT_PATH')) {
	exit();
}
/**
 * Class for Blue Room Xortify Log
 * @author Simon Roberts <simon@xoops.org>
 * @copyright copyright (c) 2009-2003 ICMS.org
 * @package kernel
 */
class XortifyLog extends icms_core_Object
{

    function XortifyLog($id = null)
    {
        $this->initVar('lid', XOBJ_DTYPE_INT, null, false);
		$this->initVar('uid', XOBJ_DTYPE_INT, null, false);
		$this->initVar('uname', XOBJ_DTYPE_TXTBOX, false, false, 64);
		$this->initVar('email', XOBJ_DTYPE_TXTBOX, false, false, 255);
		$this->initVar('ip4', XOBJ_DTYPE_TXTBOX, false, false, 15);
		$this->initVar('ip6', XOBJ_DTYPE_TXTBOX, false, false, 128);
		$this->initVar('proxy-ip4', XOBJ_DTYPE_TXTBOX, false, false, 15);
		$this->initVar('proxy-ip6', XOBJ_DTYPE_TXTBOX, false, false, 128);
		$this->initVar('network-addy', XOBJ_DTYPE_TXTBOX, false, false, 255);
		$this->initVar('provider', XOBJ_DTYPE_TXTBOX, false, false, 128);
		$this->initVar('agent', XOBJ_DTYPE_TXTBOX, false, false, 255);
		$this->initVar('extra', XOBJ_DTYPE_OTHER, false, false);
		$this->initVar('date', XOBJ_DTYPE_INT, null, false);
		$this->initVar('action', XOBJ_DTYPE_ENUM, 'monitored', false, false, false, array('banned', 'blocked', 'monitored'));
		
		foreach($this->vars as $key => $data) {
			$this->vars[$key]['persistent'] = true;
		}
		
    }

    function toArray() {
    	$ret = parent::toArray();
    	$ret['date_datetime'] = date(_DATESTRING, $this->getVar('date'));
    	$ret['action'] = ucfirst($this->getVar('action'));
    	foreach($ret as $key => $value)
    		$ret[str_replace('-', '_', $key)] = $value;
    	return $ret;
    }
    
    function runPrePlugin($default = true) {
		
		include_once((ICMS_ROOT_PATH.'/modules/xortify/plugin/'.$this->getVar('provider').'.php'));
		
		switch ($this->getVar('action')) {
			case 'banned':
			case 'blocked':
			case 'monitored':
				$func = ucfirst($this->getVar('action')).'PreHook';
				break;
			default:
				return $default;
				break;
		}
		
		if (function_exists($func))  {
			return @$func($default, $this);
		}
		return $default;
	}
    
	function runPostPlugin($lid) {
		
		include_once((ICMS_ROOT_PATH.'/modules/xortify/plugin/'.$this->getVar('provider').'.php'));
		
		switch ($this->getVar('action')) {
			case 'banned':
			case 'blocked':
			case 'monitored':
				$func = ucfirst($this->getVar('action')).'PostHook';
				break;
			default:
				return $lid;
				break;
		}
		
		if (function_exists($func))  {
			return @$func($this, $lid);
		}
		return $lid;
	}
}


/**
* ICMS Xortify Log handler class.
* This class is responsible for providing data access mechanisms to the data source
* of ICMS user class objects.
*
* @author  Simon Roberts <simon@chronolabs.coop>
* @package kernel
*/
class XortifyLogHandler extends icms_ipf_Handler
{
    function __construct(&$db) 
    {
		$this->db = $db;
        parent::__construct($db, 'log', "lid", "network-addy", '', 'xortify');
    }
	
    public function insert(&$object, $force = true, $checkObject = true, $debug = false) {
		$module_handler = icms::handler('icms_module');
		$config_handler = icms::handler('icms_config');
		$xoModule = $module_handler->getByDirname('xortify');
		$xoConfig = $config_handler->getConfigList($xoModule->getVar('mid'));
		
		$criteria = new icms_db_criteria_Item('`date`', time()-$xoConfig['logdrops'], '<=');
		$this->deleteAll($criteria, true);
		
    	if ($object->isNew()) {
    		$object->setVar('date', time());
    	}
		$run_plugin_action=false;
    	if ($object->vars['action']['changed']==true) {	
			$run_plugin_action=true;
		}
    	if ($run_plugin_action){
    		if ($object->runPrePlugin($xoConfig['save_'.$object->getVar('action')])==true)
    			$lid = parent::insert($object, $force, $checkObject, $debug);
    		else 
    			return false;
    	} else 	
    		$lid = parent::insert($object, $force, $checkObject, $debug);		
    	if ($run_plugin_action)
    		return $object->runPostPlugin($lid);
    	else 	
    		return $lid;
    }
    
    function getCountByField($field, $value) {
    	$criteria = new icms_db_criteria_Item('`'.$field.'`', $value);
    	$count = $this->getCount($criteria);
    	return ($count>0?$count:'0');
    }
}

?>