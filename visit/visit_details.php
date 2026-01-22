<?php
include '../includes/header.php';

validateCanSeePage('doctor');

$visitId = $_SESSION['active_visitId'] ?? '';
if (empty($visitId)) {
    //header("Location: /hospital/doctor/dashboard.php?error=novisit");
    postTo("/hospital/doctor/dashboard.php", [INFO => VISIT_NO_EXIST]);
    exit;
}

$patiendDiseasesAdded = $_SESSION['temp_diseases'] ?? [];

$connection = connectDB();

$patientVisitHistory = [];

$visitData = querySingle($connection, 'SELECT Id, PatientId, VisitDate, Summary, Status, LongDescription  FROM `visits` WHERE Id = ?', [$visitId]);
$patientData = querySingle($connection, 'SELECT Id, Name, Surname, Height, Weight FROM `users` WHERE Id = ?', [$visitData['PatientId']]);
$patientVisitHistoryQuery = "SELECT Id, Summary, VisitDate, PatientDescription, LongDescription FROM `visits` WHERE PatientId = ? ORDER BY VisitDate LIMIT 5;";
$patientVisitHistory = queryAll($connection, $patientVisitHistoryQuery, [$patientData['Id']]);

$patientDiagnoses = queryAll($connection, "SELECT P.Id, P.DiagnosisDate, P.Description, D.Name, D.IcdCode, D.Category FROM `patientdiagnoses` AS P
JOIN Diseases as D on P.DiseaseId = D.Id
WHERE P.PatientId = ? ORDER BY P.DiagnosisDate", [$patientData['Id']]);

$patientDiseasesThisVisit = queryAll($connection, "SELECT P.*, D.Name FROM patientdiagnoses AS P JOIN Diseases as D on P.DiseaseId = D.Id WHERE VisitId = ? AND PatientId = ?", [$visitId, $patientData['Id']]);

$dieseaseKeyValue = queryAll($connection, "SELECT Id, Name FROM `diseases`");
closeConn($connection);

if (!isset($_SESSION['temp_diseases'])) {
    $_SESSION['temp_diseases'] = [];
}
if (isset($_POST['add_disease_temp'])) {
    $dId = $_POST['diseaseId'];
    $desc = $_POST['disease-description'];

    if ($dId != -1) {
        $diseaseName = '';
        foreach ($dieseaseKeyValue as $d) {
            if ($d['Id'] == $dId) {
                $diseaseName = $d['Name'];
                break;
            }
        }
        $_SESSION['temp_diseases'][] = [
            'Id' => $dId,
            'Name' => $diseaseName,
            'Description' => $desc
        ];

        // header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $visitId);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}
if (isset($_POST['remove_disease_temp'])) {
    $idToRemove = key($_POST['remove_disease_temp']);

    if (isset($_SESSION['temp_diseases'])) {
        foreach ($_SESSION['temp_diseases'] as $key => $disease) {
            if ($disease['Id'] == $idToRemove) {

                unset($_SESSION['temp_diseases'][$key]);

                $_SESSION['temp_diseases'] = array_values($_SESSION['temp_diseases']);
                break;
            }
        }
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>
<div class="row">
    <div class="col-7 col-lg-3 py-2 order-1">
        <form method=POST action="visit_save_then_close.php" id="ogform">
            <div class="card">
                <div class="card-body">
                    <span class="card-title">
                        <h5>Visit</h5>
                    </span>
                    <input type="hidden" name="patient_id" value="<?= $patientData['Id'] ?>">
                    <input type="hidden" name="doctor_id" value="<?= $_SESSION['user_id'] ?>">
                    <input type="hidden" name="visit_id" value="<?= $visitId ?>">
                    <label for="visit-description">Summary</label></td> </br>
                    <input class="form-control w-100" id="visit-description" type=text name=visit-description value=<?= $visitData["Summary"] ?>> </br>
                    <label for="visit-longDescription">Description:</label></br>
                    <textarea class="form-control w-100" id="visit-longDescription" name="visit-longDescription" rows="5" cols="40" placeholder="Write patient symptomps..."><?= $visitData['LongDescription'] ?></textarea>
                    <p>Update patient info</p>
                    <div class="row">
                        <span class="col-6">Height</span> <span class="col-6">Weight</span>
                    </div>
                    <div class="d-flex justify-content-around">
                        <span>
                            <input class="form-control" type=number name=patient-height min=0 max=250 value="<?= $patientData['Height'] ?>">cm
                        </span>
                        <span>
                            <input class="form-control" type=number name=patient-weight min=0 max=200 value="<?= $patientData['Weight'] ?>">kg
                        </span>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="col-12 col-lg-5 py-2 order-3 order-lg-2">
        <div class="card h-100">
            <table class="table table-sm table-striped table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($patientDiseasesThisVisit)) : ?>
                        <?php foreach ($patientDiseasesThisVisit as $row) : ?> <tr>
                                <td class="lh-1" style="font-size: small;"><?= htmlspecialchars($row['Name']) ?></td>
                                <td class="lh-1" style="font-size: small;"><?= htmlspecialchars($row['Description']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?> </tbody>
                <?php if (!empty($_SESSION['temp_diseases'])) : ?>
                    <?php foreach ($_SESSION['temp_diseases'] as $row) : ?> <tr>
                            <td class="lh-1" style="font-size: small;"><?= htmlspecialchars($row['Name']) ?></td>
                            <td class="lh-1" style="font-size: small;"><?= htmlspecialchars($row['Description']) ?></td>
                            <form method=post action=''>
                                <td><button class="btn btn-sm btn-secondary" type=submit name=remove_disease_temp[<?= htmlspecialchars($row["Id"]) ?>]>Remove</button></td>
                            </form>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?> </tbody>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-5 col-lg-4 py-2 order-2 order-lg-3">
        <div class="d-flex flex-column justify-content-between h-100">
            <div class="card">
                <div class="card-body">
                    <span class="card-title">
                        <h5>Add disease</h5>
                    </span>
                    <div>
                        <form method=post action=''>
                            <span>Disease</span>
                            <select class="form-select" name=diseaseId>
                                <option value='-1'>None</option>
                                <?php foreach ($dieseaseKeyValue as $row): ?>
                                    <option value=<?= $row['Id'] ?>><?= htmlspecialchars($row['Name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <tr>
                                <td>Description</td>
                                <td><input class="form-control" type=text name=disease-description placeholder="Short summary"></td>
                                <td><button class="btn btn-small btn-secondary" type=submit name=add_disease_temp>Add</button></td>
                            </tr>
                        </form>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2 align-items-bottom justify-content-between">
                <div class="card mt-3">
                    <a class="text-decoration-none text-dark w-100" href="/hospital/doctor/visit_prescription.php">
                        <button class="btn btn-secondary w-100" type="button">Add prescription</button>
                    </a>
                </div>
                <div class="card-button pt-3 d-flex justify-content-end">
                    <button class="btn btn-secondary" type="submit" form='ogform'>Save and end visit</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div>
        <h2>Patient</h2>
        <p>Information</p>
        <span>Fullname: <?= htmlspecialchars($patientData['Name']) . ' ' . htmlspecialchars($patientData['Surname']) ?></span>
        <h5>Dieseases history</h5>
        <table class="table table-sm table-striped table-hover" border="1">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Diagnosis Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($patientDiagnoses)) : ?>
                    <?php foreach ($patientDiagnoses as $row) : ?> <tr>
                            <td class="lh-1" style="font-size: small;"><?= htmlspecialchars($row['Name']) ?></td>
                            <td class="lh-1" style="font-size: small;"><?= htmlspecialchars($row['Description']) ?></td>
                            <td class="lh-1" style="font-size: small;"><?= htmlspecialchars($row['DiagnosisDate']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?> </tbody>
            </tbody>
        </table>
        <h5>Visit history</h5>
        <table border="1">
            <thead>
                <th>Visit Id</th>
                <th>Short description</th>
                <th>Date</th>
            </thead>
            <tbody>
                <?php if (!empty($patientVisitHistory)) : ?>
                    <?php foreach ($patientVisitHistory as $row) : ?> <tr>
                            <td>#<?= htmlspecialchars($row['Id']) ?></td>
                            <td><?= htmlspecialchars($row['LongDescription']) ?></td>
                            <td><?= htmlspecialchars($row['PatientDescription']) ?></td>
                            <td><?= htmlspecialchars($row['VisitDate']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?> </tbody>
            </tbody>
        </table>
    </div>
</div>
<?php include '../includes/footer.php'; ?>