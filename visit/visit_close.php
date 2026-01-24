<?php 
    require_once __DIR__ . "/../helpers/constants.php";
    require_once __DIR__ . "/../helpers/functions.php";
    session_start();

    unset($_SESSION['active_visitId']);
    
    if($_GET['from'] === 'cancel'){
        postTo("/hospital/dashboard.php");
        exit;
    }

    postTo("/hospital/dashboard.php", [INFO => VISIT_CLOSE]);
    exit;
?>