<?php
if(!isset($_SESSION['username'])){
    header("Location: loginpage.php");
    exit;
}

//in case of some username set
$username = htmlspecialchars($_SESSION['username']);
if(!preg_match("/[a-zA-Z0-9]{4,60}/", $username)){
    header("Location: logout.php");
    exit;
}