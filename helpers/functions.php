<?php
require_once __DIR__ . "/../helpers/messagesManager.php";
require_once __DIR__ . "/../helpers/database.php";

function validateFormItems(...$items)
{
    foreach ($items as $item) {
        if (empty($item)) 
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

function setIfEmpty(&$variable, $setTO = '')
{
    if (empty($variable)) {
        $variable = $setTO;
    }
}

function postTo($location, $hiddenParams = [])
{
    $hiddenParamsString = '';
    if(!empty($hiddenParams)){

        foreach ($hiddenParams as $name => $value)
            $name = htmlspecialchars($name ?? '') ?? '';
        $value = htmlspecialchars($value ?? '') ?? '';
        $hiddenParamsString .= "<input hidden name='$name' value='$value'/> \n";
        
    }
    echo "

<form id='postTo' method='post' action='$location'> $hiddenParamsString </form>


<script>
    document.getElementById('postTo').submit();
</script>

";

exit;
}
