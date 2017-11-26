<?php
require_once "includes/start.php";
require_once "includes/loggedin.php";
require_once "includes/admin.php";

require_once "includes/dbdata.php";
require_once "includes/transfer.php";


global $username;

$transfers = array();

$conn = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);

$result = $conn->query("SELECT * FROM transfers WHERE accepted = FALSE ORDER BY date DESC");

$conn->close();

if($result){
    while($row = mysqli_fetch_array($result)){
        $transfer = new Transfer();
        $transfer->id = $row[0];
        $transfer->from = $row[1];
        $transfer->to = $row[2];
        $transfer->date = $row[3];
        $transfer->value = $row[4];

        array_push($transfers, $transfer);
    }
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
        .transfer{
            font-family: "Courier New", Courier, monospace;
            position: relative;
            border: 1px solid black;
            padding: 10px;
        }
        .accept{
            position: absolute;
            right: 20px;
            top: 20px;
            bottom: 20px;
            border: solid black 1px;
            padding: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <header>
        <h1 class="text-center">
            Lista przelewów niezaakceptowanych
        </h1>
        <h2 class="text-center">
            Wybierz co chcesz zrobić
        </h2>
        <a class="button logout" href="logout.php">
            Wyloguj się
        </a>
        <a class="button back" href="index.php">
            Wróć
        </a>
    </header>
    <div class="transfers">
        <?php
        foreach($transfers as $transfer){
            echo "
    <div class='transfer'>
        <h3>From: " . $transfer->from . "</h3>
        <h3>To: " . $transfer->to . "</h3>
        <h3>Date: " . $transfer->date . "</h3>
        <h3>Value: " . $transfer->value . "zł</h3>
        <a class='accept' href='accepttransfer.php?id=" . $transfer->id . "'>Akceptuj przelew</a>
    </div>
            ";
        }
        ?>
    </div>
</div>
</body>
</html>