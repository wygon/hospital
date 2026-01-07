<?php
include __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../helpers/functions.php';
require_once __DIR__ . '/../classes/Database.php';

validateCanSeePage('doctor');

$db = new Database();

$latestVisitQuery =
    "SELECT V.Id, V.Summary, V.VisitDate, V.Status, P.Name, P.Surname FROM `visits` AS V
JOIN users AS P on V.PatientId = P.Id 
WHERE V.DoctorId = ? AND V.Status = 'completed' ORDER BY V.VisitDate LIMIT 5;";

$upcommingVisitQuery =
    "SELECT V.Id, V.Summary, V.VisitDate, V.Status, P.Name, P.Surname FROM `visits` AS V
JOIN users AS P on V.PatientId = P.Id 
WHERE V.DoctorId = ? AND V.Status = 'scheduled' ORDER BY V.VisitDate LIMIT 5;";

$latestVisit = $db->queryAll($latestVisitQuery, [$_SESSION['user_id']]);
$upcommingVisit = $db->queryAll($upcommingVisitQuery, [$_SESSION['user_id']]);

if (isset($_GET['info'])) {
    global $SUCCESS_INFO, $INFORMATION_INFO;

    if ($_GET['info'] == 'visit_closed') {
        $SUCCESS_INFO = 'Visit ended!';
    }

    if ($_GET['info'] == 'loggedin') {
        $INFORMATION_INFO = 'Welcome back!';
    }
}

//include '../includes/infoLine.php';
$db->closeConn();
?>

<h1>Welcome doctor <?= htmlspecialchars($_SESSION['name'] . ' ' . $_SESSION['surname']) ?></h1>
<div class="container">
    <div class="row pb-2">
        <div class="col-6">
            <h3>Latest visits</h3>
            <table class="table table-sm" border="1">
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
                                <td><a href="/hospital/visit/visit_start.php?id=<?= $row['Id'] ?>&destination=start&see=1">DETAILS</a></td>
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
        <div class="col-6">
            <h3>Incomming visits</h3>
            <table border="1">
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
                                    <button type="button" onclick=startVisit(<?= $row['Id'] ?>)>
                                        Start visit
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="5"><a href="visits.php?type=incomming?doctorId=<?= $_SESSION['user_id'] ?>">See all visits</a></td>
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