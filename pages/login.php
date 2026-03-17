<?php

$username_err = $passwd_err = '';

if (isset($_POST['username']) && isset($_POST['passwd'])) {
  $username = $_POST['username'];
  $passwd = $_POST['passwd'];

  if (usernameExists($username)) {
    if (logUserIn($username, $passwd)) {
      header('Location: ./?page=dashboard');
    } else {
      $passwd_err = 'Password not match';
    }
  } else {
    $username_err = 'Username not found';
  }
}

?>

<!DOCTYPE html>
<html>

<head>

  <title>Login</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    body {
      background: #e9ecef;
      font-family: Arial;
    }

    .login-box {
      width: 420px;
      margin: 80px auto;
      background: white;
      border: 1px solid #ddd;
      border-radius: 5px;
    }

    .login-header {
      text-align: center;
      padding: 20px;
      border-bottom: 1px solid #ddd;
    }

    .login-header img {
      width: 70px;
    }

    .login-header h5 {
      color: #2c3e90;
      font-weight: bold;
      margin-top: 10px;
    }

    .login-body {
      padding: 30px;
    }

    .input-group-text {
      background: #dee2e6;
    }

    .footer {
      text-align: center;
      padding: 20px;
      font-size: 14px;
      color: #666;
      border-top: 1px solid #ddd;
    }

    /* LOADING SCREEN */

    #loading-screen {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.4);
      display: none;
      align-items: center;
      justify-content: center;
      z-index: 9999;
    }

    .loading-box {
      background: white;
      padding: 30px;
      border-radius: 12px;
      text-align: center;
      width: 220px;
      /* តូចជាងមុន */
    }

    .spinner {
      margin: 20px auto;
      width: 40px;
      height: 40px;
      border: 5px solid #ddd;
      border-top: 5px solid #0d6efd;
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      0% {
        transform: rotate(0deg);
      }

      100% {
        transform: rotate(360deg);
      }
    }
  </style>

</head>

<body>
  <div id="loading-screen">

    <div class="loading-box">

      <h4>Loading...</h4>

      <div class="spinner"></div>

    </div>

  </div>
  <div class="login-box">

    <div class="login-header">

      <img src="assets/images/ZANDO-NEW-LOGO-2025.png">

      <h5>Welcome to our store.</h5>

    </div>

    <div class="login-body">

      <h5 class="text-center mb-4">Sign in to start your session</h5>

      <form method="post">

        <div class="input-group mb-3">

          <input type="text" name="username" class="form-control <?php echo $username_err ? 'is-invalid' : '' ?>"
            placeholder="Username" value="<?php echo isset($_POST['username']) ? $_POST['username'] : '' ?>">

          <span class="input-group-text">
            <i class="fa fa-user"></i>
          </span>

          <div class="invalid-feedback">
            <?php echo $username_err ?>
          </div>

        </div>

        <div class="input-group mb-3">

          <input type="password" name="passwd" class="form-control <?php echo $passwd_err ? 'is-invalid' : '' ?>"
            placeholder="Password">

          <span class="input-group-text">
            <i class="fa fa-lock"></i>
          </span>

          <div class="invalid-feedback">
            <?php echo $passwd_err ?>
          </div>

        </div>

        <div class="d-flex justify-content-between align-items-center">

          <div>
            <input type="checkbox"> Remember Me
          </div>

          <button class="btn btn-primary px-4">
            Sign In
          </button>

        </div>

      </form>

    </div>

    <div class="footer">

      Copyright © 2025-2026
      <b style="color:#0d6efd;">LEABHENG DEVELOPER</b> <br>
   
    </div>

  </div>

</body>
<script>

  document.querySelector("form").addEventListener("submit", function (e) {

    e.preventDefault();

    /* បង្ហាញ Loading */
    document.getElementById("loading-screen").style.display = "flex";

    /* រង់ចាំ 3 វិនាទី */
    setTimeout(() => {
      this.submit();
    }, 2000);

  });

</script>

</html>