<?php 

    function setMessage($info){        
        
        if(!isset($info) || empty($info)) return;
        global $ERROR_INFO;
        global $SUCCESS_INFO;
        global $INFORMATION_INFO;

        switch($info){
            case OTHER_SESION_ACTIVE:
                $ERROR_INFO = 'Desired visit cant be started because: Other visit is active. Close active visit to start next! <a href="/hospital/doctor/visit_close.php">Close active visit</a>' ;
                break;
            case VISIT_NO_EXIST:
                break;
            case VISIT_CLOSE:
                $SUCCESS_INFO = 'Visit ended! Well done ' . $_SESSION['name'] . '!';
                break;
            case LOGGED_IN:
                $INFORMATION_INFO = 'Welcome back ' . $_SESSION[USER_FULLNAME] . "!";
                break;
        }

        if($info == OTHER_SESION_ACTIVE){
            $ERROR_INFO = 'Desired visit cant be started because: Other visit is active. Close active visit to start next! <a href="/hospital/doctor/visit_close.php">Close active visit</a>' ;
            return;
        }
        
        if($info == VISIT_NO_EXIST){
            $ERROR_INFO = 'Desired visit cant be started because: Other visit is active. Close active visit to start next! <a href="/hospital/doctor/visit_close.php">Close active visit</a>' ;
            return;
        }



    }

?>