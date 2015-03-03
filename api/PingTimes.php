<?php
require("../config.php");

$fromDate = $_GET['fromDate'];
$toDate = $_GET['toDate'];

if (empty($fromDate)){
	$fromDate = date("Y-m-d", strtotime("-1 month"));
}

if (empty($toDate)){
	$toDate = date("Y-m-d");
}

mysql_connect($dbServer, $dbUser, $dbPassword) or die(mysql_error());
mysql_select_db($dbName) or die(mysql_error());

$query = sprintf("SELECT * FROM PingLog WHERE Service = 'Enventis' AND DateTime >= '%s' and DateTime <= '%s'", 
	mysql_real_escape_string($fromDate), mysql_real_escape_string($toDate));

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

print json_encode($rows);

?>