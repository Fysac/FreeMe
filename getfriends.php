<?php
error_reporting(E_ALL ^ E_DEPRECATED);
require_once("../dbconfig.php");

if (!isset($_GET["username"]) || empty($_GET["username"])){
	exit("Missing or empty username");
}
if (!isset($_GET["token"]) || empty($_GET["token"])){
	exit("Missing token!");
}

$username = $_GET["username"];
$token = $_GET["token"];

mysql_connect($DB_HOST, $DB_USER, $DB_PASS);
mysql_select_db($DATABASE);

$row = mysql_fetch_row(mysql_query("SELECT tokenhash from users WHERE username = '$username'"));

if (!password_verify($token, $row[0])){
	exit("Invalid token");
}

$row = mysql_fetch_row(mysql_query("SELECT id from users WHERE username = '$username'"));
$id = $row[0];

$friends = mysql_query("SELECT friend_name, friend_username FROM friends WHERE id = '$id'");
if (mysql_num_rows($friends) == 0) {
    exit("You have no friends.");
}
while ($row = mysql_fetch_assoc($friends)) {
    echo $row["friend_name"]."\n".$row["friend_username"]."\n";
}

?>