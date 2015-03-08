<?php

require_once('bh-config.php');
date_default_timezone_set(TIMEZONE);

$current_datetime = new DateTime('NOW');

$hour = $current_datetime->format('H:i');
$day_week = $current_datetime->format('w');

$key = array_search($hour, $business_hours[$day_week]);
echo "Script started";
echo "<br/>";

if($key){
	// Retrieve all numbers
	$data = curlWrap("/channels/voice/phone_numbers.json",NULL,"GET");
		echo ($key == "start")? "Opening the office...": "Closing the office...";
		echo "<br/>";
		
		foreach ($data->phone_numbers as $phone_number) {
			//Put request /api/v2/channels/voice/phone_numbers/{id}.json
			$u = "/channels/voice/phone_numbers/".$phone_number->id.".json";
			//Replace the value
			echo "Setting the voicemail for number ".$phone_number->number;
			echo "<br/>";
			if($key=='start'){
				$greetings = array_diff($phone_number->greeting_ids, [START_GREETING]);
				$greetings[] = END_GREETING;
			}else{
				$greetings = array_diff($phone_number->greeting_ids, [END_GREETING]);
				$greetings[] = START_GREETING;	
			}
			$g = array("greeting_ids" => $greetings);
			curlWrap($u,json_encode($g),"PUT");
		}
}else{
	echo "It's not time to change the voicemail greeting.";
}

function curlWrap($url, $json, $action)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_MAXREDIRS, 10 );
	curl_setopt($ch, CURLOPT_URL, "https://".ZD_SUBDOMAIN.".zendesk.com/api/v2".$url);
	curl_setopt($ch, CURLOPT_USERPWD, ZD_EMAIL."/token:".ZD_TOKEN);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	
	switch($action){
		case "POST":
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
			break;
		case "GET":
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
			break;
		case "PUT":
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
			break;
		case "DELETE":
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
			break;
		default:
			break;
	}

	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
	curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	$output = curl_exec($ch);
	curl_close($ch);
	$decoded = json_decode($output);
	return $decoded;
}

?>