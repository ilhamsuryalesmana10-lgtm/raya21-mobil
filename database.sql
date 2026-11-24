CREATE DATABASE IF NOT EXISTS raya21_mobil CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE raya21_mobil;

CREATE TABLE IF NOT EXISTS admin (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  dibuat TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS kategori (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(80) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS mobil (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(120) NOT NULL,
  merek VARCHAR(60) NOT NULL,
  tahun SMALLINT NOT NULL,
  kategori_id INT NULL,
  harga DECIMAL(15,2) NOT NULL,
  transmisi VARCHAR(30) NOT NULL,
  bahan_bakar VARCHAR(30) NOT NULL,
  status ENUM('Tersedia','Terjual') NOT NULL DEFAULT 'Tersedia',
  foto TEXT NULL,
  deskripsi TEXT NULL,
  dibuat TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (kategori_id) REFERENCES kategori(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

INSERT IGNORE INTO kategori(nama) VALUES ('MPV'),('Hatchback'),('SUV'),('City Car'),('Sedan');

INSERT INTO mobil (nama,merek,tahun,kategori_id,harga,transmisi,bahan_bakar,status,foto,deskripsi) VALUES
('Toyota Avanza G','Toyota',2015,(SELECT id FROM kategori WHERE nama='MPV'),115000000,'Manual','Bensin','Tersedia','','Avanza keluarga, kondisi dan kelengkapan dapat dicek langsung.'),
('Honda Jazz RS','Honda',2014,(SELECT id FROM kategori WHERE nama='Hatchback'),148000000,'Automatic','Bensin','Tersedia','','Hatchback dengan kabin praktis untuk penggunaan harian.'),
('Toyota Kijang Innova G','Toyota',2013,(SELECT id FROM kategori WHERE nama='MPV'),165000000,'Manual','Diesel','Tersedia','','Pilihan mobil keluarga dengan ruang kabin cukup luas.'),
('Daihatsu Xenia X','Daihatsu',2016,(SELECT id FROM kategori WHERE nama='MPV'),108000000,'Manual','Bensin','Tersedia','','Mobil keluarga dengan penggunaan yang praktis.'),
('Suzuki Ertiga GL','Suzuki',2015,(SELECT id FROM kategori WHERE nama='MPV'),112000000,'Manual','Bensin','Tersedia','','MPV dengan kapasitas keluarga dan bagasi.'),
('Honda Brio Satya E','Honda',2018,(SELECT id FROM kategori WHERE nama='City Car'),125000000,'Manual','Bensin','Tersedia','','City car untuk mobilitas sehari-hari.'),
('Toyota Agya TRD','Toyota',2017,(SELECT id FROM kategori WHERE nama='City Car'),103000000,'Manual','Bensin','Tersedia','','City car berukuran ringkas.'),
('Mitsubishi Pajero Sport','Mitsubishi',2014,(SELECT id FROM kategori WHERE nama='SUV'),235000000,'Automatic','Diesel','Tersedia','','SUV dengan kabin dan ground clearance tinggi.'),
('Toyota Yaris E','Toyota',2013,(SELECT id FROM kategori WHERE nama='Hatchback'),112000000,'Automatic','Bensin','Terjual','','Hatchback untuk penggunaan perkotaan.'),
('Daihatsu Terios TX','Daihatsu',2016,(SELECT id FROM kategori WHERE nama='SUV'),145000000,'Manual','Bensin','Tersedia','','SUV kompak dengan kapasitas penumpang.'),
('Honda Mobilio E','Honda',2015,(SELECT id FROM kategori WHERE nama='MPV'),119000000,'Automatic','Bensin','Tersedia','','MPV dengan konfigurasi tempat duduk keluarga.'),
('Toyota Calya G','Toyota',2019,(SELECT id FROM kategori WHERE nama='MPV'),132000000,'Manual','Bensin','Tersedia','','MPV kompak keluaran tahun relatif muda.');
