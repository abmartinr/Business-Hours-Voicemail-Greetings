# Business hours

With this script you can automatically change the voicemail greeting for every number in your Zendesk account.

## Summary

1. [Edit the bh-config.php](#edit-the-bh-configphp).
2. [Create a Cron Job that will run every 30 minutes](#create-a-cron-job-that-will-run-every-30-minutes).
3. [Credits & Extra info](#credits-&-extra-info).

### 1.Edit the bh-config.php

**ZD_SUBDOMAIN**

This is the subdomain of your Zendesk account. If your account is https://test.zendesk.com the value should be `test`.

If you are using hostmapping in your account you have to use the unmapped version.

```
support.mydomain.com -> test.zendesk.com
```

The value should be `test`. 

**ZD_EMAIL**

Your Zendesk user email.

**ZD_TOKEN**

This is the API token for your Zendesk account. You can get one by going to **Admin** > **Channels** > **API**

**TIMEZONE**

The timezone of your account. Make sure that the timezone matches the one in Zendesk or otherwise you will change the greetings when you don't want to. All possible timezone values can be found [here](http://php.net/manual/en/timezones.php).

**START_GREETING**

This is the greeting ID that will be used when **you are within** your business hours. To get it you have to:

1. Right click the edit link for that greeting.
2. Copy the only number you will see in the URL

e.g.

```
https://test.zendesk.com/voice/greetings/20025412/edit
``` 

The ID for this greeting is `20025412`.

**END_GREETING**

This is the greeting ID that will be used when **you are NOT within** your business hours. To get it you have to:

1. Right click the edit link for that greeting.
2. Copy the only number you will see in the URL

e.g.

```
https://test.zendesk.com/voice/greetings/20025111/edit
``` 

The ID for this greeting is `20025111`.

**business_hours**

Here you can configure the business hours for each day of the week.
The days are as follows: 

0 -> Sunday
1 -> Monday
2 -> Tuesday
3 -> Wednesday
4 -> Thursday
5 -> Friday
6 -> Saturday

Hour format must be in 24H and can only have o'clock on half-past hours.

e.g.

```
$business_hours = array(
	'0' => array(
		'start' => '',
		'end' => ''
		),
	'1' => array(
		'start' => '07:00',
		'end' => '17:30'
		),
	'2' => array(
		'start' => '07:00',
		'end' => '17:30'
		),
	'3' => array(
		'start' => '07:00',
		'end' => '17:30'
		),
	'4' => array(
		'start' => '07:00',
		'end' => '17:30'
		),
	'5' => array(
		'start' => '07:00',
		'end' => '17:30'
		),
	'6' => array(
		'start' => '',
		'end' => ''
		)
	);
```

In this example my office hours are Mon-Fri from 07:00 until 17:00. 

### 2. Create a Cron Job that will run every 30 minutes.

Every 30 minutes we will need to run this script, to do so you can achieve it by creating a Cron Job.

```
*/30 * * * *	php -q /path/to/the/script/business_hours.php
```

### Credits & Extra info

Pull requests are welcome. :)