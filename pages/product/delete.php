<?php
if (!isset($_GET['id']) || getProductByID($_GET['id']) === null) {
    header('Location: ./?page=product/home');
    exit;
}
if (deleteProduct($_GET['id'])) {
    header('Location: ./?page=product/home');
    exit;
} else {
    echo '<div class="alert alert-danger" role="alert">
        Can not delete product!
        </div>';
}
