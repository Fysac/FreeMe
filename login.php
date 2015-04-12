<?php
error_reporting(E_ALL ^ E_DEPRECATED);
require_once("../dbconfig.php");

if (!isset($_POST["username"]) || empty($_POST["username"])){
	exit("Missing or empty username");
}

if (!isset($_POST["password"]) || empty($_POST["password"])){
	exit("Missing or empty password");
}

$username = $_POST["username"];
$password = $_POST["password"];

mysql_connect($DB_HOST, $DB_USER, $DB_PASS);
mysql_select_db($DATABASE);

$username = mysql_real_escape_string($username);

$row = mysql_fetch_row(mysql_query("SELECT passhash from users WHERE username = '$username'"));
if (password_verify($password, $row[0])){
	$token = time().uniqid("", true).$username;
	$hash = password_hash($token, PASSWORD_DEFAULT);
	mysql_query("UPDATE users SET tokenhash = '$hash' WHERE username = '$username'");

	echo $token;
}
else {
	echo "Invalid username or password.";
}
?>