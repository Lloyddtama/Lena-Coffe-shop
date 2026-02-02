<?php
include 'koneksi.php';

$username = 'admin';
$password = password_hash('123', PASSWORD_DEFAULT); // Enkripsi otomatis
$role = 'admin';

$query = mysqli_query($conn, "INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role')");

if ($query) {
    echo "User berhasil dibuat! Silakan login dengan user: admin, pass: 123";
} else {
    echo "Gagal atau User sudah ada.";
}
?>