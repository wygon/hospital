<?php 
    if(str_contains($_SERVER['PHP_SELF'], "login"))
        exit;

    if (session_status() === PHP_SESSION_NONE) {
        header("Location: login.php");
        exit;
    }
    else if(!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }

?>
