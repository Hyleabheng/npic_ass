<?php

$user_label_err = $username_err = $passwd_err = $confirm_passwd_err = '';
if (isset($_POST['user_label']) && isset($_POST['username']) && isset($_POST['passwd']) && isset($_POST['confirm_passwd'])) {
    $user_label = $_POST['user_label'];
    $username = $_POST['username'];
    $passwd = $_POST['passwd'];
    $confirm_passwd = $_POST['confirm_passwd'];

    if (empty($user_label)) {
        $user_label_err = 'User Label is required';
    }
    if (empty($username)) {
        $username_err = 'Username is required';
    } else {
        if (usernameExists($username)) {
            $username_err = 'Username already exists';
        }
    }
    if (empty($passwd)) {
        $passwd_err = 'Password is required';
    } else {
        if (empty($confirm_passwd)) {
            $confirm_passwd_err = 'Confirm Password is required';
        } else {
            if ($passwd !== $confirm_passwd) {
                $confirm_passwd_err = 'Password not match';
            }
        }
    }
    if (empty($user_label_err) && empty($username_err) && empty($passwd_err) && empty($confirm_passwd_err)) {
        if (createUser($user_label, $username, $passwd)) {
            header('Location: ./?page=user/home');
            exit;
        } else {
            echo '<div class="alert alert-danger" role="alert">
        User Added Failed
        </div>';
        }
    }
}


?>

<form action="./?page=user/create" method="post" class="container py-5" style="max-width:600px;">

    <div class="card shadow-lg border-0 rounded-4">

        <div class="card-header bg-gradient bg-primary text-white text-center rounded-top-4">
            <h4 class="mb-0">Create User</h4>
        </div>

        <div class="card-body p-4">
            <div class="mb-3">
                <label for="user_label" class="form-label fw-bold">User Label</label>
                <input id="user_label" type="text" name="user_label"
                    class="form-control <?php echo $user_label_err ? 'is-invalid' : '' ?>"
                    placeholder="Enter user label"
                    value="<?php echo $_POST['user_label'] ?? '' ?>">
                <div class="invalid-feedback"><?php echo $user_label_err ?></div>
            </div>

            <div class="mb-3">
                <label for="username" class="form-label fw-bold">Username</label>
                <input id="username" type="text" name="username"
                    class="form-control <?php echo $username_err ? 'is-invalid' : '' ?>"
                    placeholder="Enter username"
                    value="<?php echo $_POST['username'] ?? '' ?>">
                <div class="invalid-feedback"><?php echo $username_err ?></div>
            </div>

            <div class="mb-3">
                <label for="passwd" class="form-label fw-bold">Password</label>
                <input id="passwd" type="password" name="passwd"
                    class="form-control <?php echo $passwd_err ? 'is-invalid' : '' ?>"
                    placeholder="Enter password"
                    value="<?php echo $_POST['passwd'] ?? '' ?>">
                <div class="invalid-feedback"><?php echo $passwd_err ?></div>
            </div>

            <div class="mb-3">
                <label for="confirm_passwd" class="form-label fw-bold">Confirm Password</label>
                <input id="confirm_passwd" type="password" name="confirm_passwd"
                    class="form-control <?php echo $confirm_passwd_err ? 'is-invalid' : '' ?>"
                    placeholder="Confirm password"
                    value="<?php echo $_POST['confirm_passwd'] ?? '' ?>">
                <div class="invalid-feedback"><?php echo $confirm_passwd_err ?></div>
            </div>
        </div>

        <div class="card-footer bg-white d-flex justify-content-between">
            <a role="button" href="./?page=user/home" class="btn btn-outline-secondary">
                Cancel
            </a>
            <button type="submit" class="btn btn-primary">
                Create User
            </button>
        </div>

    </div>

</form>
