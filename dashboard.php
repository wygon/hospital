<?php 
    session_start();

    if($_SESSION['role'] == 'doctor')
    {
        include 'doctor/dashboard.php';   
    }
    else if($_SESSION['role'] == 'patient')
    {
        include 'patient/dashboard.php';   
    }
?>