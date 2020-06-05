<?php
// login.php

session_start();

$arr = array();

//STEP 1. Check the variables passing to this file by POST
$username = htmlentities($_REQUEST["username"]);
$password = htmlentities($_REQUEST["password"]);

echo $username;

//STEP2. Secure way to build connection
$file = parse_ini_file("config.ini");
$host = trim($file["DB_SERVER"]);
$usernm = trim($file["DB_USERNAME"]);
$pass = trim($file["DB_PASSWORD"]);
$name = trim($file["DB_NAME"]);
$port = trim($file["DB_PORT"]);

//include access.php to call from function from this file.
require ("access.php");
$access = new access($host, $usernm, $pass, $name, $port);
$access->connect();

//STEP 3. Get user Information
$user = $access->getUser($username);

if (empty($user)) {
	$returnArray["status"] = "403";
	$returnArray["message"] = "User Does not Exist!";
	echo json_encode($returnArray);
	return;
}

else {
//STEP 4. Check if valid password

$secured_password = $user["password"];
//$salt = $user["Salt"];
// check if password is matching with database

$app_login_password = $password;//sha1($password . $salt);
if($secured_password == $app_login_password) {
	$returnArray["status"] = "200";
	$returnArray["message"] = "Logged in Successfully!";
	$returnArray["user"] = $user["Login_ID"];
	$returnArray["pass"] = $user["password"];
	header("location: http://cms.greenwich.edu.vn");
	


     
    // when this page's PHP script is done loading.
  //  header('Location: mainpage.php');
	
}
else {
	$returnArray["status"] = "403";
	$returnArray["message"] = "Incorrect Email ID (or) Password!";
}
}
	$access->disconnect();
	echo json_encode($returnArray);


?>