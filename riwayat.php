<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: transaksi.php");
    exit;
}

include 'koneksi.php';

$query = mysqli_query($conn, "SELECT transaksi.*, users.username 
                              FROM transaksi 
                              LEFT JOIN users ON transaksi.id_user = users.id_user 
                              ORDER BY id_transaksi DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi - Kasir Kita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        :root {
            --primary-grad: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        }
        body { background-color: #f4f7fe; font-family: 'Inter', sans-serif; }
        
        .navbar { 
            background: rgba(78, 115, 223, 0.95) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .card-main {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            background: white;
        }

        .table thead th {
            background-color: #f8f9fc;
            border: none;
            color: #4e73df;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 1px;
            padding: 20px;
        }

        .table tbody td {
            padding: 18px 20px;
            vertical-align: middle;
            border-color: #f8f9fc;
        }

        .nota-badge {
            background: #eef2ff;
            color: #4e73df;
            font-weight: 700;
            padding: 5px 12px;
            border-radius: 8px;
            font-family: 'Courier New', Courier, monospace;
        }

        .total-amount {
            font-weight: 800;
            color: #1cc88a;
        }

        .btn-action {
            width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            transition: all 0.3s;
            border: none;
            margin: 0 2px;
        }

        .btn-print { background: #e0f2ff; color: #0d6efd; }
        .btn-print:hover { background: #0d6efd; color: white; transform: scale(1.1); }
        
        .btn-delete { background: #ffe5e5; color: #e74a3b; }
        .btn-delete:hover { background: #e74a3b; color: white; transform: scale(1.1); }

        .search-container {
            position: relative;
            max-width: 300px;
        }
        .search-container i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
        }
        .search-container input {
            padding-left: 40px;
            border-radius: 10px;
            border: 1px solid #e3e6f0;
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
                <li class="nav-item"><a class="nav-link active" href="riwayat.php">Riwayat</a></li>
                <li class="nav-item"><a class="nav-link" href="laporan.php">Laporan</a></li>
                <li class="nav-item"><a class="nav-link" href="transaksi.php">Transaksi</a></li>
                <li class="nav-item"><a class="nav-link text-warning fw-bold" href="logout.php">Keluar</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container pb-5" data-aos="fade-up">
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h2 class="fw-bold text-dark mb-1"><i class="fas fa-history text-primary me-2"></i>Riwayat Transaksi</h2>
            <p class="text-muted">Pantau semua jejak penjualan toko Anda</p>
        </div>
        <div class="col-md-6 d-flex justify-content-md-end">
            <div class="search-container">
                <i class="fas fa-search"></i>
                <input type="text" class="form-control" placeholder="Cari nomor nota...">
            </div>
        </div>
    </div>

    <div class="card card-main">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">No. Nota</th>
                            <th>Waktu Transaksi</th>
                            <th>Kasir Bertugas</th>
                            <th>Total Pembayaran</th>
                            <th class="text-center">Opsi Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($query)) : ?>
                        <tr>
                            <td class="ps-4">
                                <span class="nota-badge">#<?= str_pad($row['id_transaksi'], 5, '0', STR_PAD_LEFT); ?></span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-light p-2 rounded me-3 text-primary">
                                        <i class="far fa-calendar-alt"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold" style="font-size: 0.9rem;"><?= date('d M Y', strtotime($row['tgl_transaksi'])); ?></div>
                                        <small class="text-muted"><i class="far fa-clock me-1"></i><?= date('H:i', strtotime($row['tgl_transaksi'])); ?> WIB</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="https://ui-avatars.com/api/?name=<?= $row['username'] ?? 'S'; ?>&background=4e73df&color=fff&size=30" class="rounded-circle me-2" alt="">
                                    <span class="fw-semibold text-secondary"><?= $row['username'] ?? 'Sistem'; ?></span>
                                </div>
                            </td>
                            <td>
                                <span class="total-amount">Rp <?= number_format($row['total_bayar'], 0, ',', '.'); ?></span>
                            </td>
                            <td class="text-center">
                                <a href="cetak_struk.php?id=<?= $row['id_transaksi']; ?>" target="_blank" class="btn-action btn-print" title="Cetak Struk">
                                    <i class="fas fa-print"></i>
                                </a>
                                
                                <?php if ($_SESSION['role'] == 'admin') : ?>
                                    <a href="hapus_riwayat.php?id=<?= $row['id_transaksi']; ?>" 
                                       class="btn-action btn-delete" 
                                       title="Hapus Permanen"
                                       onclick="return confirm('Data transaksi akan dihapus permanen. Lanjutkan?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
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