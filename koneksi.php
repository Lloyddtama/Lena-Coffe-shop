<?php
$conn = mysqli_connect("localhost", "root", "", "db_kasir");

if (!$conn) {
    die("Gagal terhubung ke database: " . mysqli_connect_error());
}
?>