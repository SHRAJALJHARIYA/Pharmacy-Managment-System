<?php
include('topbar.php');
if(empty($_SESSION['login_email'])) {
    header("Location: index.php"); 
    exit;
} 
      
$email = $_SESSION["login_email"];

$stmt = $dbh->prepare("SELECT * FROM users WHERE email=?");
$stmt->execute([$email]);
$row_user = $stmt->fetch();

if(isset($_POST["btnsave"])) {
    $errors = [];
    
    // Validate required fields
    if(empty($_POST['cmdcustomer'])) $errors[] = 'Customer name is required';
    if(empty($_POST['cmddrug'])) $errors[] = 'Drug selection is required';
    if(empty($_POST['txtsalesDate'])) $errors[] = 'Sales date is required';
    if(empty($_POST['txtqty']) || $_POST['txtqty'] <= 0) $errors[] = 'Quantity must be greater than zero';
    if(empty($_POST['txtprice'])) $errors[] = 'Price is required';
    
    // Check if quantity exceeds stock
    if(!empty($_POST['txtqty']) && !empty($_POST['txtstock']) && $_POST['txtqty'] > $_POST['txtstock']) {
        $errors[] = 'Quantity exceeds available stock';
    }
    
    if(empty($errors)) {
        // Begin transaction
        $dbh->beginTransaction();
        try {
            // Add sale details
            $sql = 'INSERT INTO sales(customerName, drugName, saleDate, quantity, unitPrice) 
                    VALUES(:customerName, :drugName, :saleDate, :quantity, :unitPrice)';
            $statement = $dbh->prepare($sql);
            $statement->execute([
                ':customerName' => $_POST['cmdcustomer'],
                ':drugName' => $_POST['cmddrug'],
                ':saleDate' => $_POST['txtsalesDate'],
                ':quantity' => $_POST['txtqty'],
                ':unitPrice' => $_POST['txtprice']
            ]);
            
            // Update stock summary of drug
            $newQty = $_POST['txtstock'] - $_POST['txtqty'];
            $sql = "UPDATE tblproduct SET qty=? WHERE product_name=?";
            $stmt = $dbh->prepare($sql);
            $stmt->execute([$newQty, $_POST['cmddrug']]);
            
            // Commit transaction
            $dbh->commit();
            
            $_SESSION['success'] = 'Sale completed successfully!';
        } catch (Exception $e) {
            // Rollback transaction on error
            $dbh->rollBack();
            $_SESSION['error'] = 'Error processing sale: ' . $e->getMessage();
        }
    } else {
        $_SESSION['error'] = implode('<br>', $errors);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Pharmacy Sales System - Process Sale</title>
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css">
  
  <style>
    .required-field::after {
      content: " *";
      color: red;
    }
    .stock-warning {
      color: #dc3545;
      font-size: 0.85rem;
      display: none;
    }
    .field-error {
      border-color: #dc3545 !important;
    }
    .summary-card {
      background-color: #f8f9fa;
      border-left: 4px solid #007bff;
    }
    .low-stock {
      color: #ffc107;
      font-weight: bold;
    }
    .critical-stock {
      color: #dc3545;
      font-weight: bold;
    }
    .select2-container--bootstrap4.select2-container--focus .select2-selection {
      border-color: #80bdff;
      box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
    .card-header {
      background-color: #f8f9fa;
    }
    .total-section {
      background-color: #e9ecef;
      padding: 15px;
      border-radius: 5px;
      margin-top: 10px;
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
        <a href="#" class="nav-link">Sales</a>
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
            <h1><i class="fas fa-shopping-cart"></i>Cart</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">Process Sale</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <form id="salesForm" action="" method="POST">
          <div class="row">
            <!-- Customer Information -->
            <div class="col-md-6">
              <div class="card card-primary card-outline">
                <div class="card-header">
                  <h3 class="card-title"><i class="fas fa-user"></i> Customer Information</h3>
                </div>
                <div class="card-body">
                  <div class="form-group">
                    <label class="required-field">Customer</label>
                    <div class="input-group">
                      <select name="cmdcustomer" id="cmdcustomer" class="form-control select2" required>
                        <option value="">Select Customer</option>
                        <?php
                        $sql = "SELECT * FROM customer ORDER BY fullName";
                        $stmt = $dbh->query($sql);
                        $stmt->setFetchMode(PDO::FETCH_ASSOC);
                        while ($row = $stmt->fetch()) {
                          echo '<option value="'.$row['fullName'].'">'.$row['fullName'].'</option>';
                        }
                        ?>
                      </select>
                      <div class="input-group-append">
                        <a href="add-customer.php" class="btn btn-outline-primary" data-toggle="tooltip" title="Add New Customer">
                          <i class="fas fa-plus"></i>
                        </a>
                      </div>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label class="required-field">Sales Date</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                      </div>
                      <input type="date" class="form-control" name="txtsalesDate" id="txtsalesDate" 
                        value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label>Product ID</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                      </div>
                      <input type="text" class="form-control" name="txtproductID" id="txtproductID" readonly>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Product Selection -->
            <div class="col-md-6">
              <div class="card card-primary card-outline">
                <div class="card-header">
                  <h3 class="card-title"><i class="fas fa-pills"></i> Product Selection</h3>
                </div>
                <div class="card-body">
                  <div class="form-group">
                    <label class="required-field">Select Product</label>
                    <select name="cmddrug" id="cmddrug" class="form-control select2" onchange="GetDrugDetail1(this.value)" required>
                      <option value="">Select Product</option>
                      <?php
                      $sql = "SELECT * FROM tblproduct WHERE qty > 0 ORDER BY product_name";
                      $stmt = $dbh->query($sql);
                      $stmt->setFetchMode(PDO::FETCH_ASSOC);
                      while ($row = $stmt->fetch()) {
                        $stockClass = '';
                        if ($row['qty'] < 5) {
                          $stockClass = 'critical-stock';
                        } elseif ($row['qty'] < 10) {
                          $stockClass = 'low-stock';
                        }
                        echo '<option value="'.$row['product_name'].'" class="'.$stockClass.'">'.$row['product_name'].' (Stock: '.$row['qty'].')</option>';
                      }
                      ?>
                    </select>
                  </div>
                  
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Category</label>
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-tag"></i></span>
                          </div>
                          <input type="text" class="form-control" name="txtcategory" id="txtcategory" readonly>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Expiry Date</label>
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-calendar-times"></i></span>
                          </div>
                          <input type="text" class="form-control" name="txtexpirydate" id="txtexpirydate" readonly>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label>Current Stock</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-cubes"></i></span>
                      </div>
                      <input type="text" class="form-control" name="txtstock" id="txtstock" readonly>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Pricing and Quantity Section -->
          <div class="row">
            <div class="col-md-12">
              <div class="card card-success card-outline">
                <div class="card-header">
                  <h3 class="card-title"><i class="fas fa-calculator"></i> Sale Details</h3>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="required-field">Quantity</label>
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <button type="button" class="btn btn-outline-secondary" onclick="decrementQty()">
                              <i class="fas fa-minus"></i>
                            </button>
                          </div>
                          <input type="number" class="form-control text-center" name="txtqty" id="txtqty" min="1" value="1" required>
                          <div class="input-group-append">
                            <button type="button" class="btn btn-outline-secondary" onclick="incrementQty()">
                              <i class="fas fa-plus"></i>
                            </button>
                          </div>
                        </div>
                        <small id="stock-warning" class="stock-warning">
                          <i class="fas fa-exclamation-triangle"></i> Quantity exceeds available stock
                        </small>
                      </div>
                    </div>
                    
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="required-field">Unit Price</label>
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-money-bill"></i></span>
                          </div>
                          <input type="text" class="form-control" name="txtprice" id="txtprice" readonly required>
                        </div>
                      </div>
                    </div>
                    
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Total Cost</label>
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-rupee-sign"></i></span>
                          </div>
                          <input type="text" class="form-control font-weight-bold" name="txttotalcost" id="txttotalcost" readonly>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <!-- Sale Summary Section -->
                  <div class="total-section mt-4">
                    <div class="row">
                      <div class="col-md-8">
                        <div id="sale-summary">
                          <!-- Sale summary will be displayed here -->
                          <p id="summary-product">Product: <span class="font-weight-bold">Select a product</span></p>
                          <p id="summary-quantity">Quantity: <span class="font-weight-bold">0</span></p>
                          <p id="summary-price">Price: <span class="font-weight-bold">₹0.00</span></p>
                        </div>
                      </div>
                      <div class="col-md-4 text-right">
                        <h4 class="mb-3">Total: <span id="summary-total" class="text-success font-weight-bold">₹0.00</span></h4>
                        <button type="submit" name="btnsave" id="btnSave" class="btn btn-success btn-lg">
                          <i class="fas fa-check-circle"></i> Complete Sale
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
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
<!-- Select2 -->
<script src="plugins/select2/js/select2.full.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.js"></script>

<script>
// Initialize Select2
$(function () {
  $('.select2').select2({
    theme: 'bootstrap4',
    placeholder: "Select an option",
    allowClear: true
  });
  
  // Initialize tooltips
  $('[data-toggle="tooltip"]').tooltip();
  
  // Set current date as default
  document.getElementById('txtsalesDate').valueAsDate = new Date();
});

// Drug details fetching function
function GetDrugDetail1(str) {
  if (str.length == 0) {
    document.getElementById("txtexpirydate").value = "";
    document.getElementById("txtstock").value = "";
    document.getElementById("txtcategory").value = "";
    document.getElementById("txtproductID").value = "";
    document.getElementById("txtprice").value = "";
    document.getElementById("txttotalcost").value = "";
    updateSaleSummary();
    return;
  } else {
    // Show loading indicator
    Swal.fire({
      title: 'Loading...',
      text: 'Fetching product details',
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      }
    });
    
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        var myObj = JSON.parse(this.responseText);
        document.getElementById("txtexpirydate").value = myObj[0];
        document.getElementById("txtstock").value = myObj[1];
        document.getElementById("txtcategory").value = myObj[2];
        document.getElementById("txtproductID").value = myObj[3];
        document.getElementById("txtprice").value = myObj[4];
        
        // Close loading indicator
        Swal.close();
        
        // Update total cost calculation
        calculateTotal();
        
        // Update stock status indication
        updateStockStatusIndication(myObj[1]);
        
        // Update sale summary
        updateSaleSummary();
      }
    };
    xmlhttp.open("GET", "search/search_sales.php?name=" + encodeURIComponent(str), true);
    xmlhttp.send();
  }
}

// Function to update stock status indication
function updateStockStatusIndication(stockValue) {
  const stockElement = document.getElementById("txtstock");
  stockValue = parseInt(stockValue);
  
  if (stockValue <= 0) {
    stockElement.classList.add("field-error");
    stockElement.classList.remove("low-stock", "critical-stock");
    stockElement.style.backgroundColor = "#f8d7da";
  } else if (stockValue < 5) {
    stockElement.classList.add("critical-stock");
    stockElement.classList.remove("field-error", "low-stock");
    stockElement.style.backgroundColor = "#fff3cd";
  } else if (stockValue < 10) {
    stockElement.classList.add("low-stock");
    stockElement.classList.remove("field-error", "critical-stock");
    stockElement.style.backgroundColor = "#fff3cd";
  } else {
    stockElement.classList.remove("field-error", "low-stock", "critical-stock");
    stockElement.style.backgroundColor = "";
  }
}

// Function to calculate total and check stock
function calculateTotal() {
  const price = parseFloat(document.getElementById("txtprice").value) || 0;
  const qty = parseInt(document.getElementById("txtqty").value) || 0;
  const stock = parseInt(document.getElementById("txtstock").value) || 0;
  
  // Format total with 2 decimal places
  const total = (price * qty).toFixed(2);
  document.getElementById("txttotalcost").value = total;
  
  // Check if quantity exceeds stock
  const qtyElement = document.getElementById("txtqty");
  const warningElement = document.getElementById("stock-warning");
  const saveButton = document.getElementById("btnSave");
  
  if (qty > stock) {
    qtyElement.classList.add("field-error");
    warningElement.style.display = "block";
    saveButton.disabled = true;
  } else {
    qtyElement.classList.remove("field-error");
    warningElement.style.display = "none";
    saveButton.disabled = false;
  }
  
  // Update sale summary
  updateSaleSummary();
}

// Function to increment quantity
function incrementQty() {
  const qtyElement = document.getElementById("txtqty");
  const currentQty = parseInt(qtyElement.value) || 0;
  qtyElement.value = currentQty + 1;
  calculateTotal();
}

// Function to decrement quantity
function decrementQty() {
  const qtyElement = document.getElementById("txtqty");
  const currentQty = parseInt(qtyElement.value) || 0;
  if (currentQty > 1) {
    qtyElement.value = currentQty - 1;
    calculateTotal();
  }
}

// Function to update sale summary
function updateSaleSummary() {
    const product = document.getElementById("cmddrug").value || "Select a product";
    const qty = parseInt(document.getElementById("txtqty").value) || 0;
    const price = parseFloat(document.getElementById("txtprice").value) || 0;
    const total = (price * qty).toFixed(2);
    
    document.getElementById("summary-product").innerHTML = `Product: <span class="font-weight-bold">${product}</span>`;
    document.getElementById("summary-quantity").innerHTML = `Quantity: <span class="font-weight-bold">${qty}</span>`;
    document.getElementById("summary-price").innerHTML = `Price: <span class="font-weight-bold">₹${price.toFixed(2)}</span>`;
    document.getElementById("summary-total").innerHTML = `₹${total}`;
}

// Add event listeners
document.addEventListener('DOMContentLoaded', function() {
  document.getElementById("txtqty").addEventListener("input", calculateTotal);
  document.getElementById("txtprice").addEventListener("input", calculateTotal);
  
  // Form validation
  document.getElementById("salesForm").addEventListener("submit", function(event) {
    const qty = parseInt(document.getElementById("txtqty").value) || 0;
    const stock = parseInt(document.getElementById("txtstock").value) || 0;
    
    if (qty > stock) {
      event.preventDefault();
      Swal.fire({
        icon: 'error',
        title: 'Invalid Quantity',
        text: 'Quantity cannot exceed available stock.',
        confirmButtonColor: '#dc3545'
      });
    }
    
    // Check for empty required fields
    const requiredFields = ['cmdcustomer', 'cmddrug', 'txtsalesDate', 'txtqty', 'txtprice'];
    let emptyFields = [];
    
    requiredFields.forEach(field => {
      if (!document.getElementById(field).value) {
        emptyFields.push(field);
        document.getElementById(field).classList.add("field-error");
      } else {
        document.getElementById(field).classList.remove("field-error");
      }
    });
    
    if (emptyFields.length > 0) {
      event.preventDefault();
      Swal.fire({
        icon: 'warning',
        title: 'Required Fields',
        text: 'Please fill all required fields marked with *',
        confirmButtonColor: '#ffc107'
      });
    }
  });
});

// SweetAlert for PHP success/error messages
<?php if(!empty($_SESSION['success'])) { ?>
Swal.fire({
  icon: 'success',
  title: 'Success!',
  text: '<?php echo $_SESSION['success']; ?>',
  confirmButtonColor: '#28a745'
});
<?php unset($_SESSION["success"]); } ?>

<?php if(!empty($_SESSION['error'])) { ?>
Swal.fire({
  icon: 'error',
  title: 'Error',
  html: '<?php echo $_SESSION['error']; ?>',
  confirmButtonColor: '#dc3545'
});
<?php unset($_SESSION["error"]); } ?>
</script>
</body>
</html>