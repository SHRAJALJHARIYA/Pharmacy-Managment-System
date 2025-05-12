<?php
session_start();
require_once('database/connect.php');

if(isset($_POST['btnlogin']))
{
  $status ="1";
  //login
  $sql = "SELECT * FROM `users` WHERE `email`=? AND `password`=? AND `status`=?";
  $query = $dbh->prepare($sql);
  $query->execute(array($_POST['txtemail'],$_POST['txtpassword'],$status));
  $row = $query->rowCount();
  $fetch = $query->fetch();
  if($row > 0) {
    $_SESSION['login_email'] = $fetch['email'];
    $_SESSION['login_groupname'] = $fetch['groupname'];
    $_SESSION['login_fullname'] = $fetch['fullname'];
    $_SESSION['logged']=time();
    
    //save activity log details
    $fullname=$fetch['fullname'];
    $task= $fullname.' '.'Logged In'.' '. 'On' . ' '.$current_date;
    $sql = 'INSERT INTO activity_log(task) VALUES(:task)';
    $statement = $dbh->prepare($sql);
    $statement->execute([
      ':task' => $task
    ]);

    header("Location: index.php"); 
  }else { 
    $_SESSION['error']='Wrong Email and Password';
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login Form | PharmacWala</title>
  <link rel="icon" type="image/png" sizes="16x16" href="../assets/logo.png">
  
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- Animate.css -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
  <!-- Custom CSS -->
  <style>
    :root {
      --primary-color: #3f51b5;
      --secondary-color: #ff5722;
      --accent-color: #8bc34a;
    }
    
    body {
      background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
      min-height: 100vh;
      overflow-x: hidden;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .login-container {
      max-width: 500px;
      width: 100%;
      margin: 0 auto;
      padding: 2rem;
      background: rgba(255, 255, 255, 0.95);
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      position: relative;
      z-index: 1;
      animation: fadeInUp 0.8s;
    }
    
    .logo-container {
      text-align: center;
      margin-bottom: 2rem;
      position: relative;
    }
    
    .logo {
      width: 120px;
      height: 120px;
      object-fit: contain;
      animation: pulse 2s infinite;
    }
    
    .medical-icon {
      position: absolute;
      opacity: 0.1;
      z-index: -1;
    }
    
    .pill-icon {
      top: -30px;
      left: -30px;
      font-size: 100px;
      color: var(--primary-color);
      animation: float 6s ease-in-out infinite;
    }
    
    .heartbeat-icon {
      bottom: -40px;
      right: -30px;
      font-size: 120px;
      color: var(--secondary-color);
      animation: heartbeat 1.5s ease infinite;
    }
    
    .form-control {
      border-radius: 50px;
      padding: 12px 20px;
      border: 2px solid #e0e0e0;
      transition: all 0.3s;
    }
    
    .form-control:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 0.25rem rgba(63, 81, 181, 0.25);
    }
    
    .input-group-text {
      border-radius: 50px 0 0 50px;
      background: transparent;
      border-right: none;
    }
    
    .btn-login {
      background: var(--primary-color);
      border: none;
      border-radius: 50px;
      padding: 12px;
      font-weight: 600;
      letter-spacing: 1px;
      transition: all 0.3s;
      width: 100%;
    }
    
    .btn-login:hover {
      background: #303f9f;
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(63, 81, 181, 0.3);
    }
    
    .remember-me {
      color: #555;
    }
    
    /* Animations */
    @keyframes float {
      0% { transform: translateY(0px); }
      50% { transform: translateY(-20px); }
      100% { transform: translateY(0px); }
    }
    
    @keyframes heartbeat {
      0% { transform: scale(1); }
      25% { transform: scale(1.1); }
      50% { transform: scale(1); }
      75% { transform: scale(1.1); }
      100% { transform: scale(1); }
    }
    
    @keyframes pulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.05); }
      100% { transform: scale(1); }
    }
    
    .circles {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      overflow: hidden;
      z-index: 0;
    }
    
    .circles li {
      position: absolute;
      display: block;
      list-style: none;
      width: 20px;
      height: 20px;
      background: rgba(63, 81, 181, 0.1);
      animation: animate 25s linear infinite;
      bottom: -150px;
      border-radius: 50%;
    }
    
    .circles li:nth-child(1) {
      left: 25%;
      width: 80px;
      height: 80px;
      animation-delay: 0s;
    }
    
    .circles li:nth-child(2) {
      left: 10%;
      width: 20px;
      height: 20px;
      animation-delay: 2s;
      animation-duration: 12s;
    }
    
    .circles li:nth-child(3) {
      left: 70%;
      width: 20px;
      height: 20px;
      animation-delay: 4s;
    }
    
    .circles li:nth-child(4) {
      left: 40%;
      width: 60px;
      height: 60px;
      animation-delay: 0s;
      animation-duration: 18s;
    }
    
    .circles li:nth-child(5) {
      left: 65%;
      width: 20px;
      height: 20px;
      animation-delay: 0s;
    }
    
    .circles li:nth-child(6) {
      left: 75%;
      width: 110px;
      height: 110px;
      animation-delay: 3s;
    }
    
    .circles li:nth-child(7) {
      left: 35%;
      width: 150px;
      height: 150px;
      animation-delay: 7s;
    }
    
    .circles li:nth-child(8) {
      left: 50%;
      width: 25px;
      height: 25px;
      animation-delay: 15s;
      animation-duration: 45s;
    }
    
    .circles li:nth-child(9) {
      left: 20%;
      width: 15px;
      height: 15px;
      animation-delay: 2s;
      animation-duration: 35s;
    }
    
    .circles li:nth-child(10) {
      left: 85%;
      width: 150px;
      height: 150px;
      animation-delay: 0s;
      animation-duration: 11s;
    }
    
    @keyframes animate {
      0% {
        transform: translateY(0) rotate(0deg);
        opacity: 1;
        border-radius: 0;
      }
      100% {
        transform: translateY(-1000px) rotate(720deg);
        opacity: 0;
        border-radius: 50%;
      }
    }
    
    /* Popup Styles */
    .popup {
      display: none;
      position: fixed;
      left: 0;
      top: 0;
      height: 100%;
      width: 100%;
      overflow: auto;
      background-color: rgba(0,0,0,0.5);
      z-index: 999;
    }
    
    .popup--visible {
      display: block;
    }
    
    .popup__background {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.4);
      z-index: 10;
    }
    
    .popup__content {
      position: relative;
      background-color: #fefefe;
      margin: 15% auto;
      padding: 20px;
      border-radius: 10px;
      width: 40%;
      box-shadow: 0 5px 15px rgba(0,0,0,.5);
      z-index: 20;
      text-align: center;
    }
    
    .popup__content__title {
      margin-bottom: 15px;
      font-weight: 500;
    }
    
    .button {
      background: #3f51b5;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 5px;
      cursor: pointer;
      transition: all 0.3s;
    }
    
    .button:hover {
      background: #303f9f;
    }
    
    .button--success {
      background: #4CAF50;
    }
    
    .button--success:hover {
      background: #3e8e41;
    }
    
    .button--error {
      background: #f44336;
    }
    
    .button--error:hover {
      background: #d32f2f;
    }
    
    .-success .popup__content__title {
      color: #4CAF50;
    }
    
    .-error .popup__content__title {
      color: #f44336;
    }
  </style>
