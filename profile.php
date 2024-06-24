<?php
session_start();
require '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['id'])) {
    echo "No user ID provided.";
    exit();
}

$user_id = $_GET['id'];

$query = "SELECT first_name, last_name, date_of_birth, email, description, address, photo, role, telephone FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "User not found.";
    exit();
}

$age = date_diff(date_create($user['date_of_birth']), date_create('today'))->y;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo $user['first_name'] . ' ' . $user['last_name']; ?>'s Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
    <link rel="stylesheet" href="https://rawcdn.githack.com/Loopple/loopple-public-assets/ad60f16c8a16d1dcad75e176c00d7f9e69320cd4/argon-dashboard/css/nucleo/css/nucleo.css">
    <link rel="stylesheet" href="assets/css/theme.css">
    <link rel="stylesheet" href="assets/css/loopple/loopple.css">
    <style>
        .profile-card {
            max-width: 600px;
            margin: 0 auto;
        }

        .card {
            transition: transform 0.2s ease-in-out;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card .icon-placeholder {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            overflow: hidden;
            margin-bottom: 10px;
            margin-left: auto;
            margin-right: auto;
        }

        .card .icon-placeholder img {
            width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
<nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-white loopple-fixed-start" id="sidenav-main">
    <div class="navbar-inner">
        <div class="collapse navbar-collapse" id="sidenav-collapse-main">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" href="javascript:">
                        <i class="fa fa-desktop text-primary"></i>
                        <span class="nav-link-text">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="javascript:">
                        <i class="fa fa-lock text-danger"></i>
                        <span class="nav-link-text">Login</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="main-content">

    <div class="header bg-primary pb-8 pt-5 pt-md-8">
        <div class="container-fluid">
            <div class="header-body">
                <!-- Card stats -->
            </div>
        </div>
    </div>
    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col">
                <div class="card shadow profile-card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="icon-placeholder">
                                    <img src="<?php echo $user['photo']; ?>" alt="User photo">
                                </div>
                            </div>
                            <div class="col ml--2 text-center">
                                <h5 class="card-title text-uppercase text-muted mb-0"><?php echo ucfirst($user['role']); ?></h5>
                                <span class="h2 font-weight-bold mb-0"><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></span>
                                <p class="mt-3 mb-0 text-sm">
                                    <span class="text-nowrap">Age: <?php echo $age; ?></span><br>
                                    <span class="text-nowrap">Role: <?php echo ucfirst($user['role']); ?></span><br>
                                    <span class="text-nowrap">Phone: <?php echo $user['telephone']; ?></span><br>
                                    <span class="text-nowrap">Email: <?php echo ucfirst($user['email']); ?></span><br>
                                    <span class="text-nowrap"> <?php echo ucfirst($user['description']); ?></span><br>
                                    <span class="text-nowrap">Address: <?php echo $user['address']; ?></span><br>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <a href="dashboard.php" class="btn btn-primary">Back to Matches</a>
                        <a href="messaging.php?user_id=<?php echo $user_id; ?>" class="btn btn-secondary">Send Message</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer -->
    </div>
</div>
<!-- Argon Scripts -->
<!-- Core -->
<script src="./assets/vendor/jquery/dist/jquery.min.js"></script>
<script src="./assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="./assets/js/argon-dashboard.min.js?v=1.1.0"></script>
</body>
</html>
