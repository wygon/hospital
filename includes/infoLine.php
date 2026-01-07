<?php 
    function printInfo($color, &$text){
            echo '<div class="alert text-center '. $color .'"> <p>';
                echo $text;
            echo '</p></div>';
            $text = '';
    }
    
    global $ERROR_INFO;
    if(!empty($ERROR_INFO)){
        printInfo('alert-danger', $ERROR_INFO);
    }

    global $SUCCESS_INFO;
    if(!empty($SUCCESS_INFO))
    {
        printInfo('alert-success', $SUCCESS_INFO);
    }

    global $INFORMATION_INFO;
    if(!empty($INFORMATION_INFO))
    {
        printInfo('alert-info', $INFORMATION_INFO);
    }

?>