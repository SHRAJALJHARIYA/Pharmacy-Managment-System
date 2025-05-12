<?php
session_start();
require_once('database/connect.php'); // Database connection file

// Check if user is logged in
if(empty($_SESSION['login_email'])) {
    header("Location: index.php"); 
    exit();
}

// Check if category ID is provided
if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = 'Invalid category ID';
    header("Location: categories.php");
    exit();
}

$category_id = intval($_GET['id']);

// Handle form submission
if(isset($_POST["btnupdate"])) {
    $category_name = trim($_POST['txtcategory_name']);
    
    if(empty($category_name)) {
        $_SESSION['error'] = 'Category name cannot be empty';
    } else {
        try {
            // Check if category already exists (excluding current category)
            $stmt = $dbh->prepare("SELECT * FROM tblcategory WHERE category_name = ? AND ID != ?");
            $stmt->execute([$category_name, $category_id]); 
            
            if ($stmt->rowCount() > 0) {
                $_SESSION['error'] = 'Category already exists in our database';
            } else {
                // Update category
                $sql = 'UPDATE tblcategory SET category_name = ? WHERE ID = ?';
                $statement = $dbh->prepare($sql);
                
                if ($statement->execute([$category_name, $category_id])) {
                    $_SESSION['success'] = 'Category updated successfully';
                    header("Location: add-category.php");
                    exit();
                }
            }
        } catch(PDOException $e) {
            $_SESSION['error'] = 'Database error: ' . $e->getMessage();
        }
    }
}

// Get current category data
try {
    $stmt = $dbh->prepare("SELECT * FROM tblcategory WHERE ID = ?");
    $stmt->execute([$category_id]);
    $category = $stmt->fetch();

    if(!$category) {
        $_SESSION['error'] = 'Category not found';
        header("Location: categories.php");
        exit();
    }
} catch(PDOException $e) {
    $_SESSION['error'] = 'Database error: ' . $e->getMessage();
    header("Location: categories.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Category - Admin Dashboard</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    .container {
        max-width: 600px;
        margin-top: 50px;
    }
    .form-container {
        background-color: #fff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>
    
  <div class="container">
    <div class="form-container">
      <h2 class="text-center mb-4"><i class="fas fa-edit me-2"></i>Edit Category</h2>
      
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
      
      <form method="post" action="">
        <div class="mb-3">
          <label for="category_name" class="form-label">Category Name</label>
          <input type="text" class="form-control form-control-lg" id="category_name" 
                 name="txtcategory_name" value="<?php echo htmlspecialchars($category['category_name']); ?>" 
                 required>
        </div>
        
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
          <a href="add-category.php" class="btn btn-secondary me-md-2">
            <i class="fas fa-times me-1"></i> Cancel
          </a>
          <button type="submit" name="btnupdate" class="btn btn-primary">
            <i class="fas fa-save me-1"></i> Update Category
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Bootstrap JS Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>