<?php
include 'fungsi.php'; // Ambil file fungsi buat dipake di file ini

// Mulai session baru kalo belum ada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Proses data kalo ada yang dikirim lewat metode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Cek apakah semua field yang wajib diisi sudah terisi
    if (
        empty($_POST['maskapai']) || 
        empty($_POST['asal']) || 
        empty($_POST['tujuan']) || 
        empty($_POST['kelas']) || 
        empty($_POST['jumlah_penumpang']) ||
        empty($_POST['payment_method'])
    ) {
        // Simpan pesan error ke session
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Semua field harus diisi!';
        
        // Kembalikan user ke halaman form
        header('Location: form_tiket.php');
        exit;
    }

    // Ambil data yang dikirim dari form dan bersihin dari karakter berbahaya
    $maskapai = htmlspecialchars($_POST['maskapai']);
    $bandaraAsal = htmlspecialchars($_POST['asal']);
    $bandaraTujuan = htmlspecialchars($_POST['tujuan']);
    $kelas = htmlspecialchars($_POST['kelas']);
    $jumlahPenumpang = (int)$_POST['jumlah_penumpang']; // Ubah ke tipe integer
    $metode_pembayaran = htmlspecialchars($_POST['payment_method']);
    
    // Hitung harga tiket berdasarkan kelas yang dipilih
    $hargaTiket = hitungHargaTiket($kelas);
    
    // Hitung total pajak per penumpang berdasarkan bandara asal dan tujuan
    $pajakPerPenumpang = hitungTotalPajak($bandaraAsal, $bandaraTujuan);
    
    // Hitung total pajak untuk semua penumpang
    $totalPajak = $pajakPerPenumpang * $jumlahPenumpang;
    
    // Hitung total harga tiket untuk semua penumpang
    $totalHargaTiket = $hargaTiket * $jumlahPenumpang;
    
    // Jumlahkan total harga tiket dengan total pajak
    $totalHarga = $totalHargaTiket + $totalPajak;
    
    // Buat kode tiket unik untuk pesanan ini
    $kodeTiket = generateKodeTiket();

    // Siapkan semua data yang akan disimpan
    $flightData = [
        'nomor' => rand(10000, 99999), // Buat nomor acak 5 digit
        'tanggal' => date('Y-m-d H:i:s', strtotime('+5 hours')), // Tambah 5 jam dari waktu sekarang
        'maskapai' => $maskapai,
        'asal' => $bandaraAsal,
        'tujuan' => $bandaraTujuan,
        'kelas' => $kelas,
        'nama_kelas' => getNamaKelas($kelas), // Ambil nama kelas dari kode kelas
        'jumlah_penumpang' => $jumlahPenumpang,
        'harga' => $hargaTiket,
        'total_harga_tiket' => $totalHargaTiket,
        'pajak_per_penumpang' => $pajakPerPenumpang,
        'total_pajak' => $totalPajak,
        'total' => $totalHarga,
        'metode_pembayaran' => $metode_pembayaran,
        'kode_tiket' => $kodeTiket
    ];
    
    // Cek dulu apakah array flights udah ada di session
    if (!isset($_SESSION['flights'])) {
        $_SESSION['flights'] = []; // Kalo belum, bikin dulu array kosongnya
    }
    $_SESSION['flights'][] = $flightData; // Tambahin data baru ke array
    
    // Simpan status sukses dan data pesanan terakhir ke session
    $_SESSION['status'] = 'success';
    $_SESSION['last_flight_data'] = $flightData;

    // Arahkan user ke halaman hasil pemesanan
    header('Location: hasil_pemesanan.php');
    exit;
} else {
    // Kalo ada yang coba akses file ini pakai metode selain POST
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Metode tidak diijinkan';
    
    // Kembalikan ke halaman utama
    header('Location: index.php');
    exit;
}