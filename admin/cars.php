<?php

require '../config/database.php';
require '../includes/functions.php';

require_admin();
require '_layout.php';

$edit = null;
$errors = [];

// Mengambil data mobil berdasarkan ID untuk proses edit
if (isset($_GET['edit'])) {

    $st = $pdo->prepare(
        'SELECT * FROM mobil WHERE id = ?'
    );

    $st->execute([(int) $_GET['edit']]);

    $edit = $st->fetch();
}

// Proses form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    verify_csrf();

    $action = $_POST['action'] ?? '';

    // Proses hapus mobil
    if ($action === 'delete') {

        $st = $pdo->prepare(
            'DELETE FROM mobil WHERE id = ?'
        );

        $st->execute([(int) $_POST['id']]);

        header('Location: cars.php?msg=deleted');
        exit;
    }

    // Mengambil data dari form
    $data = [
        'nama' => trim($_POST['nama'] ?? ''),
        'merek' => trim($_POST['merek'] ?? ''),
        'tahun' => (int) ($_POST['tahun'] ?? 0),
        'kategori_id' => (int) ($_POST['kategori_id'] ?? 0),
        'harga' => (float) ($_POST['harga'] ?? 0),
        'transmisi' => trim($_POST['transmisi'] ?? ''),
        'bahan_bakar' => trim($_POST['bahan_bakar'] ?? ''),
        'status' => $_POST['status'] ?? 'Tersedia',
        'foto' => trim($_POST['foto'] ?? ''),
        'deskripsi' => trim($_POST['deskripsi'] ?? '')
    ];

    // Validasi data mobil
    if (
        $data['nama'] === '' ||
        $data['merek'] === '' ||
        $data['tahun'] < 1980 ||
        $data['tahun'] > date('Y') + 1 ||
        $data['harga'] <= 0 ||
        $data['transmisi'] === '' ||
        $data['bahan_bakar'] === ''
    ) {

        $errors[] = 'Lengkapi data. Tahun atau harga tidak valid.';
    }

    // Validasi status mobil
    if (!in_array($data['status'], ['Tersedia', 'Terjual'], true)) {

        $errors[] = 'Status tidak valid.';
    }

    // Jika tidak ada kesalahan validasi
    if (!$errors) {

        // Proses tambah mobil
        if ($action === 'save' && empty($_POST['id'])) {

            $st = $pdo->prepare(
                'INSERT INTO mobil
                (nama, merek, tahun, kategori_id, harga,
                transmisi, bahan_bakar, status, foto, deskripsi)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
            );

            $st->execute([
                $data['nama'],
                $data['merek'],
                $data['tahun'],
                $data['kategori_id'] ?: null,
                $data['harga'],
                $data['transmisi'],
                $data['bahan_bakar'],
                $data['status'],
                $data['foto'],
                $data['deskripsi']
            ]);

        } else {

            // Proses edit mobil
            $st = $pdo->prepare(
                'UPDATE mobil SET
                nama = ?,
                merek = ?,
                tahun = ?,
                kategori_id = ?,
                harga = ?,
                transmisi = ?,
                bahan_bakar = ?,
                status = ?,
                foto = ?,
                deskripsi = ?
                WHERE id = ?'
            );

            $st->execute([
                $data['nama'],
                $data['merek'],
                $data['tahun'],
                $data['kategori_id'] ?: null,
                $data['harga'],
                $data['transmisi'],
                $data['bahan_bakar'],
                $data['status'],
                $data['foto'],
                $data['deskripsi'],
                (int) $_POST['id']
            ]);
        }

        header('Location: cars.php?msg=saved');
        exit;
    }

    // Mempertahankan data form jika validasi gagal
    $edit = $data;
    $edit['id'] = (int) ($_POST['id'] ?? 0);
}

// Mengambil data kategori
$categories = $pdo->query(
    'SELECT * FROM kategori ORDER BY nama'
)->fetchAll();

// Mengambil seluruh data mobil
$cars = $pdo->query(
    'SELECT m.*, k.nama kategori
     FROM mobil m
     LEFT JOIN kategori k ON k.id = m.kategori_id
     ORDER BY m.id DESC'
)->fetchAll();

// Menampilkan header admin
admin_header('Data Mobil', 'cars');

if (isset($_GET['msg'])) {
    echo '<div class="notice">Data berhasil diproses.</div>';
}

foreach ($errors as $error) {
    echo '<div class="notice">' . e($error) . '</div>';
}

?>

<!-- Form Tambah dan Edit Mobil -->

