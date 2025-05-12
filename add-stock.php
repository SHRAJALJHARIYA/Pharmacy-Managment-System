<?php
include('topbar.php');
if(empty($_SESSION['login_email'])) {   
    header("Location: index.php"); 
    exit; // Add exit after redirect for security
} 
      
$email = $_SESSION["login_email"];

// Use prepared statement to prevent SQL injection
$stmt = $dbh->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$row_user = $stmt->fetch();

// Get current date for stock-in date field
$current_date = date('Y-m-d');

if(isset($_POST["btnsave"])) {
    // Validate all required fields
    if(empty($_POST['cmddrug']) || empty($_POST['txtqty']) || empty($_POST['txtprice']) || empty($_POST['txtexpirydate'])) {
        $_SESSION['error'] = 'Please fill all required fields';
    } elseif(strtotime($_POST['txtexpirydate']) < strtotime('today')) {
        $_SESSION['error'] = 'Expiry date must be in the future';
    } elseif(!is_numeric($_POST['txtprice']) || $_POST['txtprice'] <= 0) {
        $_SESSION['error'] = 'Price must be a positive number';
    } elseif(!is_numeric($_POST['txtqty']) || $_POST['txtqty'] <= 0) {
        $_SESSION['error'] = 'Quantity must be a positive number';
    } else {
        try {
            // Begin transaction for data integrity
            $dbh->beginTransaction();
            
            // Add stock in details
            $sql = 'INSERT INTO tblstock(productID, stockDate, drugName, unitPrice, quantity, expiryDate) 
                    VALUES(:productID, :stockDate, :drugName, :unitPrice, :quantity, :expiryDate)';
            $statement = $dbh->prepare($sql);
            $statement->execute([
                ':productID' => $_POST['txtproductID'],
                ':stockDate' => $_POST['txtstockinDate'],
                ':drugName' => $_POST['cmddrug'],
                ':unitPrice' => $_POST['txtprice'],
                ':quantity' => $_POST['txtqty'],
                ':expiryDate' => $_POST['txtexpirydate']
            ]);
            
            // Update stock summary of drug
            $newQty = $_POST['txtstock'] + $_POST['txtqty'];
            $sql = "UPDATE tblproduct SET qty = ?, price = ? WHERE product_name = ?";
            $stmt = $dbh->prepare($sql);
            $stmt->execute([$newQty, $_POST['txtprice'], $_POST['cmddrug']]);
            
            // Commit transaction
            $dbh->commit();
            
            $_SESSION['success'] = 'Stock added successfully';
        } catch(PDOException $e) {
            // Roll back transaction on error
            $dbh->rollBack();
            $_SESSION['error'] = 'Database error: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Stock In - Admin Dashboard</title>
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  
  <script type="text/javascript">
    function calculation() {
      var price = parseFloat(document.getElementById("txtprice").value) || 0;
      var qty = parseFloat(document.getElementById("txtqty").value) || 0;
      document.getElementById("txttotalcost").value = (qty * price).toFixed(2);
    }
    
    // Confirm before submitting form
    function confirmSubmit() {
      return confirm("Are you sure you want to add this stock?");
    }
    
    // Reset form fields
    function resetForm() {
      document.getElementById("stockForm").reset();
      document.getElementById("txttotalcost").value = "0.00";
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
        <a href="dashboard.php" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="stock-history.php" class="nav-link">Stock History</a>
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
      <!-- User dropdown -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-user"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header"><?php echo $row_user['fullname']; ?></span>
          <div class="dropdown-divider"></div>
          <a href="profile.php" class="dropdown-item">
            <i class="fas fa-user mr-2"></i> Profile
          </a>
          <div class="dropdown-divider"></div>
          <a href="logout.php" class="dropdown-item">
            <i class="fas fa-sign-out-alt mr-2"></i> Sign Out
          </a>
        </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index.php" class="brand-link">
      <span class="brand-text font-weight-light">Pharmacy System</span>
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
            <h1>Stock In Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Stock In</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- SELECT2 EXAMPLE -->
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Add New Stock</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->
          <form id="stockForm" action="" method="POST" onsubmit="return confirmSubmit()">
            <div class="card-body">
              <div class="row">
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Select Drug <span class="text-danger">*</span></label>
                    <?php
                      $sql = "SELECT * FROM tblproduct ORDER BY product_name";
                      $drug = $dbh->query($sql);                       
                      $drug->setFetchMode(PDO::FETCH_ASSOC);
                      echo '<select name="cmddrug" id="cmddrug" class="form-control select2" style="width: 100%;" onchange="GetDrugDetail1(this.value)" required>';
                      echo '<option value="">Select Drug</option>';
                      while ($row = $drug->fetch()) {
                        echo '<option value="'.$row['product_name'].'">'.$row['product_name'].'</option>';
                      }
                      echo '</select>';
                    ?>
                  </div>
                  <!-- /.form-group -->
                  <div class="form-group">
                    <label for="exampleInputEmail1">Stock-in Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="txtstockinDate" id="txtstockinDate" value="<?php echo $current_date; ?>" required>
                  </div>

                  <div class="form-group">
                    <label for="exampleInputEmail1">Current Stock</label>
                    <input type="text" class="form-control" name="txtstock" id="txtstock" value="<?php if (isset($_POST['txtstock'])) echo $_POST['txtstock']; ?>" readonly>
                  </div>
                </div>
                <!-- /.col -->
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Category</label>
                    <input type="text" class="form-control" name="txtcategory" id="txtcategory" value="<?php if (isset($_POST['txtcategory'])) echo $_POST['txtcategory']; ?>" readonly>
                  </div>

                  <!-- /.form-group -->
                  <div class="form-group">
                    <label for="exampleInputEmail1">Quantity <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="txtqty" id="txtqty" value="0" min="1" onchange="calculation()" required>
                  </div>
                </div>
                <!-- /.col -->
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Expiry Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="txtexpirydate" id="txtexpirydate" value="<?php if (isset($_POST['txtexpirydate'])) echo $_POST['txtexpirydate']; ?>" required>
                  </div>
                  <!-- /.form-group -->
                  <div class="form-group">
                    <label for="exampleInputEmail1">Unit Price <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" class="form-control" name="txtprice" id="txtprice" value="<?php if (isset($_POST['txtprice'])) echo $_POST['txtprice']; ?>" onchange="calculation()" required>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Product ID</label>
                    <input type="text" class="form-control" name="txtproductID" id="txtproductID" value="<?php if (isset($_POST['txtproductID'])) echo $_POST['txtproductID']; ?>" readonly>
                  </div>

                  <div class="form-group">
                    <label for="exampleInputEmail1">Total Cost</label>
                    <input type="text" class="form-control" name="txttotalcost" id="txttotalcost" value="<?php if (isset($_POST['txttotalcost'])) echo $_POST['txttotalcost']; else echo '0.00'; ?>" readonly>
                  </div>
                </div>
              </div>
              <!-- /.row -->
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
              <button type="submit" name="btnsave" class="btn btn-primary"><i class="fa fa-save"></i> Save Stock</button>
              <button type="button" onclick="resetForm()" class="btn btn-secondary"><i class="fa fa-redo"></i> Reset</button>
              <a href="stock-list.php" class="btn btn-info float-right"><i class="fa fa-list"></i> View Stock List</a>
            </div>
          </form>
        </div>
        <!-- /.card -->
        
        <!-- Recently Added Stock -->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Recently Added Stock</h3>
          </div>
          <div class="card-body">
            <table id="recentStock" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Drug Name</th>
                  <th>Stock Date</th>
                  <th>Quantity</th>
                  <th>Unit Price (₹)</th>
                  <th>Total Cost (₹)</th>
                  <th>Expiry Date</th>
                </tr>
              </thead>
              <tbody>
                <?php
$stmt = $dbh->query("SELECT 
    ts.*,
    (ts.quantity * ts.unitPrice) as total_cost 
FROM tblstock ts 
ORDER BY ts.stockDate DESC, ts.purchaseID DESC 
LIMIT 4");

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    // Format dates for better display
    $stockDate = date('d-m-Y', strtotime($row['stockDate']));
    $expiryDate = date('d-m-Y', strtotime($row['expiryDate']));
    
    echo "<tr>";
    echo "<td>".$row['drugName']."</td>";
    echo "<td>".$stockDate."</td>";
    echo "<td>".$row['quantity']."</td>";
    echo "<td>₹".number_format($row['unitPrice'], 2)."</td>";
    echo "<td>₹".number_format($row['total_cost'], 2)."</td>";
    echo "<td>".$expiryDate."</td>";
    echo "</tr>";
}
?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
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
<script>
  function GetDrugDetail1(str) {
    if (str.length == 0) {
        document.getElementById("txtexpirydate").value = "";
        document.getElementById("txtstock").value = "";
        document.getElementById("txtcategory").value = "";
        document.getElementById("txtproductID").value = "";
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                var myObj = JSON.parse(this.responseText);
                // Make sure the date is in YYYY-MM-DD format for the input field
                if(myObj[0]) {
                    var expiryDate = new Date(myObj[0]);
                    document.getElementById("txtexpirydate").value = expiryDate.toISOString().split('T')[0];
                }
                document.getElementById("txtstock").value = myObj[1];
                document.getElementById("txtcategory").value = myObj[2];
                document.getElementById("txtproductID").value = myObj[3];
                if(myObj[4]) {
                    document.getElementById("txtprice").value = myObj[4];
                    calculation();
                }
            }
        };
        xmlhttp.open("GET", "search/search_stock.php?name=" + str, true);
        xmlhttp.send();
    }
  }
</script>
<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Select2 -->
<script src="plugins/select2/js/select2.full.min.js"></script>
<!-- DataTables -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2();
    
    //Initialize DataTable
    $('#recentStock').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>
<link rel="stylesheet" href="popup_style.css">
<?php if(!empty($_SESSION['success'])) { ?>
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
<?php unset($_SESSION["success"]); } ?>
<?php if(!empty($_SESSION['error'])) { ?>
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
<?php unset($_SESSION["error"]); } ?>
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