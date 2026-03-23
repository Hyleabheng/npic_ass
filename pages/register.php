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
            header('Location: ./?page=login&registered=1');
            exit;
        } else {
            echo '<div class="alert alert-danger" role="alert">
            Registration failed! Please try again.
            </div>';
        }
    }
}

?>
<div class="container py-5">
    <div class="auth-page">
        <div class="card auth-card shadow-lg border-0 rounded-4">
            <div class="card-body">

                <div class="text-center mb-4">
                    <div class="auth-brand mb-2">
                        <img src="assets/images/ZANDO-NEW-LOGO-2025.png" alt="Register">
                    </div>
                    <div class="fw-bold">Create account</div>
                    <div class="text-muted small">Register to continue</div>
                </div>

                <form method="post" action="./?page=register">

                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input name="name"
                                   value="<?php echo $name ?>"
                                   type="text"
                                   class="form-control
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
                                   class="form-control
                                   <?php echo empty($usernameErr) ? '' : 'is-invalid' ?>">
                            <div class="invalid-feedback">
                                <?php echo $usernameErr ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input name="passwd"
                                   type="password"
                                   class="form-control
                                   <?php echo empty($passwdErr) ? '' : 'is-invalid' ?>">
                            <div class="invalid-feedback">
                                <?php echo $passwdErr ?>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Confirm Password</label>
                            <input name="confirmPasswd"
                                   type="password"
                                   class="form-control">
                        </div>

                        <div class="d-grid">
                            <button type="submit"
                                    class="btn btn-primary btn-auth">
                                Register
                            </button>
                        </div>

                        <div class="text-center mt-3">
                            <span class="text-muted small">Already have an account?</span>
                            <a class="small fw-semibold" href="./?page=login">Back to Login</a>
                        </div>

                    </form>

            </div>
        </div>
    </div>
</div>

