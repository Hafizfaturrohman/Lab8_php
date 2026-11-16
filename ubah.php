<?php
require_once __DIR__ . '/koneksi.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    die('ID barang tidak valid. <a href="index.php">Kembali</a>');
}

$stmt = mysqli_prepare($conn, "SELECT * FROM data_barang WHERE id_barang = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$data) {
    die('Data barang tidak ditemukan. <a href="index.php">Kembali</a>');
}

$error = '';
$nama = $data['nama'];
$kategori = $data['kategori'];
$harga_jual = $data['harga_jual'];
$harga_beli = $data['harga_beli'];
$stok = $data['stok'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $nama = trim($_POST['nama'] ?? '');
    $kategori = $_POST['kategori'] ?? '';
    $harga_jual = filter_var($_POST['harga_jual'] ?? '', FILTER_VALIDATE_INT);
    $harga_beli = filter_var($_POST['harga_beli'] ?? '', FILTER_VALIDATE_INT);
    $stok = filter_var($_POST['stok'] ?? '', FILTER_VALIDATE_INT);
    $gambar = $data['gambar'];

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
        $gambar_baru = '';
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
                $gambar_baru = uniqid('barang_', true) . '.' . $ext;
                if (move_uploaded_file($file['tmp_name'], __DIR__ . '/gambar/' . $gambar_baru)) {
                    $gambar = $gambar_baru;
                } else {
                    $error = 'Gambar tidak dapat disimpan.';
                }
            }
        }

        if ($error === '') {
            $stmt = mysqli_prepare($conn, "UPDATE data_barang SET nama=?, kategori=?, gambar=?, harga_beli=?, harga_jual=?, stok=? WHERE id_barang=?");
            mysqli_stmt_bind_param($stmt, "sssiiii", $nama, $kategori, $gambar, $harga_beli, $harga_jual, $stok, $id);

            if (mysqli_stmt_execute($stmt)) {
                if ($gambar_baru !== '' && !empty($data['gambar']) && is_file(__DIR__ . '/gambar/' . $data['gambar'])) {
                    unlink(__DIR__ . '/gambar/' . $data['gambar']);
                }
                header('Location: index.php');
                exit;
            }
            if ($gambar_baru !== '' && is_file(__DIR__ . '/gambar/' . $gambar_baru)) {
                unlink(__DIR__ . '/gambar/' . $gambar_baru);
            }
            $error = 'Data gagal diperbarui: ' . mysqli_stmt_error($stmt);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Barang | Lab8Web</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="form-container">
    <a href="index.php" class="back-link">← Kembali ke Data Barang</a>
    <h1>Ubah Barang</h1>
    <p class="subtitle">Perbarui informasi barang yang dipilih.</p>

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
                <input id="harga_beli" type="number" name="harga_beli" min="0" value="<?= (int)$harga_beli; ?>" required>
            </div>
            <div>
                <label for="harga_jual">Harga Jual (Rp)</label>
                <input id="harga_jual" type="number" name="harga_jual" min="0" value="<?= (int)$harga_jual; ?>" required>
            </div>
        </div>

        <label for="stok">Stok</label>
        <input id="stok" type="number" name="stok" min="0" value="<?= (int)$stok; ?>" required>

        <label for="file_gambar">Ganti Gambar (opsional)</label>
        <input id="file_gambar" type="file" name="file_gambar" accept=".jpg,.jpeg,.png,.webp">
        <small>Biarkan kosong jika tidak ingin mengganti gambar.</small>

        <div class="form-actions">
            <button type="submit" name="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
</body>
</html>
