<?php
if (!isset($_GET['id']) || getProductByID($_GET['id']) === null) {
    header('Location: ./');
}

addProductToCart($_GET['id']);

header('Location: ./');
?>