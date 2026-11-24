<?php

require '../config/database.php';
require '../includes/functions.php';

require_admin();
require '_layout.php';

// Menghitung total mobil
$total = (int) $pdo->query(
    'SELECT COUNT(*) FROM mobil'
)->fetchColumn();

// Menghitung mobil tersedia
$available = (int) $pdo->query(
    "SELECT COUNT(*) FROM mobil WHERE status = 'Tersedia'"
)->fetchColumn();

// Menghitung mobil terjual
$sold = (int) $pdo->query(
    "SELECT COUNT(*) FROM mobil WHERE status = 'Terjual'"
)->fetchColumn();

// Mengambil 5 data mobil terbaru
$latest = $pdo->query(
    'SELECT m.*, k.nama kategori
     FROM mobil m
     LEFT JOIN kategori k ON k.id = m.kategori_id
     ORDER BY m.id DESC
     LIMIT 5'
)->fetchAll();

// Menampilkan header dashboard admin
admin_header('Dashboard', 'dashboard');

?>

<div class="stats">

    <div class="stat">
        <small>Total mobil</small>
        <strong><?= $total ?></strong>
    </div>

    <div class="stat">
        <small>Mobil tersedia</small>
        <strong><?= $available ?></strong>
    </div>

    <div class="stat">
        <small>Mobil terjual</small>
        <strong><?= $sold ?></strong>
    </div>

</div>

<div class="panel">

    <h2>Mobil Terbaru</h2>

    <div class="table-wrap">

        <table>

            <thead>
                <tr>
                    <th>Nama Mobil</th>
                    <th>Tahun</th>
                    <th>Harga</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($latest as $c): ?>

                    <tr>
                        <td><?= e($c['nama']) ?></td>
                        <td><?= e($c['tahun']) ?></td>
                        <td><?= rupiah($c['harga']) ?></td>
                        <td><?= e($c['status']) ?></td>
                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    </div>

    <p>
        <a class="btn" href="cars.php">
            Kelola Data Mobil
        </a>
    </p>

</div>

<?php

admin_footer();

?>