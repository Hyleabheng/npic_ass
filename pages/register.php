<?php
$nameErr = $usernameErr = $passwdErr = '';
$name = $username = '';
if (isset($_POST['name'], $_POST['username'], $_POST['passwd'], $_POST['confirmPasswd'])) {
    $name = trim($_POST['name']);
    $username = trim($_POST['username']);
    $passwd = trim($_POST['passwd']);
    $confirmPasswd = trim($_POST['confirmPasswd']);
    if (empty($name)) {
        $nameErr = 'please input name!';
    }
    if (empty($username)) {
        $usernameErr = 'please input username!';
    }
    if (empty($passwd)) {
        $passwdErr = 'please input password!';
    }
    if ($passwd !== $confirmPasswd) {
        $passwdErr = 'password does not match!';
    }
    if (usernameExists($username)) {
        $usernameErr = 'please choose another username !';
    }
    if (empty($nameErr) && empty($usernameErr) && empty($passwdErr)) {
        if (registerUser($name, $username, $passwd)) {
            $name = $username = '';
            echo '<div class="alert alert-success" role="alert">
            Registration successful! You can now 
            <a href="./?page=login" class="alert-link">login</a>.
            </div>';
        } else {
            echo '<div class="alert alert-danger" role="alert">
            Registration failed! Please try again.
            </div>';
        }
    }
}

?>
<style>
.loading-overlay {
    position: fixed;
    inset: 0;
    background: rgba(248,249,250,0.95);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

/* 🔹 ring small */
.loading-ring {
    position: relative;
    width: 70px;
    height: 70px;
}

.loading-ring svg {
    transform: rotate(-90deg);
}

/* 🔹 text */
.loading-text {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: 600;
    color: #0d6efd;
    letter-spacing: 0.5px;
}


.register-header {
    width: 100%;
    height: 70px;      
    border-radius: 10px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fff;
}

.register-header img {
    max-height: 60px;    
    width: auto;
    object-fit: contain;
}
</style>

<div class="loading-overlay" id="loading">
    <div class="loading-ring">
        <svg width="70" height="70">
            <circle cx="35" cy="35" r="28"
                stroke="#e3f0ff"
                stroke-width="6"
                fill="none" />
            <circle cx="35" cy="35" r="28"
                stroke="#0d6efd"
                stroke-width="6"
                fill="none"
                stroke-linecap="round"
                stroke-dasharray="175"
                stroke-dashoffset="120">
                <animateTransform
                    attributeName="transform"
                    type="rotate"
                    from="0 35 35"
                    to="360 35 35"
                    dur="2.50s"
                    repeatCount="indefinite"/>
            </circle>
        </svg>
        <div class="loading-text">loading</div>
    </div>
</div>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-5">

            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-4">

                    <!-- 🔹 REGISTER IMAGE HEADER -->
                    <div class="register-header mb-4">
                        <img src="assets/images/big-logo-Dqx3Weoi.png" alt="Register">
                    </div>

                    <form method="post" action="./?page=register" onsubmit="showLoading()">

                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input name="name"
                                   value="<?php echo $name ?>"
                                   type="text"
                                   class="form-control rounded-3
                                   <?php echo empty($nameErr) ? '' : 'is-invalid' ?>">
                            <div class="invalid-feedback">
                                <?php echo $nameErr ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input name="username"
                                   value="<?php echo $username ?>"
                                   type="text"
                                   class="form-control rounded-3
                                   <?php echo empty($usernameErr) ? '' : 'is-invalid' ?>">
                            <div class="invalid-feedback">
                                <?php echo $usernameErr ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input name="passwd"
                                   type="password"
                                   class="form-control rounded-3
                                   <?php echo empty($passwdErr) ? '' : 'is-invalid' ?>">
                            <div class="invalid-feedback">
                                <?php echo $passwdErr ?>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Confirm Password</label>
                            <input name="confirmPasswd"
                                   type="password"
                                   class="form-control rounded-3">
                        </div>

                        <div class="d-grid">
                            <button type="submit"
                                    class="btn btn-primary btn-lg rounded-3">
                                Register
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

