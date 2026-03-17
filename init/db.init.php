<?php
$host = '127.0.0.1:3307'; //you can use localhost
$dbname = 'npic_pos';
$user = 'root';
$password = '';

//mysql connect
$db = new mysqli($host, $user, $password, $dbname);

if ($db->connect_error) {
    echo 'Connection failed.' . $db->connect_error;
    die();
}
