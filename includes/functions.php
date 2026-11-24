<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
function e($value): string { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function rupiah($value): string { return 'Rp' . number_format((float)$value, 0, ',', '.'); }
function admin_logged_in(): bool { return !empty($_SESSION['admin_id']); }
function require_admin(): void {
    if (!admin_logged_in()) { header('Location: login.php'); exit; }
}
function csrf_token(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function verify_csrf(): void {
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
        http_response_code(403); exit('Permintaan tidak valid. Muat ulang halaman lalu coba lagi.');
    }
}
function image_or_placeholder($url): string {
    $url = trim((string)$url);
    return $url !== '' ? $url : 'assets/img/car-placeholder.svg';
}
