<?php 
    session_start();

    $visitId = $_GET['id'] ?? null;

    $destination = $_GET['destination'] ?? '';

    if(!$visitId){
        $visitId = $_SESSION['active_visitId'] ?? -1;
    }

    if($visitId){
        $visitExist = $_SESSION['active_visitId'] ?? -1;

        if($destination == 'start'){
            $destination = 'visit_details';
        }
        else if($destination == 'edit'){
            $destination = 'visit_edit';
        }

        if($visitExist == -1 || $visitExist == $visitId){
            $_SESSION['active_visitId'] = $visitId;
            header("Location: ". $destination .".php?id=" . $visitId);
        }
        else{
            $_SESSION['active_visitId'] = $visitExist;
            header("Location: " . $destination . ".php?id=" . $visitExist . "&info=otherVisitIsActive");
        }
    }
?>