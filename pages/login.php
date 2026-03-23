<?php

$username_err = $passwd_err = '';

if (isset($_POST['username']) && isset($_POST['passwd'])) {
  $username = $_POST['username'];
  $passwd = $_POST['passwd'];

  if (usernameExists($username)) {
    if (logUserIn($username, $passwd)) {
      header('Location: ./?page=dashboard');
      exit;
    } else {
      $passwd_err = 'Password not match';
    }
  } else {
    $username_err = 'Username not found';
  }
}

?>
<div class="container py-5">
  <div class="auth-page">
    <div class="card auth-card shadow-lg border-0 rounded-4">
      <div class="card-body">

        <?php if (isset($_GET['registered']) && $_GET['registered'] == '1') { ?>
          <div class="alert alert-success" role="alert">
            Registration successful! Please sign in.
          </div>
        <?php } ?>

        <div class="text-center mb-4">
          <div class="auth-brand mb-2">
            <img src="assets/images/ZANDO-NEW-LOGO-2025.png" alt="Logo">
          </div>
          <div class="fw-bold">Welcome back</div>
          <div class="text-muted small">Sign in to continue</div>
        </div>

        <form method="post" action="./?page=login" id="login-form">
          <div class="mb-3">
            <label for="username" class="form-label fw-bold">Username</label>
            <input id="username" type="text" name="username"
              class="form-control <?php echo $username_err ? 'is-invalid' : '' ?>" placeholder=" username"
              value="<?php echo $_POST['username'] ?? '' ?>">
            <div class="invalid-feedback"><?php echo $username_err ?></div>
          </div>

          <div class="mb-3">
            <label for="passwd" class="form-label fw-bold">Password</label>
            <input id="passwd" type="password" name="passwd"
              class="form-control <?php echo $passwd_err ? 'is-invalid' : '' ?>" placeholder="password">
            <div class="invalid-feedback"><?php echo $passwd_err ?></div>
          </div>

          <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="1" id="rememberMe" disabled>
              <label class="form-check-label text-muted" for="rememberMe">Remember me</label>
            </div>
            <a href="./?page=register" class="small">Create account</a>
          </div>

          <div class="d-grid">
            <button class="btn btn-primary btn-auth" type="submit">
              Sign In
            </button>
          </div>
        </form>

        <div class="text-center text-muted small mt-4">
          Copyright © 2025-2026 <span class="fw-semibold text-primary">LEABHENG DEVELOPER</span>
        </div>

      </div>
    </div>
  </div>
</div>


