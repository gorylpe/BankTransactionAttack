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

$conn->close();
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
        .buttons{
            width: 100%;
        }
        .buttons a{
            display: inline-block;
            width: 39%;
            padding: 2%;
            margin: 2%;
            text-align: center;
            font-size: 30pt;
            border: 1px solid black;
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
                Wybierz co chcesz zrobić
            </h2>
            <a class="button logout" href="logout.php">
                Wyloguj się
            </a>
            <h2>Stan konta: <?php echo $accountVALUE;?>zł</h2>
            <h2>Numer konta: <?php echo $accountNUM;?></h2>
        </header>
        <div class="buttons">
            <a href="transfers.php">
                Historia przelewów
            </a>
            <a href="newtransfer.php">
                Wyślij przelew
            </a>
        </div>
    </div>
</body>
</html>