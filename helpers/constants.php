<?php
//DB_DATA

$settingsPath = __DIR__ . '/../appsettings.cfg';

if (file_exists($settingsPath)) {
    $lines = file($settingsPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Rozdziel klucz=wartość
        list($name, $value) = explode('=', $line, 2);

        if (!defined($name)) {
            define($name, $value);
        }
    }
}

// define('DB_HOST', 'localhost');
// define('DB_USER', 'root');
// define('DB_PASS', '');
// define('DB_NAME', 'hospital');

//SESSION_KEYS
define('USER_ID', 'user_id');
define('USER_FULLNAME', 'user_fullname');

define('EMPLOYEE', 'doctor');
define('USER', 'patient');

//
define('PAGE_OPEN_MODE', 'open_mode');
define('EDIT', 'edit');
define('READONLY_W', 'see');

define('ID', 'id');


define('INFO', 'info');

//INFO TYPES
//errors
define('OTHER_SESION_ACTIVE', 'otherVisitIsActive');
define('VISIT_NO_EXIST', 'novisit');
define('DOCTOR_BUSY', 'doctor_busy');
define('ERROR_DOWNLOAD', 'error_download');
define('NOT_LOGGED_IN', 'not_logged_in');
define('WRONG_LOGIN_OR_PASSWORD', 'WRONG_LOGIN_OR_PASSWORD');
define('ENTERED_WRONG_DATA', 'entered_wrong_data');
define('DOCTOR_MUST_SPECIFY_SPECIALIZATION', 'DOCTOR_MUST_SPECIFY_SPECIALIZATION');

//success
define('VISIT_CLOSE', 'visit_close');
define('VISIT_ADDED', 'visit_added');
define('POSITIVE_DOWNLOAD', 'positive_download');

//INFO
define('LOGGED_IN', 'logged_in');



?>