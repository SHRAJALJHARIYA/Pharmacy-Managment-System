<?php
session_start();
require_once('database/connect.php');

if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    $session_id = session_id();
    
    $sql = "DELETE FROM tblcart WHERE id = ? AND session_id = ?";
    $stmt = $dbh->prepare($sql);
    
    if($stmt->execute([$id, $session_id])) {
        $_SESSION['success'] = 'Item removed from cart';
    } else {
        $_SESSION['error'] = 'Failed to remove item';
    }
}

header('Location: add-stock.php');
exit();