<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Matches</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
    <link rel="stylesheet" href="https://rawcdn.githack.com/Loopple/loopple-public-assets/ad60f16c8a16d1dcad75e176c00d7f9e69320cd4/argon-dashboard/css/nucleo/css/nucleo.css">
    <link rel="stylesheet" href="assets/css/theme.css">
    <link rel="stylesheet" href="assets/css/loopple/loopple.css">
    <style>
        .container {
            margin: 0;
        }
        .card {
            transition: transform 0.2s ease-in-out;
            height: 100%;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card .icon-placeholder {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            overflow: hidden;
            margin-bottom: 10px;
        }
        .card .icon-placeholder img {
            width: 100%;
            height: auto;
        }
        .results-section {
            display: none;
        }
        .results-section.active {
            display: block;
        }
        .btn-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }
        .btn-circle i {
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
<nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-white loopple-fixed-start" id="sidenav-main">
    <div class="navbar-inner">
        <div class="collapse navbar-collapse" id="sidenav-collapse-main">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" href="">
                        <i class="fa fa-desktop text-primary"></i>
                        <span class="nav-link-text">Dashboard</span>
                    </a>
                </li>
                <?php
                session_start();
                require '../config/database.php';

                if (!isset($_SESSION['user_id'])) {
                    header('Location: login.php');
                    exit();
                }

                $user_id = $_SESSION['user_id'];
                $role = $_SESSION['role'];

                if ($role == 'third_year') {
                    echo '
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0);" id="first-year-btn">
                            <i class="fa fa-user text-info"></i>
                            <span class="nav-link-text">First Year Students</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0);" id="laureate-btn">
                            <i class="fa fa-user-graduate text-success"></i>
                            <span class="nav-link-text">Laureates</span>
                        </a>
                    </li>';
                }
                ?>
                
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
    <?php
    $query = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Example query to get the number of matches for notifications
    $matches_query = "SELECT COUNT(*) AS match_count FROM users WHERE id = ?";
    $stmt_matches = $conn->prepare($matches_query);
    $stmt_matches->bind_param("i", $user_id);
    $stmt_matches->execute();
    $matches_result = $stmt_matches->get_result();
    $matches_count = $matches_result->fetch_assoc()['match_count'];
    ?>
    <nav class="navbar navbar-top navbar-expand navbar-dark border-bottom bg-warning" id="navbarTop">
        <div class="container-fluid">
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav align-items-center ml-md-auto">
                    <li class="nav-item d-xl-none">
                        <div class="pr-3 sidenav-toggler sidenav-toggler-dark active" data-action="sidenav-pin" data-target="#sidenav-main">
                            <div class="sidenav-toggler-inner">
                                <i class="sidenav-toggler-line"></i>
                                <i class="sidenav-toggler-line"></i>
                                <i class="sidenav-toggler-line"></i>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item d-sm-none">
                        <a class="nav-link" href="#" data-action="search-show" data-target="#navbar-search-main">
                            <i class="ni ni-zoom-split-in"></i>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-bell"></i>
                            <span class="badge badge-danger"><?php echo $matches_count; ?></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <span class="dropdown-item">You have <?php echo $matches_count; ?> new matches</span>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="http://localhost/mentoriny/public/messaging.php?<?php echo $matches["id"]; ?>=">
                            <i class="fa fa-envelope"></i>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <div class="media align-items-center">
                                <span class="avatar avatar-sm rounded-circle">
                                    <img alt="Image placeholder" src="<?php echo $user['photo']; ?>">
                                </span>
                                <div class="media-body ml-2 d-none d-lg-block">
                                    <span class="mb-0 text-sm font-weight-bold"><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></span>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="settings.php" class="dropdown-item">
                                <i class="fa fa-tools"></i>
                                <span>Settings</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <form action="../includes/logout.php" method="POST">
                                <button type="submit" class="dropdown-item">
                                    <i class="fa fa-sign-out-alt"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
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
                <div class="card shadow">
                    <div class="card-header border-0">
                        
                    </div>
                    <div class="card-body">
                        <?php
                        require '../config/database.php';
                        require '../includes/functions.php';

                        if (!isset($_SESSION['user_id'])) {
                            header('Location: login.php');
                            exit();
                        }

                        $user_id = $_SESSION['user_id'];
                        $role = $_SESSION['role'];

                        function get_user_details($conn, $user_id) {
                            $query = "SELECT * FROM users WHERE id = ?";
                            $stmt = $conn->prepare($query);
                            $stmt->bind_param("i", $user_id);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            return $result->fetch_assoc();
                        }

                        function calculate_score_percentage($score, $role) {
                            if ($role == 'first_year' || $role == 'third_year') {
                                return ($score / 80) * 100;
                            } elseif ($role == 'laureat') {
                                return ($score / 65) * 100;
                            }
                            return 0;
                        }

                        function display_card($conn, $id, $role, $score) {
                            $user_details = get_user_details($conn, $id);
                            $full_name = $user_details['first_name'] . ' ' . $user_details['last_name'];
                            $age = date_diff(date_create($user_details['date_of_birth']), date_create('today'))->y;
                            $phone = $user_details['telephone'];
                            $linkedin = $user_details['linkedin'];
                            $photo = $user_details['photo'];
                            $score_percentage = calculate_score_percentage($score, $role);
                        
                            echo "
                            <div class='col-xl-3 col-md-6 mb-4'>
                                <div class='card shadow'>
                                    <div class='card-body'>
                                        <div class='row align-items-center'>
                                            <div class='col-auto'>
                                                <div class='icon-placeholder'>
                                                    <img src='$photo' alt='User photo'>
                                                </div>
                                            </div>
                                            <div class='col ml--2'>
                                                <span class='h2 font-weight-bold mb-0'>$full_name</span>
                                                <p class='mt-3 mb-0 text-sm'>
                                                    <span class='text-nowrap'>Age: $age years old</span><br>
                                                    <span class='text-nowrap'>" . ucfirst($role) . "</span><br>
                                                    <span class='text-nowrap'>Phone: $phone</span><br>
                                                    <span class='text-nowrap'><strong>Score: " . round($score_percentage, 2) . "%</strong></span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class='d-flex justify-content-center mt-3'>
                                        <a href='profile.php?id=$id' class='btn btn-circle btn-primary mx-2'>
                                            <i class='fas fa-user'></i>
                                        </a>";
                            if ($linkedin != null) {
                                echo "
                                        <a href='$linkedin' class='btn btn-circle btn-info mx-2'>
                                            <i class='fab fa-linkedin'></i>
                                        </a>";
                            }
                            echo "
                                    </div>
                                </div>
                            </div>
                            ";
                        }
                        ?>

                        <?php if ($role == 'third_year'): ?>
                            <div id="first-year-results" class="results-section active">
                                <h2>First Year Results</h2>
                                <div class="row">
                                    <?php
                                    $mentor_matches = find_matches_third_to_first($conn, $user_id);
                                    foreach ($mentor_matches as $match) {
                                        display_card($conn, $match['first_year_id'], 'first_year', $match['score']);
                                    }
                                    ?>
                                </div>
                            </div>
                            
                            <div id="laureate-results" class="results-section">
                                <h2>Laureate Results</h2>
                                <div class="row">
                                    <?php
                                    $mentee_matches = find_matches_third_to_laureate($conn, $user_id);
                                    foreach ($mentee_matches as $match) {
                                        display_card($conn, $match['laureate_id'], 'laureat', $match['score']);
                                    }
                                ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="row">
                                <?php
                                if ($role == 'first_year') {
                                    $mentor_matches = find_matches_first_to_third($conn, $user_id);
                                    foreach ($mentor_matches as $match) {
                                        display_card($conn, $match['third_year_id'], 'third_year', $match['score']);
                                    }
                                } elseif ($role == 'laureat') {
                                    $mentee_matches = find_matches_laureate_to_third($conn, $user_id);
                                    foreach ($mentee_matches as $match) {
                                        display_card($conn, $match['third_year_id'], 'third_year', $match['score']);
                                    }
                                }
                                ?>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
        <!-- Footer -->
    </div>
</div>
<!-- Argon Scripts -->
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<!-- Argon Dashboard JS -->
<script src="./assets/js/argon-dashboard.min.js?v=1.1.0"></script>

<!-- Core -->
<script src="./assets/vendor/jquery/dist/jquery.min.js"></script>
<script src="./assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="./assets/js/argon-dashboard.min.js?v=1.1.0"></script>

<script>
    $(document).ready(function(){
        $('#first-year-btn').click(function(){
            $('#first-year-results').addClass('active');
            $('#laureate-results').removeClass('active');
        });

        $('#laureate-btn').click(function(){
            $('#laureate-results').addClass('active');
            $('#first-year-results').removeClass('active');
        });
    });
</script>
</body>
</html>
