<?php
require("config.php");

$url = "https://api.uptimerobot.com/getMonitors?apiKey=" . $apiKey . "&monitors=" . $monitorID . "&format=xml&responseTimes=1";
 
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
				"' AND DateTime = '" . date("Y-m-d H:i:s", strtotime($responsetime['datetime'])) . "'") or die(mysql_error());

			if (mysql_num_rows($result) == 0){
				$sql = "INSERT INTO PingLog (DateTime, PingTime, Service) VALUES('" . date("Y-m-d H:i:s", strtotime($responsetime['datetime'])) . 
					"', " . intval($responsetime['value']) . ", '" . $serviceName . "')";
				mysql_query($sql) or die(mysql_error());	

				echo "Date: " . date("Y-m-d H:i:s", strtotime($responsetime['datetime'])) . " | ";
				echo "Response time: " . $responsetime['value'] . " ms \n";
				$count++;
			}
		}
	}
}

echo "Record count: " . $count . "\n\n";
/*XML Parsing - End*/
?>