<div class="panel">

    <h2>
        <?= $edit ? 'Edit Mobil' : 'Tambah Mobil' ?>
    </h2>

    <form method="post">

        <input
            type="hidden"
            name="csrf"
            value="<?= e(csrf_token()) ?>"
        >

        <input
            type="hidden"
            name="action"
            value="save"
        >

        <input
            type="hidden"
            name="id"
            value="<?= (int) ($edit['id'] ?? 0) ?>"
        >

        <div class="form-grid">

            <!-- Nama Mobil -->
            <div class="field">

                <label>Nama mobil</label>

                <input
                    class="form-control"
                    name="nama"
                    required
                    value="<?= e($edit['nama'] ?? '') ?>"
                    placeholder="Toyota Avanza G"
                >

            </div>

            <!-- Merek -->
            <div class="field">

                <label>Merek</label>

                <input
                    class="form-control"
                    name="merek"
                    required
                    value="<?= e($edit['merek'] ?? '') ?>"
                >

            </div>

            <!-- Tahun -->
            <div class="field">

                <label>Tahun</label>

                <input
                    class="form-control"
                    type="number"
                    name="tahun"
                    min="1980"
                    max="<?= date('Y') + 1 ?>"
                    required
                    value="<?= e($edit['tahun'] ?? '2015') ?>"
                >

            </div>

            <!-- Kategori -->
            <div class="field">

                <label>Kategori</label>

                <select
                    class="form-control"
                    name="kategori_id"
                >

                    <option value="">
                        -- Pilih kategori --
                    </option>

                    <?php foreach ($categories as $cat): ?>

                        <option
                            value="<?= (int) $cat['id'] ?>"
                            <?= (int) ($edit['kategori_id'] ?? 0) === (int) $cat['id'] ? 'selected' : '' ?>
                        >
                            <?= e($cat['nama']) ?>
                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

            <!-- Harga -->
            <div class="field">

                <label>Harga (rupiah)</label>

                <input
                    class="form-control"
                    type="number"
                    name="harga"
                    min="1"
                    required
                    value="<?= e($edit['harga'] ?? '') ?>"
                >

            </div>

            <!-- Transmisi -->
            <div class="field">

                <label>Transmisi</label>

                <select
                    class="form-control"
                    name="transmisi"
                >

                    <option
                        <?= ($edit['transmisi'] ?? '') === 'Manual' ? 'selected' : '' ?>
                    >
                        Manual
                    </option>

                    <option
                        <?= ($edit['transmisi'] ?? '') === 'Automatic' ? 'selected' : '' ?>
                    >
                        Automatic
                    </option>

                </select>

            </div>

            <!-- Bahan Bakar -->
            <div class="field">

                <label>Bahan bakar</label>

                <select
                    class="form-control"
                    name="bahan_bakar"
                >

                    <option
                        <?= ($edit['bahan_bakar'] ?? '') === 'Bensin' ? 'selected' : '' ?>
                    >
                        Bensin
                    </option>

                    <option
                        <?= ($edit['bahan_bakar'] ?? '') === 'Diesel' ? 'selected' : '' ?>
                    >
                        Diesel
                    </option>

                    <option
                        <?= ($edit['bahan_bakar'] ?? '') === 'Hybrid' ? 'selected' : '' ?>
                    >
                        Hybrid
                    </option>

                </select>

            </div>

            <!-- Status -->
            <div class="field">

                <label>Status</label>

                <select
                    class="form-control"
                    name="status"
                >

                    <option
                        <?= ($edit['status'] ?? 'Tersedia') === 'Tersedia' ? 'selected' : '' ?>
                    >
                        Tersedia
                    </option>

                    <option
                        <?= ($edit['status'] ?? '') === 'Terjual' ? 'selected' : '' ?>
                    >
                        Terjual
                    </option>

                </select>

            </div>

            <!-- Foto -->
            <div class="field full">

                <label>URL foto (opsional)</label>

                <input
                    class="form-control"
                    name="foto"
                    value="<?= e($edit['foto'] ?? '') ?>"
                    placeholder="https://... atau kosong untuk placeholder"
                >

            </div>

            <!-- Deskripsi -->
            <div class="field full">

                <label>Deskripsi</label>

                <textarea
                    class="form-control"
                    name="deskripsi"
                    rows="3"
                    style="width:100%"
                    placeholder="Deskripsi singkat mobil..."
                ><?= e($edit['deskripsi'] ?? '') ?></textarea>

            </div>

        </div>

        <!-- Tombol Simpan -->
        <p>

            <button class="btn" type="submit">
                Simpan
            </button>

            <?php if ($edit): ?>

                <a
                    class="btn secondary"
                    href="cars.php"
                >
                    Batal edit
                </a>

            <?php endif; ?>

        </p>

    </form>

</div>

<!-- Daftar Mobil -->

<div class="panel">

    <h2>Daftar Mobil</h2>

    <div class="table-wrap">

        <table>

            <thead>

                <tr>
                    <th>Mobil</th>
                    <th>Tahun</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>

            </thead>

            <tbody>

                <?php foreach ($cars as $car): ?>

                    <tr>

                        <td>
                            <?= e($car['nama']) ?>
                        </td>

                        <td>
                            <?= e($car['tahun']) ?>
                        </td>

                        <td>
                            <?= e($car['kategori'] ?? '-') ?>
                        </td>

                        <td>
                            <?= rupiah($car['harga']) ?>
                        </td>

                        <td>
                            <?= e($car['status']) ?>
                        </td>

                        <td>

                            <div class="actions">

                                <!-- Tombol Edit -->
                                <a
                                    class="btn small"
                                    href="cars.php?edit=<?= (int) $car['id'] ?>"
                                >
                                    Edit
                                </a>

                                <!-- Tombol Hapus -->
                                <form
                                    method="post"
                                    onsubmit="return confirm('Hapus data mobil ini?')"
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
                                        value="<?= (int) $car['id'] ?>"
                                    >

                                    <button
                                        class="btn small danger"
                                        type="submit"
                                    >
                                        Hapus
                                    </button>

                                </form>

                            </div>

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