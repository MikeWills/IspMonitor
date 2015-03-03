<?php
/*Configuration - Start*/
$apiKey     = ""; /* Replace with your apiKey */
$monitorID  = 111111; /* Replace with your monitorID */
$url    = "https://api.uptimerobot.com/getMonitors?apiKey=" . $apiKey . "&monitors=" . $monitorID . "&format=xml&responseTimes=1";

$dbServer = "localhost"; /* Enter your DB host */
$dbName = ""; /* Enter your DB name */
$dbUser = ""; /* Enter your DB username */
$dbPassword = ""; /* Enter your DB password */
$service = ""; /* This allows you to track multiple services. */
/*Configuration - End*/
 
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

		if (intval($responsetime['value']) != 0){
			
			mysql_connect($dbServer, $dbUser, $dbPassword) or die(mysql_error());
			mysql_select_db($dbName) or die(mysql_error());

			$result = mysql_query("SELECT DateTime FROM PingLog WHERE Service = '" . $serviceName . 
				"' AND DateTime = '" . $responsetime['datetime'] . "'") or die(mysql_error());

			if (mysql_num_rows($result) == 0){
				$sql = "INSERT INTO PingLog (DateTime, PingTime, Service) VALUES('" . $responsetime['datetime'] . 
					"', " . intval($responsetime['value']) . ", '" . $serviceName . "')";
				mysql_query($sql) or die(mysql_error());	

				echo "Date: " . $responsetime['datetime'] . " | ";
				echo "Response time: " . $responsetime['value'] . " ms \n";
				$count++;
			}
		}
	}
}

echo "Record count: " . $count . "\n\n";
/*XML Parsing - End*/
?>