<li class="nav-item" >
    <a href="chatbot.php" class="nav-link" style="transition: background-color 0.3s;">
      <i class="fas fa-robot nav-icon" style="animation: bounce 1s infinite;"></i>
      <p>AI Chatbot</p>
    </a>
  </li>

  <style>
  @keyframes bounce {
    0%, 20%, 50%, 80%, 100% {
      transform: translateY(0);
    }
    40% {
      transform: translateY(-10px);
    }
    60% {
      transform: translateY(-5px);
    }
  }

  .nav-link:hover {
    background-color:rgb(255, 187, 0); /* Change to your desired hover color */
  }
  </style>  <li class="nav-item menu-open">
            <a href="index.php" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
			</li>

      <li class="nav-item">
  <a href="#" class="nav-link">
    <i class="fas fa-users nav-icon"></i>
    <p>Users <i class="fas fa-angle-left right"></i></p>
  </a>
  <ul class="nav nav-treeview">
    <?php if($_SESSION['login_groupname'] == "Super Admin"){?>
    <li class="nav-item">
      <a href="add-admin.php" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p>Add User</p>
      </a>
    </li>
    <?php } ?>

    <li class="nav-item">
      <a href="edit-profile.php" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p>Edit Profile</p>
      </a>
    </li>

    <li class="nav-item">
      <a href="edit-photo.php" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p>Edit Photo</p>
      </a>
    </li>

    <?php if($_SESSION['login_groupname'] == "Super Admin") { ?>
    <li class="nav-item">
      <a href="user-record.php" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p>Admin Record</p>
      </a>
    </li>
    <?php } ?>
  </ul>
</li>

<?php if($_SESSION['login_groupname'] == "Super Admin"){ ?>
<li class="nav-item">
  <a href="add-category.php" class="nav-link">
    <i class="fas fa-tags nav-icon"></i>
    <p>Categories</p>
  </a>
</li>
<?php } ?>

<?php if($_SESSION['login_groupname'] == "Super Admin"){ ?>
<li class="nav-item">
  <a href="add-supplier.php" class="nav-link">
    <i class="fas fa-truck nav-icon"></i>
    <p>Suppliers</p>
  </a>
</li>
<?php } ?>

<?php if($_SESSION['login_groupname'] == "Super Admin"){ ?>
<li class="nav-item">
  <a href="add-product.php" class="nav-link">
    <i class="fas fa-box nav-icon"></i>
    <p>Products</p>
  </a>
</li>

<?php } ?>

<li class="nav-item">
  <a href="#" class="nav-link">
    <i class="fas fa-shopping-cart nav-icon"></i>
    <p>Sales <i class="fas fa-angle-left right"></i></p>
  </a>
  <ul class="nav nav-treeview">
    <li class="nav-item">
      <a href="add-sales.php" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p>Add Sales</p>
      </a>
    </li>
    <li class="nav-item">
      <a href="sales-record.php" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p>Sales Records</p>
      </a>
    </li>
  </ul>
</li>

<li class="nav-item">
  <a href="#" class="nav-link">
    <i class="fas fa-boxes nav-icon"></i>
    <p>Stock In <i class="fas fa-angle-left right"></i></p>
  </a>
  <ul class="nav nav-treeview">
    <li class="nav-item">
      <a href="add-stock.php" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p>Add Stock</p>
      </a>
    </li>
    <li class="nav-item">
      <a href="stock-record.php" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p>Stock Records</p>
      </a>
    </li>
  </ul>
</li>

<li class="nav-item">
  <a href="changepassword.php" class="nav-link">
    <i class="fas fa-key nav-icon"></i>
    <p>Change Password</p>
  </a>
</li>

<?php if($_SESSION['login_groupname'] == "Super Admin"){ ?>
<li class="nav-item">
  <a href="backup_db.php" class="nav-link">
    <i class="fas fa-database nav-icon"></i>
    <p>Backup DB</p>
  </a>
</li>
<?php } ?>

<li class="nav-item">
  <a href="logout.php" class="nav-link">
    <i class="fas fa-sign-out-alt nav-icon"></i>
    <p>Logout</p>
  </a>
</li>
