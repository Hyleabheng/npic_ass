<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function redirectTo(string $url): void
{
    if (!headers_sent()) {
        header('Location: ' . $url);
        exit();
    }

    echo '<script>window.location.href=' . json_encode($url) . ';</script>';
    echo '<noscript><meta http-equiv="refresh" content="0;url=' . htmlspecialchars($url, ENT_QUOTES) . '"></noscript>';
    exit();
}

// Logout should work immediately when visiting ?page=logout
$_SESSION = [];

if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}

session_unset();
session_destroy();

redirectTo('./?page=login');
?>