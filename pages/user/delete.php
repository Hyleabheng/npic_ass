<?php
if (!isset($_GET['id']) || getUserByID($_GET['id']) === null) {
    header('Location: ./?page=user/home');
}
if (deleteUser($_GET['id'])) {
    echo '<div class="alert alert-success" role="alert">
            User deleted successfully. <a href="./?page=user/home">User page</a>
            </div>';
} else {
    echo '<div class="alert alert-danger" role="alert">
        can not delete user! <a href="./?page=user/home">User page</a>
        </div>';
}
