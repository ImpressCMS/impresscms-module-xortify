<?php
/**
 * @package     xortify
 * @subpackage  module
 * @description	Sector Network Security Drone
 * @author	    Simon Roberts WISHCRAFT <simon@chronolabs.coop>
 * @copyright	copyright (c) 2010-2013 ICMS.org
 * @licence		GPL 2.0 - see docs/LICENCE.txt
 */


	define('_MI_XOR_DESC', 'Xortify - Sector Network Security - Fortify your ICMS');
	define('_MI_XOR_CREDITS', 'GI-JOE (Protector), WISHCRAFT, Trabis');
	
	define('_XOR_MI_USERNAME', 'Xortify Cloud Username');
	define('_XOR_MI_USERNAME_DESC', 'You can get one of these by going to the menu <a href="'.ICMS_URL.'/modules/xortify/admin/index.php?op=signup&fct=signup">Sign-up</a>');
	define('_XOR_MI_PASSWORD', 'Xortify Cloud Password');
	define('_XOR_MI_PASSWORD_DESC', 'You assign one of these by going to the menu <a href="'.ICMS_URL.'/modules/xortify/admin/index.php?op=signup&fct=signup">Sign-up</a>');
	define('_XOR_MI_SECONDS', 'Seconds to base Cache List on!');
	define('_XOR_MI_SECONDS_DESC', 'Period of time for ban list to be invocated!');
	
	define('_XOR_MI_PROVIDERS', 'Provider Plug-ins');
	define('_XOR_MI_PROVIDERS_DESC', 'Provider\'s that are supported by Xortify');
	
	define('_XOR_ADMENU1', 'Ban List');
	define('_XOR_ADMENU2', 'Sign-up');
	define('_XOR_ADMENU3', 'Usage Logs');
	
	// ADMIN SIGNUP FORM
	define('_XOR_FRM_TITLE', 'Sign-up to Xortify.com - Fortify your ICMS');	
	define('_XOR_FRM_UNAME', 'Your Xortify Username');
	define('_XOR_FRM_EMAIL', 'Your Email Address');
	define('_XOR_FRM_PASS', 'Your Password');
	define('_XOR_FRM_VPASS', 'Validate Your Password');
	define('_XOR_FRM_URL', 'Your Primary URL');
	define('_XOR_FRM_VIEWEMAIL', 'View Your Email');
	define('_XOR_FRM_TIMEZONE', 'Your Timezone');
	define('_XOR_FRM_DISCLAIMER', 'Xortify Disclaimer');
	define('_XOR_FRM_DISCLAIMER_AGREE', 'Agree to Xortify Disclaimer');
	define('_XOR_FRM_PUZZEL', 'Solve Puzzel');
	define('_XOR_FRM_REGISTER', 'Register with Xortify.com');
	define('_XOR_USERCREATED_PLEASEACTIVATE', 'Check Your Email for Activation Email, Click on the activation link to activate module!');
	define('_XOR_FRM_NOSOAP_DISCLAIMER', '<strong>There is no API Communication with Xortify.com check you have PHP SOAP installed as an extension and you have no firewalls preventing normal SOAP Protocol Communication. You will not be able to continue until this message has been replaced with the Xortify Disclaimer.<br/><br/>The Submit button below will appear when this is working, xortify is currently <strong>Offline</strong>');
	
	// Version 1.18
	define('_XOR_MI_RECORDS', 'Records to Retrieve!');
	define('_XOR_MI_RECORDS_DESC', 'Number of total records to retrieve!');
	
	define('_XOR_MI_SECONDS_37600', '5 Hours');
	define('_XOR_MI_SECONDS_28800', '4 Hours');
	define('_XOR_MI_SECONDS_14400', '3 Hours');
	define('_XOR_MI_SECONDS_7200', '2 Hours');
	define('_XOR_MI_SECONDS_3600', '1 Hour');
	define('_XOR_MI_SECONDS_1800', '30 minutes');
	define('_XOR_MI_SECONDS_1200', '20 minutes');
	define('_XOR_MI_SECONDS_600', '10 minutes');
	define('_XOR_MI_SECONDS_300', '5 Minutes');
	define('_XOR_MI_SECONDS_180', '3 Minutes');
	define('_XOR_MI_SECONDS_60', '1 Minute');
	define('_XOR_MI_SECONDS_30', '30 Seconds');

	define('_XOR_MI_RECORDS_177600', '177600 Records');
	define('_XOR_MI_RECORDS_48800', '48800 Records');
	define('_XOR_MI_RECORDS_24400', '24400 Records');
	define('_XOR_MI_RECORDS_12200', '12200 Records');
	define('_XOR_MI_RECORDS_3600', '3600 Records');
	define('_XOR_MI_RECORDS_1800', '1800 Records');
	define('_XOR_MI_RECORDS_1200', '1200 Records');
	define('_XOR_MI_RECORDS_600', '600 Records');
	define('_XOR_MI_RECORDS_300', '300 Records');
	define('_XOR_MI_RECORDS_180', '180 Records');
	define('_XOR_MI_RECORDS_60', '60 Records');
	define('_XOR_MI_RECORDS_30', '30 Records');

	define('_XOR_MI_PROVIDER_XORTIFY', 'Xortify Module');
	define('_XOR_MI_PROVIDER_PROTECTOR', 'Protector Module');
	define('_XOR_MI_PROVIDER_STOPFORUMSPAM', 'Stop Forum Spam');
	define('_XOR_MI_PROVIDER_SPIDERS', 'Spiders Module');
	define('_XOR_MI_PROVIDER_PROJECTHONEYPOT', 'Project Honeypot');
	
	// Version 2.27
	define('_XOR_MI_PROTOCOL', 'Cloud Communication Protocol');
	define('_XOR_MI_PROTOCOL_DESC', 'This is the protocol that is used in the communication with the xortify cloud!');
	define('_XOR_MI_PROTOCOL_SOAP', 'SOAP Protocol');
	define('_XOR_MI_PROTOCOL_CURL', 'CURL Protocol');
	define('_XOR_MI_PROTOCOL_JSON', 'Open Access Protocol');
	
	define('_XOR_MI_URISOAP', 'SOAP Cloud Base URL');
	define('_XOR_MI_URISOAP_DESC', 'This is the URL for SOAP Communication (With Trailing Slash)');
	define('_XOR_MI_URICURL', 'CURL Cloud Base URL');
	define('_XOR_MI_URICURL_DESC', 'This is the URL for CURL JSON Communication (With Trailing Slash)');
	define('_XOR_MI_URIJSON', 'Open Access Cloud Base URL');
	define('_XOR_MI_URIJSON_DESC', 'This is the URL for wGET JSON (With Trailing Slash)');
	
	// Version 2.40
	define('_XOR_MI_PHP_DAYS', '%s day(s)');
	define('_XOR_MI_PHP_NUMBEROFDAYS', '<a href="http://www.projecthoneypot.org">Project Honeypot</a> - Last Number of Days Seen');
	define('_XOR_MI_PHP_NUMBEROFDAYS_DESC', 'This is the number of days upto that the IP has been seen and flagged by project honeypot');
	define('_XOR_MI_PHP_SEVERITY', '%s percent');
	define('_XOR_MI_PHP_SEVERITYLEVEL', '<a href="http://www.projecthoneypot.org">Project Honeypot</a> - Severity Level of IP');
	define('_XOR_MI_PHP_SEVERITYLEVEL_DESC', 'This is the severity level on the honeypot that the IP has been seen and flagged by project honeypot');
	define('_XOR_MI_PHP_SCANMODE', '<a href="http://www.projecthoneypot.org">Project Honeypot</a> - What will be banned');
	define('_XOR_MI_PHP_SCANMODE_DESC', 'This list is in a hierachy which will ban from what you select and down in the list from severity.');
	define('_XOR_MI_PHP_SCANMODE_SUSPICIOUS', 'Suspicious IP');
	define('_XOR_MI_PHP_SCANMODE_HARVESTER', 'Harvester IP');
	define('_XOR_MI_PHP_SCANMODE_SUSICIOUSHARVESTER', 'Suspicious + Harvester IP');
	define('_XOR_MI_PHP_SCANMODE_SPAMMER', 'Comment Spammer');
	define('_XOR_MI_PHP_SCANMODE_SUSPICIOUSSPAMMER', 'Suspicious + Comment Spammer');
	define('_XOR_MI_PHP_SCANMODE_HARVESTERSPAMMER', 'Harvester + Comment Spammer');
	define('_XOR_MI_PHP_SCANMODE_SUSPICIOUSHARVESTERSPAMMER', 'Suspicious + Harvester + Comment Spammer');
	define('_XOR_MI_SFS_EMAIILFREQ','Occured %s time(s)');
	define('_XOR_MI_SFS_EMAILFREQUENCY','<a href="http://www.stopforumspam.com">Stop Forum Spam</a> - Number of time email address has been reported');
	define('_XOR_MI_SFS_EMAILFREQUENCY_DESC','This is the number of time the email has been reported as spam and will ban the IP and details when it occurs at this level.');
	define('_XOR_MI_SFS_UNAMEFREQ','Occured %s time(s)');
	define('_XOR_MI_SFS_UNAMEFREQUENCY','<a href="http://www.stopforumspam.com">Stop Forum Spam</a> - Number of time username has been reported');
	define('_XOR_MI_SFS_UNAMEFREQUENCY_DESC','This is the number of time the username has been reported as spam and will ban the IP and details when it occurs at this level.');
	define('_XOR_MI_SFS_IPFREQ','Occured %s time(s)');
	define('_XOR_MI_SFS_IPFREQUENCY','<a href="http://www.stopforumspam.com">Stop Forum Spam</a> - Number of time IP address has been reported');
	define('_XOR_MI_SFS_IPFREQUENCY_DESC','This is the number of time the IP Address has been reported as spam and will ban the IP and details when it occurs at this level.');
	define('_XOR_MI_SFS_EMAILLASTSEEN', '<a href="http://www.stopforumspam.com">Stop Forum Spam</a> - How long ago the email address was seen');
	define('_XOR_MI_SFS_EMAILLASTSEEN_DESC', 'This is the period of time allocated to wence the email was last seen, if it occured after this period it will not be banned!');
	define('_XOR_MI_SFS_UNAMELASTSEEN', '<a href="http://www.stopforumspam.com">Stop Forum Spam</a> - How long ago the username was seen');
	define('_XOR_MI_SFS_UNAMELASTSEEN_DESC', 'This is the period of time allocated to wence the username was last seen, if it occured after this period it will not be banned!');
	define('_XOR_MI_SFS_IPLASTSEEN', '<a href="http://www.stopforumspam.com">Stop Forum Spam</a> - How long ago the IP address was seen');
	define('_XOR_MI_SFS_IPLASTSEEN_DESC', 'This is the period of time allocated to wence the IP address was last seen, if it occured after this period it will not be banned!');
	define('_XOR_MI_SFS_LASTSEEN_24HOURS', '24 Hours');
	define('_XOR_MI_SFS_LASTSEEN_1WEEK', '1 Week');
	define('_XOR_MI_SFS_LASTSEEN_FORTNIGHT', 'A Fortnight');
	define('_XOR_MI_SFS_LASTSEEN_1MONTH', '1 Month');
	define('_XOR_MI_SFS_LASTSEEN_2MONTHS', '2 Months');
	define('_XOR_MI_SFS_LASTSEEN_3MONTHS', '3 Months');
	define('_XOR_MI_SFS_LASTSEEN_4MONTHS', '4 Months');
	define('_XOR_MI_SFS_LASTSEEN_5MONTHS', '5 Months');
	define('_XOR_MI_SFS_LASTSEEN_6MONTHS', '6 Months');
	define('_XOR_MI_SFS_LASTSEEN_12MONTHS', '1 Year');
	define('_XOR_MI_SFS_LASTSEEN_24MONTHS', '2 Years');
	define('_XOR_MI_SFS_LASTSEEN_36MONTHS', '3 Years');
	define('_XOR_MI_LOG_BANNED', 'Log Banned Action');
	define('_XOR_MI_LOG_BANNED_DESC', 'When Xortify Bans something on the cloud, then log it!');
	define('_XOR_MI_LOG_BLOCKED', 'Log Blocked Action');
	define('_XOR_MI_LOG_BLOCKED_DESC', 'When Xortify Blocks something as detected on the cloud, then log it!');
	define('_XOR_MI_LOG_MONITORED', 'Log Monitored Action');
	define('_XOR_MI_LOG_MONITORED_DESC', 'When Xortify Monitor\'s something/someone as detected on the cloud, then log it!');
	define('_XOR_MI_LOGDROPS', 'Log Deletes Itself After');
	define('_XOR_MI_LOGDROPS_DESC', 'This is how long the log stays on your site for after a record reaches this age it is deleted!');
	
	// version 2.41
	define('_XOR_MI_PROTOCOL_CURLSERIAL', 'cURL Serilisation Protocol');
	define('_XOR_MI_PROTOCOL_WGETSERIAL', 'wGET Serilisation Protocol');
	define('_XOR_MI_PROTOCOL_CURLXML', 'cURL XML Exchange Protocol');
	define('_XOR_MI_PROTOCOL_WGETXML', 'wGET XML Exchange Protocol');
	define('_XOR_MI_URISERIAL', 'Serilisation Cloud Base URL');
	define('_XOR_MI_URISERIAL_DESC', 'This is the URL for Serialisation Communication in cURL or wGET (With Trailing Slash)');
	define('_XOR_MI_URIXML', 'XML Cloud Base URL');
	define('_XOR_MI_URIXML_DESC', 'This is the URL for cURL or wGET for XML Communication (With Trailing Slash)');
	
	//Version 2.50
	define('_XOR_MI_IPCACHE', 'IP Cache Time');
	define('_XOR_MI_IPCACHE_DESC', 'This is the amount of time an IP Address and information on it is cached!');
	define('_XOR_MI_SECONDS_29030400', '1 Year');
	define('_XOR_MI_SECONDS_14515200', '6 Months');
	define('_XOR_MI_SECONDS_7257600', '3 Months');
	define('_XOR_MI_SECONDS_2419200', '1 Month');
	define('_XOR_MI_SECONDS_1209600', '1 Fortnight');
	define('_XOR_MI_SECONDS_604800', '1 Week');
	define('_XOR_MI_SECONDS_86400', '24 Hours');
	define('_XOR_MI_SECONDS_43200', '12 Hours');
	
	//Version 2.5.4
	define('_XOR_MI_CRONTYPE', 'Type of cron scheduling');
	define('_XOR_MI_CRONTYPE_DESC', 'This is the type of scheduling system you are using for your cron job');
	define('_XOR_MI_CRONTYPE_PRELOADER', 'Preloader');
	define('_XOR_MI_CRONTYPE_CRONTAB', 'UNIX Cron Job');
	define('_XOR_MI_CRONTYPE_SCHEDULER', 'Windows Scheduled Task');
	define('_XOR_MI_CRONINTERVAL', 'Cron Interval');
	define('_XOR_MI_CRONINTERVAL_DESC', 'This is the interval in seconds between each cron');
	
	// Version 2.56
	define('_XOR_ADMENU4', 'Dashboard');
	define('_XOR_ADMENU5', 'About Xortify');
	
	// Version 2.99
	define('_XOR_MI_FAULT_DELAY', 'Number of second to delay function on fault!');
	define('_XOR_MI_FAULT_DELAY_DESC', 'If a fault occurs in the preloader, xortify will delay this function before calling it again this many seconds (Default 10 minutes)');
	define('_XOR_MI_CURL_TIMOUT', 'Total amount of seconds a CURL Waits for a Response');
	define('_XOR_MI_CURL_TIMOUT_DESC', 'This is the total amount of seconds a CURL waits for a response after resolving the DNS.');
	define('_XOR_MI_CURL_CONNECTTIMOUT', 'Total amount of seconds a CURL Waits for DNS to resolve');
	define('_XOR_MI_CURL_CONNECTTIMOUT_DESC', 'This is the total amount of seconds a CURL waits for a DNS Lookup to resolve.');
	
	// Version 3.00
	// Google Analytics for Collecting Statistics on Banned Users and Passing User on Xortify. 
	// One Instance Per Session - Do Not Change _XOR_MI_ICMS_GOOGLE_ANALYTICS_ACCOUNTID_... 
	define('_XOR_MI_ICMS_GOOGLE_ANALYTICS_ACCOUNTID_FAILEDTOPASS', 'UA-27709249-1');
	define('_XOR_MI_ICMS_GOOGLE_ANALYTICS_ACCOUNTID_USERPASSED', 'UA-27726722-1');
	// Set your own with _XOR_MI_CLIENT_GOOGLE_ANALYTICS_ACCOUNTID_.
	define('_XOR_MI_CLIENT_GOOGLE_ANALYTICS_ACCOUNTID_FAILEDTOPASS', '');
	define('_XOR_MI_CLIENT_GOOGLE_ANALYTICS_ACCOUNTID_USERPASSED', '');
	
?>