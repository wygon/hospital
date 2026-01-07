<?php
include 'includes/header.php';

$error = '';
$receivedError = $_GET['from'] ?? '';

if (!empty($receivedError)) {
    if ($receivedError == 'invalidLogin')
        $error = "You can't go into this area.";
}

function login($username, $password)
{
    $sql = "SELECT Id, Password, Role, Name, Surname FROM Users WHERE Username = ?";

    $connection = connectDB();

    $result = execute($connection, $sql, [$username]);

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if ($user['Password'] == $password) {
            return $user;
        }
        // if (password_verify($password, $user['Password'])) {
        //     return $user; // Zwracamy dane użytkownika
        // }
    }

    closeConn($connection);
    return false;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $userData = login($username, $password);

    if ($userData) {
        $_SESSION['user_id'] = $userData['Id'];
        $_SESSION['role']    = $userData['Role'];
        $_SESSION['name']    = $userData['Name'];
        $_SESSION['surname'] = $userData['Surname'];

        $_SESSION[USER_FULLNAME] = $_SESSION['name'] . ' ' . $_SESSION['surname'];

        header("Location: dashboard.php");

        exit;
    } else {
        $error = "Niepoprawny login lub hasło!";
    }
    $db->closeConn();
}

?>

<div class="login-container">
    <h2>Zaloguj się do systemu</h2>
    <?php if ($error): ?> <p style="color:red;"><?= $error ?></p> <?php endif; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Login" required><br>
        <input type="password" name="password" placeholder="Hasło" required><br>
        <button type="submit">Zaloguj</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>