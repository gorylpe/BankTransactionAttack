<?php
require_once "includes/start.php";

if(isset($_SESSION['username'])){
    header("Location: index.php");
    exit;
}

?>

<html>
<head>
    <title>Bank Przestrzelskich - Logowanie</title>
    <link rel="stylesheet" href="includes/style.css">
    <style>
        .error{
            background-color: red;
        }
        .correct{
            background-color: green;
        }
        form{
            text-align: center;
        }
        form h2{
            margin-bottom: 5px;
        }
        #login input{
            width: 50%;
            margin: 20px 24%;
            border: 2px solid black;
            line-height: 1.5;
            font-size: 20pt;
        }
        #login button{
            background-color: white;
            font-size: 20pt;
            width: 24%;
            margin-top: 50px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container" id="login">
        <h1 class="text-center">
            Witaj w Banku Przestrzelskich!
        </h1>
        <h2 class="text-center">Zaloguj się</h2>
        <?php
            if(isset($_GET['status'])){
                switch(htmlspecialchars($_GET['status'])){
                    case "success_register":
                        echo "<h2 class='text-center correct'>ZAREJESTROWANO</h2>";
                        break;
                    case "user_exists":
                        echo "<h2 class='text-center error'>UŻYTKOWNIK JUŻ ISTNIEJE!</h2>";
                        break;
                    case "user_wrong":
                        echo "<h2 class='text-center error'>BŁĘDNA NAZWA UŻYTKOWNIKA</h2>";
                        break;
                    case "password_wrong":
                        echo "<h2 class='text-center error'>BŁĘDNE HASŁO</h2>";
                        break;
                    case "login_error":
                        echo "<h2 class='text-center error'>LOGOWANIE NIEUDANE</h2>";
                        break;
                }
            }
        ?>
        <form method="post">
            <h2 class="text-center">Login</h2>
            <input name="username" type="text" title="username">
            <h2 class="text-center">Password</h2>
            <input name="password" type="password" title="password">
            <button class="button" title="register" type="submit" formaction="register.php">Register</button>
            <button class="button" title="login" type="submit" formaction="login.php">Login</button>
        </form>
    </div>
</body>
</html>
