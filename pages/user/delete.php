<?php
if (!isset($_GET['id']) || getUserByID($_GET['id']) === null) {
    header('Location: ./?page=user/home');
    exit;
}
if (deleteUser($_GET['id'])) {
    header('Location: ./?page=user/home');
    exit;
} else {
    echo '<div class="alert alert-danger" role="alert">
        Can not delete user!
        </div>';
}
