<?php
if (!isset($_GET['id']) || getStockByID($_GET['id']) === null) {
    header('Location: ./?page=stock/home');
    exit;
}
if (deleteStock($_GET['id'])) {
    header('Location: ./?page=stock/home');
    exit;
} else {
    echo '<div class="alert alert-danger" role="alert">
        Can not delete stock!
        </div>';
}
