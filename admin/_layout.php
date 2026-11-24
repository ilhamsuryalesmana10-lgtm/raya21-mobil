<?php

// Fungsi untuk menampilkan header dan layout admin
function admin_header($title, $active = '')
{
?>

<!doctype html>
<html lang="id">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= e($title) ?> - Admin Raya 21</title>

    <link
        rel="stylesheet"
        href="../assets/css/style.css"
    >

</head>

<body class="admin-body">

    <div class="admin-layout">

        <!-- Sidebar -->
        <aside class="sidebar">

            <a
                class="brand"
                href="dashboard.php"
            >

                <span class="brand-mark">
                    R21
                </span>

                <span>
                    RAYA <b>21 MOBIL</b>
                    <small>ADMIN PANEL</small>
                </span>

            </a>

            <!-- Menu Navigasi Admin -->
            <nav>

                <a
                    class="<?= $active === 'dashboard' ? 'active' : '' ?>"
                    href="dashboard.php"
                >
                    Dashboard
                </a>

                <a
                    class="<?= $active === 'cars' ? 'active' : '' ?>"
                    href="cars.php"
                >
                    Data Mobil
                </a>

                <a
                    class="<?= $active === 'categories' ? 'active' : '' ?>"
                    href="categories.php"
                >
                    Kategori
                </a>

                <a href="logout.php">
                    Logout
                </a>

                <a
                    href="../index.php"
                    target="_blank"
                >
                    Lihat Website ↗
                </a>

            </nav>

        </aside>

        <!-- Konten Utama Admin -->
        <main class="admin-main">

            <div class="admin-top">

                <h1>
                    <?= e($title) ?>
                </h1>

                <span>
                    Halo, Admin
                </span>

            </div>

<?php
}

// Fungsi untuk menutup layout admin
function admin_footer()
{
?>

        </main>

    </div>

</body>

</html>

<?php
}
?>