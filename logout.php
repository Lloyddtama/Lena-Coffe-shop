<?php
session_start();
// Hapus semua data session
$_SESSION = [];
session_unset();
session_destroy();

// Arahkan ke login dengan pesan sukses
header("Location: login.php?pesan=logout");
exit;