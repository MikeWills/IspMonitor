<?php
require("../config.php");

date_default_timezone_set($timezone);
$date = date('Y-m-d');

if (!empty($_GET['date'])){
	$date = $_GET['date'];
}

//echo $date;

mysql_connect($dbServer, $dbUser, $dbPassword) or die(mysql_error());
mysql_select_db($dbName) or die(mysql_error());

$query = sprintf("SELECT DateTime, PingTime, (SELECT AVG(PingTime) FROM PingLog WHERE Service = 'Enventis' AND DATE(DateTime) = '%s') AS Average FROM PingLog WHERE Service = 'Enventis' AND DATE(DateTime) = '%s' ORDER BY DateTime", 
	mysql_real_escape_string($date), mysql_real_escape_string($date));

$result = mysql_query($query);

if (!$result) {
    $message  = 'Invalid query: ' . mysql_error() . "\n";
    $message .= 'Whole query: ' . $query;
    die($message);
}

$rows = array();
while($r = mysql_fetch_array($result)){
	$rows[] = $r;
}

header('Content-Type: application/json');
echo json_encode($rows);

?>