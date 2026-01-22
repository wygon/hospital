<?php
include __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../helpers/functions.php';

validateCanSeePage('patient');

$connection = connectDB();

$visitTaken = queryAll($connection, "SELECT V.Id, U.Name as DoctorName, U.Surname as DoctorSurname, V.VisitDate, v.Summary FROM `visits` as V
Join users as UU on V.PatientId = UU.Id
JOIN users as U on V.DoctorId = U.Id
WHERE V.Status = 'completed' AND UU.Id = ?
ORDER BY V.VisitDate DESC LIMIT 5;", [$_SESSION[USER_ID]]);

$visitScheduled = queryAll($connection, "SELECT V.Id, U.Name as DoctorName, U.Surname as DoctorSurname, V.VisitDate, v.Summary FROM `visits` as V
Join users as UU on V.PatientId = UU.Id
JOIN users as U on V.DoctorId = U.Id
WHERE V.Status = 'scheduled' AND UU.Id = ?
ORDER BY V.VisitDate DESC LIMIT 5;", [$_SESSION[USER_ID]]);

closeConn($connection);
?>

<h1>Welcome patient <?= htmlspecialchars($_SESSION['name'] . ' ' . $_SESSION['surname']) ?></h1>

<div class="row">
    <div class="col-6">
        <div class="card">
            <div class="card-title">
                <h5>My taken visits</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($visitTaken)): ?>
                    <table class="table table-sm table-striped table-hover">
                        <thead>
                            <tr>
                                <td>Id</td>
                                <td>Doctor</td>
                                <td>Description</td>
                                <td>Visit date</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($visitTaken as $row): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['Id']) ?></td>
                                    <td><?= htmlspecialchars($row['DoctorName']) . ' ' .  htmlspecialchars($row['DoctorSurname']) ?></td>
                                    <td><?= htmlspecialchars($row['Summary']) ?></td>
                                    <td><?= htmlspecialchars($row['VisitDate']) ?></td>
                                    <td><a href="/hospital/visit/visit_start.php?id=<?= htmlspecialchars($row['Id']) ?>&destination=edit&see=1">DETAILS</a></td>
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
    <div class="col-6">
        <div class="card">
            <div class="card-title">
                <h5>My scheduled visits</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($visitScheduled)): ?>
                    <table class="table table-sm table-striped table-hover">
                        <thead>
                            <tr>
                                <td>Id</td>
                                <td>Doctor</td>
                                <td>Description</td>
                                <td>Visit date</td>
                                <td></td>
                                <td>Cancel</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($visitScheduled as $row): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['Id']) ?></td>
                                    <td><?= htmlspecialchars($row['DoctorName']) . ' ' .  htmlspecialchars($row['DoctorSurname']) ?></td>
                                    <td><?= htmlspecialchars($row['Summary']) ?></td>
                                    <td><?= htmlspecialchars($row['VisitDate']) ?></td>
                                    <td><a href="/hospital/visit/visit_start.php?id=<?= $row['Id'] ?>&destination=edit">DETAILS</a></td>
                                    <td class="text-center">
                                        <a href="/hospital/visit/visit_cancel.php?id=<?= $row['Id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </a>
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


<?php include __DIR__ . '/../includes/footer.php'; ?>

<script>
    function startVisit(visitId) {
        if (confirm("Czy na pewno chcesz rozpocząć wizytę #" + visitId + "?")) {
            window.location.href = "visit_start.php?id=" + visitId;
        }
    }
</script>