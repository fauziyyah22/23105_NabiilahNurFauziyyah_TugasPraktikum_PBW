<?php
// File ini tugasnya mengurus penyimpanan dan pengambilan data tiket pesawat pakai session

// Mulai session baru kalau belum ada yang aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Bikin array kosong di session untuk nyimpan data penerbangan kalau belum ada
if (!isset($_SESSION['flights'])) {
    $_SESSION['flights'] = [];
}

// Fungsi buat ngambil semua data penerbangan yang sudah disimpan
function getAllFlights() {
    return isset($_SESSION['flights']) ? $_SESSION['flights'] : [];
}

// Fungsi buat nambahin data penerbangan baru ke dalam session
function saveNewFlight($flightData) {
    // Masukin data baru ke dalam array session yang udah ada
    $_SESSION['flights'][] = $flightData;
    return true;
}

// Fungsi buat hapus data penerbangan berdasarkan kode tiket tertentu
function deleteFlight($ticketCode) {
    $newData = [];
    
    // Looping semua data penerbangan yang ada di session
    foreach ($_SESSION['flights'] as $flight) {
        // Kalo kode tiketnya beda, masukin ke array baru
        if ($flight['kode_tiket'] !== $ticketCode) {
            $newData[] = $flight;
        }
    }
    
    // Ganti data lama di session dengan data baru yang udah difilter
    $_SESSION['flights'] = $newData;
    return true;
}

// Fungsi buat hapus semua data penerbangan sekaligus
function deleteAllFlights() {
    // Reset array session jadi kosong lagi
    $_SESSION['flights'] = [];
    return true;
}

// Bagian ini bakal jalan kalau ada request dengan metode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil jenis aksi dari form yang dikirim
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    
    // Tentukan mau ngapain berdasarkan aksi yang dipilih
    switch ($action) {
        case 'save':
            // Proses penyimpanan data baru
            $flightData = [
                'tanggal' => date('Y-m-d H:i:s'),
                'maskapai' => $_POST['maskapai'],
                'asal' => $_POST['asal'],
                'tujuan' => $_POST['tujuan'],
                'kelas' => $_POST['kelas'],
                'kelas_nama' => $_POST['kelas_nama'],
                'jumlah_penumpang' => (int)$_POST['jumlah_penumpang'],
                'harga_tiket' => (int)$_POST['harga_tiket'],
                'pajak' => (int)$_POST['pajak'],
                'total' => (int)$_POST['total'],
                'metode_pembayaran' => $_POST['metode_pembayaran'],
                'kode_tiket' => $_POST['kode_tiket']
            ];
            
            // Panggil fungsi simpan data dan catat hasilnya
            $result = saveNewFlight($flightData);
            $_SESSION['status'] = $result ? 'success' : 'error';
            $_SESSION['message'] = $result ? 'Data berhasil disimpan' : 'Gagal menyimpan data';
            
            // Alihkan ke halaman daftar penerbangan
            header('Location: daftar_penerbangan.php');
            exit;
            
        case 'getAll':
            // Langsung aja ke halaman daftar, nanti datanya diambil langsung dari session
            header('Location: daftar_penerbangan.php');
            exit;
            
        case 'delete':
            // Proses hapus data berdasarkan kode tiket
            $ticketCode = $_POST['kode_tiket'];
            $result = deleteFlight($ticketCode);
            $_SESSION['status'] = $result ? 'success' : 'error';
            $_SESSION['message'] = $result ? 'Data berhasil dihapus' : 'Gagal menghapus data';
            
            // Alihkan ke halaman daftar lagi
            header('Location: daftar_penerbangan.php');
            exit;
            
        case 'deleteAll':
            // Proses hapus semua data penerbangan
            $result = deleteAllFlights();
            $_SESSION['status'] = $result ? 'success' : 'error';
            $_SESSION['message'] = $result ? 'Semua data berhasil dihapus' : 'Gagal menghapus data';
            
            // Alihkan ke halaman daftar
            header('Location: daftar_penerbangan.php');
            exit;
            
        default:
            // Kasus kalau aksinya nggak dikenali
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Action tidak valid';
            
            // Alihkan ke halaman utama
            header('Location: index.php');
            exit;
    }
}

// Kalo ada yang langsung akses file ini pakai metode GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Langsung diarahkan ke halaman daftar penerbangan
    header('Location: daftar_penerbangan.php');
    exit;
}