<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: transaksi.php");
    exit;
}

include 'koneksi.php';
$ambil_data = mysqli_query($conn, "SELECT * FROM produk");
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir Kita - Manajemen Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #f8f9fc;
        }
        body { 
            background-color: #f4f7fe; 
            font-family: 'Inter', sans-serif;
            color: #333;
        }
        .navbar { 
            background: rgba(78, 115, 223, 0.9) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .card { 
            border: none; 
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        .table thead {
            background-color: var(--secondary-color);
        }
        .table thead th {
            border: none;
            color: #4e73df;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 1px;
            padding: 20px;
        }
        .table tbody td {
            padding: 18px 20px;
            vertical-align: middle;
            border-color: #f1f1f1;
        }
        .table tbody tr {
            transition: all 0.2s ease;
        }
        .table tbody tr:hover {
            background-color: #f8f9ff;
            transform: scale(1.005);
        }
        .btn-action {
            width: 35px;
            height: 35px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            transition: all 0.3s;
        }
        .btn-action:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .badge-stok {
            padding: 6px 12px;
            border-radius: 8px;
            font-weight: 600;
        }
        .add-btn {
            border-radius: 12px;
            padding: 10px 20px;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(25, 135, 84, 0.3);
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#"><i class="fas fa-shopping-cart me-2"></i>Lena Coffee</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link active" href="produk.php">Produk</a></li>
                <li class="nav-item"><a class="nav-link" href="user.php">User</a></li>
                <li class="nav-item"><a class="nav-link" href="riwayat.php">Riwayat</a></li>
                <li class="nav-item"><a class="nav-link" href="laporan.php">Laporan</a></li>
                <li class="nav-item"><a class="nav-link" href="transaksi.php">Transaksi</a></li>
                <li class="nav-item"><a class="nav-link text-warning fw-bold" href="logout.php">Keluar</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container" data-aos="fade-up">
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h2 class="fw-bold m-0"><i class="fas fa-box-open text-primary me-2"></i>Produk</h2>
            <p class="text-muted mb-0">Kelola daftar inventaris barang toko Anda</p>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <a href="tambah_produk.php" class="btn btn-success add-btn">
                <i class="fas fa-plus me-2"></i>Tambah Produk Baru
            </a>
        </div>
    </div>

    <div class="card overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th>Informasi Produk</th>
                            <th>Harga Satuan</th>
                            <th class="text-center">Stok</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; while($row = mysqli_fetch_assoc($ambil_data)) : ?>
                        <tr>
                            <td class="text-center text-muted"><?= $i++; ?></td>
                            <td>
                                <div class="fw-bold text-dark"><?= $row['nama_produk']; ?></div>
                                <small class="text-muted">ID: #PRD-<?= $row['id_produk']; ?></small>
                            </td>
                            <td class="fw-semibold text-primary">
                                Rp <?= number_format($row['harga'], 0, ',', '.'); ?>
                            </td>
                            <td class="text-center">
                                <?php if($row['stok'] <= 5): ?>
                                    <span class="badge bg-light-danger text-danger border border-danger badge-stok">
                                        <i class="fas fa-exclamation-triangle me-1"></i><?= $row['stok']; ?> (Kritis)
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-light-info text-info border border-info badge-stok">
                                        <?= $row['stok']; ?> Tersedia
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <a href="edit_produk.php?id=<?= $row['id_produk']; ?>" class="btn-action bg-warning text-white me-1">
                                    <i class="fas fa-edit fa-sm"></i>
                                </a>
                                <a href="hapus_produk.php?id=<?= $row['id_produk']; ?>" 
                                   class="btn-action bg-danger text-white" 
                                   onclick="return confirm('Yakin ingin menghapus produk ini?')">
                                    <i class="fas fa-trash fa-sm"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="mt-4 text-center text-muted small">
        <p>&copy; 2024 Kasir Kita - Modern POS System</p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 800,
        once: true
    });
</script>
</body>
</html>