<?php
/**
 * @package     xortify
 * @subpackage  module
 * @description	Sector Network Security Drone
 * @author	    Simon Roberts WISHCRAFT <simon@chronolabs.coop>
 * @copyright	copyright (c) 2010-2013 ICMS.org
 * @licence		GPL 2.0 - see docs/LICENCE.txt
 */


	include_once (dirname(__FILE__).'/forms.objects.php');
	include_once (dirname(dirname(__FILE__)).'/class/auth/authfactory.php');
	
	function XortifySignupForm()
	{
	
		$form = new icms_form_Theme(_XOR_FRM_TITLE, "xortify_member", "", "post");
		
		$form->addElement(new icms_form_elements_Text(_XOR_FRM_UNAME, "uname", 35, 128, (isset($_REQUEST['uname'])?$_REQUEST['uname']:'')));					
		$form->addElement(new icms_form_elements_Password(_XOR_FRM_PASS, "pass", 35, 128, (isset($_REQUEST['pass'])?$_REQUEST['pass']:'')), false);					
		$form->addElement(new icms_form_elements_Password(_XOR_FRM_VPASS, "vpass", 35, 128, (isset($_REQUEST['vpass'])?$_REQUEST['vpass']:'')), false);					
		$form->addElement(new icms_form_elements_Text(_XOR_FRM_EMAIL, "email", 35, 128, (isset($_REQUEST['email'])?$_REQUEST['email']:'')));											
		$form->addElement(new icms_form_elements_Text(_XOR_FRM_URL, "url", 35, 128, (isset($_REQUEST['url'])?$_REQUEST['url']:'')));											
		$form->addElement(new icms_form_elements_RadioYN(_XOR_FRM_VIEWEMAIL, "viewemail", (isset($_REQUEST['viewemail'])?$_REQUEST['viewemail']:false)));
		$form->addElement(new icms_form_elements_SelectTimezone(_XOR_FRM_TIMEZONE, "timezone", (isset($_REQUEST['timezone'])?$_REQUEST['timezone']:'')));
		$xortifyAuth =& XortifyAuthFactory::getAuthConnection(false, $GLOBALS['xoopsModuleConfig']['protocol']);
		$myts =& icms_core_Textsanitizer::getInstance();
		$disclaimer = $xortifyAuth->network_disclaimer();
		if (strlen(trim($disclaimer))==0)
			{
				$disclaimer = _XOR_FRM_NOSOAP_DISCLAIMER;
			}
		$form->addElement(new icms_form_elements_Label(_XOR_FRM_DISCLAIMER, $myts->nl2br($disclaimer)));	
		$form->addElement(new icms_form_elements_RadioYN(_XOR_FRM_DISCLAIMER_AGREE, "agree", false));				
		$form->addElement(new icms_form_elements_Captcha(_XOR_FRM_PUZZEL, 'xoopscaptcha', false), true);
		$form->addElement(new icms_form_elements_Hidden('op', 'signup'));	
		$form->addElement(new icms_form_elements_Hidden('fct', 'save'));
		if ($disclaimer != _XOR_FRM_NOSOAP_DISCLAIMER)
			$form->addElement(new icms_form_elements_Button('', 'submit', _XOR_FRM_REGISTER, 'submit'));	
		return $form->render();
	}
?>