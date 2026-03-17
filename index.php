<?php

require_once('init/init.php');

include('includes/header.inc.php');
include('includes/navbar.inc.php');
if (isset($_GET['page'])) {
    $page = $_GET['page']; // about

    $admin_pages = [
        'user/home',
        'user/create',
        'user/update',
        'user/delete',
        'category/home',
        'category/create',
        'category/update',
        'category/delete',
        'product/home',
        'product/create',
        'product/update',
        'product/delete',
        'stock/home',
        'stock/create',
        'stock/update',
        'stock/delete',
    ];
    $user_pages = [
        'cart/home',
        'cart/create'
    ];

    $before_logIn_pages = ['login', 'register'];
    $after_logIn_pages = [
        'dashboard',
        ...$admin_pages,
        ...$user_pages // flat copy
    ];

    // var_dump($after_logIn_pages);

    if (
        $page === 'logout'  ||
        (in_array($page, $before_logIn_pages) && !LoggedInUser()) ||
        (in_array($page, $after_logIn_pages) && LoggedInUser())
    ) {
        if (in_array($page, $admin_pages) && !isAdmin()) {
            header("Location: ./");
        }
        if (in_array($page, $user_pages) && !isUser()) {
            header("Location: ./");
        }
        include('pages/' . $page . '.php');
    } else if (in_array($page, $after_logIn_pages) && !LoggedInUser()) {
        header("Location: ./?page=login");
    } else {
        header("Location: ./");
    }
} else {
    include('pages/home.php');
}
include('includes/footer.inc.php');


$db->close();
