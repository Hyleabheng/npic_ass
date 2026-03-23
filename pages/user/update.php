<?php
if (!isset($_GET['id']) || getUserByID($_GET['id']) === null) {
    header('Location: ./?page=user/home');
    exit;
}

$manage_user = getUserByID($_GET['id']);
$user_label_err = $username_err = '';
if (isset($_POST['user_label']) && isset($_POST['username']) && isset($_POST['passwd'])) {
    $id_user = $_GET['id'];
    $user_label = $_POST['user_label'];
    $username = $_POST['username'];
    $passwd = $_POST['passwd'];

    if (empty($user_label)) {
        $user_label_err = 'User Label is required';
    }

    if (!empty($username) && usernameExists($username)) {
        $username_err = 'Username already exists';
    }

    if (empty($user_label_err) && empty($username_err)) {
        if (updateUser($id_user, $user_label, $username, $passwd)) {
            header('Location: ./?page=user/home');
            exit;
        } else {
            echo '<div class="alert alert-danger" role="alert">
                    Can not update user!
                    </div>';
        }
    }
}
?>

<form action="./?page=user/update&id=<?php echo $manage_user->id_user ?>" method="post" class="container py-5"
    style="max-width:650px;">

    <div class="card shadow-lg border-0 rounded-4">

        <div class="card-header bg-gradient bg-primary text-white text-center rounded-top-4">
            <h4 class="mb-0">Update User (ID: <?php echo $manage_user->id_user ?>)</h4>
        </div>

        <div class="card-body p-4">
            <div class="mb-3">
                <label for="user_label" class="form-label fw-bold">User Label</label>
                <input id="user_label" type="text" name="user_label"
                    class="form-control <?php echo $user_label_err ? 'is-invalid' : '' ?>"
                    placeholder="Enter user label"
                    value="<?php echo $_POST['user_label'] ?? $manage_user->user_label ?>">
                <div class="invalid-feedback"><?php echo $user_label_err ?></div>
            </div>

            <div class="mb-3">
                <label for="username" class="form-label fw-bold">Username (optional)</label>
                <input id="username" type="text" name="username"
                    class="form-control <?php echo $username_err ? 'is-invalid' : '' ?>"
                    placeholder="Leave blank to keep current username"
                    value="<?php echo $_POST['username'] ?? '' ?>">
                <div class="invalid-feedback"><?php echo $username_err ?></div>
            </div>

            <div class="mb-3">
                <label for="passwd" class="form-label fw-bold">New Password (optional)</label>
                <input id="passwd" type="password" name="passwd" class="form-control"
                    placeholder="Leave blank to keep current password"
                    value="<?php echo $_POST['passwd'] ?? '' ?>">
            </div>
        </div>

        <div class="card-footer bg-white d-flex justify-content-between">
            <a role="button" href="./?page=user/home" class="btn btn-outline-secondary">
                Cancel
            </a>
            <button type="submit" class="btn btn-primary">
                Update User
            </button>
        </div>

    </div>

</form>
