<?php
include __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../helpers/functions.php';
require_once __DIR__ . '/../classes/Database.php';

validateCanSeePage('patient');

$db = new Database();

$limit = $_GET['show'] ?? 5;

$visits = $db->queryAll("SELECT DISTINCT V.Id as VisitId, V.VisitDate, V.Summary, V.Status, V.LongDescription, V.PatientDescription, D.Name as DoctorName, 
D.Surname as DoctorSurname, S.Name as Specialization, P.Id as PrescriptionId, P.ReciptCode
FROM `visits` as V 
JOIN `users` as D 
JOIN `specializations` as S
LEFT JOIN `prescriptions` as P
ON D.Id = V.DoctorId AND S.Id = D.Specialization AND P.VisitId = V.Id
WHERE PatientId = ?
GROUP BY V.Id
ORDER BY V.VisitDate DESC
LIMIT ?;", [$_SESSION['user_id'], $limit]);

$info = $_GET['info'] ?? '';

if ($info == 'positive_download') {
    global $SUCCESS_INFO;
    $SUCCESS_INFO = 'Succesfully downloaded prescription. Thanks!';
} else if ($info == 'error_download') {
    global $ERROR_INFO;
    $ERROR_INFO = 'Error existed while downloading prescription! Try again!';
}

include __DIR__ . '/../includes/infoLine.php';
$db->closeConn();
?>
<div class="row">
    <div class="d-flex  justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">All visit history - <?= $_SESSION['name'] . ' ' . $_SESSION['surname'] ?></h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($visits)): ?>
                        <table class="table table-sm table-striped table-hover">
                            <thead>
                                <tr>
                                    <td>Id</td>
                                    <td>Summary</td>
                                    <td>Visit date</td>
                                    <td>Status</td>
                                    <td>My information</td>
                                    <td>Doctor</td>
                                    <td>Specialization</td>
                                    <td>Doctor information</td>
                                    <td>Get prescription</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($visits as $row): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['VisitId']) ?></td>
                                        <td><?= htmlspecialchars($row['Summary']) ?></td>
                                        <td><?= htmlspecialchars($row['VisitDate']) ?></td>
                                        <td class="<?= getBgColorBasedOnStatus($row['Status']) ?> text-center"><?= htmlspecialchars($row['Status']) ?></td>
                                        <td><?= htmlspecialchars($row['PatientDescription']) ?></td>
                                        <td><?= htmlspecialchars($row['DoctorName']) . ' ' .  htmlspecialchars($row['DoctorSurname']) ?></td>
                                        <td class="text-center"><?= htmlspecialchars($row['Specialization']) ?></td>
                                        <td><?= htmlspecialchars($row['LongDescription']) ?></td>
                                        <td class="text-center">
                                            <?php if (!empty($row['PrescriptionId'])) : ?>
                                                <form method=post action='/hospital/prescriptions/download.php'>
                                                    <input type="hidden" name="prescriptionId" value="<?= $row['PrescriptionId'] ?>">
                                                    <button class="btn btn-sm btn-secondary" type="submit"><?= htmlspecialchars($row['ReciptCode']) ?></button>
                                                </form>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                    <?php else: ?>
                        <p>Nothing to show</p>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>


<?php include __DIR__ . '/../includes/footer.php'; ?>