<?php
include 'koneksi.php';

// Ambil ID dari URL (id yang dikirim saat klik tombol hapus di produk.php)
$id = $_GET['id'];

// Perintah SQL untuk menghapus data berdasarkan ID
$query = mysqli_query($conn, "DELETE FROM produk WHERE id_produk = '$id'");

if ($query) {
    // Jika berhasil, munculkan pesan dan kembali ke halaman produk
    echo "<script>
            alert('Produk Berhasil Dihapus!');
            window.location='produk.php';
          </script>";
} else {
    // Jika gagal, tampilkan pesan error
    echo "Gagal menghapus data: " . mysqli_error($conn);
}
?>