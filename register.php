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

$stmnt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmnt->bind_param("s", $username);
$stmnt->execute();
$result = $stmnt->get_result();
if($result->num_rows > 0){
    //user exists
    header("Location: loginpage.php?status=user_exists");
    exit;
}

$salt = random_bytes(16);
$hash = hash_pbkdf2("sha256", $password, $salt, 1000, 16);
$accountNUM = "";
for($i = 0; $i < 26; $i++){
    $accountNUM .= strval(rand(0,9));
}
$value = "0";

$stmnt = $conn->prepare("INSERT INTO users VALUES (?, ?, ?, ?, ?)");
$stmnt->bind_param("sssss", $username, $salt, $hash, $value, $accountNUM);
$stmnt->execute();

$conn->close();

header("Location: loginpage.php?status=success_register");
exit;