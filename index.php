<?php
require_once __DIR__ . '/koneksi.php';

$sql = "SELECT * FROM data_barang ORDER BY id_barang DESC";
$result = mysqli_query($conn, $sql);
if (!$result) {
    die("Gagal mengambil data: " . htmlspecialchars(mysqli_error($conn)));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Barang | Lab8Web</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <header class="page-header">
        <div>
            <span class="eyebrow">PRAKTIKUM 8 · PHP & MYSQL</span>
            <h1>Data Barang</h1>
            <p class="subtitle">Aplikasi sederhana untuk mengelola inventaris barang.</p>
        </div>
        <a href="tambah.php" class="btn btn-primary">+ Tambah Barang</a>
    </header>

    <section class="panel">
        <div class="panel-heading">
            <div>
                <h2>Daftar Inventaris</h2>
                <p>Data barang yang tersimpan di database.</p>
            </div>
            <span class="count"><?= mysqli_num_rows($result); ?> barang</span>
        </div>

        <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Gambar</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Harga Beli</th>
                    <th>Harga Jual</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php $no = 1; ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td>
                            <?php if (!empty($row['gambar']) && is_file(__DIR__ . '/gambar/' . $row['gambar'])): ?>
                                <img class="product-image"
                                     src="gambar/<?= rawurlencode($row['gambar']); ?>"
                                     alt="<?= htmlspecialchars($row['nama']); ?>">
                            <?php else: ?>
                                <div class="image-placeholder">No image</div>
                            <?php endif; ?>
                        </td>
                        <td class="product-name"><?= htmlspecialchars($row['nama']); ?></td>
                        <td><span class="category"><?= htmlspecialchars($row['kategori']); ?></span></td>
                        <td>Rp <?= number_format((float)$row['harga_beli'], 0, ',', '.'); ?></td>
                        <td>Rp <?= number_format((float)$row['harga_jual'], 0, ',', '.'); ?></td>
                        <td><?= (int)$row['stok']; ?></td>
                        <td>
                            <div class="action-group">
                                <a class="btn btn-edit" href="ubah.php?id=<?= (int)$row['id_barang']; ?>">Ubah</a>
                                <form action="hapus.php" method="post"
                                      onsubmit="return confirm('Yakin ingin menghapus barang ini?');">
                                    <input type="hidden" name="id" value="<?= (int)$row['id_barang']; ?>">
                                    <button type="submit" class="btn btn-delete">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="8" class="empty">Belum ada data barang. Silakan tambahkan barang baru.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
        </div>
    </section>
    <footer>Lab8Web · PHP, MySQL, dan MySQLi</footer>
</div>
</body>
</html>
