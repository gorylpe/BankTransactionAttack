<?php
require_once "includes/start.php";
require_once "includes/loggedin.php";

require_once "includes/dbdata.php";

global $username;

$conn = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);

$stmnt = $conn->prepare("SELECT accountNUM FROM users WHERE username = ?");
$stmnt->bind_param("s", $username);
$stmnt->execute();

$result = $stmnt->get_result();
$row = $result->fetch_row();
$accountNUM = $row[0];

$conn->close();

$toNUM = "";
if(isset($_POST['to'])) {
    $toNUM = htmlspecialchars($_POST['to']);
}
$value = "";
if(isset($_POST['value'])) {
    $value = htmlspecialchars($_POST['value']);
}
$title = "";
if(isset($_POST['title'])) {
    $title = htmlspecialchars($_POST['title']);
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
        .error{
            background-color: red;
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
        <?php
        if(isset($_GET['status'])){
            switch(htmlspecialchars($_GET['status'])){
                case "insufficient_funds":
                    echo "<h2 class='text-center error'>NIEWYSTARCZAJĄCE ŚRODKI</h2>";
                    break;
                case "error":
                    echo "<h2 class='text-center error'>NIEOCZEKIWANY BŁĄD</h2>";
                    break;
            }
        }
        ?>
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
        <input name="to" type="text" title="to" maxlength="26" pattern="[0-9]{26}" value="<?php if($toNUM !== "") echo $toNUM;?>">
        <h2 class="text-center">Value</h2>
        <input name="value" type="text" title="value" maxlength="100" pattern="[0-9]{1,98}(.[0-9]{1,2})?" value="<?php if($value !== "") echo $value;?>">
        <h2 class="text-center">Title</h2>
        <input name="title" type="text" title="title" maxlength="100" pattern="[a-zA-Z 0-9]+" value="<?php if($title !== "") echo $title;?>">
        <button class="button" title="confirm" type="submit" formaction="confirmtransfer.php">Wyślij</button>
    </form>
</div>
</body>
</html>