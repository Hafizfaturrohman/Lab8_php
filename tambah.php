<?php
require_once __DIR__ . '/koneksi.php';

$error = '';
$nama = '';
$kategori = 'Elektronik';
$harga_jual = '';
$harga_beli = '';
$stok = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $nama = trim($_POST['nama'] ?? '');
    $kategori = $_POST['kategori'] ?? '';
    $harga_jual = filter_var($_POST['harga_jual'] ?? '', FILTER_VALIDATE_INT);
    $harga_beli = filter_var($_POST['harga_beli'] ?? '', FILTER_VALIDATE_INT);
    $stok = filter_var($_POST['stok'] ?? '', FILTER_VALIDATE_INT);
    $gambar = '';

    $kategori_valid = ['Komputer', 'Elektronik', 'Hand Phone'];

    if ($nama === '') {
        $error = 'Nama barang wajib diisi.';
    } elseif (!in_array($kategori, $kategori_valid, true)) {
        $error = 'Kategori tidak valid.';
    } elseif ($harga_jual === false || $harga_jual < 0 ||
              $harga_beli === false || $harga_beli < 0 ||
              $stok === false || $stok < 0) {
        $error = 'Harga dan stok harus berupa angka nol atau lebih.';
    } else {
        if (isset($_FILES['file_gambar']) && $_FILES['file_gambar']['error'] !== UPLOAD_ERR_NO_FILE) {
            $file = $_FILES['file_gambar'];
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            if ($file['error'] !== UPLOAD_ERR_OK) {
                $error = 'Upload gambar gagal.';
            } elseif (!in_array($ext, $allowed, true)) {
                $error = 'Format gambar harus JPG, JPEG, PNG, atau WEBP.';
            } elseif ($file['size'] > 2 * 1024 * 1024) {
                $error = 'Ukuran gambar maksimal 2 MB.';
            } else {
                $gambar = uniqid('barang_', true) . '.' . $ext;
                if (!move_uploaded_file($file['tmp_name'], __DIR__ . '/gambar/' . $gambar)) {
                    $error = 'Gambar tidak dapat disimpan ke folder gambar.';
                }
            }
        }

        if ($error === '') {
            $stmt = mysqli_prepare($conn, "INSERT INTO data_barang (nama, kategori, gambar, harga_beli, harga_jual, stok) VALUES (?, ?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "sssiii", $nama, $kategori, $gambar, $harga_beli, $harga_jual, $stok);

            if (mysqli_stmt_execute($stmt)) {
                header('Location: index.php');
                exit;
            }
            if ($gambar !== '' && is_file(__DIR__ . '/gambar/' . $gambar)) {
                unlink(__DIR__ . '/gambar/' . $gambar);
            }
            $error = 'Data gagal disimpan: ' . mysqli_stmt_error($stmt);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Barang | Lab8Web</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="form-container">
    <a href="index.php" class="back-link">← Kembali ke Data Barang</a>
    <h1>Tambah Barang</h1>
    <p class="subtitle">Masukkan informasi barang yang akan ditambahkan.</p>

    <?php if ($error): ?><div class="alert"><?= htmlspecialchars($error); ?></div><?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <label for="nama">Nama Barang</label>
        <input id="nama" type="text" name="nama" maxlength="30" value="<?= htmlspecialchars($nama); ?>" required>

        <label for="kategori">Kategori</label>
        <select id="kategori" name="kategori" required>
            <?php foreach (['Komputer', 'Elektronik', 'Hand Phone'] as $option): ?>
                <option value="<?= $option; ?>" <?= $kategori === $option ? 'selected' : ''; ?>><?= $option; ?></option>
            <?php endforeach; ?>
        </select>

        <div class="form-grid">
            <div>
                <label for="harga_beli">Harga Beli (Rp)</label>
                <input id="harga_beli" type="number" name="harga_beli" min="0" value="<?= htmlspecialchars((string)$harga_beli); ?>" required>
            </div>
            <div>
                <label for="harga_jual">Harga Jual (Rp)</label>
                <input id="harga_jual" type="number" name="harga_jual" min="0" value="<?= htmlspecialchars((string)$harga_jual); ?>" required>
            </div>
        </div>

        <label for="stok">Stok</label>
        <input id="stok" type="number" name="stok" min="0" value="<?= htmlspecialchars((string)$stok); ?>" required>

        <label for="file_gambar">Gambar Barang (opsional)</label>
        <input id="file_gambar" type="file" name="file_gambar" accept=".jpg,.jpeg,.png,.webp">
        <small>Format JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB.</small>

        <div class="form-actions">
            <button type="submit" name="submit" class="btn btn-primary">Simpan Barang</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
</body>
</html>
