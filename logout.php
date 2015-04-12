<?php
error_reporting(E_ALL ^ E_DEPRECATED);
require_once("../dbconfig.php");

if (!isset($_POST["username"]) || empty($_POST["username"])){
	exit("Missing or empty username");
}

if (!isset($_POST["token"]) || empty($_POST["token"])){
	exit("Missing or empty token");
}

$username = $_POST["username"];
$token = $_POST["token"];

mysql_connect($DB_HOST, $DB_USER, $DB_PASS);
mysql_select_db($DATABASE);

$username = mysql_real_escape_string($username);

$row = mysql_fetch_row(mysql_query("SELECT tokenhash from users WHERE username = '$username'"));
if (password_verify($token, $row[0])){
	mysql_query("UPDATE users SET tokenhash = '' WHERE username = '$username'");
	echo "Logged out";
}
else {
	echo "Couldn't log out (invalid token?)";
}
?>