<?php
require_once "includes/start.php";
require_once "includes/loggedin.php";

require_once "includes/dbdata.php";

global $username;

$conn = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);

$stmnt = $conn->prepare("SELECT accountNUM, accountVALUE FROM users WHERE username = ?");
$stmnt->bind_param("s", $username);
$stmnt->execute();

$result = $stmnt->get_result();
$row = $result->fetch_row();
$accountNUM = $row[0];
$accountVALUE = $row[1];

$toNUM = htmlspecialchars($_POST['to']);
if(!preg_match("/[0-9]{26}/", $toNUM)){
    header("Location: newtransfer.php?status=error");
    exit;
}
$value = htmlspecialchars($_POST['value']);
if(!preg_match("/[0-9]{1,98}(.[0-9]{1,2})?/", $value)){
    header("Location: newtransfer.php?status=error");
    exit;
}
$title = htmlspecialchars($_POST['title']);
if(!preg_match("/[a-zA-Z 0-9]+/", $title)) {
    header("Location: newtransfer.php?status=error");
    exit;
}

if(bccomp($accountVALUE, $value, 2) === -1){
    header("Location: newtransfer.php?status=error");
    exit;
}

$stmnt = $conn->prepare("SELECT username, accountVALUE FROM users WHERE accountNUM = ?");
$stmnt->bind_param("s", $toNUM);
$stmnt->execute();
$result = $stmnt->get_result();
$toUSERNAME = "";
$toVALUE = "";
if($result->num_rows > 0){
    $row = $result->fetch_row();
    $toUSERNAME = $row[0];
    $toVALUE = $row[1];
}

$conn->autocommit(FALSE);

$newAccountValue = bcsub($accountVALUE, $value, 2);
$stmnt = $conn->prepare("UPDATE users SET accountVALUE = ? WHERE username = ?");
$stmnt->bind_param("ss", $newAccountValue, $username);
$stmnt->execute();

if($toUSERNAME !== ""){
    $newToValue = bcadd($toVALUE, $value, 2);
    $stmnt = $conn->prepare("UPDATE users SET accountVALUE = ? WHERE username = ?");
    $stmnt->bind_param("ss", $newToValue, $toUSERNAME);
    $stmnt->execute();
}

$stmnt = $conn->prepare("INSERT INTO transfers VALUES (DEFAULT, ?, ?, CURRENT_TIMESTAMP , ?, ?)");
$stmnt->bind_param("ssss", $accountNUM, $toNUM, $value, $title);
$stmnt->execute();

$conn->commit();
$conn->autocommit(true);

header("Location: gettransfer.php?id=" . $stmnt->insert_id . "&status=send_success");
$conn->close();
exit;
