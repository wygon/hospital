<?php
include "../includes/header.php";
require_once '../classes/Database.php';

$visitId = $_SESSION['active_visitId'] ?? null;

if(empty($visitId)){
    header('Location: /hospital/doctor/dashboard.php');
    exit;
}

$db = new Database();

$medicineKeyValue = $db->queryAll("SELECT Id, Name, DosageForm FROM `medicines`");

$result = $db->querySingle("SELECT Id FROM `prescriptions` ORDER BY Id DESC LIMIT 1") ?? 0;
if($result){
    $latestPrescriptionId = (int)$result;
}else{
    $latestPrescriptionId = 1;
}

$doctor = $db->querySingle("SELECT Name, Surname, Specialization FROM `users` WHERE Id = ?",
 [$_SESSION['user_id']]);

$visit = $db->querySingle("SELECT PatientId FROM `visits` WHERE Id = ?", [$visitId]);

$patient = $db->querySingle("SELECT Name, Surname, Pesel FROM `users` Where Id = ?", [$visit['PatientId']]);

$prescription = $db->querySingle("SELECT Id FROM `prescriptions` WHERE VisitId = ? ",
[$visitId]);

$medicines = $db->queryAll("SELECT P.IssueDate, P.ReciptCode, PI.Instructions, PI.Quantity, M.Id as MedicineId, M.Name, M.DosageForm FROM prescriptions AS P
LEFT JOIN prescriptionitems as PI on P.Id = PI.PrescriptionId
LEFT JOIN medicines as M on M.Id = PI.MedicineId
WHERE P.Id = ?;", [$prescription['Id']]);

if(!isset($_SESSION['temp_medicines'])){
    if (!empty($medicines)) {
        foreach ($medicines as $m) {
            $_SESSION['temp_medicines'][] = [
                'Id'           => $m['MedicineId'],
                'Name'         => $m['Name'],
                'DosageForm'   => $m['DosageForm'],
                'Instructions' => $m['Instructions'],
                'Quantity'     => $m['Quantity']
            ];
        }
    }
}

if (isset($_POST['add_medicine_temp'])) {
    $medicineId = $_POST['medicineId'];
    $instruction = $_POST['medicineInstructions'];
    $quantity = $_POST['medicineQuantity'];

    if ($medicineId != -1) {
        $medicineName = '';
        foreach ($medicineKeyValue as $m) {
            if ($m['Id'] == $medicineId) {
                $medicineName = $m['Name'];
                break;
            }
        }
        foreach($_SESSION['temp_medicines'] as &$m)
        {
            if($m['Id'] == $medicineId){
                $quantityAdded = true;
                $m['Quantity'] += $quantity;
                $m['Instructions'] = $instruction;
                break;
            }
        }
        unset($m);
        if(!isset($quantityAdded)){
            $_SESSION['temp_medicines'][] = [
                'Id' => $medicineId,
                'Name' => $medicineName,
                'Instructions' => $instruction,
                'Quantity' => $quantity
            ];
        }

        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

removeFromTempList('remove_medicine_temp',
        'temp_medicines',
        'Succesfully removed item from prescription.',
        $_SERVER['PHP_SELF'] . "?id=" . $visitId);

    include "../includes/infoLine.php";
    $db->closeConn();
?>
<div class="row">
    <div class="card card-sm col-5">
        <div class="card-body">
            <form method=post action=''>
                <div class="card-title">Add medicine</div>
                <div class="mb-3">
                    <label for="medicineSelect" class="form-label">Choose medicine:</label>
                    <select name="medicineId" id="medicineSelect" class="form-select-sm shadow-sm">
                        <option value="-1" selected>-- choose medicine --</option>

                        <?php if (!empty($medicineKeyValue)) : ?>
                            <?php foreach ($medicineKeyValue as $row) : ?>
                                <option value="<?= htmlspecialchars($row['Id']) ?>">
                                    <?= htmlspecialchars($row['Name']) ?> | <?= htmlspecialchars($row['DosageForm']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <option value="-1" disabled>No data</option>
                        <?php endif; ?>

                    </select>
                </div>

                <div class="row">
                    <div class="d-flex flex-column">
                        <label for="medicineInstructions">Instructions:</label>
                        <input class="form-control-sm" name='medicineInstructions' placeholder="Give dose instructions" />
                    </div>

                    <div class="d-flex justify-content-end">
                        <div class="d-flex flex-column w-25">
                            <label for="medicineQuantity">Quantity:</label>
                            <input min=1 max=67 class="form-control-sm" name='medicineQuantity' type=number placeholder="Quantity" value="1" />
                        </div>
                    </div>
                </div>
                    
                <div class="d-flex justify-content-end mt-3">
                    <button class="btn btn-sm btn-secondary px-4" type=sumbit name='add_medicine_temp'>Add</button>
                </div>
            </form>
        </div>
    </div>
    <div class="col-5">
        <div class="card">
            <div class="card-header">
                <span class="d-flex justify-content-between card-title">
                    <span>PRESCRIPTION</span>
                    <span class="fw-bold">#<?= $latestPrescriptionId ?></span>
                </span>
            </div>
            <div class="card-body pb-0">
                <div class="d-flex flex-column" style="font-size: small;">
                    <span><?= $patient['Name'] . ' ' . $patient['Surname'] ?></span>
                    <span>Pesel: <?= $patient['Pesel'] ?></span>
                </div>
            </div>
            <div class="card-body pt-0">
                <span>Medicines:</span>
                <table class="table table-sm table-striped table-hover">
                    <tbody>
                        <?php if (!empty($_SESSION['temp_medicines'])) : ?>
                            <?php foreach ($_SESSION['temp_medicines'] as $row) : ?> <tr>
                                    <!-- <td class="lh-1" style="font-size: small;"><?= htmlspecialchars($row['Id']) ?></td> -->
                                    <td class="lh-1" style="font-size: small;"><?= htmlspecialchars($row['Name']) ?></td>
                                    <td class="lh-1" style="font-size: small;"><?= htmlspecialchars($row['Instructions']) ?></td>
                                    <td class="lh-1" style="font-size: small;"><?= htmlspecialchars($row['Quantity']) ?> szt</td>
                                    
                                    <form method=post action=''><td>
                                        <button type="submit" name="remove_medicine_temp[<?= $row['Id'] ?>]" class="btn btn-outline-danger btn-sm px-2 py-0">
                                        <i class="bi bi-x-lg"></i>
                                        </button>
                                    </td></form>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?> </tbody>
                    </tbody>
                </table>
            </div>
            <div class="card-footer p-0 pe-2 fst-italic d-flex flex-column align-items-end">
                <span>Doctor: <?= $doctor['Name'] . ' ' . $doctor['Surname'] ?></span>
                <span><?= $doctor['Specialization'] ?></span>
                <span style="font-size: small;" ><?= date("Y-m-d H:i:s") ?></span>
            </div>
        </div>
    </div>
    <div class="col-2 d-flex align-items-end">
        <form method=post action='visit_save_prescription.php'><button class="btn btn-sm btn-secondary">Submit prescription</button></form>
    </div>
</div>

<?php include "../includes/footer.php"; ?>