<html>
<head>
<title>Pc Accessories Shopping Mall</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="myweb.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">
<div class="header">
  <h1>PC Accessories Shopping Mall</h1>
  <p>A <b>Shopping Mall</b> website created by <a href="http://facebook.com/">me</a>.</p>
  <img src="img/scroll1.png" width=10%>
</div>

<div class="navbar">
  <a href="index.php">Home</a>
  <a href="new.php">New Procduct</a>
  <a href="s2.php">Second Hand</a>
  <button class="open-button" onclick="openForm()">Login</button>

<div class="form-popup" id="myForm">
  <form method="get" action="login.php" class="form-container">
    <h1>Login</h1>

    <label for="email"><b>User</b></label>
    <input type="text" placeholder="Enter your user" name="username">

    <label for="psw"><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="password">

    <button type="submit" class="btn">Login</button>
    <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
  </form>
</div>

<script>
function openForm() {
  document.getElementById("myForm").style.display = "block";
}

function closeForm() {
  document.getElementById("myForm").style.display = "none";
}
</script>

<button class="open-form" onclick="openRes()">Register</button>

<div class="form" id="register">
  <form method="post" class="form-container" name="register">
  <h2>Register Form</h2>
  <div class="input-container">
    <i class="fa fa-user icon"></i>
    <input class="input-field" type="text" placeholder="Username" name="usn">
  </div>

  <div class="input-container">
    <i class="fa fa-envelope icon"></i>
    <input class="input-field" type="text" placeholder="Email" name="email">
  </div>

  <div class="input-container">
    <i class="fa fa-key icon"></i>
    <input class="input-field" type="password" placeholder="Password" name="psw">
  </div>

  <input type="submit" class="btn" value="Register" name="register">
  <button type="button" class="btn cancel" onclick="closeRes()">Close</button>
</form>
</div>
<script>
function openRes() {
  document.getElementById("register").style.display = "block";
}

function closeRes() {
  document.getElementById("register").style.display = "none";
}
</script>
  <button class="right" onclick="window.location.href='register.html'"><b>Register or Login</b></button>
  <button class="right" onclick="window.location.href='cart.html'"><b>Cart</b></button>
</div>
<link rel="stylesheet" href="login.css">
<style>
header{padding:200px}
</style>
</head>
<body>
<header style="background-color:white"></header>
<div class="footer">
<div class="row">
  <div class="column">
    <div class="card">
      <img src="img/img_avatar.png" alt="avatar" class="avatar" style="width:100%">
      <div class="container">
        <h2>Mai Xuan Nghia</h2>
        <p class="title">Creater</p>
        <p>Demo for submition assignment</p>
        <p>nghiamxgd18432@fpt.edu.com</p>
        <p><button class="button">Contact</button></p>
      </div>
    </div>
  </div>

  <div class="column">
    <div class="card">
      <div class="container">
        <h2>Our shop in Vietnam</h2>
        <a class="ta" href="#">At Hà Nội>></a>
		124 Hoang Hoa Tham, Cau Giay, Ha Noi.
		<br><br>
		<a  class="ta" href="#">At Đà Nẵng>></a>
		324 Phan Chau Trinh, Hai Chau, Da Nang
		<br><br>
		<a class="ta" href="#">At Sài Gòn>></a>
		130 Le Thanh Tong, Binh Thanh, Sai Gon
        <br><br>
		<a class="ta" href="#">At Cần Thơ>></a>
		2nd Floor, Complex Building, Ngo Van, Can Tho
		<style>
		.ta{
  display: block;
  color: white;
  text-align: center;
  padding: 14px 20px;
  text-decoration: none;
}
		</style>
      </div>
    </div>
  </div>
  <div class="column">
    <div class="card">
      <a href="http://facebook.com" class="fa fa-facebook"></a>Our Facebook<br>
	  <a href="http://twitter.com" class="fa fa-twitter"></a>Our Twitter<br>
	  <a href="http://mail.google.com" class="fa fa-google"></a>Our Gmail<br>
	  <a href="http://reddit.com" class="fa fa-reddit"></a>Our Reddit<br>
	  <b style="color:black">Phone Number:</b> it will be updated late!
    </div>
  </div>
</div>
	<h3 align="center">© Copyright 2019. Assignment 2: Create a shopping mall Website</h3>
</div>
</body>
</html>

<?php

if($_POST)
{

if(isset($_POST['register']))
{
        register();//it will call a function
    }
}

    function register()

    {
              //STEP2. Secure way to build connection
$file = parse_ini_file("config.ini");
$host = trim($file["DB_SERVER"]);
$usernm = trim($file["DB_USERNAME"]);
$pass = trim($file["DB_PASSWORD"]);
$name = trim($file["DB_NAME"]);
$port = trim($file["DB_PORT"]);

$username = $_POST['usn'];
$email= $_POST['email'];
$password = $_POST['psw'];
//include access.php to call from function from this file.
require("access.php");
$access = new access($host, $usernm, $pass, $name, $port);
$access->connect();

//STEP 3. Create user in Access.php
$register = $access->createUser($username, $email, $password);
    }

?>
