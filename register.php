<?php
session_start();

require_once 'config/config.php';
require_once 'classes/database.php';
require_once 'classes/user.php';
require_once 'helpers/functions.php';

$error = '';

function register($db, $name, $surname, $username, $password, $role, $specialization) {
        $sql = "INSERT INTO users (Name, Surname, Username, Password, Role, Specialization,)
         VALUES (?,?,?,?,?,?)";

        $db->execute($sql, [$name, $surname, $username, $password, $role, $specialization]);
        $result = $db->lastInsertId();

        if($result != 0)
            return $result;

        return false;
    }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new Database();

    $username       = $_POST['username'] ?? '';
    $password       = $_POST['password'] ?? '';
    $name           = $_POST['name'] ?? '';
    $surname        = $_POST['surname'] ?? '';
    $role           = $_POST['role'] ?? '';
    $specialization = $_POST['specialization'] ?? null;

    if(validateFormItems($name, $surname, $username, $password, $role)){
        $error = "Entered wrong data";
        exit;
    };

    if($role = 'doctor' && isEmpty($specialization)){
        $error = "Doctor must specify specialization!";
        exit;
    }

    $goodRegister = $userObj->register($name, $surname, $username, $password, $role, $specialization);
    
    if ($goodRegister) 
    {
        echo 
        "
        <a href='login.php?=username=$username'>Your registration work, log in now!</a>
        ";

        header("Location: patient/dashboard.php");
        exit;
    } else {
    }
    $db->closeConn();
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Register</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div>
        <h2>Register</h2>
        <?php if ($error): ?> <p style="color:red;"><?= $error ?></p> <?php endif; ?>

        <form method="POST">
            <table>
                <tr>
                    <td><label for="name">Name:</label> </td>
                    <td> <input type="text" name="name" placeholder="Name" required> </td>
                </tr>
                <tr>
                    <td> <label for="surname">Surname:</label> </td>
                    <td> <input type="text" name="surname" placeholder="Surname" required> </td>
                </tr>
                <tr>
                    <td> <label for="username">Login</label> </td>
                    <td> <input type="text" name="username" placeholder="Login" required> </td>
                </tr>
                <tr>
                    <td> <label for="password">Password</label> </td>
                    <td> <input type="password" name="password" placeholder="Password" required> </td>
                </tr>
                <tr>
                    <td> <label for="role"></label>Role: </td>
                    <td> <select id="role" type="text" name="role" placeholder="" required style="width: 100%;">
                            <option value="patient">Patient</option>
                            <option value="doctor">Doctor</option>
                        </select></td>
                </tr>
                <tr id="specializationRow" style="display: none;">
                    <td> <label for="specialization">Specialization:</label> </td>
                    <td> <input id="specialization" type="text" name="specialization" placeholder="Specialization" required> </td>
                </tr>
            </table>
            <button type="submit">Register</button>
        </form>
    </div>
</body>
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