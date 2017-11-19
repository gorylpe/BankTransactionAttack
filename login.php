<?php
require_once "includes/start.php";
require_once "includes/dbdata.php";

$conn = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);
$conn->set_charset('latin1');

$username = htmlspecialchars($_POST['username']);
$password = htmlspecialchars($_POST['password']);

if(!preg_match("/[a-zA-Z0-9]{4,60}/", $username)){
    header("Location: loginpage.php?status=user_wrong");
    exit;
}

if(!preg_match("/[a-zA-Z0-9]{4,60}/", $password)){
    header("Location: loginpage.php?status=password_wrong");
    exit;
}

$stmnt = $conn->prepare("SELECT salt, hash FROM users WHERE username = ?");
$stmnt->bind_param("s", $username);
$stmnt->execute();
$result = $stmnt->get_result();
$row = $result->fetch_row();

$salt = $row[0];
$actual_hash = $row[1];

$hash = hash_pbkdf2("sha256", $password, $salt, 1000, 16);

$conn->close();

if($hash == $actual_hash){
    $_SESSION['username'] = $username;
    header("Location: index.php");
    exit;
}

header("Location: loginpage.php?status=login_error");
exit;
