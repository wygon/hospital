<?php 

    if (session_status() === PHP_SESSION_NONE) {
        header("Location: login.php");
    }
    else if(!isset($_SESSION['user_id'])) {
        header("Location: login.php");
    }

?>
