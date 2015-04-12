<?php
error_reporting(E_ALL ^ E_DEPRECATED);
require_once("../dbconfig.php");

if (!isset($_GET["username"]) || empty($_GET["username"])){
	exit("Missing or empty username");
}
if (!isset($_GET["friend"]) || empty($_GET["friend"])){
	exit("Missing or empty friend");
}
if (!isset($_GET["token"]) || empty($_GET["token"])){
	exit("Missing token!");
}

$username = $_GET["username"];
$token = $_GET["token"];
$friend = $_GET["friend"];

mysql_connect($DB_HOST, $DB_USER, $DB_PASS);
mysql_select_db($DATABASE);

$row = mysql_fetch_row(mysql_query("SELECT tokenhash from users WHERE username = '$username'"));

if (!password_verify($token, $row[0])){
	exit("Invalid token");
}

$row = mysql_fetch_row(mysql_query("SELECT id from users WHERE username = '$username'"));
$id = $row[0];

$friends = mysql_query("SELECT friend_username FROM friends WHERE id = '$id'");
$row = mysql_fetch_assoc($friends);

for ($i = 0; $i < count($row); $i++){
	if(strcmp($row["friend_username"], $friend) == 0 || strcmp($username, $friend) == 0){
    	$row = mysql_fetch_row(mysql_query("SELECT id from users WHERE username = '$friend'"));
		$friendid = $row[0];

    	$status = mysql_fetch_assoc(mysql_query("SELECT * from freedom WHERE userid = '$friendid'"));
    	exit($status["free"]."\n".$status["timeout"]."\n".$status["status"]);
    }
}
exit("You're not friends with that user");
?>
