<?php
    include 'includes/header.php';

    require_once 'config/config.php';
    require_once 'classes/database.php';
    require_once 'classes/user.php';

$error = '';
$receivedError = $_GET['from'] ?? '';

if(!empty($receivedError)){
    if($receivedError == 'invalidLogin')
    $error = "You can't go into this area.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new Database();
    $userObj = new User($db);

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $userData = $userObj->login($username, $password);

    if ($userData) {
        $_SESSION['user_id'] = $userData['Id'];
        $_SESSION['role']    = $userData['Role'];
        $_SESSION['name']    = $userData['Name'];
        $_SESSION['surname'] = $userData['Surname'];
        
        $USER_FULLNAME = $_SESSION['name'] . ' ' . $_SESSION['surname'];
        
        if ($userData['Role'] === 'doctor') {
            header("Location: doctor/dashboard.php");
            // header("Location: index.php");
        } else {
            header("Location: patient/dashboard.php");
        }
        exit;
    } else {
        $error = "Niepoprawny login lub hasło!";
    }
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
