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

$transfers = array();

$stmnt = $conn->prepare("SELECT * FROM transfers WHERE fromNUM = ? OR toNUM = ? ORDER BY date DESC");
$stmnt->bind_param("ss", $accountNUM, $accountNUM);
$stmnt->execute();
$result = $stmnt->get_result();

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
        .details{
            position: absolute;
            right: 20px;
            top: 20px;
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
            Lista przelewów dla konta <?php echo $accountNUM;?>
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
            <h3>To: " . $transfer->to . "</h3>
            <h3>Value: " . $transfer->value . "zł</h3>
            <a class='button details' href='gettransfer.php?id=" . $transfer->id . "'>Szczegóły</a>
        </div>
                ";
            }
        ?>
    </div>
</div>
</body>
</html>