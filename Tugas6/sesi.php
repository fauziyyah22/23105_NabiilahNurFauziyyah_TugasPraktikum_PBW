<?php
// File khusus buat ngurusin penyimpanan dan pengambilan data pake session

// Mulai session kalo belum ada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Bikin array kosong di session untuk data penerbangan kalo belum ada
if (!isset($_SESSION['flights'])) {
    $_SESSION['flights'] = [];
}

// Fungsi buat ngambil semua data penerbangan dari session
function getAllFlights() {
    return isset($_SESSION['flights']) ? $_SESSION['flights'] : [];
}

// Fungsi buat nambah data penerbangan baru ke session
function saveNewFlight($flightData) {
    // Tambahin data ke array di session
    $_SESSION['flights'][] = $flightData;
    return true;
}

// Fungsi buat hapus satu data berdasarkan kode tiket
function deleteFlight($ticketCode) {
    $newData = [];
    
    // Looping semua data yang ada
    foreach ($_SESSION['flights'] as $flight) {
        // Kalo kode tiketnya beda, simpan ke array baru
        if ($flight['kode_tiket'] !== $ticketCode) {
            $newData[] = $flight;
        }
    }
    
    // Ganti data lama di session dengan data baru yang udah difilter
    $_SESSION['flights'] = $newData;
    return true;
}

// Fungsi buat hapus semua data penerbangan
function deleteAllFlights() {
    // Kosongin array di session
    $_SESSION['flights'] = [];
    return true;
}

// Kode yang bakal jalan kalo ada request AJAX dengan metode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json'); // Set header response jadi JSON
    
    // Ambil jenis aksi dari data yang dikirim
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    
    // Tentukan mau ngapain berdasarkan aksi
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
            
            // Simpan data dan kirim hasil ke client
            $result = saveNewFlight($flightData);
            echo json_encode(['success' => $result]);
            break;
            
        case 'getAll':
            // Ambil semua data penerbangan dan kirim ke client
            $flights = getAllFlights();
            echo json_encode(['success' => true, 'data' => $flights]);
            break;
            
        case 'delete':
            // Hapus data berdasarkan kode tiket
            $ticketCode = $_POST['kode_tiket'];
            $result = deleteFlight($ticketCode);
            echo json_encode(['success' => $result]);
            break;
            
        case 'deleteAll':
            // Hapus semua data penerbangan
            $result = deleteAllFlights();
            echo json_encode(['success' => $result]);
            break;
            
        default:
            // Kalo aksinya nggak dikenali
            echo json_encode(['success' => false, 'message' => 'Action tidak valid']);
            break;
    }
    
    exit; // Hentikan eksekusi script
}