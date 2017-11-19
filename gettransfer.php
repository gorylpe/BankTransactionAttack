<?php
require_once "includes/start.php";
require_once "includes/loggedin.php";

require_once "includes/dbdata.php";
require_once "includes/transfer.php";

global $username;

$conn = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);

$stmnt = $conn->prepare("SELECT accountNUM FROM users WHERE username = ?");
$stmnt->bind_param("s", $username);
$stmnt->execute();

$result = $stmnt->get_result();
$row = $result->fetch_row();
$accountNUM = $row[0];

$error = "";
$id = htmlspecialchars($_GET['id']);
if(!preg_match("/[0-9]+/", $id)){
    $error = "ERROR";
}

$stmnt = $conn->prepare("SELECT * FROM transfers WHERE id = ?");
$stmnt->bind_param("s", $id);
$stmnt->execute();
$result = $stmnt->get_result();
$row = $result->fetch_row();

$conn->close();

$transfer = new Transfer();
$transfer->id = $row[0];
$transfer->from = $row[1];
$transfer->to = $row[2];
$transfer->date = $row[3];
$transfer->value = $row[4];
$transfer->title = $row[5];

if($transfer->from != $accountNUM && $transfer->to != $accountNUM){
    $error = "ERROR";
}

?>

<html>
<head>
    <title>Bank Przestrzelskich</title>
    <link rel="stylesheet" href="includes/style.css">
    <style>
        .logout{
            position: absolute;
            right: 20px;
            top: 20px;
        }
        .back{
            position: absolute;
            left: 20px;
            top: 20px;
        }
        .correct{
            background-color: green;
        }
        .transfer{
            font-family: "Courier New", Courier, monospace;
            position: relative;
            border: 1px solid black;
            padding: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <header>
        <h1 class="text-center">
            Witaj <?php echo $username;?>
        </h1>
        <h2 class="text-center">
            Przelew ID <?php echo $id;?>
        </h2>
        <?php
        if(isset($_GET['status'])){
            switch(htmlspecialchars($_GET['status'])){
                case "send_success":
                    echo "<h2 class='text-center correct'>POMYŚLNIE ZREALIZOWANO PRZELEW</h2>";
                    break;
            }
        }
        ?>
        <a class="button logout" href="logout.php">
            Wyloguj się
        </a>
        <a class="button back" href="transfers.php">
            Wróć
        </a>
    </header>
    <?php
    if($error === "") {
        echo "
    <div class='transfer'>
        <h3>Title: " . $transfer->title . "</h3>
        <h3>From : " . $transfer->from . "</h3>
        <h3>To . : " . $transfer->to . "</h3>
        <h3>Date: " . $transfer->date . "</h3>
        <h3>Value: " . $transfer->value . "zł</h3>
    </div>";
    } else {
        echo "
    <div class='transfer'>
        <h3>BŁĄD</h3>
    </div>";
    }
    ?>
</div>
</body>
</html>
