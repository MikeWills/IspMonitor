<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ISP Tester</title>
</head>
<body>
<?php
/*Note: You'll need the ID of the monitor. For that, simply go to "https://api.uptimerobot.com/getMonitors?apiKey=yourApiKey" and get the ID of the monitor to be queried.*/
/*And, this code requires PHP 5+ or PHP 4 with SimpleXML enabled.*/
 
/*Variables - Start*/
$apiKey     = ""; /*replace with your apiKey*/
$monitorID  = 111111; /*replace with your monitorID*/
$url    = "https://api.uptimerobot.com/getMonitors?apiKey=" . $apiKey . "&monitors=" . $monitorID . "&format=xml&responseTimes=1";
/*Variables - End*/
 
/*Curl Request - Start*/
$c = curl_init($url);
curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
$responseXML = curl_exec($c);
curl_close($c);
/*Curl Request - End*/
 
/*XML Parsing - Start*/
$xml = simplexml_load_string($responseXML);
$count = 0;

foreach($xml->monitor as $monitor) {
	foreach ($monitor->responsetime as $responsetime) {
		echo "Date:" . $responsetime['datetime'] . " | ";
		echo "Response time: " . $responsetime['value'] . " ms<br><br>";
		$count++;
	}
}

echo "Record count: " . $count;
/*XML Parsing - End*/
?>
</body>
</html>