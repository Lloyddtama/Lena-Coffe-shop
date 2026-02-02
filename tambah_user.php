<?php
session_start();
include 'koneksi.php';

if (isset($_POST['simpan'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $insert = mysqli_query($conn, "INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role')");

    if ($insert) {
        echo "<script>alert('User berhasil ditambah!'); window.location='user.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 card p-4 shadow-sm">
                <h4 class="mb-4">Tambah User Baru</h4>
                <form method="POST">
                    <div class="mb-3">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Role</label>
                        <select name="role" class="form-select">
                            <option value="kasir">Kasir</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <button type="submit" name="simpan" class="btn btn-primary w-100">Simpan User</button>
                    <a href="user.php" class="btn btn-link w-100 mt-2">Batal</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>