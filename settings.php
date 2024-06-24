<?php
session_start();
require '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'] ? password_hash($_POST['password'], PASSWORD_DEFAULT) : $user['password'];
    $photo = $_POST['photo'];
    $telephone = $_POST['telephone'];
    $description = $_POST['description'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $address = $_POST['address'];
    $date_of_birth = $_POST['date_of_birth'];
    $linkedin = $_POST['linkedin'];
    $photo = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $photoTmpPath = $_FILES['photo']['tmp_name'];
        $photoName = basename($_FILES['photo']['name']);
        $photoSize = $_FILES['photo']['size'];
        $photoType = $_FILES['photo']['type'];
        $photoExtension = strtolower(pathinfo($photoName, PATHINFO_EXTENSION));
        $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');

        if (in_array($photoExtension, $allowedExtensions)) {
            $uploadDir = '../uploads/photos/';
            $photoNewName = uniqid() . '.' . $photoExtension;
            $photoPath = $uploadDir . $photoNewName;

            if (move_uploaded_file($photoTmpPath, $photoPath)) {
                $photo = $photoPath;
            } else {
                echo "Error moving the uploaded file.";
                exit();
            }
        } else {
            echo "Invalid file type.";
            exit();
        }
    }
    $update_query = "UPDATE users SET email = ?, password = ?, photo = ?, telephone = ?, description = ?, first_name = ?, last_name = ?, address = ?, date_of_birth = ?, linkedin = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("ssssssssssi", $email, $password, $photo, $telephone, $description, $first_name, $last_name, $address, $date_of_birth, $linkedin, $user_id);

    if ($update_stmt->execute()) {
        header('Location: settings.php?success=1');
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Settings</title>
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
                        <a href="dashboard.php" class="dropdown-item">
                                <i class="fa fa-home"></i>
                                <span>Home</span>
                            </a>
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
                    
                    <div class="card-body">
                        <h1>Settings</h1>
                        <?php if (isset($_GET['success'])): ?>
                            <div class="alert alert-success">
                                Profile updated successfully!
                            </div>
                        <?php endif; ?>
                        <form action="settings.php" method="POST" enctype="multipart/form-data" >
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input required type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password (leave blank to keep current password)</label>
                                <input required type="password" class="form-control" id="password" name="password">
                            </div>
                            <div class="form-group">
                                <label for="photo">Photo URL</label>
                                <input required type="file" class="form-control" id="photo" name="photo" value="<?php echo htmlspecialchars($user['photo']); ?>">
                            </div>
                            <div class="form-group">
                                <label for="telephone">Telephone</label>
                                <input required type="text" class="form-control" id="telephone" name="telephone" value="<?php echo htmlspecialchars($user['telephone']); ?>">
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea required class="form-control" id="description" name="description"><?php echo htmlspecialchars($user['status']); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="first_name">First Name</label>
                                <input required type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>">
                            </div>
                            <div class="form-group">
                                <label for="last_name">Last Name</label>
                                <input required type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>">
                            </div>
                            <div class="form-group">
                                <label for="address">Address</label>
                                <textarea required class="form-control" id="address" name="address"><?php echo htmlspecialchars($user['address']); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="date_of_birth">Date of Birth</label>
                                <input required type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="<?php echo htmlspecialchars($user['date_of_birth']); ?>">
                            </div>
                            <div class="form-group">
                                <label for="linkedin">LinkedIn Profile</label>
                                <input required type="url" class="form-control" id="linkedin" name="linkedin" value="<?php echo htmlspecialchars($user['linkedin']); ?>">
                            </div>
                            <button type="submit" class="btn btn-primary">Update Profile</button>
                            <button type="submit" class="btn btn-danger">Cancel</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
        <footer class="footer">
            <div class="row align-items-center justify-content-xl-between">
                <div class="col-xl-6">
                    <div class="copyright text-center text-xl-left text-muted">
                        &copy; <?php echo date("Y"); ?> <a href="https://www.creative-tim.com" class="font-weight-bold ml-1" target="_blank">Creative Tim</a>
                    </div>
                </div>
                <div class="col-xl-6">
                    <ul class="nav nav-footer justify-content-center justify-content-xl-end">
                        <li class="nav-item">
                            <a href="https://www.creative-tim.com" class="nav-link" target="_blank">Creative Tim</a>
                        </li>
                        <li class="nav-item">
                            <a href="https://www.creative-tim.com/presentation" class="nav-link" target="_blank">About Us</a>
                        </li>
                        <li class="nav-item">
                            <a href="https://www.creative-tim.com/blog" class="nav-link" target="_blank">Blog</a>
                        </li>
                        <li class="nav-item">
                            <a href="https://www.creative-tim.com/license" class="nav-link" target="_blank">License</a>
                        </li>
                    </ul>
                </div>
            </div>
        </footer>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
