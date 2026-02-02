<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: transaksi.php");
    exit;
}

include 'koneksi.php';

// 1. Statistik Ringkasan
$p = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM produk"));
$tanggal_sekarang = date('Y-m-d');
$t = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM transaksi WHERE DATE(tgl_transaksi) = '$tanggal_sekarang'"));
$o = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_bayar) as total FROM transaksi WHERE DATE(tgl_transaksi) = '$tanggal_sekarang'"));

// 2. Fitur Stok Menipis
$stok_limit = 5;
$s_low = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM produk WHERE stok <= $stok_limit"));
$detail_stok_low = mysqli_query($conn, "SELECT nama_produk, stok FROM produk WHERE stok <= $stok_limit ORDER BY stok ASC LIMIT 5");

// 3. Ambil Top 5 Produk Terlaris
$query_terlaris = mysqli_query($conn, "
    SELECT p.nama_produk, p.stok, SUM(dt.jumlah) as total_terjual 
    FROM detail_transaksi dt 
    JOIN produk p ON dt.id_produk = p.id_produk 
    GROUP BY dt.id_produk 
    ORDER BY total_terjual DESC 
    LIMIT 5
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Kasir Kita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        :root {
            --glass-bg: rgba(255, 255, 255, 0.9);
            --grad-primary: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            --grad-success: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
            --grad-warning: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%);
            --grad-danger: linear-gradient(135deg, #e74a3b 0%, #be2617 100%);
        }
        body { background-color: #f0f2f5; font-family: 'Inter', sans-serif; }
        .navbar { 
            background: rgba(78, 115, 223, 0.95) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .stat-card {
            border: none;
            border-radius: 16px;
            color: white;
            transition: all 0.3s cubic-bezier(.25,.8,.25,1);
            overflow: hidden;
            position: relative;
        }
        .stat-card:hover { transform: translateY(-7px); box-shadow: 0 15px 30px rgba(0,0,0,0.15); }
        .stat-card i {
            position: absolute;
            right: -10px;
            bottom: -10px;
            font-size: 5rem;
            opacity: 0.2;
        }
        .card-custom {
            border: none;
            border-radius: 20px;
            background: var(--glass-bg);
            box-shadow: 0 8px 25px rgba(0,0,0,0.05);
        }
        .table thead th {
            background-color: transparent;
            border-bottom: 2px solid #f1f1f1;
            color: #6e707e;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
        }
        .rank-circle {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-weight: bold;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top mb-5">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#"><i class="fas fa-shopping-cart me-2"></i>Lena Coffee</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link active" href="index.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="produk.php">Produk</a></li>
                <li class="nav-item"><a class="nav-link" href="user.php">User</a></li>
                <li class="nav-item"><a class="nav-link" href="riwayat.php">Riwayat</a></li>
                <li class="nav-item"><a class="nav-link" href="laporan.php">Laporan</a></li>
                <li class="nav-item"><a class="nav-link" href="transaksi.php">Transaksi</a></li>
                <li class="nav-item"><a class="nav-link text-warning fw-bold" href="logout.php">Keluar</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container pb-5">
    <div class="row align-items-center mb-4" data-aos="fade-down">
        <div class="col-md-6">
            <h2 class="fw-bold text-dark mb-1">Dashboard</h2>
            <p class="text-muted">Pantau aktivitas toko Anda hari ini</p>
        </div>
        <div class="col-md-6 text-md-end">
            <div class="bg-white px-4 py-2 rounded-pill shadow-sm d-inline-block">
                <i class="far fa-calendar-alt text-primary me-2"></i>
                <span class="fw-bold"><?= date('d F Y'); ?></span>
            </div>
        </div>
    </div>
    
    <div class="row g-4 mb-5" data-aos="fade-up">
        <div class="col-md-3">
            <div class="card stat-card h-100" style="background: var(--grad-primary);">
                <div class="card-body p-4">
                    <h6 class="text-uppercase small fw-bold opacity-75">Total Produk</h6>
                    <h2 class="fw-bold mb-0"><?= $p['total']; ?></h2>
                    <i class="fas fa-box"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card h-100" style="background: var(--grad-success);">
                <div class="card-body p-4">
                    <h6 class="text-uppercase small fw-bold opacity-75">Transaksi Hari Ini</h6>
                    <h2 class="fw-bold mb-0"><?= $t['total']; ?></h2>
                    <i class="fas fa-receipt"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card h-100" style="background: var(--grad-warning);">
                <div class="card-body p-4">
                    <h6 class="text-uppercase small fw-bold opacity-75">Omzet Hari Ini</h6>
                    <h3 class="fw-bold mb-0">Rp <?= number_format($o['total'] ?? 0, 0, ',', '.'); ?></h3>
                    <i class="fas fa-wallet"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card h-100" style="background: <?= ($s_low['total'] > 0) ? 'var(--grad-danger)' : 'var(--grad-primary)'; ?>;">
                <div class="card-body p-4">
                    <h6 class="text-uppercase small fw-bold opacity-75">Stok Menipis</h6>
                    <h2 class="fw-bold mb-0"><?= $s_low['total']; ?> <small class="fs-6">Item</small></h2>
                    <i class="fas fa-exclamation-circle"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-8" data-aos="fade-right">
            <div class="card card-custom h-100">
                <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center py-4 px-4">
                    <h5 class="fw-bold mb-0 text-dark">Tren Penjualan</h5>
                    <select class="form-select form-select-sm border-0 bg-light rounded-pill px-3" style="width: auto;" id="filterGrafik" onchange="updateChart(this.value)">
                        <option value="harian">7 Hari Terakhir</option>
                        <option value="mingguan">4 Minggu Terakhir</option>
                        <option value="bulanan">6 Bulan Terakhir</option>
                    </select>
                </div>
                <div class="card-body px-4">
                    <div style="height:320px;">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4" data-aos="fade-left">
            <div class="card card-custom h-100">
                <div class="card-header bg-transparent border-0 py-4 px-4">
                    <h5 class="fw-bold mb-0 text-danger">Stok Kritis</h5>
                </div>
                <div class="card-body p-0">
                    <?php if($s_low['total'] > 0) : ?>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <tbody>
                                    <?php while($row_s = mysqli_fetch_assoc($detail_stok_low)) : ?>
                                    <tr class="border-transparent">
                                        <td class="ps-4">
                                            <div class="fw-bold small"><?= $row_s['nama_produk']; ?></div>
                                        </td>
                                        <td class="text-end pe-4">
                                            <span class="badge bg-light-danger text-danger border border-danger rounded-pill">Sisa <?= $row_s['stok']; ?></span>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="p-4">
                            <a href="produk.php" class="btn btn-sm btn-outline-danger w-100 rounded-pill fw-bold">Restock Sekarang</a>
                        </div>
                    <?php else : ?>
                        <div class="text-center py-5">
                            <img src="https://cdn-icons-png.flaticon.com/512/5264/5264565.png" width="80" class="opacity-25 mb-3" alt="Safe">
                            <p class="text-muted small">Semua stok aman terkendali!</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-12" data-aos="fade-up">
            <div class="card card-custom">
                <div class="card-header bg-transparent border-0 py-4 px-4">
                    <h5 class="fw-bold mb-0 text-dark">5 Produk Paling Laris</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive px-4 pb-4">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th class="text-center">Posisi</th>
                                    <th>Nama Barang</th>
                                    <th class="text-center">Unit Terjual</th>
                                    <th class="text-center">Status Inventori</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; while($row_t = mysqli_fetch_assoc($query_terlaris)) : ?>
                                <tr>
                                    <td class="text-center">
                                        <?php 
                                            $colors = ['#FFD700', '#C0C0C0', '#CD7F32', '#f4f7fe', '#f4f7fe'];
                                            $text = ($no <= 3) ? 'white' : 'dark';
                                        ?>
                                        <div class="rank-circle" style="background: <?= $colors[$no-1]; ?>; color: <?= ($no<=3)?'white':'black'; ?>">
                                            <?= $no; ?>
                                        </div>
                                    </td>
                                    <td class="fw-bold"><?= $row_t['nama_produk']; ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-primary border border-primary px-3 py-2 rounded-pill"><?= $row_t['total_terjual']; ?> pcs</span>
                                    </td>
                                    <td class="text-center">
                                        <?php if($row_t['stok'] <= $stok_limit): ?>
                                            <span class="text-danger small fw-bold"><i class="fas fa-arrow-down me-1"></i>Hampir Habis</span>
                                        <?php else: ?>
                                            <span class="text-success small fw-bold"><i class="fas fa-check me-1"></i>Stok Aman (<?= $row_t['stok']; ?>)</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php $no++; endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({ duration: 800, once: true });

    let myChart;
    function renderChart(labels, data) {
        const ctx = document.getElementById('myChart').getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(78, 115, 223, 0.4)');
        gradient.addColorStop(1, 'rgba(78, 115, 223, 0)');

        if (myChart) { myChart.destroy(); }
        myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Omzet Penjualan',
                    data: data,
                    borderColor: '#4e73df',
                    backgroundColor: gradient,
                    borderWidth: 4,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#4e73df',
                    pointBorderWidth: 3,
                    pointRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [5, 5] }, ticks: { callback: v => 'Rp ' + v.toLocaleString() } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    function updateChart(filter) {
        fetch('get_grafik_data.php?filter=' + filter)
            .then(res => res.json())
            .then(res => renderChart(res.labels, res.data));
    }
    updateChart('harian');
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>