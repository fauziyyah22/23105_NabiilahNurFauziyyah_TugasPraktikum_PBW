<?php
session_start();

// Periksa status login
$is_logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;

// Jika sudah login, redirect ke index
if ($is_logged_in) {
    header("Location: index.php");
    exit();
}

// Ambil pesan jika ada
$message = isset($_SESSION['message']) ? $_SESSION['message'] : null;
unset($_SESSION['message']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Sistem Akademik</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --ungu: #8a2be2;
            --pink: #ff69b4;
            --ungu-muda: rgba(138, 43, 226, 0.5);
            --pink-muda: rgba(255, 105, 180, 0.5);
            --gelap: #1a1a2e;
            --abu: #e6e6e6;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            margin: 0;
            background: url('img.png') center/cover no-repeat fixed;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .auth-container {
            width: 90%;
            max-width: 1200px;
            background-color: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            display: flex;
            min-height: 80vh;
        }
        
        .auth-graphic {
            flex: 1;
            background: url('img.png') center/cover no-repeat;
            position: relative;
            display: flex;
            align-items: flex-end;
        }
        
        .auth-graphic::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, var(--ungu-muda), var(--pink-muda));
            backdrop-filter: blur(3px);
        }
        
        .graphic-content {
            position: relative;
            z-index: 2;
            color: white;
            padding: 3rem;
            width: 100%;
        }
        
        .graphic-content h1 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
        }
        
        .graphic-content p {
            font-size: 1rem;
            opacity: 0.9;
        }
        
        .auth-form {
            flex: 1;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .form-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--ungu);
            margin-bottom: 0.5rem;
        }
        
        .form-subtitle {
            color: #666;
            margin-bottom: 2rem;
        }
        
        .form-control {
            border: 2px solid #e0e0e0;
            padding: 0.75rem 1rem;
            margin-bottom: 1.5rem;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--ungu);
            box-shadow: 0 0 0 0.25rem rgba(138, 43, 226, 0.25);
        }
        
        .btn-auth {
            background: linear-gradient(45deg, var(--ungu), var(--pink));
            border: none;
            padding: 0.75rem;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: all 0.3s;
            border-radius: 8px;
        }
        
        .btn-auth:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(138, 43, 226, 0.3);
        }
        
        .auth-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: #666;
        }
        
        .auth-footer a {
            color: var(--ungu);
            font-weight: 600;
            text-decoration: none;
        }
        
        .auth-footer a:hover {
            color: var(--pink);
            text-decoration: underline;
        }
        
        /* Tampilan Mobile */
        @media (max-width: 992px) {
            .auth-container {
                flex-direction: column;
                width: 95%;
                min-height: auto;
            }
            
            .auth-graphic {
                min-height: 200px;
                flex: none;
            }
            
            .graphic-content {
                padding: 1.5rem;
            }
            
            .graphic-content h1 {
                font-size: 1.8rem;
            }
            
            .auth-form {
                padding: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-graphic">
            <div class="graphic-content">
                <h1>Selamat Datang Kembali</h1>
                <p>Masuk ke dashboard admin untuk mengelola sistem akademik</p>
            </div>
        </div>
        
        <div class="auth-form">
            <h1 class="form-title">Masuk Admin</h1>
            <p class="form-subtitle">Silakan masuk dengan akun admin Anda</p>
            
            <?php if ($message): ?>
                <div class="alert alert-<?= $message['type'] === 'success' ? 'success' : 'danger' ?>">
                    <?= $message['text'] ?>
                </div>
            <?php endif; ?>
            
            <form action="login-proccess.php" method="post">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" name="login" class="btn btn-auth">
                    <i class="fas fa-sign-in-alt me-2"></i> Masuk
                </button>
            </form>
            
            <div class="auth-footer">
                Belum punya akun? <a href="register.php">Daftar disini</a>
            </div>
        </div>
    </div>
</body>
</html>