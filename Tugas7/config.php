<?php
/**
 * config.php - File konfigurasi untuk sistem akademik
 * 
 * File ini berisi konfigurasi database dan fungsi-fungsi umum
 * yang digunakan di seluruh aplikasi.
 * 
 * @author Tim Pengembang Sistem Akademik
 * @version 1.0
 * @package SistemAkademik
 */
// Konstanta konfigurasi database - Gunakan untuk mempermudah koneksi dan maintenance
// Sebaiknya ganti dengan kredensial yang sesuai dengan lingkungan produksi
define('DB_HOST', 'localhost');     // Alamat host database
define('DB_USER', 'root');           // Username database
define('DB_PASS', '');               // Password database
define('DB_NAME', 'akademik');       // Nama database akademik

/**
 * Fungsi untuk membuat koneksi ke database
 * 
 * @return mysqli Objek koneksi database
 * @throws Exception Jika koneksi database gagal
 */
function connectDB() {
    // Buat koneksi baru menggunakan mysqli
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Periksa apakah koneksi berhasil
    if ($conn->connect_error) {
        // Hentikan eksekusi dan tampilkan pesan kesalahan jika koneksi gagal
        die("Koneksi gagal: " . $conn->connect_error);
    }
    
    // Atur pengkodean karakter ke UTF-8 untuk mendukung berbagai bahasa
    $conn->set_charset("utf8");
    
    // Kembalikan objek koneksi yang berhasil
    return $conn;
}

/**
 * Fungsi untuk membersihkan input dari potensi serangan
 * 
 * @param string $data Input yang akan dibersihkan
 * @return string Input yang sudah dibersihkan
 */
// Fungsi untuk membersihkan input dari karakter berbahaya (mencegah SQL Injection dan XSS)
function bersihkan_input($data) {
    $data = trim($data);                 // Menghapus spasi di awal dan akhir string
    $data = stripslashes($data);         // Menghapus backslash
    $data = htmlspecialchars($data);     // Mengubah karakter HTML khusus menjadi entitas
    return $data;
}

// Cek apakah user sudah login untuk halaman admin
$admin_pages = ['index.php', 'mahasiswa.php', 'matakuliah.php', 'krs.php'];
$current_page = basename($_SERVER['PHP_SELF']);

