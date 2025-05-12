<?php
session_start();
require_once('database/connect.php'); // Include database connection

// Check if user is logged in
if(empty($_SESSION['login_email'])) {
    header("Location: index.php");
    exit();
}

// Check if supplier ID is provided
if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = 'Invalid supplier ID';
    header("Location: add-supplier.php");
    exit();
}

$supplier_id = intval($_GET['id']);

// Handle form submission
if(isset($_POST["btnupdate"])) {
    $supplier_name = trim($_POST['txtsupplier']);
    $contact_no = trim($_POST['txtcontact']);
    $address = trim($_POST['txtaddress']);
    
    if(empty($supplier_name) || empty($contact_no) || empty($address)) {
        $_SESSION['error'] = 'All fields are required';
    } else {
        try {
            // Update supplier details
            $sql = 'UPDATE tblsupplier SET supplier_name = ?, contact_no = ?, address = ? WHERE ID = ?';
            $stmt = $dbh->prepare($sql);
            
            if($stmt->execute([$supplier_name, $contact_no, $address, $supplier_id])) {
                $_SESSION['success'] = 'Supplier updated successfully';
                header("Location: add-supplier.php");
                exit();
            } else {
                $_SESSION['error'] = 'Failed to update supplier';
            }
        } catch(PDOException $e) {
            $_SESSION['error'] = 'Database error: ' . $e->getMessage();
        }
    }
}

// Fetch current supplier details
try {
    $stmt = $dbh->prepare("SELECT * FROM tblsupplier WHERE ID = ?");
    $stmt->execute([$supplier_id]);
    $supplier = $stmt->fetch();

    if(!$supplier) {
        $_SESSION['error'] = 'Supplier not found';
        header("Location: add-supplier.php");
        exit();
    }
} catch(PDOException $e) {
    $_SESSION['error'] = 'Database error: ' . $e->getMessage();
    header("Location: add-supplier.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Supplier - Admin Dashboard</title>
  <!-- Include AdminLTE CSS -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .content-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        background-color: #f4f6f9;
    }
    .card {
        width: 100%;
        max-width: 600px;
    }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <!-- Include Top Bar -->
    <!-- <?php include('topbar.php'); ?> -->
    
    <!-- Include Sidebar -->
    <!-- <?php include('sidebar.php'); ?> -->

    <!-- Content Wrapper -->
    <div class="content-wrapper" style="user-select: text;margin: 2px;">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-edit me-2"></i>Edit Supplier</h3>
            </div>
            <form method="post" action="">
                <div class="card-body">
                    <?php if(isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if(isset($_SESSION['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <label for="supplier_name" class="form-label">Supplier Name</label>
                        <input type="text" class="form-control" id="supplier_name" 
                               name="txtsupplier" value="<?php echo htmlspecialchars($supplier['supplier_name']); ?>" 
                               required>
                    </div>
                    <div class="mb-3">
                        <label for="contact_no" class="form-label">Contact Number</label>
                        <input type="text" class="form-control" id="contact_no" 
                               name="txtcontact" value="<?php echo htmlspecialchars($supplier['contact_no']); ?>" 
                               pattern="[0-9]{10}" title="Please enter a valid 10-digit number" required>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="txtaddress" rows="3" required><?php echo htmlspecialchars($supplier['address']); ?></textarea>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <a href="add-supplier.php" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i> Cancel
                    </a>
                    <button type="submit" name="btnupdate" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Update Supplier
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Include AdminLTE JS -->
<script src="dist/js/adminlte.min.js"></script>
<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>