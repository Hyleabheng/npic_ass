<?php
$oldPasswd = $newPasswd = $confirmNewPasswd = '';
$oldPasswdErr = $newPasswdErr = '';

if (isset($_POST['changePasswd'], $_POST['oldPasswd'], $_POST['newPasswd'], $_POST['confirmNewPasswd'])) {
    $oldPasswd = trim($_POST['oldPasswd']);
    $newPasswd = trim($_POST['newPasswd']);
    $confirmNewPasswd = trim($_POST['confirmNewPasswd']);
    if (empty($oldPasswd)) {
        $oldPasswdErr = 'please input your old password';
    }
    if (empty($newPasswd)) {
        $newPasswdErr = 'please input your new password';
    }
    if ($newPasswd !== $confirmNewPasswd) {
        $newPasswdErr = 'password does not match';
    } else {
        if (!isUserHasPassword($oldPasswd)) {
            $oldPasswdErr = 'password is incorrect';
        }
    }
    if (empty($oldPasswdErr) && empty($newPasswdErr)) {
        if (setUserNewPassowrd($newPasswd)) {
            unset($_SESSION['user_id']);
            echo '<div class="alert alert-success" role="alert">
                Password changed successfully. Please login again.
                </div>';
        } else {
            echo '<div class="alert alert-danger" role="alert">
                try aggain.
                </div>';
        }
    }

    if (isset($_POST['uploadPhoto']) && isset($_FILES['photo'])) {
        $file = $_FILES['photo'];
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $newName = 'uploads/profile/profile_' . time() . '.' . $ext;

        move_uploaded_file($file['tmp_name'], $newName);

        $query = $db->prepare("UPDATE tbl_users SET photo=? WHERE id=?");
        $query->bind_param('si', $newName, $user->id);
        $query->execute();
    }
}


?>
<div class="container mt-4">
    <div class="row g-4">

        <!-- Profile Photo -->
        <div class="col-md-6">
            <div class="card shadow border-0 rounded-4 h-100">
                <div class="card-body text-center">

                    <h5 class="fw-bold mb-4">Profile Photo</h5>

                    <form method="post" action="./?page=profile" enctype="multipart/form-data">

                        <input name="photo" type="file" id="profileUpload" hidden accept="image/*"
                            onchange="previewImage(this)">

                        <label for="profileUpload" role="button">
                            <img id="preview" src="./assets/images/emptyuser.png" class="rounded-circle shadow mb-3"
                                style="width:160px;height:160px;object-fit:cover;">
                        </label>

                        

                        <div class="d-flex justify-content-center gap-3 mt-3">
                            <button type="submit" name="uploadPhoto" onclick="return confirmUpload()"
                                class="btn btn-success rounded-3 px-4">
                                Upload
                            </button>

                            <button type="submit" name="deletePhoto" onclick="return confirmDelete()"
                                class="btn btn-danger rounded-3 px-4">
                                Delete
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
        <!-- Change Password -->
        <div class="col-md-6">
            <div class="card shadow border-0 rounded-4 h-100">
                <div class="card-body">

                    <h5 class="fw-bold text-center mb-4">Change Password</h5>

                    <form method="post" action="./?page=profile">

                        <div class="mb-3">
                            <label class="form-label">Old Password</label>
                            <input name="oldPasswd" value="<?php echo $oldPasswd ?>" type="password" class="form-control rounded-3
                                   <?php echo empty($oldPasswdErr) ? '' : 'is-invalid' ?>">
                            <div class="invalid-feedback">
                                <?php echo $oldPasswdErr ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input name="newPasswd" type="password" class="form-control rounded-3
                                   <?php echo empty($newPasswdErr) ? '' : 'is-invalid' ?>">
                            <div class="invalid-feedback">
                                <?php echo $newPasswdErr ?>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Confirm New Password</label>
                            <input name="confirmNewPasswd" type="password" class="form-control rounded-3">
                        </div>
                        <div class="d-grid">
                            <button type="submit" name="changePasswd" class="btn btn-primary btn-lg rounded-3">
                                Change Password
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>

    </div>
</div>
<script>

    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('preview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    function confirmUpload() {
        return confirm("Are you sure you want to upload this photo?");
    }
    function confirmDelete() {
        return confirm("Are you sure you want to delete your profile photo?");
    }
</script>