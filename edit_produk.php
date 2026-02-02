<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
?>
<?php
include 'koneksi.php';

// 1. Ambil ID dari URL
$id = $_GET['id'];

// 2. Ambil data produk yang mau diedit berdasarkan ID
$query = mysqli_query($conn, "SELECT * FROM produk WHERE id_produk = '$id'");
$data  = mysqli_fetch_assoc($query);

// 3. Proses simpan perubahan
if (isset($_POST['update'])) {
    $nama  = $_POST['nama_produk'];
    $harga = $_POST['harga'];
    $stok  = $_POST['stok'];

    $update = mysqli_query($conn, "UPDATE produk SET 
                nama_produk = '$nama', 
                harga = '$harga', 
                stok = '$stok' 
                WHERE id_produk = '$id'");

    if ($update) {
        echo "<script>alert('Data berhasil diperbarui!'); window.location='produk.php';</script>";
    } else {
        echo "Gagal update: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Produk</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        input { display: block; width: 300px; padding: 10px; margin: 10px 0; }
        button { padding: 10px 20px; background: #ffc107; color: black; border: none; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body>

    <h2>Edit Produk</h2>
    <form method="POST">
        <label>Nama Produk:</label>
        <input type="text" name="nama_produk" value="<?= $data['nama_produk']; ?>" required>
        
        <label>Harga Jual:</label>
        <input type="number" name="harga" value="<?= $data['harga']; ?>" required>
        
        <label>Stok Barang:</label>
        <input type="number" name="stok" value="<?= $data['stok']; ?>" required>
        
        <button type="submit" name="update">Simpan Perubahan</button>
        <a href="produk.php">Batal</a>
    </form>

</body>
</html>