<?php
require __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../helpers/functions.php';

validateCanSeePage('doctor');

$connection = connectDB();

$latestVisitQuery =
    "SELECT V.Id, V.Summary, V.VisitDate, V.Status, P.Name, P.Surname FROM `visits` AS V
JOIN users AS P on V.PatientId = P.Id 
WHERE V.DoctorId = ? AND V.Status = 'completed' ORDER BY V.VisitDate LIMIT 5;";

$upcommingVisitQuery =
    "SELECT V.Id, V.Summary, V.VisitDate, V.Status, P.Name, P.Surname FROM `visits` AS V
JOIN users AS P on V.PatientId = P.Id 
WHERE V.DoctorId = ? AND V.Status = 'scheduled' ORDER BY V.VisitDate LIMIT 5;";

$latestVisit = queryAll($connection, $latestVisitQuery, [$_SESSION['user_id']]);
$upcommingVisit = queryAll($connection, $upcommingVisitQuery, [$_SESSION['user_id']]);

if (isset($_GET['info'])) {
    global $SUCCESS_INFO, $INFORMATION_INFO;

    if ($_GET['info'] == 'visit_closed') {
        $SUCCESS_INFO = 'Visit ended!';
    }

    if ($_GET['info'] == 'loggedin') {
        $INFORMATION_INFO = 'Welcome back!';
    }
}

closeConn($connection);
?>

<div class="container-sm">
    <div class="row pb-2">
        <div class="col-lg-6 col-md-12">
            <h3>Latest visits</h3>
            <table class="table table-sm table-striped table-hover" border="1">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>Patient</th>
                        <th>Status</th>
                        <th>Short Description</th>
                        <th>Visit date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($latestVisit)): ?>
                        <?php foreach ($latestVisit as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['Id']) ?></td>
                                <td><?= htmlspecialchars($row['Name']) . ' ' .  htmlspecialchars($row['Surname']) ?></td>
                                <td class=<?= getBgColorBasedOnStatus($row['Status']) ?>><?= htmlspecialchars($row['Status']) ?></td>
                                <td><?= htmlspecialchars($row['Summary']) ?></td>
                                <td><?= htmlspecialchars($row['VisitDate']) ?></td>
                                <td><a href="/hospital/visit/visit_start.php?id=<?= $row['Id'] ?>&destination=start&see=1"><button class="btn btn-sm btn-secondary">DETAILS</button></a></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="5"><a href="visits.php?type=ended?doctorId=<?= $_SESSION['user_id'] ?>">See all visits</a></td>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">There is not visit to show.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="col-lg-6 col-md-12">
            <h3>Incomming visits</h3>
            <table class="table table-sm table-striped table-hover" border="1">
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Status</th>
                        <th>Short description</th>
                        <th>Visit date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($upcommingVisit)): ?>
                        <?php foreach ($upcommingVisit as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['Name']) . ' ' .  htmlspecialchars($row['Surname']) ?></td>
                                <td class=<?= getBgColorBasedOnStatus($row['Status']) ?>><?= htmlspecialchars($row['Status']) ?></td>
                                <td><?= htmlspecialchars($row['Summary']) ?></td>
                                <td><?= htmlspecialchars($row['VisitDate']) ?></td>
                                <td>
                                    <button class="btn btn-sm btn-secondary" onclick=startVisit(<?= $row['Id'] ?>)>
                                        Start visit
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="5"><a href="visits.php?type=incomming?doctorId=<?= htmlspecialchars($_SESSION['user_id']) ?>">See all visits</a></td>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <td colspan="14">There is not visit to show.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php include __DIR__ . '/../includes/footer.php'; ?>

    <script>
        function startVisit(visitId) {
            if (confirm("Czy na pewno chcesz rozpocząć wizytę #" + visitId + "?")) {
                window.location.href = "/hospital/visit/visit_start.php?id=" + visitId + "&destination=start";
            }
        }
    </script>