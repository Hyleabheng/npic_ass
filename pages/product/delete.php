<?php
if (!isset($_GET['id']) || getProductByID($_GET['id']) === null) {
    header('Location: ./?page=product/home');
}
if (deleteProduct($_GET['id'])) {
    echo '<div class="alert alert-success" role="alert">
            Product deleted successfully. <a href="./?page=product/home">Product page</a>
            </div>';
} else {
    echo '<div class="alert alert-danger" role="alert">
        can not delete Product! <a href="./?page=product/home">Product page</a>
        </div>';
}
