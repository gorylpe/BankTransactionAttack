<?php
require_once "includes/start.php";
require_once "includes/loggedin.php";
require_once "includes/admin.php";

require_once "includes/dbdata.php";

$conn = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);

$id = htmlspecialchars($_GET['id']);
$stmnt = $conn->prepare("UPDATE transfers SET accepted = TRUE WHERE id = ?");
$stmnt->bind_param("s", $id);
$stmnt->execute();

$conn->close();

header("Location: transfersaccept.php");
exit;