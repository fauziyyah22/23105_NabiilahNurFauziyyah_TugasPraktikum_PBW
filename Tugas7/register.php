<?php
session_start();

require_once 'config.php';
$message = isset($_SESSION['message']) ? $_SESSION['message'] : null;
unset($_SESSION['message']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Admin - Sistem Akademik</title>
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
            margin-bottom: 1rem;
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
        
        .password-strength {
            height: 4px;
            background-color: #e0e0e0;
            margin-bottom: 1.5rem;
            border-radius: 2px;
            overflow: hidden;
        }
        
        .strength-bar {
            height: 100%;
            width: 0%;
            transition: all 0.3s;
        }
        
        .password-hints {
            font-size: 0.8rem;
            color: #666;
            margin-bottom: 1.5rem;
        }
        
        .password-hints ul {
            padding-left: 1.25rem;
            margin-bottom: 0;
        }
        
        .password-hints li {
            margin-bottom: 0.25rem;
        }
        
        .password-hints li.valid {
            color: var(--ungu);
            font-weight: 500;
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
        
        /* Floating labels */
        .form-group {
            position: relative;
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            position: absolute;
            top: -10px;
            left: 15px;
            background: white;
            padding: 0 5px;
            font-size: 0.8rem;
            color: var(--ungu);
            font-weight: 500;
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
                <h1>Bergabung Dengan Tim Kami</h1>
                <p>Daftar sebagai admin untuk mengelola sistem akademik</p>
            </div>
        </div>
        
        <div class="auth-form">
            <h1 class="form-title">Daftar Admin</h1>
            <p class="form-subtitle">Buat akun admin baru</p>
            
            <?php if ($message): ?>
                <div class="alert alert-<?= $message['type'] === 'success' ? 'success' : 'danger' ?>">
                    <?= $message['text'] ?>
                </div>
            <?php endif; ?>
            
            <form action="auth.php" method="post">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama" name="nama" required placeholder=" ">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required placeholder=" ">
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required placeholder=" ">
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required placeholder=" ">
                    <div class="password-strength">
                        <div class="strength-bar" id="strengthBar"></div>
                    </div>
                    <div class="password-hints">
                        <ul>
                            <li id="lengthHint">Minimal 8 karakter</li>
                            <li id="upperHint">Setidaknya 1 huruf besar</li>
                            <li id="lowerHint">Setidaknya 1 huruf kecil</li>
                            <li id="numberHint">Setidaknya 1 angka</li>
                            <li id="specialHint">Setidaknya 1 karakter khusus</li>
                        </ul>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Konfirmasi Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required placeholder=" ">
                </div>
                
                <button type="submit" name="register" class="btn btn-auth">
                    <i class="fas fa-user-plus me-2"></i> Daftar Sekarang
                </button>
                
                <div class="auth-footer">
                    Sudah punya akun? <a href="login.php">Masuk disini</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Password strength checker
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            
            if (passwordInput) {
                passwordInput.addEventListener('input', checkPasswordStrength);
            }
            
            function checkPasswordStrength() {
                const password = passwordInput.value;
                const strengthBar = document.getElementById('strengthBar');
                const hints = {
                    length: document.getElementById('lengthHint'),
                    upper: document.getElementById('upperHint'),
                    lower: document.getElementById('lowerHint'),
                    number: document.getElementById('numberHint'),
                    special: document.getElementById('specialHint')
                };
                
                let strength = 0;
                
                // Check length
                if (password.length >= 8) {
                    strength += 20;
                    hints.length.classList.add('valid');
                } else {
                    hints.length.classList.remove('valid');
                }
                
                // Check uppercase letters
                if (/[A-Z]/.test(password)) {
                    strength += 20;
                    hints.upper.classList.add('valid');
                } else {
                    hints.upper.classList.remove('valid');
                }
                
                // Check lowercase letters
                if (/[a-z]/.test(password)) {
                    strength += 20;
                    hints.lower.classList.add('valid');
                } else {
                    hints.lower.classList.remove('valid');
                }
                
                // Check numbers
                if (/[0-9]/.test(password)) {
                    strength += 20;
                    hints.number.classList.add('valid');
                } else {
                    hints.number.classList.remove('valid');
                }
                
                // Check special characters
                if (/[^A-Za-z0-9]/.test(password)) {
                    strength += 20;
                    hints.special.classList.add('valid');
                } else {
                    hints.special.classList.remove('valid');
                }
                
                // Update strength bar
                strengthBar.style.width = strength + '%';
                
                // Change color based on strength
                if (strength < 40) {
                    strengthBar.style.backgroundColor = '#ff4757';
                } else if (strength < 80) {
                    strengthBar.style.backgroundColor = '#ffa502';
                } else {
                    strengthBar.style.backgroundColor = '#2ed573';
                }
            }
        });
    </script>
</body>
</html>