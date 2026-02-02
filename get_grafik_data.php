<?php
include 'koneksi.php';

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'harian';
$labels = [];
$data = [];

if ($filter == 'harian') {
    // Ambil 7 hari terakhir
    $query = mysqli_query($conn, "SELECT DATE(tgl_transaksi) as tgl, SUM(total_bayar) as total FROM transaksi GROUP BY tgl ORDER BY tgl DESC LIMIT 7");
    while($r = mysqli_fetch_assoc($query)) {
        $labels[] = date('d M', strtotime($r['tgl']));
        $data[] = (int)$r['total'];
    }
} elseif ($filter == 'mingguan') {
    // Ambil 4 minggu terakhir
    $query = mysqli_query($conn, "SELECT WEEK(tgl_transaksi) as mgg, YEAR(tgl_transaksi) as thn, SUM(total_bayar) as total FROM transaksi GROUP BY thn, mgg ORDER BY thn DESC, mgg DESC LIMIT 4");
    while($r = mysqli_fetch_assoc($query)) {
        $labels[] = "Minggu " . $r['mgg'];
        $data[] = (int)$r['total'];
    }
} elseif ($filter == 'bulanan') {
    // Ambil 6 bulan terakhir
    $query = mysqli_query($conn, "SELECT MONTHNAME(tgl_transaksi) as bln, YEAR(tgl_transaksi) as thn, SUM(total_bayar) as total FROM transaksi GROUP BY thn, bln ORDER BY tgl_transaksi DESC LIMIT 6");
    while($r = mysqli_fetch_assoc($query)) {
        $labels[] = $r['bln'] . " " . $r['thn'];
        $data[] = (int)$r['total'];
    }
}

// Balik data agar urut dari waktu terlama ke terbaru di grafik
echo json_encode([
    'labels' => array_reverse($labels),
    'data' => array_reverse($data)
]);