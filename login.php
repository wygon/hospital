<?php
require 'includes/header.php';
require_once 'helpers/functions.php';
require_once 'helpers/constants.php';

$error = '';
$receivedError = $_GET['from'] ?? '';

if (isset($_GET['from']) && $_GET['from'] == 'invalidLogin') {
    $error = "You can't go into this area. Please login first.";
}


if (isset($_POST[INFO])) {
    if ($_POST[INFO] === WRONG_LOGIN_OR_PASSWORD) {
        $error = "Invalid username or password.";
    }
}

function login($username, $password)
{
    $sql = "SELECT Id, Password, Role, Name, Surname FROM Users WHERE Username = ?";

    $connection = connectDB();

    $result = execute($connection, $sql, [$username]);

    $user = null;
    if ($result && $result->num_rows === 1) {
        $dbUser = $result->fetch_assoc();

        if (password_verify($password, $dbUser['Password'])) {
            $user = $dbUser;
        }
    }

    closeConn($connection);
    return $user ?? false;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST[INFO])) {
    
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $userData = login($username, $password);

    if ($userData) {
        $_SESSION['user_id'] = $userData['Id'];
        $_SESSION['role'] = $userData['Role'];
        $_SESSION['name'] = $userData['Name'];
        $_SESSION['surname'] = $userData['Surname'];

        $_SESSION[USER_FULLNAME] = $_SESSION['name'] . ' ' . $_SESSION['surname'];

        header("Location: dashboard.php");
        exit;
    } else {
        //postTo($_SERVER['PHP_SELF'], [INFO => WRONG_LOGIN_OR_PASSWORD]);
        $error = "Wrong login or password!";
    }
}

?>

<div class="container d-flex flex-column align-items-center justify-items-center">
    <h2>Login to system</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger w-50 text-center"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" id="loginForm" class="w-50">
        <div class="mb-3">
            <input type="text" name="username" class="form-control" placeholder="Login" required value=<?= $_GET['username'] ?? '' ?>>
        </div>
        <div class="mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>
        
        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-secondary">Login</button>
            <a href="register.php" class="btn btn-outline-secondary">Register</a>
        </div>
    </form>
</div>

<?php require 'includes/footer.php'; ?>
