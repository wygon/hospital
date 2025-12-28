<?php
include __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../helpers/functions.php';
require_once __DIR__ . '/../classes/Database.php';

validateCanSeePage('patient');

$db = new Database();

$desiredSpecialization = $_GET['specialization'] ?? -1;

$visitId = $_SESSION['active_visitId'];
$info = $_GET['info'] ?? '';

if ($visitId != -1) {
    $visit = $db->querySingle("SELECT * FROM visits WHERE Id = ?", [$visitId]);
    $visit['VisitOnlyDate'] = date('Y-m-d', strtotime($visit['VisitDate']));
    $visit['VisitTime'] = date('H:i', strtotime($visit['VisitDate']));

    if($info == 'doctor_busy'){
    $visit = [
        'Summary' => $_SESSION['summary'] ?? $visit['Summary'],
        'VisitOnlyDate' => $_SESSION['visit_date'] ?? '',
        'VisitTime' => $_SESSION['visit_time'] ?? '',
        'DoctorId' => $_SESSION['visit_doctor'] ?? $visit['DoctorId'],
        'PatientDescription' => $_SESSION['patient_description'] ?? $visit['PatientDescription'],
    ];
    }
} else if($info == 'doctor_busy'){
    $visit = [
        'Summary' => $_SESSION['summary'] ?? '',
        'VisitOnlyDate' => $_SESSION['visit_date'] ?? '',
        'VisitTime' => $_SESSION['visit_time'] ?? '',
        'DoctorId' => $_SESSION['visit_doctor'] ?? '',
        'PatientDescription' => $_SESSION['patient_description'] ?? '',
    ];
    } else  {
    $visit = [
        'Summary' => '',
        'VisitOnlyDate' => '',
        'VisitTime' => '',
        'DoctorId' => -1,
    ];
}

$specializations = $db->queryAll("SELECT Id, Name FROM `specializations`;");

$doctorsQuery = "SELECT U.Id, U.Name, U.Surname, S.Name as Specialization FROM `users` as U
JOIN specializations as S on U.Specialization = S.Id
WHERE Role = 'doctor'";
$doctorsQueryParameters = [];
if($desiredSpecialization != -1){
    $doctorsQuery = $doctorsQuery . ' AND Specialization = ?';
    $doctorsQueryParameters = [$desiredSpecialization];
}

$doctors = $db->queryAll($doctorsQuery, $doctorsQueryParameters);

global $ERROR_INFO;
if ($info == 'otherVisitIsActive') {
    $ERROR_INFO = 'You must submit active visit to start edit next';
} else if($info == 'doctor_busy'){
    $ERROR_INFO = 'Doctor is busy in this time. Choose other date!';
}

include '../includes/infoLine.php';
?>

<div class="row">
    <div class="col-8">
        <div class="card">
            <form id=visit_add_form method=post action='visit_add.php' class="row" onsubmit="return validateForm()">
                <div class="col-12 d-flex">
                    <span class="col-6 d-flex flex-column align-items-center justify-content-center">
                        <label for=summary>Summary</label>
                        <input id=summary type=text name=summary maxlength="50" value=<?= $visit['Summary'] ?> />
                    </span>
                    <span class="col-3 d-flex flex-column align-items-center justify-content-center">
                        <label for=visit_date>Visit date</label>
                        <input id=visit_date type=date name=visit_date value=<?= $visit['VisitOnlyDate'] ?> />
                    </span>
                    <span class="col-3 d-flex flex-column align-items-center justify-content-center">
                        <label for=visit_time>Visit time</label>
                        <input id=visit_time type=time name=visit_time value=<?= $visit['VisitTime'] ?> />
                    </span>
                </div>
                <div class="col-12 py-4">
                    <span class="col-6 d-flex flex-column align-items-center justify-content-center">
                        <label for=visit_doctor>Doctor:</label>
                        <select id=visit_doctor name=visit_doctor>
                            <?php if(!empty($doctors)): ?>
                                <option value=-1>Select doctor</option>
                                <?php else: ?>
                                <option value=-1>No doctor with this specialization</option>
                                <?php endif; ?>
                            <?php foreach ($doctors as $row): ?>
                                <option value=<?= $row['Id'] ?> <?= ($row['Id'] == $visit['DoctorId']) ? 'selected' : '' ?>>
                                    <?= $row['Name'] . ' ' . $row['Surname'] . ' | ' . $row['Specialization'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </span>
                </div>
                <span class="col-12">
                    <div class="d-flex flex-column p-2">
                        <label for=patient_description>Description</label>
                        <textarea id=patient_description name=patient_description maxlength="500"><?= $visit['PatientDescription'] ?></textarea>
                    </div>
                </span>
            </form>
            <div class="d-flex justify-content-end">
                <button class="btn btn-secondary m-3" type="submit" form=visit_add_form> Submit</button>
                <a href='visit_close.php'><button class="btn btn-secondary m-3" type="button"> Cancel</button></a>
            </div>
        </div>
    </div>
    <div class="col-3">
        <div class="card">
            <div class="card-header">
                <span class="card-title">FILTERS</span>
            </div>
            <div class="card-body">
                <form id=form_filter_doctor method=get action=''>
                    <select name=specialization>
                        <option value=-1>no filter</option>
                    <?php foreach($specializations as $row): ?>
                        <option value=<?= $row['Id'] ?> <?= ($row['Id'] == $desiredSpecialization) ? 'selected' : '' ?>>
                            <?= $row['Name'] ?>
                        </option>
                    <?php endforeach; ?>
                    </select>
                </form>
            </div>
            <div class="card-footer">
                <button class="btn btn-sm btn-secondary" type=submit form=form_filter_doctor>Filter</button>
            </div>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>


<script>
    const urlParams = new URLSearchParams(window.location.search);
    const visitId = urlParams.get('id');
    const today = new Date().toISOString().split('T')[0];

    document.getElementById('visit_date').setAttribute('min', today);
    document.getElementById('visit_date').setAttribute('value', today);

    const now = new Date();
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const currentTime = `${hours}:${minutes}`;
    const timeInput = document.getElementById('visit_time');

    timeInput.setAttribute('min', currentTime);
    if (visitId === "-1") {
        timeInput.setAttribute('value', currentTime);
    }

    function validateForm() {
        const visitTime = document.getElementById('visit_time').value;
        const visitDate = document.getElementById('visit_date').value;

        const now = new Date();
        const today = now.toISOString().split('T')[0];

        const currentHour = String(now.getHours()).padStart(2, '0');
        const currentMinute = String(now.getMinutes()).padStart(2, '0');
        const currentTime = `${currentHour}:${currentMinute}`;

        if (document.getElementById('summary').value == '') {
            alert('Summary must be specified!');
            return false;
        }


        if (visitDate === today) {
            if (visitTime < currentTime) {
                alert('You cant request visit before now time!');
                return false;
            }
        }

        if (document.getElementById('visit_doctor').value == '-1') {
            alert('Doctor must be choosen!');
            return false;
        }

        return true;
    }
</script>