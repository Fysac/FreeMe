<?php
error_reporting(E_ALL ^ E_DEPRECATED);
require_once("../dbconfig.php");

if (!isset($_POST["username"]) || empty($_POST["username"])){
	exit("Missing or empty username");
}
if (!isset($_POST["token"]) || empty($_POST["token"])){
	exit("Missing token!");
}
if (!isset($_POST["friend"]) || empty($_POST["friend"])){
	exit("Missing or empty friend");
}

$username = $_POST["username"];
$token = $_POST["token"];
$friend = $_POST["friend"];

mysql_connect($DB_HOST, $DB_USER, $DB_PASS);
mysql_select_db($DATABASE);

$row = mysql_fetch_row(mysql_query("SELECT tokenhash from users WHERE username = '$username'"));

if (!password_verify($token, $row[0])){
	exit("Invalid token");
}

if (strcmp($username, $friend) == 0){
	exit("You can't add yourself as a friend");
}

$row = mysql_fetch_row(mysql_query("SELECT id from users WHERE username = '$username'"));
$id = $row[0];

if (mysql_query("SELECT id from users WHERE username = '$friend'")){
	$row = mysql_fetch_row(mysql_query("SELECT id from users WHERE username = '$friend'"));
	if (empty($row)){
		exit("Specified friend not found.");
	}
	$friend_id = $row[0];
	$row = mysql_fetch_row(mysql_query("SELECT name from users WHERE username = '$friend'"));
	$friend_name = $row[0];
	mysql_query("INSERT INTO friends (id, friend_id, friend_username, friend_name) VALUES ('$id', '$friend_id', '$friend', '$friend_name')");
	exit("Friend added!");
}
else {
	exit("Specified friend not found.");
}
?>