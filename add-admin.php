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

 
if(isset($_POST["btncreate"]))
{

$name = $_POST['txtfullname'];
$email = $_POST['txtemail'];
$password = $_POST['txtpassword'];
$phone = $_POST['txtphone'];
$groupname = $_POST['cmdtype'];
$last_ip="NA";
$lastaccess="NA";
$status="1";

$file_type = $_FILES['avatar']['type']; //returns the mimetype
$allowed = array("image/jpg", "image/gif","image/jpeg", "image/webp","image/png");
if(!in_array($file_type, $allowed)) {
$_SESSION['error'] ='Only jpg,jpeg,Webp, gif, and png files are allowed. ';

// exit();

}else{
$image= addslashes(file_get_contents($_FILES['avatar']['tmp_name']));
$image_name= addslashes($_FILES['avatar']['name']);
$image_size= getimagesize($_FILES['avatar']['tmp_name']);
move_uploaded_file($_FILES["avatar"]["tmp_name"],"uploadImage/" . $_FILES["avatar"]["name"]);			
$location="uploadImage/" . $_FILES["avatar"]["name"];
			
///check if email already exist
$stmt = $dbh->prepare("SELECT * FROM users WHERE email=?");
$stmt->execute([$email]); 
$user = $stmt->fetch();

if ($user) {
$_SESSION['error'] ='Email Already Exist in our Database ';

} else {
 //Add User details
$sql = 'INSERT INTO users(email,password,fullname,lastaccess,last_ip,groupname,phone,status,photo) VALUES(:email,:password,:fullname,:lastaccess,:last_ip,:groupname,:phone,:status,:photo)';
$statement = $dbh->prepare($sql);
$statement->execute([
	':email' => $email,
	':password' => $password,
	':fullname' => $name,
	':lastaccess' => $lastaccess,
	':last_ip' => $last_ip,
		':groupname' => $groupname,
    ':phone' => $phone,
		':status' => $status,
		':photo' => $location

]);
if ($statement){
	$_SESSION['success'] ='User Added Successfully';
}else{
  $_SESSION['error'] ='Problem Adding User';
}
}
}
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Add Admin - Admin Dashboard</title>
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
  <style>
    .card {
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        border-radius: 8px;
    }
    
    .card-body {
        padding: 2rem;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-control {
        border-radius: 4px;
        border: 1px solid #ddd;
        padding: 8px 12px;
        height: calc(2.5rem + 2px);
    }
    
    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    }
    
    .img-thumbnail {
        padding: 0.5rem;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    
    .btn-primary {
        padding: 10px 30px;
        font-weight: 500;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,123,255,0.3);
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
            <h1 class="m-0 text-dark">&nbsp;</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Create User </li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
        
		 <!-- general form elements -->
            <div class="card card-primary" style="margin: 20px;">
              <div class="card-header">
                <h3 class="card-title">Add User </h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form action="" method="POST" enctype="multipart/form-data">
                <div class="card-body">
                  <div class="row">
                    <!-- Column 1 -->
                    
                      <div class="col-md-4">
                      <div class="form-group">
                        <label>Fullname</label>
                        <input type="text" class="form-control" name="txtfullname" 
                               value="<?php if (isset($_POST['txtfullname']))?><?php echo $_POST['txtfullname']; ?>" 
                               placeholder="Enter Fullname">
                      </div>

                      <div class="form-group">
                        <label>Password</label>
                        <input type="password" class="form-control" name="txtpassword" 
                               value="<?php if (isset($_POST['txtpassword']))?><?php echo $_POST['txtpassword']; ?>" 
                               placeholder="Enter Password">
                      </div>
                    </div>
                    
                    <!-- Column 2 -->
                     <div class="col-md-4">
                      <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" name="txtemail" 
                               value="<?php if (isset($_POST['txtemail']))?><?php echo $_POST['txtemail']; ?>" 
                               placeholder="Enter Email">
                      </div>
                    
                      <div class="form-group">
                        <label>Phone</label>
                        <input type="tel" class="form-control" name="txtphone" 
                               value="<?php if (isset($_POST['txtphone']))?><?php echo $_POST['txtphone']; ?>" 
                               placeholder="Enter Phone">
                      </div>
                    </div>
                    
                    <!-- Column 3 -->
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>User Type</label>
                        <?php
                        $sql = "select * from tblgroup where groupname !='Super Admin'";
                        $group = $dbh->query($sql);                       
                        $group->setFetchMode(PDO::FETCH_ASSOC);
                        echo '<select name="cmdtype" class="form-control">';
                        echo '<option value="">Select Group Name</option>';
                        while ($row = $group->fetch()) {
                            echo '<option value="'.$row['groupname'].'">'.$row['groupname'].'</option>';
                        }
                        echo '</select>';
                        ?>     
                      </div>
                      <div class="form-group">
                        <label>Profile Image</label>
                        <input type="file" name="avatar" class="form-control" 
                               accept="image/png,image/jpeg,image/jpg" 
                               onChange="display_img(this)">
                        <div class="text-center mt-2">
                            <img src="uploadImage/avatar.png" alt="user image" 
                                 class="img-thumbnail" style="height: 150px;" id="logo-img">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="card-footer text-center">
                    <button type="submit" name="btncreate" class="btn btn-primary px-5">
                        <i class="fas fa-save mr-2"></i>Add User
                    </button>
                </div>
              </form>
            </div>
		
        </div>
        <!-- /.row -->
        <!-- Main row -->
        <div class="row">
          <!-- Left col --><!-- /.Left col -->
          <!-- right col (We are only adding the ID to make the widgets sortable)--><!-- right col -->
        </div>
        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <?php include('footer.php');  ?>
    <div class="float-right d-none d-sm-inline-block">
      
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
<!-- jQuery UI 1.11.4 -->
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="dist/js/pages/dashboard.js"></script>
	
<link rel="stylesheet" href="popup_style.css">
<?php if(!empty($_SESSION['success'])) {  ?>
<div class="popup popup--icon -success js_success-popup popup--visible">
  <div class="popup__background"></div>
  <div class="popup__content">
    <h3 class="popup__content__title">
      <strong>Success</strong> 
    </h1>
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
    </h1>
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
     <script>
    function display_img(input) {
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();
	        reader.onload = function (e) {
	        	$('#logo-img').attr('src', e.target.result);
	        }

	        reader.readAsDataURL(input.files[0]);
	    }
	}
   
</script>
</body>
</html>
