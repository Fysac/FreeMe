<?php
error_reporting(E_ALL ^ E_DEPRECATED);
require_once("../dbconfig.php");

if (!isset($_POST["username"]) || empty($_POST["username"])){
	exit("Missing or empty username");
}
if (!isset($_POST["token"]) || empty($_POST["token"])){
	exit("Missing token!");
}

$username = $_POST["username"];
$token = $_POST["token"];

mysql_connect($DB_HOST, $DB_USER, $DB_PASS);
mysql_select_db($DATABASE);

$row = mysql_fetch_row(mysql_query("SELECT tokenhash from users WHERE username = '$username'"));

if (!password_verify($token, $row[0])){
	exit("Invalid token");
}

$row = mysql_fetch_row(mysql_query("SELECT id from users WHERE username = '$username'"));
$id = $row[0];

if (isset($_POST["free"])){
	$free = $_POST["free"];
	mysql_query("UPDATE freedom SET free = '$free' WHERE userid = '$id'");
	echo "Free boolean updated\n";
}
if (isset($_POST["timeout"])){
	$timeout = $_POST["timeout"];
	mysql_query("UPDATE freedom SET timeout = '$timeout' WHERE userid = '$id'");
	echo "Timeout updated\n";
}
if (isset($_POST["status"])){
	$status = mysql_real_escape_string($_POST["status"]);
	if (strlen($status) > 256){
		exit("Status is too big! (".strlen($status).", max is 256)");
	}
	mysql_query("UPDATE freedom SET status = '$status' WHERE userid = '$id'");
	echo "Status string updated\n";
}
?>
