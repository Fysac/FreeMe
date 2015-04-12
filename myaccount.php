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

$account = mysql_query("SELECT name, username, email FROM users WHERE id = '$id'");

while ($row = mysql_fetch_assoc($account)) {
    echo $row["name"]."\n".$row["username"]."\n".$row["email"];
}

?>