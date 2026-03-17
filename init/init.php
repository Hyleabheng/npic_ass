<?php
ob_start();
session_start();
//include database connection
require_once('init/db.init.php');
//include user functions
require_once('func/user.func.php');
//for manage user
require_once('func/manage/user.manage.php');
require_once('func/manage/category.manage.php');
require_once('func/manage/product.manage.php');
require_once('func/manage/stock.manage.php');
require_once('func/manage/cart.manage.php');
