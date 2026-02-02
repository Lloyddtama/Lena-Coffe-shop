<?php
session_start();
// Proteksi: Hanya admin yang boleh hapus user
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: transaksi.php");
    exit;
}

include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Keamanan: Jangan biarkan admin menghapus dirinya sendiri via URL
    if ($id == $_SESSION['id_user']) {
        echo "<script>alert('Anda tidak bisa menghapus akun sendiri!'); window.location='user.php';</script>";
        exit;
    }

    $query = mysqli_query($conn, "DELETE FROM users WHERE id_user = '$id'");

    if ($query) {
        echo "<script>alert('User berhasil dihapus!'); window.location='user.php';</script>";
    } else {
        echo "Gagal menghapus: " . mysqli_error($conn);
    }
}
?>