<?php 
    require_once '../classes/Database.php';
    session_start();

    if($_SERVER['REQUEST_METHOD'] !== 'POST'){
        exit;
    }

    $medicinesToAdd = $_SESSION['temp_medicines'] ?? null;

    if(!isset($medicinesToAdd)){
        exit;
    }

    $db = new Database();

    $prescriptionExist = $db->querySingle('SELECT Id FROM `prescriptions` WHERE VisitId = ?', [$_SESSION['active_visitId']]) 
    ?? null;
    if(!$prescriptionExist){
        $issueDate = (new DateTime())->modify('+2 weeks')->format('Y-m-d');
        $reciptCode = random_int(1000, 9999);

     $db->execute("INSERT INTO `prescriptions` (VisitId, IssueDate, ReciptCode) VALUES (?, ?, ?)",
        [$_SESSION['active_visitId'], $issueDate, $reciptCode]);
        $prescriptionId = $db->lastInsertId();
    }
    else{
        $prescriptionId = $prescriptionExist['Id'];
    }
    
    $medicinesDB = $db->queryAll("SELECT PI.MedicineId, PI.Instructions, PI.Quantity, M.Name, M.DosageForm, 0 AS Updated FROM medicines AS M
LEFT JOIN prescriptionitems as PI on PI.MedicineId = M.Id
WHERE PI.PrescriptionId = ?;",
    [$prescriptionId]);

    foreach($medicinesDB as &$mdb){
        foreach($medicinesToAdd as &$mta){
            if($mdb['MedicineId'] == $mta['Id']){
                $db->execute("UPDATE `prescriptionitems` set Instructions = ?, Quantity = ? 
WHERE PrescriptionId = ? AND MedicineId = ? ",
                [$mta['Instructions'], $mta['Quantity'], $prescriptionId, $mdb['Id']]);
                $mdb['Updated'] = 1;
                $mta['Updated'] = 1;
            }
        }
    }
    unset($mta);
    unset($mdb);
    
    foreach(array_filter($medicinesDB, fn($x) => $x['Updated'] == 0) as &$notUpdated)
    {
        $db->execute("DELETE FROM `prescriptionitems` WHERE PrescriptionId = ? AND MedicineId = ?;",
        [$prescriptionId, $notUpdated['MedicineId']]);

        $notUpdated['Updated'] = 1;
    }
    unset($notUpdated);

    foreach(array_filter($medicinesToAdd, fn($x) => $x['Updated'] != 1) as $medicine){
        $db->execute("INSERT INTO `prescriptionitems` VALUES (?, ?, ?, ?);",
        [$prescriptionId, $medicine['Id'], $medicine['Instructions'], $medicine['Quantity']]);
    }
    unset($medicine);
    unset($_SESSION['temp_medicines']);

    header("Location: /hospital/doctor/visit_details.php");
    exit;
?>