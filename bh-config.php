<?php

// Your Zendesk subdomain 
define('ZD_SUBDOMAIN', 'YOURSUBDOMAIN');

// Your Zendesk username 

define('ZD_EMAIL', 'YOUREMAIL');

// Your Zendesk API Token 

define('ZD_TOKEN', 'TOKEN');

// Default Timezone
// All possible values can be found here: http://php.net/manual/en/timezones.php

define('TIMEZONE', 'Europe/Dublin');


// Greetings ID's 

define('START_GREETING',20025412);
define('END_GREETING',20025422);


 
// Business hours Schedule
// Do not fill in the days that there are no business hours
// 0 -> Sunday ... 6 -> Saturday 
// Hour must be in 24H format and if necessary must include a 0

$business_hours = array(
	'0' => array(
		'start' => '09:32',
		'end' => '11:29'
		),
	'1' => array(
		'start' => '',
		'end' => ''
		),
	'2' => array(
		'start' => '',
		'end' => ''
		),
	'3' => array(
		'start' => '',
		'end' => ''
		),
	'4' => array(
		'start' => '',
		'end' => ''
		),
	'5' => array(
		'start' => '',
		'end' => ''
		),
	'6' => array(
		'start' => '23:40',
		'end' => '23:41'
		)
	);




?>