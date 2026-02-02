<?php
session_start();
include 'koneksi.php';

// Jika sudah login, tendang ke halaman masing-masing
if (isset($_SESSION['login'])) {
    if ($_SESSION['role'] == 'admin') { header("Location: index.php"); } 
    else { header("Location: transaksi.php"); }
    exit;
}

$error = ""; 

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = trim($_POST['password']);

    $result = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
    
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            $_SESSION['login'] = true;
            $_SESSION['id_user'] = $row['id_user'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];

            if ($row['role'] == 'admin') {
                header("Location: index.php");
            } else {
                header("Location: transaksi.php");
            }
            exit;
        } else {
            $error = "Password yang Anda masukkan salah.";
        }
    } else {
        $error = "Username tidak terdaftar di sistem.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Kasir Kita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        :root {
            --primary-grad: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        body { 
            background: #f4f7fe;
            background-image: radial-gradient(circle at 20% 20%, rgba(102, 126, 234, 0.1) 0%, transparent 40%),
                              radial-gradient(circle at 80% 80%, rgba(118, 75, 162, 0.1) 0%, transparent 40%);
            font-family: 'Inter', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
        }

        .login-container {
            max-width: 450px;
            width: 90%;
            margin: auto;
        }

        .card-login { 
            border: none;
            border-radius: 24px;
            background: #ffffff;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .login-header {
            background: var(--primary-grad);
            padding: 40px;
            text-align: center;
            color: white;
        }

        .login-body {
            padding: 40px;
        }

        .form-control {
            border-radius: 12px;
            padding: 12px 20px;
            background-color: #f8f9fa;
            border: 2px solid #f8f9fa;
            transition: all 0.3s;
        }

        .form-control:focus {
            background-color: #fff;
            border-color: #667eea;
            box-shadow: none;
        }

        .btn-login {
            background: var(--primary-grad);
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-weight: 700;
            letter-spacing: 0.5px;
            transition: all 0.3s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
            color: white;
        }

        .input-group-text {
            border-radius: 12px 0 0 12px;
            background-color: #f8f9fa;
            border: 2px solid #f8f9fa;
            color: #764ba2;
        }

        .alert {
            border-radius: 12px;
            font-size: 0.85rem;
            border: none;
        }
    </style>
</head>
<body>

<div class="login-container" data-aos="zoom-in">
    <div class="card card-login">
        <div class="login-header">
            <div class="mb-3">
                <i class="fas fa-shopping-bag fa-3x"></i>
            </div>
            <h3 class="fw-bold mb-0">Lena Coffee</h3>
            <p class="small opacity-75 mb-0">Silakan masuk ke akun Anda</p>
        </div>
        
        <div class="login-body">
            <?php if($error != "") : ?>
                <div class="alert alert-danger d-flex align-items-center mb-4 shadow-sm" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <div><?= $error; ?></div>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">USERNAME</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" name="username" class="form-control" placeholder="USERNAME" required autocomplete="off">
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="form-label small fw-bold text-muted">PASSWORD</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="•••••" required>
                    </div>
                </div>

                <button type="submit" name="login" class="btn btn-primary btn-login w-100 mb-3 text-white">
                    MASUK SEKARANG <i class="fas fa-arrow-right ms-2"></i>
                </button>
            </form>
        </div>
    </div>
    <div class="text-center mt-4">
        <p class="text-muted small">© 2026 <strong>Lena Coffee</strong>. Managed by Farel Dwitama</p>
    </div>
</div>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({ duration: 1000 });
</script>
</body>
</html>