if (in_array($current_page, $admin_pages) && !isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

/**
 * Fungsi untuk membuat notifikasi dengan gaya bootstrap
 * 
 * @param string $pesan Pesan notifikasi
 * @param string $tipe Tipe notifikasi (success, danger, warning, info)
 * @return string HTML notifikasi
 */
function buat_notifikasi($pesan, $tipe = 'success') {
    // Buat div notifikasi dengan kelas bootstrap yang sesuai
    return "<div class='alert alert-{$tipe}'>{$pesan}</div>";
}

/**
 * Fungsi untuk memeriksa keberadaan data di database
 * 
 * @param string $table Nama tabel
 * @param string $field Kolom yang akan diperiksa
 * @param string $value Nilai yang dicari
 * @return bool Apakah data ditemukan
 */
function cek_data_ada($table, $field, $value) {
    // Buat koneksi database
    $conn = connectDB();
    
    // Escape value untuk mencegah SQL Injection
    $value = $conn->real_escape_string($value);
    
    // Buat query untuk memeriksa data
    $query = "SELECT * FROM {$table} WHERE {$field} = '{$value}'";
    $result = $conn->query($query);
    
    // Periksa apakah ada baris yang dikembalikan
    $ada = $result->num_rows > 0;
    
    // Tutup koneksi
    $conn->close();
    
    return $ada;
}

/**
 * Fungsi untuk mengambil semua data dari sebuah tabel
 * 
 * @param string $table Nama tabel
 * @param string $order_by Kolom untuk pengurutan
 * @param string $order_direction Arah pengurutan
 * @return array Data yang diambil
 */
function ambil_semua_data($table, $order_by = '', $order_direction = 'ASC') {
    // Buat koneksi database
    $conn = connectDB();
    
    // Buat query dasar
    $query = "SELECT * FROM {$table}";
    
    // Tambahkan pengurutan jika diperlukan
    if (!empty($order_by)) {
        $query .= " ORDER BY {$order_by} {$order_direction}";
    }
    
    // Eksekusi query
    $result = $conn->query($query);
    
    // Simpan data dalam array
    $data = [];
    if ($result->num_rows > 0) {
        // Ambil setiap baris sebagai asosiatif array
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    
    // Tutup koneksi
    $conn->close();
    
    return $data;
}

/**
 * Fungsi untuk mengambil satu data dari tabel
 * 
 * @param string $table Nama tabel
 * @param string $field Kolom pencarian
 * @param string $value Nilai yang dicari
 * @return array|null Data yang ditemukan
 */
function ambil_satu_data($table, $field, $value) {
    // Buat koneksi database
    $conn = connectDB();
    
    // Escape value untuk mencegah SQL Injection
    $value = $conn->real_escape_string($value);
    
    // Buat query pencarian
    $query = "SELECT * FROM {$table} WHERE {$field} = '{$value}'";
    $result = $conn->query($query);
    
    // Inisialisasi data
    $data = null;
    
    // Ambil data jika ditemukan
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
    }
    
    // Tutup koneksi
    $conn->close();
    
    return $data;
}

/**
 * Fungsi untuk menghitung jumlah data dalam tabel
 * 
 * @param string $table Nama tabel
 * @param string $where Kondisi pencarian opsional
 * @return int Jumlah data
 */
function hitung_data($table, $where = '') {
    // Buat koneksi database
    $conn = connectDB();
    
    // Buat query penghitungan
    $query = "SELECT COUNT(*) as total FROM {$table}";
    
    // Tambahkan kondisi jika ada
    if (!empty($where)) {
        $query .= " WHERE {$where}";
    }
    
    // Eksekusi query
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    
    // Tutup koneksi
    $conn->close();
    
    // Kembalikan total data
    return $row['total'];
}

/**
 * Fungsi untuk mencatat aktivitas dalam sistem
 * 
 * @param string $aksi Jenis aktivitas
 * @param string $keterangan Detail aktivitas
 * @param string $user Pengguna yang melakukan aktivitas
 */
function log_aktivitas($aksi, $keterangan = '', $user = 'System') {
    // Buat koneksi database
    $conn = connectDB();
    
    // Escape input untuk keamanan
    $aksi = $conn->real_escape_string($aksi);
    $keterangan = $conn->real_escape_string($keterangan);
    $user = $conn->real_escape_string($user);
    
    // Ambil alamat IP pengakses
    $ip = $_SERVER['REMOTE_ADDR'];
    
    // Dapatkan timestamp saat ini
    $tanggal = date('Y-m-d H:i:s');
    
    // Buat tabel log jika belum ada
    $conn->query("
        CREATE TABLE IF NOT EXISTS log_aktivitas (
            id INT(11) PRIMARY KEY AUTO_INCREMENT,
            aksi VARCHAR(50) NOT NULL,
            keterangan TEXT,
            user VARCHAR(50) NOT NULL,
            ip VARCHAR(20) NOT NULL,
            tanggal DATETIME NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    ");
    
    // Buat query penyimpanan log
    $query = "INSERT INTO log_aktivitas (aksi, keterangan, user, ip, tanggal) 
              VALUES ('{$aksi}', '{$keterangan}', '{$user}', '{$ip}', '{$tanggal}')";
    
    // Eksekusi query
    $conn->query($query);
    
    // Tutup koneksi
    $conn->close();
}

/**
 * Fungsi untuk memformat Nomor Pokok Mahasiswa (NPM)
 * 
 * @param string $npm Nomor Pokok Mahasiswa
 * @return string NPM yang diformat
 */
function format_npm($npm) {
    // Periksa panjang NPM
    if (strlen($npm) != 13) {
        return $npm;
    }
    
    // Format: 20xxxx-xx-xxxx
    // Pecah NPM menjadi bagian-bagian yang lebih mudah dibaca
    return substr($npm, 0, 6) . '-' . substr($npm, 6, 2) . '-' . substr($npm, 8);
}

/**
 * Fungsi untuk memformat Kode Mata Kuliah
 * 
 * @param string $kodemk Kode Mata Kuliah
 * @return string Kode Mata Kuliah yang diformat
 */
function format_kodemk($kodemk) {
    // Periksa panjang kode mata kuliah
    if (strlen($kodemk) != 6) {
        return $kodemk;
    }
    
    // Format: XX-XXX
    // Pecah kode mata kuliah menjadi format yang lebih mudah dibaca
    return strtoupper(substr($kodemk, 0, 2) . '-' . substr($kodemk, 2));
}


// Buat koneksi database default
// CATATAN: Pertimbangkan untuk menghindari koneksi global di versi selanjutnya
$conn = connectDB();
?>