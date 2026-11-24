<?php

require '../config/database.php';
require '../includes/functions.php';

if (admin_logged_in()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare('SELECT * FROM admin WHERE username = ?');
    $stmt->execute([$username]);

    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password'])) {
        session_regenerate_id(true);

        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['nama'];

        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Username atau password salah.';
    }
}

?>

<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login Admin - Raya 21 Mobil</title>

    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body class="admin-body">

    <div class="login-wrap">

        <div class="login-card">

            <div class="brand">
                <span class="brand-mark">R21</span>

                <span>
                    RAYA <b>21 MOBIL</b>
                    <small>ADMIN PANEL</small>
                </span>
            </div>

            <h1 style="margin-top:22px">Login Admin</h1>

            <p class="meta">
                Masuk untuk mengelola katalog mobil.
            </p>

            <?php if ($error): ?>
                <div class="notice">
                    <?= e($error) ?>
                </div>
            <?php endif; ?>

            <form method="post">

                <input
                    type="hidden"
                    name="csrf"
                    value="<?= e(csrf_token()) ?>"
                >

                <div class="field">
                    <label>Username</label>

                    <input
                        class="form-control"
                        name="username"
                        required
                        autocomplete="username"
                    >
                </div>

                <div class="field">
                    <label>Password</label>

                    <input
                        class="form-control"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                    >
                </div>

                <button class="btn" type="submit">
                    Masuk
                </button>

            </form>

            <a class="back" href="../index.php">
                ← Kembali ke website
            </a>

        </div>

    </div>

</body>

</html>