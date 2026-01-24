<?php
session_start();

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/helpers/functions.php';
require_once __DIR__ . '/helpers/database.php';

$error = '';

$conn = connectDB();

$query = "SELECT Id, Name FROM `specializations`";

$specializations = queryAll($conn, $query);

closeConn($conn);

function register($name, $surname, $username, $password, $role, $specialization, $pesel)
{
    $connection = connectDB();

    $userExist = getCountOfRecords($connection, "`users` WHERE Username = ?", [$username]);

    if ($userExist) {
        $error = 'User already exist';
        return false;
    }

    if($role === 'patient'){
        $specialization = null;
    }

    $sql = "INSERT INTO users (Name, Surname, Username, Password, Role, Specialization, Pesel, Height, Weight)
         VALUES (?,?,?,?,?,?,?, 0, 0)";
    $hashedPass = password_hash($password, PASSWORD_DEFAULT);

    execute($connection, $sql, [$name, $surname, $username, $hashedPass, $role, $specialization, $pesel]);

    $lastInsertedId = lastInsertId($connection);
    closeConn($connection);

    return $lastInsertedId > 0 ? $lastInsertedId : false;
}

if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && !isset($_POST[constant('ENTERED_WRONG_DATA')])
    && !isset($_POST[constant('DOCTOR_MUST_SPECIFY_SPECIALIZATION')])
) {

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmpassword = $_POST['confirmpassword'] ?? '';
    $name = $_POST['name'] ?? '';
    $surname = $_POST['surname'] ?? '';
    $role = $_POST['role'] ?? '';
    $specialization = $_POST['specialization'] ?? null;
    $pesel = $_POST['pesel'] ?? null;


    if (!validateFormItems($name, $surname, $username, $password, $confirmpassword, $role, $pesel)) {
        postTo('', [INFO => ENTERED_WRONG_DATA]);
        exit;
    } else {
        $errors = '';

        if($confirmpassword != $password){
            $errors .= "Passwords are not the same.";
        }
        if (strlen($username) < 5) {
            if(strlen($errors) > 3)
                $errors .= "<br/>";

            $errors .= "Username lenght must be longer than 5";
        }
        if (strlen($password) < 5) {
            if(strlen($errors) > 3)
                $errors .= "<br/>";
            
            $errors .= "Password length must be longer than 5";
        }
        if (strlen($name) < 2) {
            if(strlen($errors) > 3)
                $errors .= "<br/>";
            
            $errors .= "Name lenght must be longer than 2";
        }
        if (strlen($surname) < 2) {
            if(strlen($errors) > 3)
                $errors .= "<br/>";
            
            $errors .= "Surname lenght must be longer than 2";
        }
    }

    if (strlen($errors) < 3) {

        if ($role == 'doctor' && (empty($specialization))) {
            postTo('', [INFO => DOCTOR_MUST_SPECIFY_SPECIALIZATION]);
            exit;
        }

        $userId = register($name, $surname, $username, $password, $role, $specialization, $pesel);

        if ($userId) {
            echo
            "<a href='login.php?username=$username'>Your registration work, log in now!</a>";
            //header("Location: /hospital/dashboard.php");
            exit;
        } else {
            if (strlen($error) <  5)
                $error = "Registration failed. Username might be taken.";
        }
    } else {
        $error = $errors;
    }
}
?>

<div class="container mt-5">
    <h2>Register New Account</h2>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <table class="table">
            <tr>
                <td><label for="name">Name:</label></td>
                <td><input id='name' type="text" name="name" class="form-control" required value=<?= $_POST['name'] ?? '' ?>></td>
            </tr>
            <tr>
                <td><label for="surname">Surname:</label></td>
                <td><input id='surname' type="text" name="surname" class="form-control" required value=<?= $_POST['surname'] ?? '' ?>></td>
            </tr>
            <tr>
                <td><label for="username">Login (Username):</label></td>
                <td><input id='username' type="text" name="username" class="form-control" required value=<?= $_POST['username'] ?? '' ?>></td>
            </tr>
            <tr>
                <td><label for="password">Password:</label></td>
                <td><input id='password' type="password" name="password" class="form-control" required value=<?= $_POST['password'] ?? '' ?>></td>
            </tr>
            <tr>
                <td><label for="confirmpassword">Confirm password:</label></td>
                <td><input id='confirmpassword' type="password" name="confirmpassword" class="form-control" required value=<?= $_POST['confirmpassword'] ?? '' ?>></td>
            </tr>
            <tr>
                <td><label for="pesel">Pesel:</label></td>
                <td><input id='pesel' type="pesel" name="pesel" class="form-control" required value=<?= $_POST['pesel'] ?? '' ?>></td>
            </tr>
            <tr>
                <td><label for="role">Role:</label></td>
                <td>
                    <select id="role" name="role" class="form-select" required value=<?= $_POST['role'] ?? '' ?>>
                        <option value="patient">Patient</option>
                        <option value="doctor">Doctor</option>
                    </select>
                </td>
            </tr>
            <tr id="specializationRow" style="display: none;">
                <td><label for="specialization">Specialization:</label></td>
                <td>
                    <select id="specialization" name="specialization" class="form-select" required value=<?= $_POST['specialization'] ?? '' ?>>
                        <?php foreach ($specializations as $s): ?>
                            <option value=<?= $s['Id'] ?>><?= $s['Name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <!-- <td><input id="specialization" type="text" name="specialization" class="form-control" value=<?= $_POST['specialization'] ?? '' ?>></td> -->
            </tr>
        </table>
        <button type="submit" class="btn btn-secondary">Create Account</button>
    </form>
</div>
<script>
    const roleSelect = document.getElementById('role');
    const specializationRow = document.getElementById('specializationRow');
    const specializationInput = document.getElementById('specialization');

    roleSelect.addEventListener('change', function() {
        if (this.value === 'doctor') {
            specializationRow.style.display = '';
            specializationInput.required = true;
        } else {
            specializationRow.style.display = 'none';
            specializationInput.required = false;
            specializationInput.value = '';
        }
    });
</script>

</html>