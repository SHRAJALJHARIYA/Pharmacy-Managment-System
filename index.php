<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

include('topbar.php');
if(empty($_SESSION['login_email']))
    {   
      header("Location: login.php"); 
    }

    $email = $_SESSION["login_email"];

//fetch user data
$stmt = $dbh->query("SELECT * FROM users where email='$email'");
$row_user = $stmt->fetch();
$phone=$row_user['phone'];
$fullname=$row_user['fullname'];


//fetch expired product
$today = date('Y-m-d');
$new_today = date('Y-m-d', strtotime($today. ' + 3 days'));

$sql = "SELECT * FROM tblproduct where expirydate <= '$new_today' ;";
$statement = $dbh->query($sql);
$row_expired = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($row_expired as $Expired) {

$expiring_drug= $Expired['product_name'];
$expiring_stock= $Expired['qty'];
$expiring_productID= $Expired['productID'];

 //send sms alert
$username='info.autosyst@yahoo.comxxxx';//Note: urlencodemust be added forusernameand 
$password='Integax@2022xxxx';// passwordas encryption code for security purpose.
$sender='JOSHANN';


$message  = 'Hello '.$fullname.', Your Drug ('.$expiring_drug.')- ('.$expiring_productID.') in your stock will expire in 3 days Time.. Available Quantity is '.$expiring_stock.'. Please dispose them. Thanks';
$api_url  = 'https://portal.nigeriabulksms.com/api/';

//Create the message data
$data = array('username'=>$username, 'password'=>$password, 'sender'=>$sender, 'message'=>$message, 'mobiles'=>$phone);
//URL encode the message data
$data = http_build_query($data);
//Send the message
$request = $api_url.'?'.$data;
$result  = file_get_contents($request);
$result  = json_decode($result);

}
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Welcome to Admin Dashboard</title>
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
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Info boxes -->
        <div class="row">
		
          <!-- /.col -->

<?php
$stmt = $dbh->prepare("SELECT count(*) FROM tblproduct");
$stmt->execute([]);
$count_drug = $stmt->fetchColumn();   
?>

          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-primary elevation-1"><i class="fa  fa-users" id="icon"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">No. Of Drug(s) </span>
                <span class="info-box-number">
                  <?php  echo $count_drug;   ?>
                  <small></small>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>

            <?php
  $stmt = $dbh->prepare("SELECT count(*) FROM tblcategory");
  $stmt->execute([]);
  $count_Category = $stmt->fetchColumn();   
  ?>

            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fa  fa-users" id="icon"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">No. Of Category(s) </span>
                  <span class="info-box-number">
                    <?php  echo $count_Category;   ?>
                  <small></small>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>


          <?php
$stmt = $dbh->prepare("SELECT count(*) FROM tblsupplier ");
$stmt->execute([]);
$count_supplier = $stmt->fetchColumn();   
?>

          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-secondary elevation-1"><i class="fa  fa-users" id="icon"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">No. Of Supplier(s) </span>
                <span class="info-box-number">
                  <?php  echo $count_supplier;   ?>
                  <small></small>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>



          <?php
$stmt = $dbh->prepare("SELECT count(*) FROM users");
$stmt->execute([]);
$count_users = $stmt->fetchColumn();   
?>

          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-success elevation-1"><i class="fa fa-user" id="icon"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">No. Of User(s) </span>
                <span class="info-box-number">
                  <?php  echo $count_users;   ?>
                  <small></small>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>


            <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-warning elevation-1"><i class="fa fa-desktop" id="icon"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">System IP Address </span>
                <span class="info-box-number">
                <?php echo $row_user['last_ip'];  ?>
                  <small></small>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
		  
          
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-danger elevation-1"><i class="fa fa-key" id="icon"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Last Login</span>
                <span class="info-box-number">
                <?php echo $row_user['lastaccess'];  ?>
                  <small></small>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>

          <?php
// Get expiry status counts
$today = date('Y-m-d');
$three_days = date('Y-m-d', strtotime($today . ' + 3 days'));
$thirty_days = date('Y-m-d', strtotime($today . ' + 30 days'));

// Count critical (3 days)
$stmt = $dbh->prepare("SELECT COUNT(*) FROM tblproduct WHERE expirydate <= ? AND expirydate >= CURRENT_DATE()");
$stmt->execute([$three_days]);
$critical_count = $stmt->fetchColumn();

