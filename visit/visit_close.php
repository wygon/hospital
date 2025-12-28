<?php 
    session_start();

    unset($_SESSION['active_visitId']);
    
    header("Location: /hospital/dashboard.php?info=visit_closed");
    exit;
?>