<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: transaksi.php");
    exit;
}
include 'koneksi.php';

$query = mysqli_query($conn, "SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User - Kasir Kita</title>
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
            overflow: hidden;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: var(--primary-grad);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            font-weight: bold;
            text-transform: uppercase;
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
            padding: 15px 20px;
            vertical-align: middle;
            border-color: #f1f1f1;
        }

        .role-badge {
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .btn-add {
            border-radius: 12px;
            padding: 10px 24px;
            font-weight: 600;
            background: var(--primary-grad);
            border: none;
            box-shadow: 0 4px 15px rgba(78, 115, 223, 0.3);
            transition: all 0.3s;
        }

        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(78, 115, 223, 0.4);
            color: white;
        }

        .action-icon {
            width: 35px;
            height: 35px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            transition: all 0.2s;
            background: #ffe5e5;
            color: #e74a3b;
            text-decoration: none;
        }

        .action-icon:hover {
            background: #e74a3b;
            color: white;
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
                <li class="nav-item"><a class="nav-link active" href="user.php">User</a></li>
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
            <h2 class="fw-bold text-dark mb-1">Manajemen User</h2>
            <p class="text-muted">Kelola hak akses pengguna aplikasi</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="tambah_user.php" class="btn btn-primary btn-add">
                <i class="fas fa-user-plus me-2"></i>Tambah User Baru
            </a>
        </div>
    </div>

    <div class="card card-main">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 80px;">No</th>
                            <th>User</th>
                            <th>Role / Jabatan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; while($row = mysqli_fetch_assoc($query)) : ?>
                        <tr>
                            <td class="text-center text-muted fw-bold"><?= $i++; ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar me-3">
                                        <?= substr($row['username'], 0, 1); ?>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark"><?= $row['username']; ?></div>
                                        <?php if($row['username'] === $_SESSION['username']) : ?>
                                            <span class="badge bg-light text-primary border border-primary x-small" style="font-size: 0.6rem;">SAYA</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php if($row['role'] == 'admin') : ?>
                                    <span class="role-badge bg-light-primary text-primary border border-primary">
                                        <i class="fas fa-user-shield me-1"></i> ADMINISTRATOR
                                    </span>
                                <?php else : ?>
                                    <span class="role-badge bg-light-success text-success border border-success">
                                        <i class="fas fa-cash-register me-1"></i> STAFF KASIR
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if($row['username'] !== $_SESSION ['username']) : ?>
                                    <a href="hapus_user.php?id=<?= $row['id_user']; ?>" 
                                       class="action-icon" 
                                       title="Hapus User"
                                       onclick="return confirm('Yakin ingin menghapus user ini?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                <?php else : ?>
                                    <span class="text-muted small italic">Aktif</span>
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