// Count approaching (30 days)
$stmt = $dbh->prepare("SELECT COUNT(*) FROM tblproduct WHERE expirydate <= ? AND expirydate > ?");
$stmt->execute([$thirty_days, $three_days]);
$approaching_count = $stmt->fetchColumn();
?>

          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-exclamation-triangle" id="icon"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Expiry Status</span>
                <span class="info-box-number">
                  <?php if ($critical_count > 0): ?>
                    <span class="text-danger"><?php echo $critical_count; ?> Critical</span><br>
                  <?php endif; ?>
                  <?php if ($approaching_count > 0): ?>
                    <span class="text-warning"><?php echo $approaching_count; ?> Approaching</span>
                  <?php else: ?>
                    <span class="text-success">All Good</span>
                  <?php endif; ?>
                </span>
              </div>
            </div>
          </div>

          <!-- /.col -->
        </div>
        <!-- /.row -->

        <!-- Add this after the stats cards section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Quick Action Buttons</h3>
                    </div>
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col-12 col-md-4 mb-2">
                                <a href="add-product.php" class="btn btn-success btn-lg btn-block">
                                    <i class="fas fa-plus-circle mr-2"></i> Add Drug
                                </a>
                            </div>
                            <div class="col-12 col-md-4 mb-2">
                                <a href="add-stock.php" class="btn btn-primary btn-lg btn-block">
                                    <i class="fas fa-download mr-2"></i> Add Stock
                                </a>
                            </div>
                            <div class="col-12 col-md-4 mb-2">
                                <a href="add-sales.php" class="btn btn-warning btn-lg btn-block">
                                    <i class="fas fa-shopping-cart mr-2"></i> Make Sale
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Quick Action Buttons section -->

        <!-- Add this after the Quick Action Buttons section -->
        <div class="row">
            <div class="col-12">
                <div class="card card-danger">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Medicine Expiry Alerts
                        </h3>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Stock</th>
                                    <th>Expiry Date</th>
                                    <th>Days Left</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                require_once('includes/expiry_checker.php');
                                $expiring_medicines = checkExpiringMedicines($dbh);
                                
                                foreach ($expiring_medicines as $medicine) {
                                    $days_left = $medicine['days_until_expiry'];
                                    $alert_class = getExpiryAlertClass($days_left);
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($medicine['product_name']); ?></td>
                                    <td><?php echo htmlspecialchars($medicine['qty']); ?></td>
                                    <td><?php echo date('d M Y', strtotime($medicine['expirydate'])); ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo $alert_class; ?>">
                                            <?php echo $days_left; ?> days
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($days_left <= 3): ?>
                                            <span class="badge badge-danger">Critical</span>
                                        <?php elseif ($days_left <= 7): ?>
                                            <span class="badge badge-warning">Warning</span>
                                        <?php else: ?>
                                            <span class="badge badge-info">Approaching</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="edit-product.php?id=<?php echo $medicine['ID']; ?>" 
                                           class="btn btn-sm btn-info">
                                            <i class="fas fa-edit"></i> Manage
                                        </a>
                                    </td>
                                </tr>
                                <?php } ?>
                                <?php if (empty($expiring_medicines)): ?>
                                <tr>
                                    <td colspan="6" class="text-center">
                                        No medicines expiring soon
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main row -->
        <div class="row">
          <!-- Left col -->
          <div class="col-md-8">
            <!-- MAP & BOX PANE -->
            <!-- /.card -->
          <div class="row">
              <div class="col-md-6">
                <!-- DIRECT CHAT -->
                <!--/.direct-chat -->
            </div>
              <!-- /.col -->

                            <!-- /.card-header -->
                  <div class="card-body p-0">
                    <ul class="users-list clearfix">
                      <li>
                   
					
                    <!-- /.users-list -->
                  </div>
				 
                  <!-- /.card-body -->
                 
                  <!-- /.card-footer -->
                </div>
                <!--/.card -->
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->

            <!-- TABLE: LATEST ORDERS -->
            <!-- /.card -->
</div>
          <!-- /.col -->

          <div class="col-md-4">
            <!-- Info Boxes Style 2 -->
            <!-- /.info-box -->
            <!-- /.info-box -->
            <!-- /.info-box -->
            <!-- /.info-box -->
<div class="card">
  <!-- /.card-header -->

              <!-- /.card-body -->
              <!-- /.footer -->
</div>
            <!-- /.card -->

            <!-- PRODUCT LIST -->
            <!-- /.card -->
</div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <strong><?php include('footer.php');  ?></strong>
  
    <div class="float-right d-none d-sm-inline-block">
   
    </div>
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- overlayScrollbars -->
<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.js"></script>

<!-- PAGE PLUGINS -->
<!-- jQuery Mapael -->
<script src="plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
<script src="plugins/raphael/raphael.min.js"></script>
<script src="plugins/jquery-mapael/jquery.mapael.min.js"></script>
<script src="plugins/jquery-mapael/maps/usa_states.min.js"></script>
<!-- ChartJS -->
<script src="plugins/chart.js/Chart.min.js"></script>

<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="dist/js/pages/dashboard2.js"></script>
</body>
</html>
