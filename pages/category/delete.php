<?php
if (!isset($_GET['id']) || getCategoryByID($_GET['id']) === null) {
    header('Location: ./?page=category/home');
}
if (deleteCategory($_GET['id'])) {
    echo '<div class="alert alert-success" role="alert">
            Category deleted successfully. <a href="./?page=category/home">Category page</a>
            </div>';
} else {
    echo '<div class="alert alert-danger" role="alert">
        can not delete category! <a href="./?page=category/home">Category page</a>
        </div>';
}
