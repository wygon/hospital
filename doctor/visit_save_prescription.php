<?php
require_once '../helpers/functions.php';
require_once '../helpers/constants.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    postTo("/hospital/doctor/visit_prescription.php");
    exit;
}

$medicinesToAdd = $_SESSION['temp_medicines'] ?? null;

if (!isset($medicinesToAdd)) {
    postTo("/hospital/doctor/visit_prescription.php");
    exit;
}

$connection = connectDB();

$prescriptionExist = querySingle($connection, 'SELECT Id FROM `prescriptions` WHERE VisitId = ?', [$_SESSION['active_visitId']])
    ?? null;
if (!$prescriptionExist) {
    $issueDate = (new DateTime())->modify('+2 weeks')->format('Y-m-d');
    do{
        $reciptCode = random_int(1000, 9999);
        $reciptCodeExists = getCountOfRecords($connection, "`prescriptions` WHERE ReciptCode = ?", [$reciptCode]);
    }
    while($reciptCodeExists > 0);

    execute(
        $connection,
        "INSERT INTO `prescriptions` (VisitId, IssueDate, ReciptCode) VALUES (?, ?, ?)",
        [$_SESSION['active_visitId'], $issueDate, $reciptCode]
    );
    $prescriptionId = lastInsertId($connection);
} else {
    $prescriptionId = $prescriptionExist['Id'];
}

$medicinesDB = queryAll(
    $connection,
    "SELECT PI.MedicineId, PI.Instructions, PI.Quantity, M.Name, M.DosageForm, 0 AS Updated FROM medicines AS M
LEFT JOIN prescriptionitems as PI on PI.MedicineId = M.Id
WHERE PI.PrescriptionId = ?;",
    [$prescriptionId]
);

foreach ($medicinesDB as &$mdb) {
    foreach ($medicinesToAdd as &$mta) {
        if ($mdb['MedicineId'] == $mta['Id']) {
            execute(
                $connection,
                "UPDATE `prescriptionitems` set Instructions = ?, Quantity = ? 
WHERE PrescriptionId = ? AND MedicineId = ? ",
                [$mta['Instructions'], $mta['Quantity'], $prescriptionId, $mdb['Id']]
            );
            $mdb['Updated'] = 1;
            $mta['Updated'] = 1;
        }
    }
}
unset($mta);
unset($mdb);

foreach (array_filter($medicinesDB, fn($x) => $x['Updated'] == 0) as &$notUpdated) {
    execute(
        $connection,
        "DELETE FROM `prescriptionitems` WHERE PrescriptionId = ? AND MedicineId = ?;",
        [$prescriptionId, $notUpdated['MedicineId']]
    );

    $notUpdated['Updated'] = 1;
}
unset($notUpdated);

foreach (array_filter($medicinesToAdd, fn($x) => $x['Updated'] != 1) as $medicine) {
    execute(
        $connection,
        "INSERT INTO `prescriptionitems` VALUES (?, ?, ?, ?);",
        [$prescriptionId, $medicine['Id'], $medicine['Instructions'], $medicine['Quantity']]
    );
}
unset($medicine);
unset($_SESSION['temp_medicines']);

closeConn($connection);
header("Location: /hospital/visit/visit_details.php");
exit;
