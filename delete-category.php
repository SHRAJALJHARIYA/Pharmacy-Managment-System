<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once('database/connect.php'); // Include database connection

// Check if user is logged in
if(empty($_SESSION['login_email'])) {
    header("Location: index.php");
    exit();
}

// Check if ID is provided
if(isset($_GET['id']) && is_numeric($_GET['id'])) { // Use 'id' (lowercase) consistently
    $category_id = intval($_GET['id']);

    try {
        // Delete the category
        $sql = "DELETE FROM tblcategory WHERE ID = ?"; // Ensure 'ID' matches the database column name
        $stmt = $dbh->prepare($sql);

        if($stmt->execute([$category_id])) {
            $_SESSION['success'] = "Category deleted successfully";
        } else {
            $_SESSION['error'] = "Failed to delete category";
        }
    } catch(PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
    }
} else {
    $_SESSION['error'] = "Invalid category ID";
}

// Redirect back to the category page
header("Location: add-category.php");
exit();
?>