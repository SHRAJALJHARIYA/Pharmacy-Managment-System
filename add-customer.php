<?php 
include('topbar.php');
if(empty($_SESSION['login_email'])) {   
    header("Location: index.php");
    exit;
}

// Get user info for sidebar
$email = $_SESSION["login_email"];
$stmt = $dbh->prepare("SELECT * FROM users WHERE email=?");
$stmt->execute([$email]);
$row_user = $stmt->fetch();

if(isset($_POST["btnsave"])) {
    $fullName = $_POST['txtfullname'];
    $email = $_POST['txtemail'];
    $mobile = $_POST['txtmobile'];
    $address = $_POST['txtaddress'];
    $city = $_POST['txtcity'];
    $district = $_POST['txtdistrict'];
    
    // Check if customer exists
    $stmt = $dbh->prepare("SELECT * FROM customer WHERE email=? OR mobile=?");
    $stmt->execute([$email, $mobile]); 
    if($stmt->fetch()) {
        $_SESSION['error'] = 'Customer Already Exists';
    } else {
        // Add new customer
        $sql = 'INSERT INTO customer(fullName, email, mobile, address, city, district) 
                VALUES(:fullName, :email, :mobile, :address, :city, :district)';
        $statement = $dbh->prepare($sql);
        if($statement->execute([
            ':fullName' => $fullName,
            ':email' => $email, 
            ':mobile' => $mobile,
            ':address' => $address,
            ':city' => $city,
            ':district' => $district
        ])) {
            $_SESSION['success'] = 'Customer Added Successfully';
            header("Location: add-sales.php");
            exit;
        } else {
            $_SESSION['error'] = 'Error Adding Customer';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add New Customer - Pharmacy POS</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css">
    
    <style>
        .bg-indigo {
            background-color: #6610f2 !important;
            color: #fff;
        }
        
        .btn-indigo {
            background-color: #6610f2;
            border-color: #6610f2;
            color: #fff;
        }
        
        .btn-indigo:hover {
            background-color: #520dc2;
            border-color: #520dc2;
            color: #fff;
        }
        
        .form-control-border {
            border-top: 0;
            border-left: 0;
            border-right: 0;
            border-radius: 0;
            box-shadow: none;
            border-bottom: 2px solid #6610f2;
            padding-left: 0;
        }
        
        .form-control-border:focus {
            border-bottom: 2px solid #520dc2;
            box-shadow: none;
        }
        
        .card {
            border-radius: 8px;
            overflow: hidden;
        }
        
        .card-indigo {
            border-top: 3px solid #6610f2;
        }
        
        .card-header {
            border-bottom: 0;
            padding: 1rem 1.25rem;
        }
        
        .form-group label {
            font-weight: 500;
            color: #555;
        }
        
        .card-footer {
            background-color: transparent;
            border-top: 1px solid rgba(0,0,0,.05);
            padding: 1.5rem;
        }
        
        .required-field::after {
            content: " *";
            color: red;
        }
        
        .field-icon {
            color: #6610f2;
            width: 20px;
            text-align: center;
            margin-right: 8px;
        }
        
        .customer-form-container {
            max-width: 800px;
            margin: 0 auto;
        }
    </style>
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
        <a href="dashboard.php" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="add-sales.php" class="nav-link">Sales</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link active">Add Customer</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" href="logout.php" role="button">
          <i class="fas fa-sign-out-alt"></i> Logout
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index.php" class="brand-link">
      <span class="brand-text font-weight-light">Pharmacy POS</span>
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
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1><i class="fas fa-user-plus"></i> Add New Customer</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item"><a href="add-sales.php">Sales</a></li>
              <li class="breadcrumb-item active">Add Customer</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="customer-form-container">
          <div class="card card-indigo shadow">
            <div class="card-header bg-light">
              <h3 class="card-title">
                <i class="fas fa-user-plus text-indigo mr-2"></i>
                Customer Information
              </h3>
              
            </div>
            <form id="customerForm" method="POST" action="">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="required-field">
                        <span class="field-icon"><i class="fas fa-user"></i></span> Full Name
                      </label>
                      <input type="text" class="form-control form-control-border" 
                             name="txtfullname" id="txtfullname" placeholder="Enter full name" required>
                    </div>
                    
                    <div class="form-group">
                      <label class="required-field">
                        <span class="field-icon"><i class="fas fa-envelope"></i></span> Email
                      </label>
                      <input type="email" class="form-control form-control-border" 
                             name="txtemail" id="txtemail" placeholder="Enter email address" required>
                    </div>
                    
                    <div class="form-group">
                      <label class="required-field">
                        <span class="field-icon"><i class="fas fa-mobile-alt"></i></span> Mobile
                      </label>
                      <input type="text" class="form-control form-control-border" 
                             name="txtmobile" id="txtmobile" placeholder="Enter 10-digit mobile number" 
                             pattern="[0-9]{10}" title="Please enter valid 10-digit mobile number" required>
                    </div>
                  </div>
                  
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="required-field">
                        <span class="field-icon"><i class="fas fa-map-marker-alt"></i></span> Address
                      </label>
                      <textarea class="form-control form-control-border" 
                                name="txtaddress" id="txtaddress" rows="2" 
                                placeholder="Enter complete address" required></textarea>
                    </div>
                    
                    <div class="form-group">
                      <label class="required-field">
                        <span class="field-icon"><i class="fas fa-city"></i></span> City
                      </label>
                      <input type="text" class="form-control form-control-border" 
                             name="txtcity" id="txtcity" placeholder="Enter city" required>
                    </div>
                    
                    <div class="form-group">
                      <label class="required-field">
                        <span class="field-icon"><i class="fas fa-map"></i></span> District
                      </label>
                      <input type="text" class="form-control form-control-border" 
                             name="txtdistrict" id="txtdistrict" placeholder="Enter district" required>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-footer text-center">
                <button type="submit" name="btnsave" class="btn btn-indigo btn-lg px-5">
                  <i class="fas fa-save mr-2"></i>Save Customer
                </button>
                <a href="add-sales.php" class="btn btn-default btn-lg px-5 ml-2">
                  <i class="fas fa-times mr-2"></i>Cancel
                </a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>
  </div>
  
  <!-- Footer -->
  <footer class="main-footer">
    <?php include('footer.php'); ?>
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.2.0
    </div>
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
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.js"></script>

<script>
$(function() {
  // Initialize tooltips
  $('[data-toggle="tooltip"]').tooltip();
  
  // Form validation
  $('#customerForm').on('submit', function(event) {
    const requiredFields = ['txtfullname', 'txtemail', 'txtmobile', 'txtaddress', 'txtcity', 'txtdistrict'];
    let emptyFields = [];
    
    requiredFields.forEach(field => {
      if (!$('#' + field).val()) {
        emptyFields.push(field);
        $('#' + field).addClass('is-invalid');
      } else {
        $('#' + field).removeClass('is-invalid');
      }
    });
    
    if (emptyFields.length > 0) {
      event.preventDefault();
      Swal.fire({
        icon: 'warning',
        title: 'Required Fields',
        text: 'Please fill all required fields marked with *',
        confirmButtonColor: '#6610f2'
      });
    }
    
    // Validate mobile number
    const mobilePattern = /^[0-9]{10}$/;
    if ($('#txtmobile').val() && !mobilePattern.test($('#txtmobile').val())) {
      event.preventDefault();
      $('#txtmobile').addClass('is-invalid');
      Swal.fire({
        icon: 'error',
        title: 'Invalid Mobile Number',
        text: 'Please enter a valid 10-digit mobile number',
        confirmButtonColor: '#dc3545'
      });
    }
    
    // Validate email format
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if ($('#txtemail').val() && !emailPattern.test($('#txtemail').val())) {
      event.preventDefault();
      $('#txtemail').addClass('is-invalid');
      Swal.fire({
        icon: 'error',
        title: 'Invalid Email',
        text: 'Please enter a valid email address',
        confirmButtonColor: '#dc3545'
      });
    }
  });
  
  // Real-time validation feedback
  $('#txtmobile').on('input', function() {
    const mobilePattern = /^[0-9]{10}$/;
    if (this.value && !mobilePattern.test(this.value)) {
      $(this).addClass('is-invalid');
    } else {
      $(this).removeClass('is-invalid');
    }
  });
  
  $('#txtemail').on('input', function() {
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (this.value && !emailPattern.test(this.value)) {
      $(this).addClass('is-invalid');
    } else {
      $(this).removeClass('is-invalid');
    }
  });
});

// SweetAlert for PHP success/error messages
<?php if(!empty($_SESSION['success'])) { ?>
Swal.fire({
  icon: 'success',
  title: 'Success!',
  text: '<?php echo $_SESSION['success']; ?>',
  confirmButtonColor: '#6610f2'
});
<?php unset($_SESSION["success"]); } ?>

<?php if(!empty($_SESSION['error'])) { ?>
Swal.fire({
  icon: 'error',
  title: 'Error',
  text: '<?php echo $_SESSION['error']; ?>',
  confirmButtonColor: '#dc3545'
});
<?php unset($_SESSION["error"]); } ?>
</script>
</body>
</html>