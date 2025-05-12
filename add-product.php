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

 
if(isset($_POST["btnsave"]))
{

$product_name = $_POST['txtproduct_name'];
$category = $_POST['cmdcategory'];
$expirydate = $_POST['txtexpirydate'];
$qty = $_POST['txtqty'];
$price = $_POST['txtprice'];


//generate random productID
function GenerateproductID() {
  $alphabet = "0123456789";
  $pass = array(); //remember to declare $pass as an array
  $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
  for ($i = 0; $i < 5; $i++) {
      $n = rand(0, $alphaLength);
     $pass[] = $alphabet[$n];
  }
  return implode($pass); //turn the array into a string
}
$productID= GenerateproductID();

// Define allowed image types and max file size
$allowed_types = [
    'image/jpeg',
    'image/jpg',
    'image/png',
    'image/gif',
    'image/webp',
    'image/bmp',
    'image/tiff'
];
$max_size = 5 * 1024 * 1024; // 5MB

if (!empty($_FILES['avatar']['tmp_name'])) {
    $file_type = $_FILES['avatar']['type'];
    $file_size = $_FILES['avatar']['size'];
    
    if (!in_array($file_type, $allowed_types)) {
        $_SESSION['error'] = 'Invalid image format. Allowed formats: JPG, JPEG, PNG, GIF, WEBP, BMP, TIFF';
    } elseif ($file_size > $max_size) {
        $_SESSION['error'] = 'Image size should not exceed 5MB';
    } else {
        $image = addslashes(file_get_contents($_FILES['avatar']['tmp_name']));
        $image_name = addslashes($_FILES['avatar']['name']);
        
        // Sanitize filename
        $info = pathinfo($_FILES["avatar"]["name"]);
        $ext = $info['extension'];
        $filename = preg_replace("/[^a-z0-9]+/i", "-", $info['filename']);
        $safeName = $filename . '_' . time() . '.' . $ext;
        
        $location = "uploadImage/" . $safeName;
        
        if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $location)) {
            // Continue with database insertion
            // ...existing database code...
            
            ///check if product already exist
            $stmt = $dbh->prepare("SELECT * FROM tblproduct WHERE product_name=? and category=?");
            $stmt->execute([$product_name,$category]); 
            $row_product = $stmt->fetch();


            if ($row_product) {
            $_SESSION['error'] ='product Already Exist in our Database ';

            } else {
            //Add course details
            $sql = 'INSERT INTO tblproduct(productID,product_name,category,expirydate,qty,price,photo) VALUES(:productID,:product_name,:category,:expirydate,:qty,:price,:photo)';
            $statement = $dbh->prepare($sql);
            $statement->execute([
            ':productID' => $productID,
            ':product_name' => $product_name,
            ':category' => $category,
            ':expirydate' => $expirydate,
            ':qty' => $qty,
            ':price' => $price,
            ':photo' => $location
            ]);
            if ($statement){
                $_SESSION['success'] ='Product Added Successfully';
            }else{
            $_SESSION['error'] ='Problem Adding Product';
            }
            }
        } else {
            $_SESSION['error'] = 'Failed to upload image';
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
  <title>Add Product - Admin Dashboard</title>
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
  <script type="text/javascript">
		function deldata(){
if(confirm("ARE YOU SURE YOU WISH TO DELETE THIS PRODUCT FROM THE DATABASE ?"))
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
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Add Product</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- Product Records Column (Left) -->
          <div class="col-md-8">
            <div class="card card-success">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-list-alt mr-2"></i>
                  Product Records
                </h3>
              </div>
              <div class="card-body">
                <div class="mb-3">
                    <div class="input-group">
                        <input type="text" id="searchInput" class="form-control" placeholder="Search products...">
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                  <table class="table table-bordered table-hover" id="example1">
                    <thead class="bg-success text-white">
                      <tr>
                        <th width="3%">#</th>
                        <th width="13%">Image</th>
                        <th width="13%">Product Name</th>
                        <th width="13%">Category</th>
                        <th width="13%">Expiry Date</th>
                        <th width="13%">Quantity</th>
                        <th width="13%">Price</th>
                        <th width="13%">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
                      $data = $dbh->query("SELECT * FROM tblproduct ORDER BY product_name DESC")->fetchAll();
                      $cnt = 1;
                      foreach ($data as $row) {
                      ?>
                      <tr>
                        <td class="text-center"><?php echo $cnt; ?></td>
                        <td class="text-center image-cell">
                          <img src="<?php echo $row['photo']; ?>" class="img-thumbnail" width="50" height="50">
                        </td>
                        <td class="text-center"><?php echo $row['product_name']; ?></td>
                        <td class="text-center"><?php echo $row['category']; ?></td>
                        <td class="text-center"><?php echo $row['expirydate']; ?></td>
                        <td class="text-center"><?php echo $row['qty']; ?></td>
                        <td class="text-center">₹<?php echo number_format($row['price'], 2); ?></td>
                        <td class="text-center">
                          <a href="edit-product.php?id=<?php echo $row['ID']; ?>" 
                             class="btn btn-success btn-sm mb-1">
                            <i class="fas fa-edit"></i>
                          </a>
                          <a href="delete-product.php?id=<?php echo $row['ID']; ?>" 
                             onclick="return deldata();" 
                             class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i>
                          </a>
                        </td>
                      </tr>
                      <?php $cnt++; } ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <!-- Add Product Form Column (Right) -->
          <div class="col-md-4">
            <div class="card card-success shadow-lg">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-plus-circle mr-2"></i>
                  Add New Product
                </h3>
              </div>
              <div class="card-body">
                <form action="" method="POST" enctype="multipart/form-data">
                  <div class="form-group">
                    <label><i class="fas fa-box mr-1"></i> Product Name</label>
                    <input type="text" class="form-control form-control-border" 
                           name="txtproduct_name" required>
                  </div>

                  <div class="form-group">
                    <label><i class="fas fa-tags mr-1"></i> Category</label>
                    <select name="cmdcategory" class="form-control form-control-border" required>
                      <option value="">Select Category</option>
                      <?php
                      $sql = "SELECT * FROM tblcategory ORDER BY category_name";
                      $stmt = $dbh->query($sql);
                      while($row = $stmt->fetch()) {
                        echo "<option value='".$row['category_name']."'>".$row['category_name']."</option>";
                      }
                      ?>
                    </select>
                  </div>

                  <div class="form-group">
                    <label><i class="fas fa-calendar mr-1"></i> Expiry Date</label>
                    <input type="date" class="form-control form-control-border" 
                           name="txtexpirydate" required>
                  </div>

                  <div class="form-group">
                    <label><i class="fas fa-cubes mr-1"></i> Quantity</label>
                    <input type="number" class="form-control form-control-border" 
                           name="txtqty" required min="0">
                  </div>

                  <div class="form-group">
                    <label><i class="fas fa-rupee-sign mr-1"></i> Unit Price</label>
                    <input type="number" class="form-control form-control-border" 
                           name="txtprice" required min="0" step="0.01">
                  </div>

                  <div class="form-group">
                    <label><i class="fas fa-image mr-1"></i> Product Image</label>
                    <div class="custom-file">
                      <input type="file" class="custom-file-input" name="avatar" 
                             id="avatar" accept="image/*" onChange="validateAndPreview(this)" required>
                      <label class="custom-file-label">Choose file</label>
                    </div>
                    <small class="text-muted">
                        Supported formats: JPG, JPEG, PNG, GIF, WEBP, BMP, TIFF (Max: 5MB)
                    </small>
                  </div>

                  <div class="form-group text-center">
                    <img src="uploadImage/drug.jpeg" id="logo-img" 
                         class="img-fluid img-thumbnail" style="max-height: 200px;">
                  </div>

                  <button type="submit" name="btnsave" class="btn btn-success btn-block">
                    <i class="fas fa-save mr-2"></i>Save Product
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Add this CSS to your existing styles -->
    <style>
    .card-success:not(.card-outline) > .card-header {
      background-color: #2fb092;
    }

    .form-control-border {
      border-top: 0;
      border-left: 0;
      border-right: 0;
      border-radius: 0;
      box-shadow: none;
      border-bottom: 1px solid #28a745;
    }

    .form-control-border:focus {
      border-bottom: 2px solid #28a745;
    }

    .table thead th {
      background-color: #2fb092;
      color: white;
      border-bottom: 2px solid #1e7e34;
    }

    .btn-success {
      background-color: #2fb092;
      border-color: #28a745;
    }

    .btn-success:hover {
      background-color: #2fb092;
      border-color: #1e7e34;
    }

    .card {
      border-radius: 15px;
      overflow: hidden;
    }

    .card-header {
      border-bottom: 0;
    }

    .img-thumbnail {
      border-color: #28a745;
    }

    .highlight {
      background-color: #fff3cd;
      padding: 2px;
      border-radius: 3px;
    }

    .table-row-highlight {
      background-color: rgba(47, 176, 146, 0.1) !important;
    }
    </style>
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
});
</script>

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

<script>
function validateAndPreview(input) {
    const maxSize = 5 * 1024 * 1024; // 5MB
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/bmp', 'image/tiff'];
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Check file type
        if (!allowedTypes.includes(file.type)) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid File Type',
                text: 'Please select a valid image file'
            });
            input.value = '';
            return false;
        }
        
        // Check file size
        if (file.size > maxSize) {
            Swal.fire({
                icon: 'error',
                title: 'File Too Large',
                text: 'Image size should not exceed 5MB'
            });
            input.value = '';
            return false;
        }
        
        // Preview image
        const reader = new FileReader();
        reader.onload = function(e) {
            $('#logo-img').attr('src', e.target.result);
        }
        reader.readAsDataURL(file);
        
        // Update file input label
        $(input).next('.custom-file-label').html(file.name);
    }
}
</script>

