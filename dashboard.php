<?php 
    session_start();

    if($_SESSION['role'] == 'doctor')
    {
        require 'doctor/dashboard.php';   
    }
    else if($_SESSION['role'] == 'patient')
    {
        require 'patient/dashboard.php';   
    }
?>