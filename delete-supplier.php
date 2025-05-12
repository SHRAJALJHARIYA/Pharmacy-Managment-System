<?php
session_start();
require_once('database/connect.php'); // Include database connection

// Check if user is logged in
if(empty($_SESSION['login_email'])) {
    header("Location: index.php");
    exit();
}

// Check if ID is provided
if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $supplier_id = intval($_GET['id']);

    try {
        // Delete the supplier
        $sql = "DELETE FROM tblsupplier WHERE ID = ?";
        $stmt = $dbh->prepare($sql);

        if($stmt->execute([$supplier_id])) {
            $_SESSION['success'] = "Supplier deleted successfully";
        } else {
            $_SESSION['error'] = "Failed to delete supplier";
        }
    } catch(PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
    }
} else {
    $_SESSION['error'] = "Invalid supplier ID";
}

// Redirect back to the supplier page
header("Location: add-supplier.php");
exit();
?>