<?php 
    require_once '../classes/Database.php';
    session_start();

    if($_SERVER['REQUEST_METHOD'] !== 'POST'){
        exit;
    }
    $pId = $_POST['patient_id'];
    $dId = $_POST['doctor_id'];
    $vId = $_POST['visit_id'];
    $visitDesc = $_POST['visit-description'];
    $visitLongDesc = $_POST['visit-longDescription'];

    $pHeight = $_POST['patient-height'];
    $pWeight = $_POST['patient-weight'];
    
    $db = new Database();

    //user
    $db->execute("UPDATE users SET Height = ?, Weight = ? WHERE Id = ?", [$pHeight, $pWeight, $pId]);

    $currentDate = date("Y-m-d H:i:s");
    
    //patientdiagnoses 
    if(!empty($_SESSION["temp_diseases"]))
        {
        foreach($_SESSION["temp_diseases"] as $disease){
                $db->execute("INSERT INTO patientdiagnoses 
        (PatientId, DiseaseId, VisitId, DiagnosisDate, Description) VALUES (?,?,?,?,?);",
            [$pId, $disease["Id"], $vId, $currentDate, $disease["Description"]]);
        }
    
        unset($_SESSION['temp_diseases']);
    }
    
    //visit
    $db->execute("UPDATE visits SET VisitDate = ?, Summary= ?, Status = ?, LongDescription = ? WHERE Id = ?",
    [$currentDate, $visitDesc, 'completed', $visitLongDesc, $vId]);

    $db->closeConn();
    header("Location: /hospital/doctor/visit_close.php");
    exit;
?>