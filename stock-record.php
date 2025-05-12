<?php
include('topbar.php');
if(empty($_SESSION['login_email'])) {   
    header("Location: login.php"); 
    exit;
}
      
$email = $_SESSION["login_email"];
// Fix SQL injection vulnerability using prepared statements
$stmt = $dbh->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$row_user = $stmt->fetch();

// Replace the existing statistics queries with:
$stats = $dbh->query("SELECT 
    COUNT(DISTINCT ts.purchaseID) as total_records,
    SUM(tp.qty) as total_stock,
    SUM(CASE WHEN tp.qty <= 10 THEN 1 ELSE 0 END) as low_stock,
    SUM(CASE WHEN ts.expiryDate <= CURDATE() THEN 1 ELSE 0 END) as expired_stock
FROM tblstock ts
LEFT JOIN tblproduct tp ON tp.product_name = ts.drugName")->fetch();

$total_stock = $stats['total_stock'] ?? 0;
$low_stock = $stats['low_stock'] ?? 0;
$expired_stock = $stats['expired_stock'] ?? 0;

// Check for date filter
$date_filter = "";
if(isset($_POST['date_range'])) {
    $date_range = explode(' - ', $_POST['date_range']);
    $start_date = date('Y-m-d', strtotime($date_range[0]));
    $end_date = date('Y-m-d', strtotime($date_range[1]));
    $date_filter = "AND (tblstock.stockDate BETWEEN '$start_date' AND '$end_date')";
}

// Handle bulk delete action
if(isset($_POST['bulk_action']) && isset($_POST['record_ids']) && $_POST['bulk_action'] == 'delete') {
    $ids = $_POST['record_ids'];
    
    if(!empty($ids)) {
        foreach($ids as $id) {
            // Get product details first
            $stock_data = $dbh->prepare("SELECT productID, quantity, drugName FROM tblstock WHERE purchaseID = ?");
            $stock_data->execute([$id]);
            $stock_row = $stock_data->fetch();
            
            if($stock_row) {
                // Update product quantity
                $update_product = $dbh->prepare("UPDATE tblproduct SET qty = qty - ? WHERE product_name = ?");
                $update_product->execute([$stock_row['quantity'], $stock_row['drugName']]);
                
                // Delete the stock record
                $delete_stock = $dbh->prepare("DELETE FROM tblstock WHERE purchaseID = ?");
                $delete_stock->execute([$id]);
            }
        }
        $_SESSION['success'] = "Selected records have been deleted successfully";
    }
}

// Export to CSV if requested
if(isset($_GET['export']) && $_GET['export'] == 'csv') {
    $filename = 'stock_records_' . date('Y-m-d') . '.csv';
    
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    $output = fopen('php://output', 'w');
    
    // Column headers
    fputcsv($output, ['#', 'Product ID', 'Purchase ID', 'Stock Date', 'Drug Name', 'Unit Price', 'Quantity', 'Current Stock', 'Expiry Date']);
    
    // Data
    $export_query = "SELECT * FROM tblstock INNER JOIN tblproduct ON tblproduct.product_name = tblstock.drugName ORDER BY tblstock.stockDate DESC";
    $export_data = $dbh->query($export_query)->fetchAll();
    
    $cnt = 1;
    foreach($export_data as $row) {
        fputcsv($output, [
            $cnt,
            $row['productID'],
            $row['purchaseID'],
            $row['stockDate'],
            $row['drugName'],
            $row['unitPrice'],
            $row['quantity'],
            $row['qty'],
            $row['expirydate']
        ]);
        $cnt++;
    }
    
    fclose($output);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Stock Records | Dashboard</title>
  <link rel="icon" type="image/png" sizes="16x16" href="../<?php echo $favicon; ?>">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Bootstrap 4 -->
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  
  <style>
    /* Enhanced styling for better visual appeal */
    .card-dashboard {
      border-radius: 15px;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card-dashboard:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
    }
    .stock-low {
      background-color: rgba(255, 193, 7, 0.15);
    }
    .stock-expired {
      background-color: rgba(220, 53, 69, 0.15);
    }
    .table-striped tbody tr:nth-of-type(odd) {
      background-color: rgba(0, 123, 255, 0.05);
    }
    .table thead th {
      background-color: #343a40;
      color: white;
      border-color: #454d55;
    }
    .badge-pill {
      padding: 0.5em 0.8em;
      border-radius: 10rem;
      font-weight: 600;
    }
    .badge-warning {
      color: #212529;
      background-color: #ffc107;
    }
    .badge-danger {
      color: #fff;
      background-color: #dc3545;
    }
    .badge-success {
      color: #fff;
      background-color: #28a745;
    }
    .form-check-input {
      transform: scale(1.3);
    }
    .btn-group .btn {
      border-radius: 4px;
      margin-right: 2px;
    }
    /* Highlight on hover for better UX */
    #stock-table tbody tr:hover {
      background-color: rgba(0, 123, 255, 0.1);
    }
    /* Image thumbnail enhancement */
    .table .img-thumbnail {
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    /* Better visibility for statuses */
    .status-indicator {
      display: inline-block;
      width: 12px;
      height: 12px;
      border-radius: 50%;
      margin-right: 5px;
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
        <a href="#" class="nav-link">Stock Records</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
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
    <a href="dashboard.php" class="brand-link">
      <span class="brand-text font-weight-light">Pharmacy System</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="<?php echo $row_user['photo']; ?>" class="img-circle elevation-2" alt="User Image">
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
    </div>
  </aside>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Stock Records</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <a href="?export=csv" class="btn btn-success mr-2">
                <i class="fas fa-download"></i> Export CSV
              </a>
              <a href="add-stock.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Stock
              </a>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Simplified Dashboard Stats - Just 2 cards -->
        <div class="row">
          <div class="col-lg-6">
            <div class="small-box bg-gradient-primary card-dashboard">
              <div class="inner">
                <h3><?php echo number_format($total_stock); ?></h3>
                <p>Total Stock Inventory</p>
              </div>
              <div class="icon">
                <i class="ion ion-cube"></i>
              </div>
              <a href="#" class="small-box-footer">Stock Details <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="small-box bg-gradient-warning card-dashboard">
              <div class="inner">
                <h3><?php echo number_format($low_stock); ?></h3>
                <p>Low Stock Items (≤ 10 units)</p>
              </div>
              <div class="icon">
                <i class="ion ion-alert-circled"></i>
              </div>
              <a href="#" class="small-box-footer">View Low Stock <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
        </div>

        <!-- Enhanced Stock Records Table Card -->
        <div class="card shadow">
          <div class="card-header bg-gradient-info text-white">
            <h3 class="card-title">
              <i class="fas fa-boxes mr-1"></i>
              Stock Inventory Records
            </h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus text-white"></i>
              </button>
            </div>
          </div>
          
          <div class="card-body">
            <!-- Filter Section -->
            <div class="row mb-4">
              <div class="col-md-6">
                <form method="post" action="">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text bg-primary text-white">
                        <i class="far fa-calendar-alt"></i>
                      </span>
                    </div>
                    <input type="text" class="form-control" id="date_range" name="date_range" placeholder="Filter by date range">
                    <div class="input-group-append">
                      <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter mr-1"></i> Filter
                      </button>
                    </div>
                  </div>
                </form>
              </div>
              <div class="col-md-6">
                <div class="btn-group float-right">
                  <button type="button" class="btn btn-info" id="btn-refresh">
                    <i class="fas fa-sync-alt mr-1"></i> Refresh
                  </button>
                  <button type="button" class="btn btn-secondary" id="btn-print">
                    <i class="fas fa-print mr-1"></i> Print
                  </button>
                </div>
              </div>
            </div>
            
            <!-- Bulk Actions -->
            <form id="bulk-action-form" method="post" action="">
              <div class="row mb-3">
                <div class="col-md-3">
                  <div class="input-group">
                    <select class="form-control" id="bulk_action" name="bulk_action">
                      <option value="">Bulk Actions</option>
                      <option value="delete">Delete Selected</option>
                    </select>
                    <div class="input-group-append">
                      <button type="submit" class="btn btn-danger" id="apply-bulk-action">
                        <i class="fas fa-check mr-1"></i> Apply
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            
              <!-- Enhanced Data Table -->
              <div class="table-responsive">
                <table id="stock-table" class="table table-bordered table-striped table-hover">
                  <thead>
                    <tr>
                      <th width="20" class="text-center">
                        <div class="icheck-primary d-inline">
                          <input type="checkbox" id="check-all">
                          <label for="check-all"></label>
                        </div>
                      </th>
                      <th width="50" class="text-center">Image</th>
                      <th>Drug Name</th>
                      <th>Purchase ID</th>
                      <th>Date Added</th>
                      <th>Unit Price</th>
                      <th>Quantity</th>
                      <th>Current Stock</th>
                      <th>Expiry Date</th>
                      <th>Status</th>
                      <th width="100" class="text-center">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                    $query = "SELECT 
                        ts.purchaseID,
                        ts.stockDate,
                        ts.drugName,
                        ts.unitPrice,
                        ts.quantity,
                        ts.expiryDate as stock_expiry,
                        tp.productID,
                        tp.qty as current_stock,
                        tp.photo,
                        DATEDIFF(ts.expiryDate, CURDATE()) as days_until_expiry
                    FROM tblstock ts
                    LEFT JOIN tblproduct tp ON tp.product_name = ts.drugName 
                    WHERE 1=1 $date_filter 
                    ORDER BY ts.stockDate DESC";
                    $data = $dbh->query($query)->fetchAll();
                    $cnt = 1;
                    foreach ($data as $row) {
                      // Calculate if expired
                      $is_expired = (strtotime($row['stock_expiry']) < time());
                      // Calculate if low stock
                      $is_low_stock = ($row['current_stock'] <= 10);
                      
                      // Set row class based on stock status
                      $row_class = "";
                      if($is_expired) {
                        $row_class = "stock-expired";
                      } elseif($is_low_stock) {
                        $row_class = "stock-low";
                      }
                    ?>
                    <tr class="<?php echo $row['days_until_expiry'] < 0 ? 'stock-expired' : ($row['current_stock'] <= 10 ? 'stock-low' : ''); ?>">
    <td class="text-center">
        <div class="icheck-primary">
            <input type="checkbox" name="record_ids[]" value="<?php echo $row['purchaseID']; ?>" class="checkbox-item">
            <label></label>
        </div>
    </td>
    <td class="text-center">
        <?php if(!empty($row['photo']) && file_exists($row['photo'])): ?>
            <img src="<?php echo htmlspecialchars($row['photo']); ?>" class="img-thumbnail" width="40" height="40" alt="Product Image">
        <?php else: ?>
            <img src="dist/img/default-product.png" class="img-thumbnail" width="40" height="40" alt="Default Image">
        <?php endif; ?>
    </td>
    <td><strong><?php echo htmlspecialchars($row['drugName']); ?></strong></td>
    <td><?php echo htmlspecialchars($row['purchaseID']); ?></td>
    <td><?php echo date('d M Y', strtotime($row['stockDate'])); ?></td>
    <td>₹<?php echo number_format($row['unitPrice'], 2); ?></td>
    <td><?php echo $row['quantity']; ?></td>
    <td>
        <span class="badge badge-<?php echo $row['current_stock'] <= 10 ? 'warning' : 'success'; ?>">
            <?php echo $row['current_stock']; ?>
        </span>
    </td>
    <td>
        <?php if($row['days_until_expiry'] < 0): ?>
            <span class="badge badge-danger">Expired</span>
        <?php else: ?>
            <?php echo date('d M Y', strtotime($row['stock_expiry'])); ?>
            <?php if($row['days_until_expiry'] <= 30): ?>
                <span class="badge badge-warning ml-1">
                    <?php echo $row['days_until_expiry']; ?> days left
                </span>
            <?php endif; ?>
        <?php endif; ?>
    </td>
    <td>
        <?php if($row['days_until_expiry'] < 0): ?>
            <span class="badge badge-danger badge-pill">Expired</span>
        <?php elseif($row['current_stock'] <= 10): ?>
            <span class="badge badge-warning badge-pill">Low Stock</span>
        <?php else: ?>
            <span class="badge badge-success badge-pill">In Stock</span>
        <?php endif; ?>
    </td>
    <td class="text-center">
        <div class="btn-group">
            <a href="view-stock-details.php?id=<?php echo $row['purchaseID']; ?>" 
               class="btn btn-info btn-sm" title="View Details">
                <i class="fas fa-eye"></i>
            </a>
            <a href="delete-stock.php?id=<?php echo $row['productID']; ?>&qty_new=<?php echo $row['quantity']; ?>&qty_old=<?php echo $row['current_stock']; ?>&pid=<?php echo $row['purchaseID']; ?>" 
               class="btn btn-danger btn-sm delete-record" 
               title="Delete Record"
               data-name="<?php echo htmlspecialchars($row['drugName']); ?>">
                <i class="fas fa-trash"></i>
            </a>
        </div>
    </td>
</tr>
                    <?php $cnt++; } ?>
                  </tbody>
                </table>
              </div>
            </form>
          </div>
          <!-- /.card-body -->
          <div class="card-footer bg-light">
            <div class="legend">
              <span class="badge badge-success badge-pill mr-2">In Stock</span>
              <span class="badge badge-warning badge-pill mr-2">Low Stock</span>
              <span class="badge badge-danger badge-pill">Expired</span>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  <!-- /.content-wrapper -->
  
  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 1.2.0
    </div>
    <?php include('footer.php'); ?>
  </footer>
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
<!-- SweetAlert2 -->
<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- daterangepicker -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<script>
  $(function () {
    // Initialize DataTable with enhanced styling
    $('#stock-table').DataTable({
      "responsive": true,
      "lengthChange": true,
      "autoWidth": false,
      "pageLength": 10,
      "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
      "order": [[ 4, "desc" ]],
      "language": {
        "search": "<i class='fas fa-search'></i> _INPUT_",
        "searchPlaceholder": "Search records...",
        "lengthMenu": "Show _MENU_ records per page",
        "info": "Showing _START_ to _END_ of _TOTAL_ records",
        "infoEmpty": "No records available",
        "zeroRecords": "No matching records found"
      },
      "initComplete": function () {
        $('.dataTables_filter input').addClass('form-control-sm');
      }
    });
    
    // Date range picker with better UI
    $('#date_range').daterangepicker({
      locale: {
        format: 'YYYY-MM-DD',
        separator: ' - ',
        applyLabel: 'Apply',
        cancelLabel: 'Cancel'
      },
      opens: 'left',
      autoUpdateInput: false
    });
    
    $('#date_range').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
    });
    
    $('#date_range').on('cancel.daterangepicker', function(ev, picker) {
      $(this).val('');
    });
    
    // Handle check all checkbox
    $('#check-all').on('click', function() {
      $('.checkbox-item').prop('checked', $(this).prop('checked'));
    });
    
    // Individual checkbox clicks should update header checkbox
    $(document).on('click', '.checkbox-item', function() {
      if ($('.checkbox-item:checked').length === $('.checkbox-item').length) {
        $('#check-all').prop('checked', true);
      } else {
        $('#check-all').prop('checked', false);
      }
    });
    
    // Enhanced confirmation before delete with SweetAlert2
    $('.delete-record').on('click', function(e) {
      e.preventDefault();
      var drugName = $(this).data('name');
      var deleteUrl = $(this).attr('href');
      
      Swal.fire({
        title: 'Confirm Deletion',
        text: "Are you sure you want to delete " + drugName + "?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
        focusCancel: true
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = deleteUrl;
        }
      });
    });
    
    // Confirmation before bulk action
    $('#apply-bulk-action').on('click', function(e) {
      var action = $('#bulk_action').val();
      var checkedItems = $('.checkbox-item:checked');
      
      if(action === '') {
        e.preventDefault();
        Swal.fire({
          title: 'Action Required',
          text: 'Please select an action from the dropdown',
          icon: 'info'
        });
        return false;
      }
      
      if(checkedItems.length === 0) {
        e.preventDefault();
        Swal.fire({
          title: 'No Items Selected',
          text: 'Please select at least one record',
          icon: 'info'
        });
        return false;
      }
      
      if(action === 'delete') {
        e.preventDefault();
        Swal.fire({
          title: 'Confirm Bulk Deletion',
          text: "You are about to delete " + checkedItems.length + " records. This action cannot be undone!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Yes, delete them!',
          cancelButtonText: 'Cancel',
          reverseButtons: true,
          focusCancel: true
        }).then((result) => {
          if (result.isConfirmed) {
            $('#bulk-action-form').submit();
          }
        });
      }
    });
    
    // Refresh button with animation
    $('#btn-refresh').on('click', function() {
      $(this).html('<i class="fas fa-spinner fa-spin mr-1"></i> Refreshing...');
      window.location.reload();
    });
    
    // Print button
    $('#btn-print').on('click', function() {
      window.print();
    });
    
    // Handle success message with improved styling
    <?php if(!empty($_SESSION['success'])): ?>
    Swal.fire({
      icon: 'success',
      title: 'Success!',
      text: '<?php echo $_SESSION['success']; ?>',
      timer: 3000,
      timerProgressBar: true,
      showConfirmButton: false,
      toast: true,
      position: 'top-end'
    });
    <?php unset($_SESSION['success']); endif; ?>
    
    // Handle error message with improved styling
    <?php if(!empty($_SESSION['error'])): ?>
    Swal.fire({
      icon: 'error',
      title: 'Error!',
      text: '<?php echo $_SESSION['error']; ?>',
      timer: 3000,
      timerProgressBar: true,
      showConfirmButton: false,
      toast: true,
      position: 'top-end'
    });
    <?php unset($_SESSION['error']); endif; ?>
  });
</script>
</body>
</html>