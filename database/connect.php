<?php 
// DB credentials.
define('DB_HOST', 'localhost');
define('DB_NAME', 'pharmadb');  // Changed from product_expiry_goodness
define('DB_USER', 'root');
define('DB_PASS', '');

// Establish database connection.
try {
    $dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME,DB_USER, DB_PASS);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection Error: " . $e->getMessage();
}
?>

