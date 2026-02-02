<?php
session_start();

// 1. Proteksi: Hanya admin yang boleh hapus
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

// 2. Ambil ID dari URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // 3. Hapus data dari tabel transaksi
    // Karena kita sudah pakai ON DELETE CASCADE di database, 
    // rincian di tabel detail_transaksi akan otomatis ikut terhapus.
    $query = mysqli_query($conn, "DELETE FROM transaksi WHERE id_transaksi = '$id'");

    if ($query) {
        // Berhasil: balik ke riwayat dengan pesan sukses
        echo "<script>
                alert('Data transaksi berhasil dihapus!');
                window.location='riwayat.php';
              </script>";
    } else {
        // Gagal: tampilkan error
        echo "Gagal menghapus: " . mysqli_error($conn);
    }
} else {
    header("Location: riwayat.php");
}
?>