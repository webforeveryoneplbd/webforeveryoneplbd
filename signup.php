<?php
session_start();
include '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $telephone = $_POST['telephone'];
    $description = $_POST['description'];
    $address = $_POST['address'];
    $date_of_birth = $_POST['date_of_birth'];
    $registration_date = date('Y-m-d'); // Set registration date to today's date
    $status = $_POST['description'];// Default status

    // Handle photo upload
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

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password, role, photo, telephone, description, address, date_of_birth, registration_date, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssssss", $first_name, $last_name, $email, $password, $role, $photo, $telephone, $description, $address, $date_of_birth, $registration_date, $status);

    // Execute the statement
    $stmt->execute();

    // Set session variables
    $_SESSION['user_id'] = $conn->insert_id;
    $_SESSION['role'] = $role;

    // Redirect based on role
    switch ($role) {
        case 'first_year':
            header("Location: form_1A.php");
            break;
        case 'third_year':
            header("Location: form_3A_mentor.php");
            break;
        case 'laureat':
            header("Location: form_laureat.php");
            break;
    }

    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sign Up</title>
</head>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap');
*{
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Poppins',sans-serif;
}
form .signup-link{
  color: #262626;
  margin-top: 20px;
  text-align: center;
}
form .pass-link a,
form .signup-link a{
  color: #4158d0;
  text-decoration: none;
}
form .pass-link a:hover,
form .signup-link a:hover{
  text-decoration: underline;
}
body{
  height: 100;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 10px;
  background: linear-gradient(-135deg, #14557a , 
#7acac3) ;}
.container{
  max-width: 800px;
  width: 100%;
  background-color: #fff;
  padding: 25px 30px;
  border-radius: 5px;
  box-shadow: 0 5px 10px rgba(0,0,0,0.15);
}
.container .title{
  font-size: 25px;
  font-weight: 500;
  position: relative;
}
.container .title::before{
  content: "";
  position: absolute;
  left: 0;
  bottom: 0;
  height: 3px;
  width: 30px;
  border-radius: 5px;
  background: linear-gradient(-135deg, #14557a , 
#7acac3);;
}
.content form .user-details{
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  margin: 20px 0 12px 0;
}
form .user-details .input-box{
  margin-bottom: 15px;
  width: calc(100% / 2 - 20px);
}
form .input-box span.details{
  display: block;
  font-weight: 500;
  margin-bottom: 5px;
}
.user-details .input-box input{
  height: 45px;
  width: 100%;
  outline: none;
  font-size: 16px;
  border-radius: 5px;
  padding-left: 15px;
  border: 1px solid #ccc;
  border-bottom-width: 2px;
  transition: all 0.3s ease;
}
.user-details .input-box input:focus,
.user-details .input-box input:valid{
  border-color: #9b59b6;
}
 form .gender-details .gender-title{
  font-size: 20px;
  font-weight: 500;
 }
 form .category{
   display: flex;
   width: 80%;
   margin: 14px 0 ;
   justify-content: space-between;
 }
 form .category label{
   display: flex;
   align-items: center;
   cursor: pointer;
 }
 form .category label .dot{
  height: 18px;
  width: 18px;
  border-radius: 50%;
  margin-right: 10px;
  background: #d9d9d9;
  border: 5px solid transparent;
  transition: all 0.3s ease;
}
 #dot-1:checked ~ .category label .one,
 #dot-2:checked ~ .category label .two,
 #dot-3:checked ~ .category label .three{
   background: #9b59b6;
   border-color: #d9d9d9;
 }
 form input[type="radio"]{
   display: none;
 }
 form .button{
   height: 45px;
   margin: 35px 0
 }
 form .button input{
   height: 100%;
   width: 100%;
   border-radius: 5px;
   border: none;
   color: #fff;
   font-size: 18px;
   font-weight: 500;
   letter-spacing: 1px;
   cursor: pointer;
   transition: all 0.3s ease;
   background: linear-gradient(-135deg, 
#7acac3, #14557a) ;
border-radius:  25px; }
 
 @media(max-width: 584px){
 .container{
  max-width: 100%;
}
form .user-details .input-box{
    margin-bottom: 15px;
    width: 100%;
  }
  form .category{
    width: 100%;
  }
  .content form .user-details{
    max-height: 300px;
    overflow-y: scroll;
  }
  .user-details::-webkit-scrollbar{
    width: 5px;
  }
  }
  @media(max-width: 459px){
  .container .content .category{
    flex-direction: column;
  }
}
.user-details .input-box select,
.user-details .input-box textarea {
  height: 45px;
  width: 100%;
  outline: none;
  font-size: 16px;
  border-radius: 5px;
  padding-left: 15px;
  border: 1px solid #ccc;
  border-bottom-width: 2px;
  transition: all 0.3s ease;
}

.user-details .input-box select:focus,
.user-details .input-box select:valid,
.user-details .input-box textarea:focus,
.user-details .input-box textarea:valid {
  border-color: #9b59b6;
}

.user-details .input-box select {
  appearance: none; /* Remove default dropdown arrow */
  cursor: pointer;
}

.user-details .input-box::after {
  content: "";
  position: absolute;
  right: 15px;
  top: 50%;
  transform: translateY(-50%);
  width: 0;
  height: 0;
  border-left: 5px solid transparent;
  border-right: 5px solid transparent;
  border-top: 8px solid #ccc;
  transition: all 0.3s ease;
}

.user-details .input-box select:focus::after {
  border-top-color: #9b59b6;
}

.user-details .input-box textarea {
  height: 100px; /* Adjust height as needed */
  padding-top: 10px;
}

</style>
<body>
<div class="container">
    <div class="title">Sign Up</div>
    <div class="content">
     <form method="post" action="" enctype="multipart/form-data">
  <div class="user-details">
    <div class="input-box">
      <span class="details">First Name</span>
      <input type="text" placeholder="Enter your first name" name="first_name" required>
    </div>
    <div class="input-box">
      <span class="details">Last Name</span>
      <input type="text" placeholder="Enter your last name" name="last_name" required>
    </div>
    <div class="input-box">
      <span class="details">Email</span>
      <input type="email" placeholder="Enter your email" name="email" required>
    </div>
    <div class="input-box">
      <span class="details">Password</span>
      <input type="password" placeholder="Enter your password" name="password" required>
    </div>
    <div class="input-box">
      <span class="details">Role</span>
      <select name="role" required>
        <option value="first_year">1st Year</option>
        <option value="third_year">3rd Year</option>
        <option value="laureat">Laureat</option>
      </select>
    </div>
    <div class="input-box photo-upload">
                    <span class="details">Photo</span>
                    <div class="photo-container">
                        <input type="file" name="photo" id="photo" required>
                        <label for="photo">
                            <i class="camera-icon"></i>
                        </label>
                    </div>
                </div>
    <div class="input-box">
      <span class="details">Telephone</span>
      <input type="text" placeholder="Enter your telephone number" name="telephone" required>
    </div><div class="input-box">
      <span class="details">Date of Birth</span>
      <input type="date" name="date_of_birth" required>
    </div>
    <div class="input-box">
      <span class="details">Description</span>
      <textarea placeholder="Enter a description" name="description" required></textarea>
</div>
    <div class="input-box">
      <span class="details">Address</span>
      <textarea placeholder="Enter your address" name="address" required></textarea>
    </div>
    
  </div>

  <div class="button">
    <input type="submit" value="Signup">
  </div>
  <div class="signup-link">
              Already a member?<a href="login.php">Login now</a>
            </div>
</form>

    </div>
  </div>
   
</body>
</html>
