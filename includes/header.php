<?php
require_once __DIR__ . '/../helpers/functions.php';
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $doctorHrefs = [
        [
            'link' => '/hospital/doctor/dashboard.php',
            'name' => 'Dashboard'
        ],
        [
            'link' => '/hospital/doctor/add_details.php',
            'name' => 'Add Details'
        ],
        [
            'link' => '/hospital/doctor/add_prescription.php',
            'name' => 'Add Prescription'
        ]
    ];

    $patientHrefs = [
        [
            'link' => '/hospital/patient/dashboard.php',
            'name' => 'Dashboard'
        ],
        [
            'link' => '/hospital/patient/book_visit.php',
            'name' => 'Book visit'
        ],
        [
            'link' => '/hospital/patient/history.php',
            'name' => 'Visit history'
        ]
    ];
    
    $role = $_SESSION['role'] ?? '';
    if(isset($_SESSION['active_visitId']) && $role == 'doctor'){
        $activeVisit = '/hospital/visit/visit_start.php?destination=start';
    }


    if($role == 'doctor'){
        $hrefs = $doctorHrefs;
    } else if($role == 'patient'){
        $hrefs = $patientHrefs;
    };
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Management System</title>
    <link rel="stylesheet" href="/hospital/assets/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/hospital/assets/css/style-main.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="navbar-brand">
                <h4>Hospital SW</h4>
            </div>
            <ul class="navbar-nav d-flex align-items d-flex flex-row">
                <?php if(isset($activeVisit)): ?>
                    <li class="nav-item p-3">
                        <a class="nav-link text-decoration-none" href=<?= $activeVisit ?>>ℹ Active visit</a>
                    </li>
                <?php endif; ?>
            <?php if (!empty($hrefs)): ?>
                <?php foreach ($hrefs as $item): ?>
                    <li class="nav-item p-3">
                        <a class="nav-link" href="<?= htmlspecialchars($item['link']) ?>">
                            <?= htmlspecialchars($item['name']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
            <li class="nav-item p-3">
                <?php
                    if(!isset($_SESSION['user_id'])){
                        echo "<a class='nav-link' href='/hospital/login.php'>Login</a>";
                    }
                    else{
                        echo "<a class='nav-link' href='/hospital/logout.php'>Logout</a>";
                    }
                ?>            
            </li>
            </ul>
        </nav>
    </header>
    <main class="container-xxl">
