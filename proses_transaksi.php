<?php
ob_start(); // Mulai penampungan output
session_start();
include 'koneksi.php';

// Pastikan hanya mengirim JSON
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    $keranjang = $data['keranjang'];
    $total = $data['total'];
    $tgl = date('Y-m-d H:i:s');
    $id_user = $_SESSION['id_user']; 

    $query_transaksi = mysqli_query($conn, "INSERT INTO transaksi (tgl_transaksi, total_bayar, id_user) 
                                           VALUES ('$tgl', '$total', '$id_user')");
    
    if ($query_transaksi) {
        $id_transaksi = mysqli_insert_id($conn);

        foreach ($keranjang as $item) {
            $id_produk = $item['id'];
            $qty = $item['qty'];
            $harga = $item['harga'];
            $subtotal = $qty * $harga;

            mysqli_query($conn, "INSERT INTO detail_transaksi (id_transaksi, id_produk, jumlah, harga_satuan, subtotal) 
                                 VALUES ('$id_transaksi', '$id_produk', '$qty', '$harga', '$subtotal')");

            mysqli_query($conn, "UPDATE produk SET stok = stok - $qty WHERE id_produk = '$id_produk'");
        }

        // Hapus semua output sebelumnya (spasi/notice/echo liar)
        ob_end_clean(); 
        echo json_encode(['status' => 'success', 'id_transaksi' => $id_transaksi]);
    } else {
        ob_end_clean();
        echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
    }
} else {
    ob_end_clean();
    echo json_encode(['status' => 'error', 'message' => 'Data kosong']);
}
exit;