<script>
$(document).ready(function() {
    $("#searchInput").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        var found = false;

        // Remove existing highlights
        $('table tbody td').each(function() {
            var text = $(this).html();
            text = text.replace(/<mark class="highlight">/g, '');
            text = text.replace(/<\/mark>/g, '');
            $(this).html(text);
        });
        
        $('table tbody tr').each(function() {
            var rowMatch = false;
            $(this).removeClass('table-row-highlight');
            
            $(this).find('td').each(function() {
                if (!$(this).hasClass('image-cell')) {
                    var cell = $(this).html();
                    var index = cell.toLowerCase().indexOf(value);
                    
                    if (index >= 0 && value.length > 0) {
                        rowMatch = true;
                        found = true;
                        var highlighted = cell.substring(0, index) + 
                                       '<mark class="highlight">' + 
                                       cell.substring(index, index + value.length) + 
                                       '</mark>' + 
                                       cell.substring(index + value.length);
                        $(this).html(highlighted);
                    }
                }
            });
            
            if (value.length > 0) {
                $(this).toggle(rowMatch);
                if (rowMatch) {
                    $(this).addClass('table-row-highlight');
                }
            } else {
                $(this).show();
            }
        });

        // Show no results message
        if (!found && value.length > 0) {
            if ($('#noResults').length === 0) {
                $('table tbody').append(
                    '<tr id="noResults"><td colspan="8" class="text-center text-muted">' +
                    'No matching products found</td></tr>'
                );
            }
        } else {
            $('#noResults').remove();
        }
    });
});
</script>

</body>
</html>
