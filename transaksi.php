<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';
$produk = mysqli_query($conn, "SELECT * FROM produk WHERE stok > 0");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi - Kasir Kita</title>
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

        .card-input { border: none; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
        
        .card-cart { 
            border: none; 
            border-radius: 15px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.08); 
            min-height: 400px;
        }

        .total-display {
            background: #f8f9fc;
            border-radius: 12px;
            padding: 20px;
            text-align: right;
            border-left: 5px solid #4e73df;
        }

        .table thead th {
            background-color: transparent;
            border-bottom: 2px solid #f1f1f1;
            color: #4e73df;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 1px;
        }

        .btn-add {
            background: var(--primary-grad);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-add:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(78, 115, 223, 0.4); }

        .btn-pay {
            background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
            border: none;
            border-radius: 12px;
            padding: 15px 30px;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .item-row:hover { background-color: #f8f9fc; }
        
        #total-harga { font-size: 2.5rem; font-weight: 800; color: #4e73df; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#"><i class="fas fa-shopping-cart me-2"></i>Lena Coffee</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if ($_SESSION['role'] == 'admin') : ?>
                    <li class="nav-item"><a class="nav-link" href="index.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="produk.php">Produk</a></li>
                    <li class="nav-item"><a class="nav-link" href="user.php">User</a></li>
                    <li class="nav-item"><a class="nav-link" href="riwayat.php">Riwayat</a></li>
                    <li class="nav-item"><a class="nav-link" href="laporan.php">Laporan</a></li>
                <?php endif; ?>
                <li class="nav-item"><a class="nav-link active" href="transaksi.php">Transaksi</a></li>
                <li class="nav-item"><a class="nav-link text-warning fw-bold" href="logout.php">Keluar</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container pb-5" data-aos="fade-up">
    <div class="row">
        <div class="col-lg-4">
            <div class="card card-input mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 text-dark"><i class="fas fa-tag text-primary me-2"></i>Input Barang</h5>
                    <form id="form-tambah">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Cari Nama Barang</label>
                            <select class="form-select bg-light border-0 p-3" id="select_produk" required style="border-radius: 10px;">
                                <option value="">-- Pilih Produk --</option>
                                <?php while($row = mysqli_fetch_assoc($produk)) : ?>
                                    <option value="<?= $row['id_produk']; ?>" 
                                            data-nama="<?= $row['nama_produk']; ?>" 
                                            data-harga="<?= $row['harga']; ?>">
                                        <?= $row['nama_produk']; ?> (Rp<?= number_format($row['harga'], 0, ',', '.'); ?>)
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted">Jumlah (Qty)</label>
                            <input type="number" id="qty" class="form-control bg-light border-0 p-3" value="1" min="1" style="border-radius: 10px;">
                        </div>
                        <button type="button" onclick="tambahKeKeranjang()" class="btn btn-primary btn-add w-100 text-white">
                            <i class="fas fa-cart-plus me-2"></i>Masukan Keranjang
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="card card-input bg-primary text-white p-4">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-user-circle fa-3x opacity-50"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-0">Kasir Aktif:</h6>
                        <span class="fw-bold"><?= $_SESSION['username']; ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card card-cart p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-list text-primary me-2"></i>Daftar Belanja</h5>
                    <span class="text-muted small">ID Transaksi: #AUTO</span>
                </div>
                
                <div class="table-responsive" style="min-height: 250px;">
                    <table class="table" id="tabel-keranjang">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Harga Satuan</th>
                                <th width="100">Qty</th>
                                <th>Subtotal</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            </tbody>
                    </table>
                </div>

                <div class="total-display mt-4">
                    <h6 class="text-muted text-uppercase mb-1 small fw-bold">Total Pembayaran</h6>
                    <div id="total-harga">Rp 0</div>
                </div>

                <div class="mt-4 d-grid">
                    <button onclick="prosesBayar()" class="btn btn-success btn-pay shadow">
                        <i class="fas fa-cash-register me-2"></i>Selesaikan Transaksi & Cetak Struk
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({ duration: 800, once: true });

    let keranjang = [];

    function tambahKeKeranjang() {
        const select = document.getElementById('select_produk');
        const option = select.options[select.selectedIndex];
        
        if(!option.value) {
        Swal.fire({
            icon: 'info',
            title: 'Pilih Produk',
            text: 'Anda belum memilih barang untuk ditambahkan.',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
        return;
        }

        const item = {
            id: option.value,
            nama: option.getAttribute('data-nama'),
            harga: parseInt(option.getAttribute('data-harga')),
            qty: parseInt(document.getElementById('qty').value)
        };

        const index = keranjang.findIndex(k => k.id === item.id);
        if(index > -1) {
            keranjang[index].qty += item.qty;
        } else {
            keranjang.push(item);
        }

        updateTabel();
        // Reset form
        document.getElementById('qty').value = 1;
        select.selectedIndex = 0;
    }

    function updateTabel() {
        const tbody = document.querySelector('#tabel-keranjang tbody');
        tbody.innerHTML = '';
        let total = 0;

        keranjang.forEach((item, index) => {
            const subtotal = item.harga * item.qty;
            total += subtotal;
            tbody.innerHTML += `
                <tr class="item-row">
                    <td class="fw-bold text-dark">${item.nama}</td>
                    <td class="text-muted">Rp ${item.harga.toLocaleString('id-ID')}</td>
                    <td><span class="badge bg-primary px-3 rounded-pill">${item.qty}</span></td>
                    <td class="fw-bold">Rp ${subtotal.toLocaleString('id-ID')}</td>
                    <td class="text-center">
                        <button onclick="hapusItem(${index})" class="btn btn-sm btn-outline-danger border-0">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
            `;
        });

        document.getElementById('total-harga').innerText = 'Rp ' + total.toLocaleString('id-ID');
    }

    function hapusItem(index) {
        keranjang.splice(index, 1);
        updateTabel();
    }

    function prosesBayar() {
    if (keranjang.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Keranjang Kosong',
            text: 'Silakan pilih produk terlebih dahulu!',
            confirmButtonColor: '#4e73df'
        });
        return;
    }
    
    const totalHarga = keranjang.reduce((sum, item) => sum + (item.harga * item.qty), 0);

    // KONFIRMASI PEMBAYARAN (POP-UP)
    Swal.fire({
        title: 'Konfirmasi Bayar',
        html: `Total yang harus dibayar: <br><h2 class="text-primary fw-bold mt-2">Rp ${totalHarga.toLocaleString('id-ID')}</h2>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#1cc88a',
        cancelButtonColor: '#e74a3b',
        confirmButtonText: '<i class="fas fa-check me-1"></i> Proses Sekarang',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        borderRadius: '15px'
    }).then((result) => {
        if (result.isConfirmed) {
            // Tampilkan Loading saat proses kirim data
            Swal.fire({
                title: 'Memproses...',
                text: 'Sedang menyimpan transaksi',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading() }
            });

            fetch('proses_transaksi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    keranjang: keranjang,
                    total: totalHarga
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // NOTIFIKASI BERHASIL (POP-UP)
                    Swal.fire({
                        icon: 'success',
                        title: 'Transaksi Berhasil!',
                        text: 'Struk akan otomatis terbuka.',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    }).then(() => {
                        window.open('cetak_struk.php?id=' + data.id_transaksi, '_blank');
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Transaksi Gagal',
                        text: data.message,
                        confirmButtonColor: '#4e73df'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'System Error',
                    text: 'Gagal terhubung ke server.',
                    confirmButtonColor: '#4e73df'
                });
            });
        }
    });
}
</script>
</body>
</html>