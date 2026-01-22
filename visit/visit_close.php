<?php 
    require_once __DIR__ . "/../helpers/constants.php";
    require_once __DIR__ . "/../helpers/functions.php";
    session_start();

    unset($_SESSION['active_visitId']);
    
    postTo("/hospital/dashboard.php", [INFO => VISIT_CLOSE]);
    exit;
?>