<?php
require_once __DIR__ . '/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    die('ID barang tidak valid.');
}

$stmt = mysqli_prepare($conn, "SELECT gambar FROM data_barang WHERE id_barang = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$data) {
    header('Location: index.php');
    exit;
}

$stmt = mysqli_prepare($conn, "DELETE FROM data_barang WHERE id_barang = ?");
mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    if (!empty($data['gambar']) && is_file(__DIR__ . '/gambar/' . $data['gambar'])) {
        unlink(__DIR__ . '/gambar/' . $data['gambar']);
    }
    header('Location: index.php');
    exit;
}

die('Gagal menghapus data barang.');
