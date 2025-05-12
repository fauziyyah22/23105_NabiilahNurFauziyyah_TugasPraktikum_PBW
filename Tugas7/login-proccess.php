<?php
session_start();
require_once 'config.php';

function redirect($url, $message = null) {
    if ($message) {
        $_SESSION['message'] = $message;
    }
    header("Location: $url");
    exit();
}

// Proses Login
if (isset($_POST['login'])) {
    $username = bersihkan_input($_POST['username']);
    $password = bersihkan_input($_POST['password']);
    
    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $admin = $result->fetch_assoc();
        
        if (password_verify($password, $admin['password'])) {
            // Set session variables
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            $_SESSION['admin_nama'] = $admin['nama'];
            $_SESSION['logged_in'] = true;
            
            // Update last login
            $update_stmt = $conn->prepare("UPDATE admin SET last_login = NOW() WHERE id = ?");
            $update_stmt->bind_param("i", $admin['id']);
            $update_stmt->execute();
            
            // Redirect ke index setelah login berhasil
            redirect('index.php', ['type' => 'success', 'text' => 'Login berhasil!']);
        } else {
            redirect('login.php', ['type' => 'error', 'text' => 'Password salah!']);
        }
    } else {
        redirect('login.php', ['type' => 'error', 'text' => 'Username tidak ditemukan!']);
    }
}

// Proses Register
if (isset($_POST['register'])) {
    $nama = bersihkan_input($_POST['nama']);
    $email = bersihkan_input($_POST['email']);
    $username = bersihkan_input($_POST['username']);
    $password = bersihkan_input($_POST['password']);
    $confirm_password = bersihkan_input($_POST['confirm_password']);
    
    // Validasi
    if ($password !== $confirm_password) {
        redirect('register.php', ['type' => 'error', 'text' => 'Password dan konfirmasi password tidak sama!']);
    }
    
    // Cek username dan email sudah ada
    $stmt = $conn->prepare("SELECT id FROM admin WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        redirect('register.php', ['type' => 'error', 'text' => 'Username atau email sudah digunakan!']);
    }
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert admin baru
    $stmt = $conn->prepare("INSERT INTO admin (nama, email, username, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nama, $email, $username, $hashed_password);
    
    if ($stmt->execute()) {
        redirect('login.php', ['type' => 'success', 'text' => 'Registrasi berhasil! Silakan login.']);
    } else {
        redirect('register.php', ['type' => 'error', 'text' => 'Gagal registrasi: ' . $conn->error]);
    }
}

// Proses Logout
if (isset($_GET['logout'])) {
    // Hapus semua data session
    $_SESSION = array();
    
    // Hapus session cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    // Hancurkan session
    session_destroy();
    
    // Redirect ke halaman login
    redirect('login.php', ['type' => 'success', 'text' => 'Anda telah logout.']);
}

// Jika akses langsung ke auth.php tanpa aksi
redirect('login.php');
