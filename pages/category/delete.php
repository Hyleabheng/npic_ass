<?php
if (!isset($_GET['id']) || getCategoryByID($_GET['id']) === null) {
    header('Location: ./?page=category/home');
    exit;
}
if (deleteCategory($_GET['id'])) {
    header('Location: ./?page=category/home');
    exit;
} else {
    echo '<div class="alert alert-danger" role="alert">
        Can not delete category!
        </div>';
}
