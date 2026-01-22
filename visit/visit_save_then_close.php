<?php 
    require_once '../helpers/functions.php';
    require_once '../helpers/constants.php';
    session_start();

    if($_SERVER['REQUEST_METHOD'] !== 'POST'){
        postTo("/hospital/");
        exit;
    }
    $pId = $_POST['patient_id'];
    $dId = $_POST['doctor_id'];
    $vId = $_POST['visit_id'];
    $visitDesc = $_POST['visit-description'];
    $visitLongDesc = $_POST['visit-longDescription'];

    $pHeight = $_POST['patient-height'];
    $pWeight = $_POST['patient-weight'];
    
    $connection = connectDB();

    //user
    execute($connection, "UPDATE users SET Height = ?, Weight = ? WHERE Id = ?", [$pHeight, $pWeight, $pId]);

    $currentDate = date("Y-m-d H:i:s");
    
    //patientdiagnoses 
    if(!empty($_SESSION["temp_diseases"]))
        {
        foreach($_SESSION["temp_diseases"] as $disease){
                execute($connection, "INSERT INTO patientdiagnoses 
        (PatientId, DiseaseId, VisitId, DiagnosisDate, Description) VALUES (?,?,?,?,?);",
            [$pId, $disease["Id"], $vId, $currentDate, $disease["Description"]]);
        }
    
        unset($_SESSION['temp_diseases']);
    }
    
    //visit
    execute($connection, "UPDATE visits SET VisitDate = ?, Summary= ?, Status = ?, LongDescription = ? WHERE Id = ?",
    [$currentDate, $visitDesc, 'completed', $visitLongDesc, $vId]);

    closeConn($connection);
    // header("Location: /hospital/visit/visit_close.php");
    postTo("/hospital/visit/visit_close.php", [INFO => VISIT_CLOSE]);
    exit;
?>