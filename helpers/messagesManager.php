<?php

function setMessage($info)
{

    if (!isset($info) || empty($info)) return;
    global $ERROR_INFO;
    global $SUCCESS_INFO;
    global $INFORMATION_INFO;

    switch ($info) {
        case OTHER_SESION_ACTIVE:
            $ERROR_INFO = 'Desired visit cant be started because: Other visit is active. Close active visit to start next! <a href="/hospital/doctor/visit_close.php">Close active visit</a>';
            break;
        case VISIT_NO_EXIST:
            break;
        case VISIT_CLOSE:
            $SUCCESS_INFO = 'Visit ended! Well done ' . $_SESSION['name'] . '!';
        case VISIT_ADDED:
            $SUCCESS_INFO = 'Visit added!';
            break;
        case LOGGED_IN:
            $INFORMATION_INFO = 'Welcome back ' . $_SESSION[USER_FULLNAME] . "!";
            break;
        case DOCTOR_BUSY:
            $ERROR_INFO = 'Doctor is busy in this time. Choose another hour.';
            break;
        case POSITIVE_DOWNLOAD:
            $SUCCESS_INFO = 'Succesfully downloaded prescription. Thanks!';
            break;
        case ERROR_DOWNLOAD:
            $ERROR_INFO = 'Error existed while downloading prescription! Try again!';
            break;
        case NOT_LOGGED_IN:
            $ERROR_INFO = 'You need to log in!';
            break;
        case WRONG_LOGIN_OR_PASSWORD:
            $ERROR_INFO = "Wrong login or password! Try again!";
            break;
    }

    if ($info == OTHER_SESION_ACTIVE) {
        $ERROR_INFO = 'Desired visit cant be started because: Other visit is active. Close active visit to start next! <a href="/hospital/doctor/visit_close.php">Close active visit</a>';
        return;
    }

    if ($info == VISIT_NO_EXIST) {
        $ERROR_INFO = 'Desired visit cant be started because: Other visit is active. Close active visit to start next! <a href="/hospital/doctor/visit_close.php">Close active visit</a>';
 
        return;
    }
}