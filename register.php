<?php
error_reporting(E_ALL ^ E_DEPRECATED);
require_once("../dbconfig.php");

if (!isset($_POST["name"]) || empty($_POST["name"])){
	exit("Missing or empty real name");
}

if (!isset($_POST["username"]) || empty($_POST["username"])){
	exit("Missing or empty username");
}

if (!isset($_POST["email"]) || empty($_POST["email"])){
	exit("Missing or empty email");
}

if (!isset($_POST["password"]) || empty($_POST["password"])){
	exit("Missing or empty password");
}

if (!isset($_POST["confirm"]) || empty($_POST["confirm"])){
	exit("Missing or empty password confirmation");
}

$name = $_POST["name"];
$username = $_POST["username"];
$email = $_POST["email"];
$password = $_POST["password"];
$confirm = $_POST["confirm"];

if (!preg_match('/^[A-Za-z0-9_]+$/', $username)){
	exit("Username can only be alphanumeric with underscores");
}

if (strlen($username) > 20 || strlen($username) < 3){
	exit("Username must be at least 3 characters and at most 15 characters");
}

if (strcmp($password, $confirm) != 0){
	exit("Passwords do not match");
}

mysql_connect($DB_HOST, $DB_USER, $DB_PASS);
mysql_select_db($DATABASE);

$username = mysql_real_escape_string($username);
$email = mysql_real_escape_string($email);

$hash = password_hash($password, PASSWORD_DEFAULT);

$is_username_taken = mysql_query("SELECT username from users WHERE username = '$username'");
if (mysql_num_rows($is_username_taken) > 0){
	exit("Username already taken");
}

$is_email_taken = mysql_query("SELECT email from users WHERE email = '$email'");
if (mysql_num_rows($is_email_taken) > 0){
	exit("Email already taken");
}

if (mysql_query("INSERT INTO users (name, username, email, passhash) VALUES ('$name', '$username', '$email', '$hash')")){
	$row = mysql_fetch_row(mysql_query("SELECT id from users WHERE username = '$username'"));
	$id = $row[0];
	mysql_query("INSERT INTO freedom (userid, free, timeout, status) VALUES ('$id', 'false', '0', '')");
	echo "Welcome to FreeMe, ".$name."!";
}
else {
	exit(mysql_error());
}
mysql_close();

?>
