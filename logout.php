
<?php


// Destroy all session data
session_unset();
session_destroy();

// Redirect to login page
header("Location: ../public/login.php");
exit();
?>
