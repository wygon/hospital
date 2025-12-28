<?php session_start();

    if (isset($_SESSION['user_id']))
    {
        if($_SESSION['role'] == 'doctor'){
            header('Location: /hospital/doctor/dashboard.php?info=loggedin');
            exit;
        }
        else if($_SESSION['role'] == 'patient'){
            header('Location: /hospital/patient/dashboard.php?info=loggedin');
            exit;
        }
    }

    header('Location: hospital/login.php');
    exit;

?>