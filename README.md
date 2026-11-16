# Lab8Web - Praktikum 8 PHP dan Database MySQL

## Deskripsi
Project ini merupakan aplikasi CRUD sederhana untuk mengelola data barang menggunakan PHP, MySQL, dan MySQLi. Project dibuat mengikuti materi Praktikum 8 Pemrograman Web.

## Tujuan Praktikum
1. Memahami konsep dasar database.
2. Memahami konsep dasar CRUD menggunakan PHP.
3. Membuat program CRUD sederhana menggunakan PHP.

## Teknologi
- PHP
- MySQL
- MySQLi
- HTML
- CSS
- XAMPP
- Visual Studio Code

## Fitur
- Menampilkan daftar barang (Read).
- Menambahkan barang (Create).
- Mengubah data barang (Update).
- Menghapus barang (Delete).
- Mengunggah gambar barang.

## Struktur Folder
```text
Lab8Web/
├── gambar/
├── screenshot/
├── koneksi.php
├── index.php
├── tambah.php
├── ubah.php
├── hapus.php
├── style.css
├── database.sql
└── README.md
```

## Cara Menjalankan
1. Jalankan Apache dan MySQL melalui XAMPP.
2. Salin folder `Lab8Web` ke direktori `C:\\xampp\\htdocs\\`.
3. Buka `http://localhost/phpmyadmin/`.
4. Pilih menu **Import**, lalu impor file `database.sql`.
5. Pastikan database `latihan1` dan tabel `data_barang` sudah terbentuk.
6. Buka `http://localhost/Lab8Web/` melalui browser.

> Jika folder project diganti namanya menjadi `lab8_php_database`, akses melalui `http://localhost/lab8_php_database/`.

## Konfigurasi Database
Pengaturan koneksi berada di `koneksi.php`. Konfigurasi bawaan XAMPP:
- Host: `localhost`
- Username: `root`
- Password: kosong
- Database: `latihan1`

Sesuaikan konfigurasi jika MySQL di komputer menggunakan username atau password berbeda.

## Penjelasan Praktikum

### 1. Membuat Database
Database `latihan1` dibuat untuk menyimpan data barang. Tabel `data_barang` memiliki kolom ID, kategori, nama, gambar, harga beli, harga jual, dan stok.

### 2. Membuat Koneksi
File `koneksi.php` menghubungkan aplikasi PHP dengan database MySQL menggunakan `mysqli_connect()`.

### 3. Menampilkan Data (Read)
File `index.php` menggunakan perintah SQL `SELECT` untuk mengambil dan menampilkan seluruh data barang.

### 4. Menambahkan Data (Create)
File `tambah.php` menggunakan perintah SQL `INSERT` untuk menyimpan barang baru. Form juga menyediakan upload gambar.

### 5. Mengubah Data (Update)
File `ubah.php` mengambil data berdasarkan ID, menampilkan data pada form, kemudian memperbaruinya menggunakan perintah SQL `UPDATE`.

### 6. Menghapus Data (Delete)
File `hapus.php` menghapus data berdasarkan ID menggunakan perintah SQL `DELETE`.

## Dokumentasi Screenshot
Simpan screenshot hasil pengerjaan di folder `screenshot/`, misalnya:
1. `01-xampp.png`
2. `02-database.png`
3. `03-tabel-data-barang.png`
4. `04-data-awal.png`
5. `05-halaman-data-barang.png`
6. `06-form-tambah.png`
7. `07-hasil-tambah.png`
8. `08-form-ubah.png`
9. `09-hasil-ubah.png`
10. `10-hasil-hapus.png`

Screenshot perlu diambil dari hasil aplikasi yang benar-benar dijalankan.

## Kesimpulan
Praktikum ini menghasilkan aplikasi pengelolaan data barang menggunakan PHP dan MySQL. Melalui aplikasi ini, operasi CRUD (Create, Read, Update, Delete) dapat diterapkan untuk menambah, menampilkan, mengubah, dan menghapus data pada database.
