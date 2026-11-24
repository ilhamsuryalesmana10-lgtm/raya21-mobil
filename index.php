<?php
require 'config/database.php'; require 'includes/functions.php';
$page_title='Beranda'; $active='home';
$stmt=$pdo->query("SELECT m.*,k.nama AS kategori FROM mobil m LEFT JOIN kategori k ON k.id=m.kategori_id WHERE m.status='Tersedia' ORDER BY m.id DESC LIMIT 6"); $cars=$stmt->fetchAll();
include 'includes/header.php';
?>
<section class="hero"><div class="container hero-grid"><div><span class="eyebrow">Showroom mobil bekas</span><h1>Temukan mobil yang cocok untuk kebutuhan Anda.</h1><p>Raya 21 Mobil menyediakan pilihan mobil bekas dengan informasi yang jelas. Lihat katalog dan pilih kendaraan yang sesuai.</p><a class="btn gold" href="catalog.php">Lihat Katalog Mobil</a></div><img class="hero-image" src="assets/img/car-placeholder.svg" alt="Ilustrasi mobil"></div></section>
<section class="section container"><div class="section-head"><div><h2>Mobil Pilihan</h2><p>Beberapa pilihan yang tersedia di katalog kami.</p></div><a href="catalog.php">Lihat semua →</a></div><div class="grid">
<?php foreach($cars as $car): ?><article class="car-card"><img src="<?= e(image_or_placeholder($car['foto'])) ?>" alt="<?= e($car['nama']) ?>" onerror="this.src='assets/img/car-placeholder.svg'"><div class="car-card-body"><h3><?= e($car['nama']) ?></h3><div class="meta"><?= e($car['tahun']) ?> · <?= e($car['transmisi']) ?> · <?= e($car['bahan_bakar']) ?></div><div class="price"><?= rupiah($car['harga']) ?></div><div class="card-actions"><span class="badge"><?= e($car['status']) ?></span><a class="btn small" href="detail.php?id=<?= (int)$car['id'] ?>">Detail</a></div></div></article><?php endforeach; ?>
</div><?php if(!$cars): ?><div class="empty">Belum ada data mobil. Admin dapat menambahkan mobil melalui dashboard.</div><?php endif; ?></section>
<?php include 'includes/footer.php'; ?>
