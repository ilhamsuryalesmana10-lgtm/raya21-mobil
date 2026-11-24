<?php
require 'config/database.php'; require 'includes/functions.php';
$page_title='Katalog Mobil'; $active='catalog';
$q=trim($_GET['q']??''); $kategori=(int)($_GET['kategori']??0);
$sql="SELECT m.*,k.nama AS kategori FROM mobil m LEFT JOIN kategori k ON k.id=m.kategori_id WHERE 1=1"; $params=[];
if($q!==''){ $sql.=" AND (m.nama LIKE ? OR m.merek LIKE ? OR m.tahun LIKE ?)"; $like="%$q%"; $params=[$like,$like,$like]; }
if($kategori>0){$sql.=" AND m.kategori_id=?";$params[]=$kategori;}
$sql.=" ORDER BY m.id DESC";$stmt=$pdo->prepare($sql);$stmt->execute($params);$cars=$stmt->fetchAll();$categories=$pdo->query('SELECT * FROM kategori ORDER BY nama')->fetchAll();
include 'includes/header.php'; ?>
<div class="page-banner"><div class="container"><h1>Katalog Mobil</h1><p>Pilih mobil berdasarkan kebutuhan dan anggaran Anda.</p></div></div>
<section class="section container"><form class="filter-form" method="get"><input name="q" placeholder="Cari nama atau merek mobil..." value="<?= e($q) ?>"><select name="kategori"><option value="0">Semua kategori</option><?php foreach($categories as $cat): ?><option value="<?= (int)$cat['id'] ?>" <?= $kategori===(int)$cat['id']?'selected':'' ?>><?= e($cat['nama']) ?></option><?php endforeach; ?></select><button class="btn" type="submit">Cari</button><a class="btn secondary" href="catalog.php">Reset</a></form>
<div class="grid"><?php foreach($cars as $car): ?><article class="car-card"><img src="<?= e(image_or_placeholder($car['foto'])) ?>" alt="<?= e($car['nama']) ?>" onerror="this.src='assets/img/car-placeholder.svg'"><div class="car-card-body"><h3><?= e($car['nama']) ?></h3><div class="meta"><?= e($car['tahun']) ?> · <?= e($car['transmisi']) ?> · <?= e($car['bahan_bakar']) ?></div><div class="price"><?= rupiah($car['harga']) ?></div><div class="card-actions"><span class="badge <?= $car['status']==='Terjual'?'sold':'' ?>"><?= e($car['status']) ?></span><a class="btn small" href="detail.php?id=<?= (int)$car['id'] ?>">Detail</a></div></div></article><?php endforeach; ?></div><?php if(!$cars): ?><div class="empty">Mobil tidak ditemukan. Coba kata pencarian lain.</div><?php endif; ?></section>
<?php include 'includes/footer.php'; ?>
