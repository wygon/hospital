<?php
session_start();

require_once __DIR__ . '/../helpers/constants.php';
require_once __DIR__ . '/../helpers/functions.php';

$visitId = $_GET['id'] ?? null;

$destination = $_GET['destination'] ?? '';

if (!$visitId) {
    $visitId = $_SESSION['active_visitId'] ?? -1;
}

if ($visitId) {
    $visitExist = $_SESSION['active_visitId'] ?? -1;

    if ($destination == 'start') {
        $destination = 'visit_details';
    } else if ($destination == 'edit') {
        $destination = 'visit_edit';
    }
    
    $postParams = [];

    if ($visitExist == -1 || $visitExist == $visitId) {
        $_SESSION['active_visitId'] = $visitId;
        // header("Location: ". $destination .".php?id=" . $visitId);
        $see = $_GET['see'] ?? '';
        if (!empty($see)) {
            $postParams = [READONLY_W => $see];
        }
        $location = $destination . ".php";
        // header("Location: ". $destination . ".php" . $see);
    } else {
        $_SESSION['active_visitId'] = $visitExist;
        // $location = $destination . ".php?id=" . $visitExist . "&info=otherVisitIsActive";
        $location = $destination . ".php";
        $info = OTHER_SESION_ACTIVE;
        $postParams = [
            ID => $visitExist, 
            INFO => $info
        ];
    }

    postTo($location, $postParams);
}
?>