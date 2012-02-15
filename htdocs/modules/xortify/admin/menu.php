<?php
/**
 * @package     xortify
 * @subpackage  module
 * @description	Sector Network Security Drone
 * @author	    Simon Roberts WISHCRAFT <simon@chronolabs.coop>
 * @copyright	copyright (c) 2010-2013 ICMS.org
 * @licence		GPL 2.0 - see docs/LICENCE.txt
 */
$module_handler =& icms::handler('icms_module');
$GLOBALS['xortifyModule'] = $module_handler->getByDirname('xortify');
$GLOBALS['xortifyImageAdmin'] = $GLOBALS['xortifyModule']->getInfo('icons32');

global $adminmenu;
$adminmenu = array();
$adminmenu[1]['title'] = _XOR_ADMENU4;
$adminmenu[1]['icon'] = $GLOBALS['xortifyImageAdmin'].'/home.png';
$adminmenu[1]['image'] = $GLOBALS['xortifyImageAdmin'].'/home.png';
$adminmenu[1]['link'] = "admin/index.php?op=dashboard";
$adminmenu[2]['title'] = _XOR_ADMENU1;
$adminmenu[2]['icon'] = $GLOBALS['xortifyImageAdmin'].'/current.bans.png';
$adminmenu[2]['image'] = $GLOBALS['xortifyImageAdmin'].'/current.bans.png';
$adminmenu[2]['link'] = "admin/index.php?op=list&fct=bans";
$adminmenu[3]['title'] = _XOR_ADMENU3;
$adminmenu[3]['icon'] = $GLOBALS['xortifyImageAdmin'].'/xortify.log.png';
$adminmenu[3]['image'] = $GLOBALS['xortifyImageAdmin'].'/xortify.log.png';
$adminmenu[3]['link'] = "admin/index.php?op=log";
$adminmenu[4]['title'] = _XOR_ADMENU2;
$adminmenu[4]['icon'] = $GLOBALS['xortifyImageAdmin'].'/access.list.png';
$adminmenu[4]['image'] = $GLOBALS['xortifyImageAdmin'].'/access.list.png';
$adminmenu[4]['link'] = "admin/index.php?op=signup&fct=signup";
$adminmenu[5]['title'] = _XOR_ADMENU5;
$adminmenu[5]['icon'] = $GLOBALS['xortifyImageAdmin'].'/about.png';
$adminmenu[5]['image'] = $GLOBALS['xortifyImageAdmin'].'/about.png';
$adminmenu[5]['link'] = "admin/index.php?op=about";

?>