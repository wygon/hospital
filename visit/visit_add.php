<?php
    session_start();
    require_once '../helpers/functions.php';
    require_once '../helpers/constants.php';

    validateCanSeePage('patient');

    $connection = connectDB();
    $summary = $_POST['summary'] ?? '';
    $visit_date = $_POST['visit_date'] ?? '';
    $visit_time = $_POST['visit_time'] ?? '';
    $visit_doctor = $_POST['visit_doctor'] ?? '';
    $patient_description = $_POST['patient_description'] ?? '';

    $_SESSION['summary'] = $_POST['summary'] ?? '';
    $_SESSION['visit_date'] = $_POST['visit_date'] ?? '';
    $_SESSION['visit_time'] = $_POST['visit_time'] ?? '';
    $_SESSION['visit_doctor'] = $_POST['visit_doctor'] ?? '';
    $_SESSION['patient_description'] = $_POST['patient_description'] ?? '';

    $sqlDateTime = $visit_date . ' ' . $visit_time . ':00';
    validateFormItems($summary, $visit_date, $visit_time);

    $dtFrom = new DateTime($sqlDateTime);
    $dtFrom->modify('-30 minutes');
    $dtTo = new DateTime($sqlDateTime);
    $dtTo->modify('+30 minutes');
    $dateFrom = $dtFrom->format('Y-m-d H:i:s');
    $dateTo = $dtTo->format('Y-m-d H:i:s');

    $doctorVisitIn30MinTime = querySingle($connection, "
SELECT COUNT(*) as total FROM `visits` WHERE DoctorId = ? AND VisitDate BETWEEN ? AND ?",
    [$visit_doctor, $dateFrom, $dateTo]);

    if($doctorVisitIn30MinTime['total'] > 0){
        // header('Location: /hospital/visit/visit_edit.php?info=doctor_busy');
        postTo("/hospital/visit/visit_edit.php", [INFO => DOCTOR_BUSY]);
        exit;
    }

    $visitId = $_SESSION['active_visitId'];

    $visit = querySingle($connection, "SELECT * FROM visits WHERE Id = ?", [$visitId]);

    if(is_numeric($visitId) && $visitId > 0){
        execute($connection, "UPDATE `visits` SET
    DoctorId = ?, VisitDate = ?, Summary = ?, Status = 'scheduled', PatientDescription = ?
    WHERE Id = $visitId;", [$visit_doctor, $sqlDateTime, $summary, $patient_description]);
    } else{
        execute($connection, "INSERT INTO `visits` (PatientId, DoctorId, VisitDate, Summary, PatientDescription) VALUES (?, ?, ?, ?, ?)",
        [$_SESSION['user_id'], $visit_doctor, $sqlDateTime, $summary, $patient_description]);
    }

    unset($_SESSION['active_visitId']);
    closeConn($connection);

    postTo("/hospital/dashboard.php", [INFO => VISIT_ADDED]);
    exit;
?>