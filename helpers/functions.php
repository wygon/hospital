<?php

function isEmpty($item)
{
    if ($item == '') {
        return false;
    }
    return true;
}

function validateFormItems(...$items)
{
    foreach ($items as $item) {
        if (isEmpty($item))
            return false;
    }
    return true;
}

function validateCanSeePage($userType)
{
    if (session_status() === PHP_SESSION_NONE) {
        $location = 'Location: ' . __DIR__ . '/../login.php';
        header($location);
    } else if (!isset($_SESSION['user_id'])) {
        $location = 'Location: ' . __DIR__ . '/../login.php?from=invalidLogin';
        header($location);
    } else if (isset($userType) && $_SESSION['role'] != $userType) {
        $location = 'Location: ' . __DIR__ . '/../login.php?from=notAllowed';
        header($location);
    }
}

function getBgColorBasedOnStatus($status)
{
    if (empty($status))
        return '';
    switch ($status) {
        case 'completed':
            return 'bg-success';
            break;
        case 'scheduled':
            return 'bg-warning';
            break;
        case 'cancelled':
            return 'bg-danger';
            break;
        default:
            return '';
            break;
    }
}

function removeFromTempList($command, $collection, $successInfo, $header)
{
    if (isset($_POST[$command])) {
        $idToRemove = key($_POST[$command]);

        if (isset($_SESSION[$collection])) {
            foreach ($_SESSION[$collection] as $key => $disease) {
                if ($disease['Id'] == $idToRemove) {
                    $item = $_SESSION[$collection][$key];
                    unset($_SESSION[$collection][$key]);
                    $_SESSION[$collection] = array_values($_SESSION[$collection]);
                    global $SUCCESS_INFO;
                    $SUCCESS_INFO = $successInfo;
                    break;
                }
            }
        }

        header("Location: " . $header);
        exit;
    }
}

?>

