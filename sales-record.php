<?php
include('topbar.php');
if(empty($_SESSION['login_email'])) {   
    header("Location: login.php"); 
} else {
}
      
$email = $_SESSION["login_email"];
//fetch user data
$stmt = $dbh->query("SELECT * FROM users where email='$email'");
$row_user = $stmt->fetch();

// Get real-time statistics for dashboard
$totalSales = $dbh->query("SELECT COUNT(*) as count FROM sales")->fetch();
$todaySales = $dbh->query("SELECT COUNT(*) as count FROM sales WHERE DATE(saleDate) = CURDATE()")->fetch();
$lowStock = $dbh->query("SELECT COUNT(*) as count FROM tblproduct WHERE qty < 10")->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sales Record | Dashboard</title>
  <link rel="icon" type="image/png" sizes="16x16" href="../<?php echo $favicon; ?>">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
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
    function deldata(drugName) {
      if(confirm("ARE YOU SURE YOU WISH TO DELETE " + drugName + " FROM THE DATABASE?")) {
        return true;
      } else {
        return false;
      }
    }
    
    // Function to update the dashboard statistics
    function updateStats() {
      $.ajax({
        url: 'get_stats.php',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
          $('#total-sales-count').text(data.totalSales);
          $('#today-sales-count').text(data.todaySales);
          $('#low-stock-count').text(data.lowStock);
        },
        complete: function() {
          // Update every 30 seconds
          setTimeout(updateStats, 30000);
        }
      });
    }
    
    // Start updating stats when document is ready
    $(document).ready(function() {
      updateStats();
    });
  </script>
  <style>
    .bg-gradient-primary {
      background: linear-gradient(to right, #4e73df, #224abe) !important;
    }
    .card-primary.card-outline {
      border-top: 3px solid #4e73df;
    }
    .btn-primary {
      background-color: #4e73df;
      border-color: #4e73df;
    }
    .btn-primary:hover {
      background-color: #2e59d9;
      border-color: #2653d4;
    }
    .btn-danger {
      background-color: #e74a3b;
      border-color: #e74a3b;
    }
    .btn-danger:hover {
      background-color: #be2617;
      border-color: #be2617;
    }
    .table thead th {
      background-color: #f8f9fc;
      border-bottom: 2px solid #e3e6f0;
    }
    .img-profile {
      height: 45px;
      width: 45px;
      object-fit: cover;
    }
    .product-img {
      height: 50px;
      width: 50px;
      object-fit: cover;
      border-radius: 5px;
      border: 1px solid #e3e6f0;
    }
    .badge-stock {
      font-size: 85%;
    }
    .page-title {
      color: #5a5c69;
      font-weight: 600;
      margin-bottom: 1.5rem;
    }
    .action-btn {
      padding: 0.25rem 0.5rem;
      font-size: 0.75rem;
    }
    .stat-card {
      border-radius: 8px;
      box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
      transition: transform 0.3s ease;
    }
    .stat-card:hover {
      transform: translateY(-3px);
    }
    .stat-card .icon {
      font-size: 2rem;
      opacity: 0.4;
    }
    .stat-number {
      font-size: 1.8rem;
      font-weight: 700;
    }
    .stat-label {
      font-size: 0.9rem;
      text-transform: uppercase;
      letter-spacing: 0.1rem;
    }
    .bg-sales {
      background: linear-gradient(45deg, #4e73df, #224abe);
    }
    .bg-today {
      background: linear-gradient(45deg, #1cc88a, #13855c);
    }
    .bg-stock {
      background: linear-gradient(45deg, #f6c23e, #dda20a);
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
        <a href="#" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Sales Dashboard</a>
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
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#" id="refresh-stats">
          <i class="fas fa-sync-alt"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    

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

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
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
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="page-title">Sales Records</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Sales Record</li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Real-time Stats Cards -->
        <div class="row">
          <div class="col-lg-4">
            <div class="card text-white mb-4 stat-card bg-sales">
              <div class="card-body">
                <div class="row align-items-center">
                  <div class="col-3">
                    <i class="fas fa-shopping-cart icon"></i>
                  </div>
                  <div class="col-9 text-right">
                    <div class="stat-number" id="total-sales-count"><?php echo $totalSales['count']; ?></div>
                    <div class="stat-label">Total Sales</div>
                  </div>
                </div>
              </div>
              <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="small text-white stretched-link" href="#">View Details</a>
                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
              </div>
            </div>
          </div>
          
          <div class="col-lg-4">
            <div class="card text-white mb-4 stat-card bg-today">
              <div class="card-body">
                <div class="row align-items-center">
                  <div class="col-3">
                    <i class="fas fa-calendar-day icon"></i>
                  </div>
                  <div class="col-9 text-right">
                    <div class="stat-number" id="today-sales-count"><?php echo $todaySales['count']; ?></div>
                    <div class="stat-label">Today's Sales</div>
                  </div>
                </div>
              </div>
              <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="small text-white stretched-link" href="#">View Details</a>
                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
              </div>
            </div>
          </div>
          
          <div class="col-lg-4">
            <div class="card text-white mb-4 stat-card bg-stock">
              <div class="card-body">
                <div class="row align-items-center">
                  <div class="col-3">
                    <i class="fas fa-exclamation-triangle icon"></i>
                  </div>
                  <div class="col-9 text-right">
                    <div class="stat-number" id="low-stock-count"><?php echo $lowStock['count']; ?></div>
                    <div class="stat-label">Low Stock Items</div>
                  </div>
                </div>
              </div>
              <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="small text-white stretched-link" href="#">View Details</a>
                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Sales Records Table -->
        <div class="row">
          <div class="col-12">
            <div class="card card-primary card-outline">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-shopping-cart mr-1"></i>
                  Sales Transactions
                </h3>
                <div class="card-tools">
                  <a href="add-sales.php" class="btn btn-primary">
                    <i class="fas fa-plus mr-1"></i> Add New Sale
                  </a>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped table-hover">
                  <thead>
                    <tr>
                      <th width="5%">#</th>
                      <th width="8%">Photo</th>
                      <th width="10%">Sales ID</th>
                      <th width="15%">Customer</th>
                      <th width="15%">Drug</th>
                      <th width="10%">Sale Date</th>
                      <th width="7%">Qty</th>
                      <th width="7%">Stock</th>
                      <th width="8%">Unit Price</th>
                      <th width="10%">Expiry Date</th>
                      <th width="5%">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                    $data = $dbh->query("SELECT * FROM sales inner join tblproduct on tblproduct.product_name = sales.drugName order by sales.saleDate DESC")->fetchAll();
                    $cnt=1;
                    foreach ($data as $row) {
                      // Calculate days until expiry
                      $expiryDate = new DateTime($row['expirydate']);
                      $today = new DateTime();
                      $daysLeft = $today->diff($expiryDate)->days;
                      $expired = $today > $expiryDate;
                      
                      // Stock status
                      $stockStatus = '';
                      if ($row['qty'] < 5) {
                        $stockStatus = 'danger';
                      } elseif ($row['qty'] < 10) {
                        $stockStatus = 'warning';
                      } else {
                        $stockStatus = 'success';
                      }
                    ?>
                    <tr>
                      <td class="text-center"><?php echo $cnt; ?></td>
                      <td class="text-center">
                        <img src="<?php echo $row['photo']; ?>" class="product-img" alt="Product Image">
                      </td>
                      <td><?php echo $row['saleID']; ?></td>
                      <td><?php echo $row['customerName']; ?></td>
                      <td><?php echo $row['drugName']; ?></td>
                      <td><?php echo date('M d, Y', strtotime($row['saleDate'])); ?></td>
                      <td class="text-center"><?php echo $row['quantity']; ?></td>
                      <td class="text-center">
                        <span class="badge badge-<?php echo $stockStatus; ?> badge-stock">
                          <?php echo $row['qty']; ?>
                        </span>
                      </td>
                      <td class="text-right">₹<?php echo number_format((float) $row['unitPrice'], 2); ?></td>
                      <td>
                        <?php if ($expired): ?>
                          <span class="badge badge-danger">Expired</span>
                        <?php else: ?>
                          <span class="badge badge-<?php echo ($daysLeft < 30) ? 'warning' : 'info'; ?>">
                            <?php echo date('M d, Y', strtotime($row['expirydate'])); ?>
                          </span>
                        <?php endif; ?>
                      </td>
                      <td class="text-center">
                        <div class="btn-group">
                          <a href="delete-sales.php?sid=<?php echo $row['saleID']; ?>&qty_new=<?php echo $row['quantity']; ?>&qty_old=<?php echo $row['qty']; ?>&pid=<?php echo $row['productID']; ?>" 
                             class="btn btn-danger btn-sm action-btn"
                             onClick="return deldata('<?php echo $row['drugName']; ?>');">
                            <i class="fas fa-trash"></i>
                          </a>
                        </div>
                      </td>
                    </tr>
                    <?php $cnt=$cnt+1; } ?>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer text-muted">
                <small><i class="fas fa-info-circle mr-1"></i> Last updated: <span id="last-updated"><?php echo date('M d, Y h:i:s A'); ?></span></small>
                <button id="manual-refresh" class="btn btn-sm btn-outline-primary float-right">
                  <i class="fas fa-sync-alt mr-1"></i> Refresh Data
                </button>
              </div>
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 1.0.0
    </div>
    <?php include('footer.php'); ?>
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
<!-- DataTables  & Plugins -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="plugins/jszip/jszip.min.js"></script>
<script src="plugins/pdfmake/pdfmake.min.js"></script>
<script src="plugins/pdfmake/vfs_fonts.js"></script>
<script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- Page specific script -->
<script>
  $(function () {
    $("#example1").DataTable({
        "responsive": true,
        "lengthChange": true,
        "autoWidth": false,
        "buttons": [
            {
                extend: 'copy',
                exportOptions: {
                    format: {
                        body: function (data, row, column, node) {
                            // Replace $ with ₹ in copied data
                            return data.replace(/\$/g, '₹');
                        }
                    }
                }
            },
            {
                extend: 'csv',
                exportOptions: {
                    format: {
                        body: function (data, row, column, node) {
                            return data.replace(/\$/g, '₹');
                        }
                    }
                }
            },
            {
                extend: 'excel',
                exportOptions: {
                    format: {
                        body: function (data, row, column, node) {
                            return data.replace(/\$/g, '₹');
                        }
                    }
                }
            },
            {
                extend: 'pdf',
                exportOptions: {
                    format: {
                        body: function (data, row, column, node) {
                            return data.replace(/\$/g, '₹');
                        }
                    }
                }
            },
            {
                extend: 'print',
                exportOptions: {
                    format: {
                        body: function (data, row, column, node) {
                            return data.replace(/\$/g, '₹');
                        }
                    }
                }
            }
        ],
        "order": [[5, "desc"]]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
  });
  
  // Manual refresh button
  $('#manual-refresh, #refresh-stats').click(function() {
    updateStats(true);
    
    // Also refresh the table data
    // This would typically require an AJAX reload of the table data
    // For this example, we're just simulating a refresh
    salesTable.ajax.reload();
    
    // Update the last updated time
    $('#last-updated').text(new Date().toLocaleString());
  });
  
  // Function to create the API file for real-time updates
  // Note: This is a description of what needs to be created - you'll need to create this file separately
  /*
  File name: get_stats.php
  
  <?php
  // Include database connection
  include('db_connection.php');
  
  // Get real-time statistics
  $totalSales = $dbh->query("SELECT COUNT(*) as count FROM sales")->fetch();
  $todaySales = $dbh->query("SELECT COUNT(*) as count FROM sales WHERE DATE(saleDate) = CURDATE()")->fetch();
  $lowStock = $dbh->query("SELECT COUNT(*) as count FROM tblproduct WHERE qty < 10")->fetch();
  
  // Return as JSON
  header('Content-Type: application/json');
  echo json_encode([
    'totalSales' => $totalSales['count'],
    'todaySales' => $todaySales['count'],
    'lowStock' => $lowStock['count'],
    'timestamp' => date('Y-m-d H:i:s')
  ]);
  ?>
  */
  
  // Function to update stats with optional visual feedback
  function updateStats(showFeedback = false) {
    if (showFeedback) {
      // Add a loading indicator
      $('.stat-number').html('<i class="fas fa-spinner fa-spin"></i>');
    }
    
    $.ajax({
      url: 'get_stats.php',
      type: 'GET',
      dataType: 'json',
      success: function(data) {
        $('#total-sales-count').text(data.totalSales);
        $('#today-sales-count').text(data.todaySales);
        $('#low-stock-count').text(data.lowStock);
        $('#last-updated').text(new Date(data.timestamp).toLocaleString());
        
        // Visual feedback of update
        if (showFeedback) {
          $('.stat-card').addClass('border border-light');
          setTimeout(function() {
            $('.stat-card').removeClass('border border-light');
          }, 300);
        }
      },
      error: function() {
        console.log('Error fetching stats');
      },
      complete: function() {
        // Update every 30 seconds if not manually refreshed
        if (!showFeedback) {
          setTimeout(function() {
            updateStats();
          }, 30000);
        }
      }
    });
  }
  
  // Start updating stats when document is ready
  $(document).ready(function() {
    updateStats();
  });
</script>
</body>
</html>