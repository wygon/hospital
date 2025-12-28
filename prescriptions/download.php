<?php 
session_start();
require_once __DIR__ . '/../helpers/functions.php';
require_once __DIR__ . '/../classes/Database.php';

validateCanSeePage('patient');

$prescriptionId = $_POST['prescriptionId'] ?? null;

if(!$prescriptionId)
{
    header('Location: /hospital/patient/history.php?info=error_download');
    exit;
}

$db = new Database();

$prescription = $db->querySingle("SELECT VisitId, IssueDate, ReciptCode from `prescriptions` WHERE Id = ?", [$prescriptionId]);

$prescriptionitems = $db->queryALL("SELECT M.Name as MedicineName, M.DosageForm, M.ActiveSubstance, PI.Quantity, PI.Instructions
FROM `prescriptionitems` as PI
JOIN `medicines` as M
ON M.Id = PI.MedicineId
WHERE PI.PrescriptionId = ?;",
[$prescriptionId]);

$visit = $db->querySingle("SELECT DoctorId, PatientId FROM `visits` WHERE Id = ?", [$prescription['VisitId']]);

$doctor = $db->querySingle("SELECT '' as Doctor, D.Name, D.Surname, S.Name as Specialization from `users` as D
JOIN `specializations` as S on D.Specialization = S.Id
WHERE D.Id =  ?",[$visit['DoctorId']]);
$doctor['Doctor'] = 'Doctor:';

$patient =  $db->querySingle("SELECT '' as Patient, Name, Surname, Pesel FROM `users` WHERE Id = ?", [$_SESSION['user_id']]);
$patient['Patient'] = 'Patient:';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="prescription_' . $prescription['ReciptCode'] .'.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['Prescription data:','Issue date', 'Recipt code:'], ';');
fputcsv($output, ['', $prescription['IssueDate'], $prescription['ReciptCode']], ';');
fputcsv($output, [], ';');

fputcsv($output, ['', 'Name:','Surname:','Pesel:'], ';');
fputcsv($output, $patient, ';');

fputcsv($output, [], ';');
fputcsv($output, ['', 'Name:','Surname:','Specialization:'], ';');

fputcsv($output, $doctor, ';');
fputcsv($output, [], ';');

fputcsv($output, ['Medicines:', 'Name:', 'Dosage Form:', 'Active substance:', 'Count:', 'Doctor instructions',], ';');

// LINIA 6+: Dane z bazy (leki)
foreach ($prescriptionitems as $m) {
    fputcsv($output, [
        '', // pusta komórka pod napisem "LEKI:"
        $m['MedicineName'],
        $m['DosageForm'],
        $m['ActiveSubstance'],
        $m['Quantity'],
        $m['Instructions']
    ], ';');
}

fputcsv($output, [], ';');

fputcsv($output, ['','','','','','Print date:', date('Y.m.d')], ';');

fclose($output);


//header('Location: /hospital/patient/history.php?info=positive_download');
?>