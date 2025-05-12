<?php
include('topbar.php');
if(empty($_SESSION['login_email']))
    {   
    header("Location: index.php"); 
    }
    else{
	}
      
$email = $_SESSION["login_email"];

$stmt = $dbh->query("SELECT * FROM users where email='$email'");
$row_user = $stmt->fetch();

 
if(isset($_POST["btnsave"])) {
    $supplier = $_POST['txtsupplier'];
    $contact = $_POST['txtcontact'];
    $address = $_POST['txtaddress'];
    
    // Check if supplier already exists
    $check_sql = "SELECT COUNT(*) FROM tblsupplier WHERE supplier_name = :supplier_name";
    $check_statement = $dbh->prepare($check_sql);
    $check_statement->execute([':supplier_name' => $supplier]);
    $count = $check_statement->fetchColumn();
    
    if($count > 0) {
        $_SESSION['error'] = 'Supplier name already exists. Please use a different name.';
    } else {
        $sql = 'INSERT INTO tblsupplier(supplier_name, contact_no, address) 
                VALUES(:supplier_name, :contact_no, :address)';
        $statement = $dbh->prepare($sql);
        
        if($statement->execute([
            ':supplier_name' => $supplier,
            ':contact_no' => $contact,
            ':address' => $address
        ])) {
            $_SESSION['success'] = 'Supplier Added Successfully';
        } else {
            $_SESSION['error'] = 'Problem Adding Supplier';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Add Supplier - Admin Dashboard</title>
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
  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <!-- summernote -->
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
  <!-- Custom Styles -->
  <style>
    .card-primary {
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
      margin-bottom: 20px;
    }
    .card-primary .card-header {
      background: linear-gradient(135deg, #4e54c8, #8f94fb);
      border-radius: 10px 10px 0 0;
      border-bottom: none;
    }
    .btn-primary {
      background: linear-gradient(135deg, #4e54c8, #8f94fb);
      border: none;
      box-shadow: 0 3px 5px rgba(0,0,0,0.2);
      transition: all 0.3s ease;
    }
    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 10px rgba(0,0,0,0.3);
      background: linear-gradient(135deg, #3a3fc4, #7a7feb);
    }
    .btn-danger {
      background: linear-gradient(135deg, #ff416c, #ff4b2b);
      border: none;
      box-shadow: 0 3px 5px rgba(0,0,0,0.2);
      transition: all 0.3s ease;
    }
    .btn-danger:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 10px rgba(0,0,0,0.3);
      background: linear-gradient(135deg, #f63b66, #f44025);
    }
    .form-control {
      border-radius: 8px;
      border: 1px solid #dce4ec;
      transition: all 0.3s ease;
    }
    .form-control:focus {
      border-color: #8f94fb;
      box-shadow: 0 0 0 0.2rem rgba(143, 148, 251, 0.25);
    }
    .table thead th {
      background-color: #f5f7ff;
      color: #495057;
      border-bottom: 2px solid #8f94fb;
    }
    .table-striped tbody tr:nth-of-type(odd) {
      background-color: #f9faff;
    }
    .table-hover tbody tr:hover {
      background-color: #eef1ff;
    }
    .info-box-icon {
      background: linear-gradient(135deg, #4e54c8, #8f94fb);
    }
    .content-wrapper {
      background-color: #f5f7ff;
    }
    label {
      color: #4e54c8;
      font-weight: 600;
    }
    .btn-block {
      font-weight: 600;
      font-size: 16px;
      padding: 10px;
    }
    .breadcrumb {
      background-color: transparent;
    }
    .breadcrumb-item a {
      color: #4e54c8;
    }
    .input-group-append .btn-navbar {
      background-color: #4e54c8;
      color: white;
    }
    .main-footer {
      background-color: #ffffff;
      border-top: 1px solid #e0e6ed;
    }
    .table td {
      vertical-align: middle;
    }
    .card-body {
      padding: 1.5rem;
    }
    .form-group {
      margin-bottom: 1.2rem;
    }
    /* Animation for form elements */
    .form-control, .btn {
      animation: fadeIn 0.5s ease-in-out;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    /* Animated icon pulse */
    .info-box-icon i {
      animation: pulse 2s infinite;
    }
    @keyframes pulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.1); }
      100% { transform: scale(1); }
    }
    /* Required field indicator */
    .required-field::after {
      content: "*";
      color: #ff416c;
      margin-left: 4px;
    }
  </style>
  <script type="text/javascript">
		function deldata(){
if(confirm("ARE YOU SURE YOU WISH TO DELETE THIS SUPPLIER FROM THE DATABASE ?"))
{
return  true;
}
else {return false;
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
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Home</a>      </li>
      
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
 
      
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index.php" class="brand-link">
	        <span class="brand-text font-weight-light"></span>    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="<?php echo $row_user['photo'];    ?>" alt="User Image" width="140" height="141" class="img-circle elevation-2">        </div>
        <div class="info">
          <a href="#" class="d-block"><?php echo $row_user['fullname'];  ?></a>
        </div>
      </div>

     

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
         
		 <?php
			   include('sidebar.php');
			   
			   ?>
		 

	
		 
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
  <!-- Content Wrapper. Contains page content -->
   
  <div class="content-wrapper">
    <div class="col-12 col-sm-6 col-md-3" style="user-select: text;">
            <div class="info-box" style="user-select: text;">
              <span class="info-box-icon elevation-1" style="user-select: text;"><i class="fa fa-users" id="icon" style="user-select: text;"></i></span>

              <div class="info-box-content" style="user-select: text;">
                <span class="info-box-text" style="user-select: text;">No. Of Supplier(s) </span>
                <span class="info-box-number" style="user-select: text;">
                  <?php
                  $count_query = $dbh->query("SELECT COUNT(*) FROM tblsupplier");
                  echo $count_query->fetchColumn();
                  ?>
                  <small style="user-select: text;"></small>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1><i class="fas fa-truck-loading mr-2"></i>Supplier Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">Add Supplier</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- SWITCHED: Now records are on the left -->
          <div class="col-md-8">
            <!-- general form elements disabled -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-list mr-2"></i>Supplier Records</h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped table-hover">
                  <thead>
                    <tr>
                      <th width="5%">#</th>
                      <th width="25%">Supplier Name</th>
                      <th width="20%">Contact Number</th>
                      <th width="30%">Address</th>
                      <th width="20%">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $sql = "SELECT * FROM tblsupplier ORDER BY supplier_name ASC";
                    $query = $dbh->prepare($sql);
                    $query->execute();
                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                    $cnt = 1;
                    if($query->rowCount() > 0) {
                        foreach($results as $row) {
                    ?>
                    <tr>
                        <td><?php echo $cnt;?></td>
                        <td><?php echo htmlentities($row->supplier_name);?></td>
                        <td><?php echo htmlentities($row->contact_no);?></td>
                        <td><?php echo htmlentities($row->address);?></td>
                        <td>
                            <div class="btn-group">
                                <a href="edit-supplier.php?id=<?php echo htmlentities($row->ID); ?>" 
                                   class="btn btn-primary btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="delete-supplier.php?id=<?php echo htmlentities($row->ID); ?>" 
                                   onclick="return confirm('Are you sure you want to delete this supplier?');"
                                   class="btn btn-danger btn-sm ml-1" title="Delete">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php 
                        $cnt++;
                        }
                    }
                    ?>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          
          <!-- SWITCHED: Now Add New is on the right -->
          <div class="col-md-4">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-plus-circle mr-2"></i>Add New Supplier</h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form id="form" action="" method="post" class="">
                <div class="card-body">
                    <div class="form-group">
                        <label class="required-field"><i class="fas fa-building mr-1"></i>Supplier Name</label>
                        <input type="text" class="form-control" name="txtsupplier" 
                               placeholder="Enter supplier name" required>
                        <small class="form-text text-muted">Each supplier name must be unique</small>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-phone mr-1"></i>Contact Number</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            </div>
                            <input type="text" class="form-control" name="txtcontact" 
                                   pattern="[0-9]{10}" title="Please enter valid 10-digit number" 
                                   placeholder="Enter 10-digit number">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="required-field"><i class="fas fa-map-marker-alt mr-1"></i>Address</label>
                        <textarea class="form-control" name="txtaddress" rows="4" 
                                  placeholder="Enter complete address" required></textarea>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" name="btnsave" class="btn btn-primary btn-block">
                    <i class="fas fa-save mr-2"></i>Save Supplier
                  </button>
                </div>
              </form>
            </div>
            
            <!-- Tips Card -->
            <div class="card bg-light">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-lightbulb mr-2"></i>Tips</h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body p-3">
                <ul class="list-unstyled">
                  <li><i class="fas fa-check-circle text-success mr-2"></i>Supplier names must be unique</li>
                  <li><i class="fas fa-check-circle text-success mr-2"></i>Enter 10-digit contact number</li>
                  <li><i class="fas fa-check-circle text-success mr-2"></i>Provide complete address for delivery</li>
                </ul>
              </div>
            </div>
            <!-- /.card -->
          </div>
          <!--/.col (right) -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      
    </div>
    <strong><?php include '../footer.php' ?>
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
<!-- DataTables -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<!-- bs-custom-file-input -->
<script src="plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- Page specific script -->
<script>
$(function () {
  bsCustomFileInput.init();
  
  // DataTable initialization with improved settings
  $('#example1').DataTable({
    "paging": true,
    "lengthChange": true,
    "searching": true,
    "ordering": true,
    "info": true,
    "autoWidth": false,
    "responsive": true,
    "pageLength": 10,
    "language": {
      "emptyTable": "No suppliers found",
      "info": "Showing _START_ to _END_ of _TOTAL_ suppliers",
      "search": "Quick Search:"
    }
  });
  
  // Form validation
  $('#form').submit(function(e) {
    var supplierName = $('input[name="txtsupplier"]').val().trim();
    var address = $('textarea[name="txtaddress"]').val().trim();
    
    if (supplierName === '') {
      e.preventDefault();
      alert('Supplier name is required!');
      return false;
    }
    
    if (address === '') {
      e.preventDefault();
      alert('Address is required!');
      return false;
    }
    
    return true;
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

Array.from(document.querySelectorAll('button[data-for]')).
forEach(addButtonTrigger);
    </script>
</body>
</html>