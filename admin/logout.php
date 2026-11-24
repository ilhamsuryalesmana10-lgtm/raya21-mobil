<?php

require '../includes/functions.php';

// Menghapus seluruh data session
$_SESSION = [];

// Memeriksa apakah session menggunakan cookie
if (ini_get('session.use_cookies')) {

    // Mengambil konfigurasi cookie session
    $p = session_get_cookie_params();

    // Menghapus cookie session
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $p['path'],
        $p['domain'],
        $p['secure'],
        $p['httponly']
    );
}

// Menghancurkan session
session_destroy();

// Mengarahkan kembali ke halaman login
header('Location: login.php');
exit;

?>