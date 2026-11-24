<?php

require '../config/database.php';
require '../includes/functions.php';

require_admin();
require '_layout.php';

$error = '';

// Proses form kategori
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    verify_csrf();

    // Proses hapus kategori
    if ($_POST['action'] === 'delete') {

        $st = $pdo->prepare(
            'DELETE FROM kategori WHERE id = ?'
        );

        $st->execute([(int) $_POST['id']]);

        header('Location: categories.php?ok=1');
        exit;

    } else {

        // Proses tambah kategori
        $nama = trim($_POST['nama'] ?? '');

        if ($nama === '') {

            $error = 'Nama kategori harus diisi.';

        } else {

            $st = $pdo->prepare(
                'INSERT INTO kategori(nama) VALUES(?)'
            );

            $st->execute([$nama]);

            header('Location: categories.php?ok=1');
            exit;
        }
    }
}

// Mengambil data kategori dan jumlah mobil
$cats = $pdo->query(
    'SELECT k.*, COUNT(m.id) jumlah
     FROM kategori k
     LEFT JOIN mobil m ON m.kategori_id = k.id
     GROUP BY k.id
     ORDER BY k.nama'
)->fetchAll();

// Menampilkan header admin
admin_header('Kategori', 'categories');

if ($error) {
    echo '<div class="notice">' . e($error) . '</div>';
}

if (isset($_GET['ok'])) {
    echo '<div class="notice">Data kategori berhasil diproses.</div>';
}

?>

<!-- Form Tambah Kategori -->

<div class="panel">

    <h2>Tambah Kategori</h2>

    <form method="post" class="filter-form">

        <input
            type="hidden"
            name="csrf"
            value="<?= e(csrf_token()) ?>"
        >

        <input
            type="hidden"
            name="action"
            value="add"
        >

        <input
            name="nama"
            placeholder="Contoh: MPV, SUV, Hatchback"
            required
        >

        <button class="btn">
            Tambah
        </button>

    </form>

</div>

<!-- Daftar Kategori -->

<div class="panel">

    <h2>Daftar Kategori</h2>

    <div class="table-wrap">

        <table>

            <thead>
                <tr>
                    <th>Nama Kategori</th>
                    <th>Jumlah mobil</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($cats as $cat): ?>

                    <tr>

                        <td>
                            <?= e($cat['nama']) ?>
                        </td>

                        <td>
                            <?= (int) $cat['jumlah'] ?>
                        </td>

                        <td>

                            <form
                                method="post"
                                onsubmit="return confirm('Hapus kategori ini? Mobil terkait akan menjadi tanpa kategori.')"
                            >

                                <input
                                    type="hidden"
                                    name="csrf"
                                    value="<?= e(csrf_token()) ?>"
                                >

                                <input
                                    type="hidden"
                                    name="action"
                                    value="delete"
                                >

                                <input
                                    type="hidden"
                                    name="id"
                                    value="<?= (int) $cat['id'] ?>"
                                >

                                <button class="btn small danger">
                                    Hapus
                                </button>

                            </form>

                        </td>

                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    </div>

</div>

<?php

admin_footer();

?>