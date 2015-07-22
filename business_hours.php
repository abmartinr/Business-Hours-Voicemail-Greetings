<?php

require_once('bh-config.php');
date_default_timezone_set(TIMEZONE);

$current_datetime = new DateTime('NOW');

$hour = $current_datetime->format('H:i');
$day_week = $current_datetime->format('w');

$key = array_search($hour, $business_hours[$day_week]);
echo "Script started\n";

if($key){
	// Retrieve all numbers
	$data = curlWrap("/channels/voice/phone_numbers.json",NULL,"GET");
		echo ($key == "start")? "Opening the office...": "Closing the office...";
		echo "\n";
		
		foreach ($data->phone_numbers as $phone_number) {
			//Put request /api/v2/channels/voice/phone_numbers/{id}.json
			$u = "/channels/voice/phone_numbers/".$phone_number->id.".json";
			//Replace the value
			echo "Setting the voicemail for number ".$phone_number->number."\n";
			
			$greetings = generateGreetingsArray($phone_number->greeting_ids, $key);
			if($greetings){
				$g = array("phone_number" => array("greeting_ids" => $greetings));	
				curlWrap($u,json_encode($g),"PUT");
			}else{
				echo "Greeting is already set up, ignoring this update.\n";
			}
		}
}else{
	echo "It's not time to change the voicemail greeting.\n";
}

function curlWrap($url, $json, $action){
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

	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	$output = curl_exec($ch);
	curl_close($ch);
	$decoded = json_decode($output);
	if($action =="PUT"){
		echo "Server response:\n" .$output. "\n";
	}
	return $decoded;
}

function generateGreetingsArray($current_greetings,$key){

	if($key=='start'){
		echo "Setting up the start of the day voicemail\n";
		// Let's first look if the START_GREETING is alread in the array of greetings.
		$res = array_search(END_GREETING, $current_greetings);
		// I'll add the START_GREETING or replace the END_GREETING
		if($res === false){
			echo "END_GREETING ".END_GREETING." was not found on this number.\n Current Greeting ID's:\n";
			var_dump($current_greetings);
			// Checking that the Array doesn't contain the START_GREETING already
			$ver = array_search(START_GREETING, $current_greetings,true);
			if($ver === false){
				echo "START_GREETING ".START_GREETING." was not found either on this number.\n";
				echo "Adding START_GREETING ".START_GREETING." into array.\n";
				$current_greetings[] = START_GREETING;
			}else{
				return false;
			}
		}else{
			echo "END_GREETING found at index " . $res . "\nCurrent Greeting ID's:\n";
			var_dump($current_greetings); 
			// I replace the value of END_GREETING with START_GREETING
			echo "Replacing greeting ".$current_greetings[$res]." from array with ".START_GREETING.".\n";
			$current_greetings[$res] = START_GREETING;
		}
	}else{
		echo "Setting up the end of the day voicemail\n";

		// Let's look if the START_GREETING is in the array of greetings
		$res = array_search(START_GREETING, $current_greetings);
		// I'll add the END_GREETING or replace the START_GREETING
		if($res === false){
			echo "START_GREETING ".START_GREETING." was not found on this number.\n Current Greeting ID's:\n";
			var_dump($current_greetings);
			// Checking that the Array doesn't contain the END_GREETING already
			$ver = array_search(END_GREETING, $current_greetings);
			if($ver === false){
				echo "END_GREETING ".END_GREETING." was not found either on this number.\n";
				echo "Adding END_GREETING ".END_GREETING." into array.\n";
				echo "Adding greeting into array";
				// I add the greeting to the array
				$current_greetings[] = END_GREETING;
			}else{
				return false;
			}
			
		}else{
			// I replace the value of END_GREETING with START_GREETING
			echo "START_GREETING found at index " . $res . "\nCurrent Greeting ID's:\n";
			var_dump($current_greetings); 
			echo "Replacing greeting ".$current_greetings[$res]." from array with ".END_GREETING.".\n";
			$current_greetings[$res] = END_GREETING;
		}
	}
	echo "New greetings array is: \n";
	var_dump($current_greetings);
	return $current_greetings;
}

?>