<?php
include('topbar.php');
if(empty($_SESSION['login_email'])) {   
    header("Location: index.php"); 
} else {
    // Continue with the page
}
      
$email = $_SESSION["login_email"];
$stmt = $dbh->query("SELECT * FROM users where email='$email'");
$row_user = $stmt->fetch();

if(isset($_POST["btnsave"])) {
    $category_name = $_POST['txtcategory_name'];
			
    // Check if category already exists
    $stmt = $dbh->prepare("SELECT * FROM tblcategory WHERE category_name=?");
    $stmt->execute([$category_name]); 
    $row_category = $stmt->fetch();

    if ($row_category) {
        $_SESSION['error'] = 'Category Already Exists in our Database';
    } else {
        // Add category details
        $sql = 'INSERT INTO tblcategory(category_name) VALUES(:category_name)';
        $statement = $dbh->prepare($sql);
        $statement->execute([
            ':category_name' => $category_name
        ]);
        
        if ($statement) {
            $_SESSION['success'] = 'Category Added Successfully';
        } else {
            $_SESSION['error'] = 'Problem Adding Category';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Add Category - Admin Dashboard</title>
  <link rel="shortcut icon" href="../assets/logo.png" type="image/x-icon" />
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  
  <style>
    .card-primary:not(.card-outline) > .card-header {
      background-color: #28a745;
    }
    .btn-primary {
      background-color: #28a745;
      border-color: #28a745;
    }
    .btn-primary:hover {
      background-color: #218838;
      border-color: #1e7e34;
    }
    .page-item.active .page-link {
      background-color: #28a745;
      border-color: #28a745;
    }
    .nav-sidebar .nav-item > .nav-link.active {
      background-color: #28a745;
    }
    .category-form {
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
      position: sticky;
      top: 20px;
    }
    .category-table {
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }
    .card {
      border-radius: 8px;
      margin-bottom: 20px;
    }
    .card-header {
      border-radius: 8px 8px 0 0;
      padding: 15px;
    }
    .input-group-text {
      background-color: #28a745;
      color: white;
      border: none;
    }
    .btn-block {
      padding: 10px;
    }
    .table thead th {
      background-color: #f4f6f9;
      border-bottom: 2px solid #28a745;
    }
    .table td, .table th {
      vertical-align: middle;
    }
    .btn {
      border-radius: 4px;
      padding: 8px 16px;
    }
  </style>
  
  <script type="text/javascript">
    function deldata(){
      if(confirm("ARE YOU SURE YOU WISH TO DELETE THIS CATEGORY FROM THE DATABASE?")) {
        return true;
      } else {
        return false;
      }
    }
  </script>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
  
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="dashboard.php" class="nav-link"><i class="fas fa-home"></i> Home</a>
      </li>
    </ul>

    <!-- SEARCH FORM -->
    <form class="form-inline ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" href="logout.php" title="Logout">
          <i class="fas fa-sign-out-alt"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index.php" class="brand-link">
      <img src="../assets/logo.png" alt="" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light"></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="<?php echo $row_user['photo']; ?>" alt="User Image" class="img-circle elevation-2">
        </div>
        <div class="info">
          <a href="#" class="d-block"><?php echo $row_user['fullname']; ?></a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <?php include('sidebar.php'); ?>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    
    <!-- Content Header (Page header) -->
     <div class="col-12 col-sm-6 col-md-3" style="user-select: text;">
            <div class="info-box" style="user-select: text;">
              <span class="info-box-icon elevation-1" style="background-color: #28a745; user-select: text;">
  <i class="fa fa-users" id="icon" style="user-select: text;"></i>
</span>


              <div class="info-box-content" style="user-select: text;">
                <span class="info-box-text" style="user-select: text;">Total Category </span>
                <span class="info-box-number" style="user-select: text;">
                  <?php
                  $count_query = $dbh->query("SELECT COUNT(*) FROM tblcategory");
                  echo $count_query->fetchColumn();
                  ?><small style="user-select: text;"></small>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1><i class="fas fa-tags nav-icon"></i> Category Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">Add Category</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- Move the table to left column -->
          <div class="col-md-8">
            <div class="card card-primary category-table">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-list-alt"></i> Category Records</h3>
              </div>
              <div class="card-body">
                <table class="table table-bordered table-striped" id="categoryTable">
                  <thead>
                    <tr>
                      <th width="5%"><div align="center">#</div></th>
                      <th width="65%"><div align="center"><i class="fas fa-tag"></i> Category Name</div></th>
                      <th width="30%"><div align="center"><i class="fas fa-cogs"></i> Actions</div></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                    $data = $dbh->query("SELECT * FROM tblcategory ORDER BY category_name ASC")->fetchAll();
                    $cnt=1;
                    foreach ($data as $row) {
                    ?>
                    <tr>
                      <td><div align="center"><?php echo $cnt; ?></div></td>
                      <td><?php echo $row['category_name']; ?></td>
                      <td>     
                        <div align="center">
                          <a href="edit-category.php?id=<?php echo $row['ID']; ?>" class="btn btn-info btn-sm mr-1" title="Edit">
                              <i class="fas fa-edit"></i> Edit
                          </a>
                          <a href="delete-category.php?id=<?php echo $row['ID']; ?>" onclick="return deldata();" class="btn btn-danger btn-sm" title="Delete">
                              <i class="fas fa-trash-alt"></i> Delete
                          </a>
                        </div>
                      </td>
                    </tr>
                    <?php $cnt=$cnt+1;} ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Move the form to right column -->
          <div class="col-md-4">
            <div class="card card-primary category-form">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-plus-circle"></i> Add New Category</h3>
              </div>
              <div class="card-body">
                <form id="form" action="" method="post">
                  <div class="form-group">
                    <label for="categoryName"><i class="fas fa-tag"></i> Category Name</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-tag"></i></span>
                      </div>
                      <input type="text" class="form-control" name="txtcategory_name" id="categoryName" 
                             placeholder="Enter category name" 
                             value="<?php if (isset($_POST['txtcategory_name'])) echo $_POST['txtcategory_name']; ?>" required>
                    </div>
                  </div>
                  <div class="form-group text-right">
                    <button type="submit" name="btnsave" class="btn btn-primary btn-block">
                      <i class="fas fa-save"></i> Save Category
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 1.0
    </div>
    <strong><?php include '../footer.php' ?></strong>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- bs-custom-file-input -->
<script src="plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<!-- DataTables -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- Page specific script -->
<script>
$(function () {
  bsCustomFileInput.init();
  
  // Initialize DataTable for better sorting and pagination
  $('#categoryTable').DataTable({
    "paging": true,
    "lengthChange": false,
    "searching": true,
    "ordering": true,
    "info": true,
    "autoWidth": false,
    "responsive": true,
    "pageLength": 10
  });
});
</script>

<link rel="stylesheet" href="popup_style.css">
<?php if(!empty($_SESSION['success'])) {  ?>
<div class="popup popup--icon -success js_success-popup popup--visible">
  <div class="popup__background"></div>
  <div class="popup__content">
    <h3 class="popup__content__title">
      <strong>Success</strong> 
    </h3>
    <p><?php echo $_SESSION['success']; ?></p>
    <p>
      <button class="button button--success" data-for="js_success-popup">Close</button>
    </p>
  </div>
</div>
<?php unset($_SESSION["success"]);  
} ?>
<?php if(!empty($_SESSION['error'])) {  ?>
<div class="popup popup--icon -error js_error-popup popup--visible">
  <div class="popup__background"></div>
  <div class="popup__content">
    <h3 class="popup__content__title">
      <strong>Error</strong> 
    </h3>
    <p><?php echo $_SESSION['error']; ?></p>
    <p>
      <button class="button button--error" data-for="js_error-popup">Close</button>
    </p>
  </div>
</div>
<?php unset($_SESSION["error"]);  } ?>
<script>
  var addButtonTrigger = function addButtonTrigger(el) {
    el.addEventListener('click', function () {
      var popupEl = document.querySelector('.' + el.dataset.for);
      popupEl.classList.toggle('popup--visible');
    });
  };

  Array.from(document.querySelectorAll('button[data-for]')).forEach(addButtonTrigger);
</script>
</body>
</html>