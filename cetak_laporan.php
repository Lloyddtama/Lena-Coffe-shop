<?php
session_start();
include 'koneksi.php';
if ($_SESSION['role'] !== 'admin') exit;

$bulan = $_GET['bulan'];
$tahun = $_GET['tahun'];

$query = mysqli_query($conn, "SELECT transaksi.*, users.username 
                              FROM transaksi 
                              LEFT JOIN users ON transaksi.id_user = users.id_user 
                              WHERE MONTH(tgl_transaksi) = '$bulan' AND YEAR(tgl_transaksi) = '$tahun'");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cetak Laporan - <?= $bulan . '-' . $tahun; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print { .no-print { display: none; } }
    </style>
</head>
<body onload="window.print()">
    <div class="container mt-4">
        <h2 class="text-center">LAPORAN PENJUALAN KASIR KITA</h2>
        <p class="text-center">Periode: <?= $bulan . ' / ' . $tahun; ?></p>
        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Kasir</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php $total = 0; $i=1; while($row = mysqli_fetch_assoc($query)) : ?>
                <tr>
                    <td><?= $i++; ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($row['tgl_transaksi'])); ?></td>
                    <td><?= $row['username']; ?></td>
                    <td>Rp <?= number_format($row['total_bayar'], 0, ',', '.'); ?></td>
                </tr>
                <?php $total += $row['total_bayar']; endwhile; ?>
                <tr class="fw-bold bg-light">
                    <td colspan="3" class="text-end">GRAND TOTAL</td>
                    <td>Rp <?= number_format($total, 0, ',', '.'); ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>