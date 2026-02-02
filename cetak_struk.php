<?php
include 'koneksi.php';
$id = $_GET['id'];

// Ambil data transaksi dan join dengan user (opsional jika sudah ada user)
$query = mysqli_query($conn, "SELECT * FROM transaksi WHERE id_transaksi = '$id'");
$transaksi = mysqli_fetch_assoc($query);

$detail = mysqli_query($conn, "SELECT detail_transaksi.*, produk.nama_produk, produk.harga 
                               FROM detail_transaksi 
                               JOIN produk ON detail_transaksi.id_produk = produk.id_produk 
                               WHERE id_transaksi = '$id'");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk #<?= $id; ?></title>
    <style>
        /* Pengaturan Ukuran Kertas Struk */
        @page { size: 80mm 200mm; margin: 0; }
        body { 
            font-family: 'Courier New', Courier, monospace; 
            width: 70mm; /* Lebar konten sedikit lebih kecil dari kertas */
            margin: 0 auto; 
            padding: 10px;
            font-size: 12px;
            color: #000;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .line { border-top: 1px dashed #000; margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; }
        .item-name { padding-top: 5px; }
        .item-detail { padding-bottom: 5px; font-size: 11px; }
        
        /* Tombol Cetak Hilang Saat Di-print */
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()">Cetak Struk</button>
        <a href="transaksi.php">Kembali ke Kasir</a>
    </div>

    <div class="text-center">
        <h3 style="margin:0;">KASIR KITA</h3>
        <p style="margin:0;">Jl. Jenderal Sudirman No. 1</p>
        <p style="margin:0;">Telp: 0812-3456-7890</p>
    </div>

    <div class="line"></div>
    
    <table border="0">
        <tr>
            <td>ID : #<?= $id; ?></td>
            <td class="text-right"><?= date("d/m/Y H:i", strtotime($transaksi['tgl_transaksi'])); ?></td>
        </tr>
        <tr>
            <td>Kasir: Admin</td>
        </tr>
    </table>

    <div class="line"></div>

    <table border="0">
        <?php while($row = mysqli_fetch_assoc($detail)) : ?>
        <tr>
            <td colspan="2" class="item-name"><?= strtoupper($row['nama_produk']); ?></td>
        </tr>
        <tr>
            <td class="item-detail"><?= $row['jumlah']; ?> x <?= number_format($row['harga']); ?></td>
            <td class="item-detail text-right"><?= number_format($row['subtotal']); ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <div class="line"></div>

    <table border="0" style="font-weight: bold; font-size: 14px;">
        <tr>
            <td>TOTAL</td>
            <td class="text-right">Rp <?= number_format($transaksi['total_bayar']); ?></td>
        </tr>
    </table>

    <div class="line"></div>
    
    <div class="text-center" style="margin-top: 10px;">
        <p>-- TERIMA KASIH --</p>
        <p style="font-size: 10px;">Barang yang sudah dibeli<br>tidak dapat ditukar kembali.</p>
    </div>

</body>
</html>