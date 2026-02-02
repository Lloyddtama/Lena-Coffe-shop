<?php
include 'koneksi.php';

// Memberi tahu browser bahwa ini adalah file Excel
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Penjualan.xls");

$query = mysqli_query($conn, "SELECT * FROM transaksi ORDER BY id_transaksi DESC");
?>

<h2>LAPORAN PENJUALAN - KASIR KITA</h2>
<table border="1">
    <tr>
        <th>No</th>
        <th>ID Transaksi</th>
        <th>Tanggal</th>
        <th>Total Pendapatan</th>
    </tr>
    <?php 
    $i = 1; 
    $total_semua = 0;
    while($row = mysqli_fetch_assoc($query)) : 
        $total_semua += $row['total_bayar'];
    ?>
    <tr>
        <td><?= $i++; ?></td>
        <td>#<?= $row['id_transaksi']; ?></td>
        <td><?= $row['tgl_transaksi']; ?></td>
        <td><?= $row['total_bayar']; ?></td>
    </tr>
    <?php endwhile; ?>
    <tr>
        <th colspan="3">TOTAL PENDAPATAN</th>
        <th><?= $total_semua; ?></th>
    </tr>
</table>