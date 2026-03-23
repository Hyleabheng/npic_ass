<?php

require_once('init/init.php');

$page = isset($_GET['page']) ? $_GET['page'] : 'home';
define('CURRENT_PAGE', $page);

include('includes/header.inc.php');
include('includes/navbar.inc.php');
if ($page !== 'home') {
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
            exit;
        }
        if (in_array($page, $user_pages) && !isUser()) {
            header("Location: ./");
            exit;
        }
        include('pages/' . $page . '.php');
    } else if (in_array($page, $after_logIn_pages) && !LoggedInUser()) {
        header("Location: ./?page=login");
        exit;
    } else {
        header("Location: ./");
        exit;
    }
} else {
    include('pages/home.php');
}
include('includes/footer.inc.php');


$db->close();
