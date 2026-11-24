RAYA 21 MOBIL - WEB KATALOG SEDERHANA
====================================
Stack: PHP native + MySQL/MariaDB + HTML/CSS
Fitur:
- Website publik: Beranda, katalog, filter cari/kategori, detail mobil, Tentang Kami
- Admin: login, dashboard ringkasan, CRUD mobil, tambah/hapus kategori
- Status kendaraan tersedia/terjual

CARA MENJALANKAN (XAMPP)
1. Install / buka XAMPP, start Apache dan MySQL.
2. Copy folder raya21 ke C:\xampp\htdocs\raya21 (atau htdocs folder kamu).
3. Buka http://localhost/phpmyadmin
4. Import file database.sql (tab Import). File ini membuat database raya21_mobil dan contoh data.
5. Jika MySQL kamu memakai password, edit config/database.php.
6. Buka http://localhost/raya21/setup_admin.php satu kali untuk membuat akun admin.
7. HAPUS setup_admin.php setelah akun dibuat.
8. Buka website: http://localhost/raya21
9. Login admin: http://localhost/raya21/admin/login.php
   Username: admin
   Password: raya21admin
   Ganti password sebelum dipakai di luar komputer lokal.

CARA MENGUBAH DATA
- Data mobil: login admin > Data Mobil > Tambah/Edit/Hapus.
- Kategori: login admin > Kategori.
- Foto: isi URL foto di kolom URL foto. Jika kosong, gambar placeholder ditampilkan.
- Isi contoh dan harga di database adalah data simulasi proyek, bukan penawaran nyata.

STRUKTUR FOLDER
raya21/
  index.php                 Beranda publik
  catalog.php               Katalog dan filter
  detail.php                Detail kendaraan
  about.php                 Tentang showroom
  database.sql               Database + data contoh
  setup_admin.php             Pembuat admin awal (hapus setelah digunakan)
  config/database.php         Koneksi PDO
  includes/functions.php      Helper, session, CSRF, autentikasi
  includes/header.php         Header publik
  includes/footer.php         Footer publik
  admin/login.php             Login admin
  admin/dashboard.php         Ringkasan admin
  admin/cars.php              CRUD mobil
  admin/categories.php         Kelola kategori
  admin/logout.php             Logout
  admin/_layout.php            Layout admin
  assets/css/style.css         Seluruh styling
  assets/img/car-placeholder.svg Gambar cadangan

BATASAN / KEKURANGAN VERSI PROYEK
- Tidak ada transaksi atau pembayaran online.
- Tidak ada booking / formulir kontak.
- Admin hanya satu role; belum ada manajemen akun admin.
- Foto dimasukkan sebagai URL, belum ada upload file.
- Tidak ada pagination katalog.
- Data contoh/harga perlu disesuaikan dan diverifikasi jika digunakan sebagai data showroom nyata.
- Belum dites untuk deployment hosting/produksi. Gunakan lokal untuk tugas dan ganti kredensial.