</head>
<body>
  <div class="container d-flex align-items-center justify-content-center min-vh-100">
    <!-- Background animation elements -->
    <div class="circles">
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
    </div>
    
    <div class="login-container animate__animated animate__fadeIn">
      <div class="logo-container">
        <i class="fas fa-pills medical-icon pill-icon"></i>
        <i class="fas fa-heartbeat medical-icon heartbeat-icon"></i>
        <img src="https://img.icons8.com/?size=100&id=a18dSVWlU1a8&format=png&color=000000" alt="Pharmacy Logo" class="logo">
        <h2 class="mt-3 mb-4 text-primary fw-bold">PharmacyWala</h2>
        A pharmcay Management System
      </div>
      
      <form action="" method="post">
        <div class="mb-4">
          <div class="input-group">
            <span class="input-group-text bg-primary text-white">
              <i class="fas fa-envelope"></i>
            </span>
            <input type="text" class="form-control" name="txtemail" placeholder="Enter Email Address" required>
          </div>
        </div>
        
        <div class="mb-4">
          <div class="input-group">
            <span class="input-group-text bg-primary text-white">
              <i class="fas fa-lock"></i>
            </span>
            <input type="password" class="form-control" name="txtpassword" placeholder="Enter Password" required>
          </div>
        </div>
        
        <div class="mb-3 form-check">
          <input type="checkbox" class="form-check-input" id="remember">
          <label class="form-check-label remember-me" for="remember">Remember me</label>
        </div>
        
        <button type="submit" name="btnlogin" class="btn btn-primary btn-login mb-3 animate__animated animate__pulse animate__infinite animate__slower">
          <i class="fas fa-sign-in-alt me-2"></i> SIGN IN
        </button>
        
        <div class="text-center mt-3">
          <a href="#" class="text-muted small">Forgot Password?</a>
        </div>
      </form>
    </div>
  </div>

  <!-- jQuery and Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  
  <!-- Success Popup -->
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
  
  <!-- Error Popup -->
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

    Array.from(document.querySelectorAll('button[data-for]')).
    forEach(addButtonTrigger);
  </script>
</body>
</html>