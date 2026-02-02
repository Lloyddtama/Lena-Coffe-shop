<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: transaksi.php");
    exit;
}

include 'koneksi.php';

$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

$query = mysqli_query($conn, "SELECT transaksi.*, users.username 
                              FROM transaksi 
                              LEFT JOIN users ON transaksi.id_user = users.id_user 
                              WHERE MONTH(tgl_transaksi) = '$bulan' 
                              AND YEAR(tgl_transaksi) = '$tahun' 
                              ORDER BY tgl_transaksi DESC");

$total_omzet = mysqli_query($conn, "SELECT SUM(total_bayar) as grand_total FROM transaksi 
                                    WHERE MONTH(tgl_transaksi) = '$bulan' 
                                    AND YEAR(tgl_transaksi) = '$tahun'");
$data_omzet = mysqli_fetch_assoc($total_omzet);

$nama_bulan = [
    '01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April', '05'=>'Mei', '06'=>'Juni',
    '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember'
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Bulanan - Kasir Kita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        :root {
            --primary-grad: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            --success-grad: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
        }
        body { background-color: #f4f7fe; font-family: 'Inter', sans-serif; }
        
        .navbar { 
            background: rgba(78, 115, 223, 0.95) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .filter-card {
            border: none;
            border-radius: 20px;
            background: white;
            box-shadow: 0 8px 25px rgba(0,0,0,0.05);
        }

        .summary-card {
            background: var(--success-grad);
            color: white;
            border: none;
            border-radius: 20px;
            position: relative;
            overflow: hidden;
        }

        .summary-card i {
            position: absolute;
            right: -10px;
            bottom: -10px;
            font-size: 6rem;
            opacity: 0.2;
        }

        .table thead th {
            background-color: #f8f9fc;
            border: none;
            color: #4e73df;
            text-transform: uppercase;
            font-size: 0.75rem;
            padding: 15px 20px;
        }

        .table tbody td {
            padding: 15px 20px;
            vertical-align: middle;
            border-color: #f1f1f1;
        }

        .btn-print {
            background: var(--success-grad);
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 12px;
            transition: all 0.3s;
        }
        .btn-print:hover { color: white; transform: translateY(-3px); box-shadow: 0 5px 15px rgba(28, 200, 138, 0.4); }

        .btn-search {
            background: var(--primary-grad);
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 12px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top mb-5">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php"><i class="fas fa-shopping-cart me-2"></i>Lena Coffee</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="produk.php">Produk</a></li>
                <li class="nav-item"><a class="nav-link" href="user.php">User</a></li>
                <li class="nav-item"><a class="nav-link" href="riwayat.php">Riwayat</a></li>
                <li class="nav-item"><a class="nav-link active" href="laporan.php">Laporan</a></li>
                <li class="nav-item"><a class="nav-link" href="transaksi.php">Transaksi</a></li>
                <li class="nav-item"><a class="nav-link text-warning fw-bold" href="logout.php">Keluar</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container pb-5">
    <div class="row g-4 mb-4">
        <div class="col-lg-8" data-aos="fade-right">
            <div class="card filter-card p-4 h-100">
                <h5 class="fw-bold mb-4 text-dark"><i class="fas fa-filter text-primary me-2"></i>Filter Laporan</h5>
                <form method="GET" class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label small fw-bold text-muted">Pilih Bulan</label>
                        <select name="bulan" class="form-select border-0 bg-light rounded-3">
                            <?php foreach ($nama_bulan as $key => $val) : ?>
                                <option value="<?= $key; ?>" <?= ($key == $bulan) ? "selected" : ""; ?>><?= $val; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted">Tahun</label>
                        <input type="number" name="tahun" class="form-control border-0 bg-light rounded-3" value="<?= $tahun; ?>">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-search w-100">
                            <i class="fas fa-sync-alt me-2"></i>Update
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-4" data-aos="fade-left">
            <div class="card summary-card p-4 h-100 shadow-sm">
                <h6 class="text-uppercase small fw-bold opacity-75">Total Omzet <?= $nama_bulan[$bulan]; ?></h6>
                <h2 class="fw-bold my-3">Rp <?= number_format($data_omzet['grand_total'] ?? 0, 0, ',', '.'); ?></h2>
                <a href="cetak_laporan.php?bulan=<?= $bulan; ?>&tahun=<?= $tahun; ?>" target="_blank" class="btn btn-print w-100 mt-2">
                    <i class="fas fa-print me-2"></i>Cetak PDF
                </a>
                <i class="fas fa-chart-line"></i>
            </div>
        </div>
    </div>

    <div class="card filter-card mt-4" data-aos="fade-up">
        <div class="card-body p-0">
            <div class="p-4 d-flex justify-content-between align-items-center border-bottom">
                <h5 class="fw-bold mb-0 text-dark">Rincian Penjualan</h5>
                <span class="badge bg-light text-primary border border-primary px-3 py-2 rounded-pill">
                    <?= $nama_bulan[$bulan] . " " . $tahun; ?>
                </span>
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">No. Transaksi</th>
                            <th>Tanggal & Waktu</th>
                            <th>Kasir Bertugas</th>
                            <th class="text-end pe-4">Total Bayar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($query) > 0) : ?>
                            <?php while($row = mysqli_fetch_assoc($query)) : ?>
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-bold text-primary">#<?= $row['id_transaksi']; ?></span>
                                </td>
                                <td>
                                    <div class="small fw-bold"><?= date('d M Y', strtotime($row['tgl_transaksi'])); ?></div>
                                    <div class="text-muted" style="font-size: 0.75rem;"><?= date('H:i', strtotime($row['tgl_transaksi'])); ?> WIB</div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border fw-normal px-2 py-1">
                                        <i class="fas fa-user-circle me-1"></i> <?= $row['username'] ?? 'Sistem'; ?>
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <span class="fw-bold text-dark">Rp <?= number_format($row['total_bayar'], 0, ',', '.'); ?></span>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="opacity-25 mb-3">
                                        <i class="fas fa-folder-open fa-4x"></i>
                                    </div>
                                    <p class="text-muted">Tidak ada transaksi ditemukan pada periode ini.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({ duration: 800, once: true });
</script>
</body>
</html>