<?php
include 'topbar.php'; 
include 'function_backup.php'; // Include the backup function

if(isset($_POST['btnbackup'])) {
    // Get credentials via POST
    $servername_db = $_POST['txtservername'];
    $username_db = $_POST['txtusername'];
    $password_db = $_POST['txtpassword'];
    $dbname_db = $_POST['txtdbname'];

    // Call the backup function
    backDb($servername_db, $username_db, $password_db, $dbname_db);

    // Log the backup activity
    $current_date = date('Y-m-d H:i:s');
    $fullname = $_SESSION['login_email']; // Assuming the user's email is stored in the session
    $task = $fullname . ' Backup database On ' . $current_date;

    $query2 = "INSERT INTO `activity_log` (task) VALUES ('$task')";
    $result2 = mysqli_query($conn, $query2);

    $_SESSION['success'] = "Database backup successfully";
    header("Location: backup_db.php");
    exit();
}
?>
