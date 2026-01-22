<?php 
    session_start();
    require_once("helpers/constants.php");
    require_once("helpers/functions.php");

    if (isset($_SESSION['user_id']))
    {
        if($_SESSION['role'] == 'doctor' || $_SESSION['role'] == 'patient'){
            postTo("/hospital/dashboard.php", [INFO => LOGGED_IN]);
            exit;
        }
    }

    postTo('/hospital/login.php', [INFO => NOT_LOGGED_IN]);
    exit;
?>