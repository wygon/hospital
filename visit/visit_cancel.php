<?php 

require_once __DIR__ . '/../helpers/functions.php';
require_once __DIR__ . '/../helpers/constants.php';

session_start();
if($_SERVER['REQUEST_METHOD'] === 'GET'){

$connection = connectDB();

$visitId = $_GET['id'] ?? -1;

if($visitId != -1){
    $sql = "UPDATE `visits` SET Status = 'cancelled' WHERE Id = ?";
    execute($connection, $sql, [$visitId]);
}
}

postTo("/hospital/dashboard.php");

?>