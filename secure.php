<?php
session_start();
require_once("./lib/dbhandler.php");

function login($username, $password){
	if (checkUser($username, $password)){
		$_SESSION['user']=$username;
		$_SESSION['loggedIn']=true;
		return true;
	}
	return false;
}

function logOut(){
	session_destroy();
}

function isLoggedIn(){
	if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn']){
		if(getUserData($_SESSION['user'])){
			return true;
		}
	}
	return false;
}

function checkUser($username, $password){
	$temp = getUserData($username);
	if ($temp['password'] == hashPlaintext($password) && strlen($password) > 0){
		return true;
	} else {
		return false;
	}
}

function hashPlaintext($pwd){
	return crypt($pwd, "UMBQMybimHrDWkqFXnhrfBEKcdz4X3dKAuKnHb");
}

function changePasswordForUser($username, $oldpassword, $newpassword){
	$temp = getUserData($username);
	if(checkUser($username, $oldpassword)){
		return changePassword($username, hashPlaintext($newpassword));
	} else {
		return false;
	}
}

function init(){
	// intially insert admin user
	echo insertUser("admin", "Administrator", hashPlaintext("1234"), "", "admin");
}

if(isset($_POST['username']) && isset($_POST['password'])) {
	login($_POST['username'], $_POST['password']);
}
if(isset($_GET['logout'])){
	logOut();
	header("location:/admin");
	die();
}
if(isset($_POST['oldpass']) && isset($_POST['newpass']) && isset($_POST['newpass2'])){
	if ($_POST['newpass']==$_POST['newpass2']){
		changePasswordForUser($_SESSION['user'], $_POST['oldpass'], $_POST['newpass2']);
	}
	header("location:/admin");
	die();
}
if(isset($_GET['deleteuser'])){
	if(getUserData($_GET['deleteuser'])){
		removeUser($_GET['deleteuser']);
	}
	header("location:/admin");
	die();
}

if(isLoggedIn()){
// do nothing
} else {
	?>
<html>  
<head>  
	<meta charset="utf-8">  
    <title>File Sharing</title>
    <script src="./lib/jq.js"></script>
    <link rel="stylesheet" href="./lib/styles.css" />
</head>  
<body>
<div id="login">
	<form class="form-container" action="/admin" method="post">
		<div class="form-title"><h2>Login</h2></div>
		<div class="form-title">Username</div>
		<input class="form-field" type="text" name="username" /><br />
		<div class="form-title">Password</div>
		<input class="form-field" type="password" name="password" /><br />
		<div class="submit-container">
			<input class="submit-button" type="submit" value="Submit" />
		</div>
	</form>
</div>
</body>
</html>
	<?php
	die();
}
?>