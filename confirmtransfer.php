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

/*$toNUM = htmlspecialchars($_POST['to']);
if(!preg_match("/[0-9]{26}/", $toNUM)){
    header("Location: newtransfer.php?status=error");
    exit;
}*/
//XSS
$toNUM = $_POST['to'];

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
    header("Location: newtransfer.php?status=insufficient_funds");
    exit;
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
        form{
            text-align: center;
        }
        form h2{
            margin-bottom: 5px;
        }
        form input{
            width: 50%;
            margin: 20px 24%;
            border: 2px solid black;
            line-height: 1.5;
            font-size: 20pt;
            text-align: center;
        }
        form button{
            background-color: white;
            font-size: 20pt;
            width: 24%;
            margin-top: 50px;
            cursor: pointer;
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
            Wyślij przelew
        </h2>
        <a class="button logout" href="logout.php">
            Wyloguj się
        </a>
        <a class="button back" href="index.php">
            Wróć
        </a>
    </header>
    <form method="post">
        <h2 class="text-center">From</h2>
        <input name="from" type="text" title="from" value="<?php echo $accountNUM?>" disabled>
        <h2 class="text-center">To</h2>
        <input name="to" type="text" title="to" value="<?php echo $toNUM?>" readonly>
        <h2 class="text-center">Value</h2>
        <input name="value" type="text" title="value" value="<?php echo $value?>" readonly>
        <h2 class="text-center">Title</h2>
        <input name="title" type="text" title="title" value="<?php echo $title?>" readonly>
        <button class="button" title="confirm" type="submit" formaction="sendtransfer.php">Potwierdź</button>
        <button class="button" title="confirm" type="submit" formaction="newtransfer.php">Edytuj</button>
    </form>
</div>
</body>
</html>