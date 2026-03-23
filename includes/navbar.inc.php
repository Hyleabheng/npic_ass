<?php
$user = LoggedInUser();
?>

<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container">

    <!-- Logo + Text -->
    <a class="navbar-brand d-flex align-items-center" href="./">
      <img src="assets/images/ZANDO-NEW-LOGO-2025.png" alt="Logo" style="height:30px; margin-right:8px;">
   
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
    data-bs-target="#navbarSupportedContent">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">

      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

        <?php if ($user) { ?>
        <li class="nav-item">
          <a class="nav-link" href="./?page=dashboard">Dashboard</a>
        </li>
        <?php } ?>

        <?php if (isAdmin()) { ?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
            Manage
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="./?page=user/home">User Accounts</a></li>
            <li><a class="dropdown-item" href="./?page=category/home">Category Page</a></li>
            <li><a class="dropdown-item" href="./?page=product/home">Product Page</a></li>
            <li><a class="dropdown-item" href="./?page=stock/home">Stock Page</a></li>
          </ul>
        </li>
        <?php } ?>

        <?php if (isUser()) { ?>
        <li class="nav-item">
          <a href="./?page=cart/home" class="btn btn-primary">
            Cart 
            <span class="badge text-bg-secondary">
              <?php echo $user ? getPendingCartProductCountForUser((int)$user->id_user) : 0 ?>
            </span>
          </a>
        </li>
        <?php } ?>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
            <?php echo (!$user ? 'Account' : $user->user_label) ?>
          </a>
          <ul class="dropdown-menu">
            <?php if (!$user) { ?>
              <li><a class="dropdown-item" href="./?page=login">Login</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="./?page=register">Register</a></li>
            <?php } else { ?>
              <li><a class="dropdown-item" href="./?page=logout">Logout</a></li>
              
            <?php } ?>
          </ul>
        </li>

      </ul>

    </div>
  </div>
</nav>