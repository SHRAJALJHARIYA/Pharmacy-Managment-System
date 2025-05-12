<?php
session_start();
require_once('database/connect.php'); // Include database connection

// Check if user is logged in
if(empty($_SESSION['login_email'])) {
    header("Location: index.php");
    exit();
}

// Check if product ID is provided
if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = 'Invalid product ID';
    header("Location: add-product.php");
    exit();
}

$product_id = intval($_GET['id']);

// Handle form submission
if(isset($_POST["btnupdate"])) {
    $product_name = trim($_POST['txtproduct_name']);
    $category = trim($_POST['cmdcategory']);
    $expirydate = trim($_POST['txtexpirydate']);
    $qty = intval($_POST['txtqty']);
    $price = floatval($_POST['txtprice']);
    $photo = $_FILES['avatar']['name'];

    // Validate required fields
    if(empty($product_name) || empty($category) || empty($expirydate) || empty($qty) || empty($price)) {
        $_SESSION['error'] = 'All fields are required';
    } else {
        try {
            // Handle photo upload if a new photo is provided
            if(!empty($photo)) {
                $file_type = $_FILES['avatar']['type'];
                $allowed = array("image/jpg", "image/jpeg", "image/png");
                if(!in_array($file_type, $allowed)) {
                    $_SESSION['error'] = 'Only jpg, jpeg, and png files are allowed';
                } else {
                    $image = addslashes(file_get_contents($_FILES['avatar']['tmp_name']));
                    $image_name = addslashes($_FILES['avatar']['name']);
                    move_uploaded_file($_FILES["avatar"]["tmp_name"], "uploadImage/" . $_FILES["avatar"]["name"]);
                    $location = "uploadImage/" . $_FILES["avatar"]["name"];

                    // Update query with photo
                    $sql = 'UPDATE tblproduct SET product_name = ?, category = ?, expirydate = ?, qty = ?, price = ?, photo = ? WHERE ID = ?';
                    $stmt = $dbh->prepare($sql);
                    $stmt->execute([$product_name, $category, $expirydate, $qty, $price, $location, $product_id]);
                }
            } else {
                // Update query without photo
                $sql = 'UPDATE tblproduct SET product_name = ?, category = ?, expirydate = ?, qty = ?, price = ? WHERE ID = ?';
                $stmt = $dbh->prepare($sql);
                $stmt->execute([$product_name, $category, $expirydate, $qty, $price, $product_id]);
            }

            if($stmt) {
                $_SESSION['success'] = 'Product updated successfully';
                header("Location: add-product.php");
                exit();
            } else {
                $_SESSION['error'] = 'Failed to update product';
            }
        } catch(PDOException $e) {
            $_SESSION['error'] = 'Database error: ' . $e->getMessage();
        }
    }
}

// Fetch current product details
try {
    $stmt = $dbh->prepare("SELECT * FROM tblproduct WHERE ID = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();

    if(!$product) {
        $_SESSION['error'] = 'Product not found';
        header("Location: add-product.php");
        exit();
    }
} catch(PDOException $e) {
    $_SESSION['error'] = 'Database error: ' . $e->getMessage();
    header("Location: add-product.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Product - Admin Dashboard</title>
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="wrapper">
    <!-- <?php include('topbar.php'); ?>
    <?php include('sidebar.php'); ?> -->

    <div class="content-wrapper">
        <div class="container mt-5">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Edit Product</h3>
                </div>
                <form method="post" action="" enctype="multipart/form-data">
                    <div class="card-body">
                        <?php if(isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                        <?php endif; ?>
                        <?php if(isset($_SESSION['success'])): ?>
                            <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
                        <?php endif; ?>

                        <div class="form-group">
                            <label>Product Name</label>
                            <input type="text" class="form-control" name="txtproduct_name" value="<?php echo htmlspecialchars($product['product_name']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Category</label>
                            <select name="cmdcategory" class="form-control" required>
                                <option value="">Select Category</option>
                                <?php
                                $categories = $dbh->query("SELECT * FROM tblcategory")->fetchAll();
                                foreach($categories as $category) {
                                    $selected = ($category['category_name'] == $product['category']) ? 'selected' : '';
                                    echo "<option value='{$category['category_name']}' $selected>{$category['category_name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Expiry Date</label>
                            <input type="date" class="form-control" name="txtexpirydate" value="<?php echo htmlspecialchars($product['expirydate']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Quantity</label>
                            <input type="number" class="form-control" name="txtqty" value="<?php echo htmlspecialchars($product['qty']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Price</label>
                            <input type="number" class="form-control" name="txtprice" value="<?php echo htmlspecialchars($product['price']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Image</label>
                            <input type="file" class="form-control" name="avatar">
                            <img src="<?php echo $product['photo']; ?>" alt="Product Image" width="100" class="mt-2">
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" name="btnupdate" class="btn btn-primary">Update Product</button>
                        <a href="add-product.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>
