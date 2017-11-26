<?php
if(!isset($_SESSION['admin'])){
    header("Location: index.php");
    exit;
}