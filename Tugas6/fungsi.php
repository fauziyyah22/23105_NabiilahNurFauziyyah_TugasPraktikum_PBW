<?php
// Data Bandara Asal dan Pajak
function getBandaraAsal() {
    // Membuat array asosiatif yang berisi nama bandara asal sebagai kunci dan nilai pajak bandara sebagai nilai
    $bandaraAsal = [
        'Soekarna Hatta' => 65000,        // Bandara Soekarno Hatta dengan pajak Rp 65.000
        'Husein Sastranegara' => 50000,   // Bandara Husein Sastranegara dengan pajak Rp 50.000
        'Abdul Rachman Saleh' => 40000,   // Bandara Abdul Rachman Saleh dengan pajak Rp 40.000
        'Juanda' => 30000                 // Bandara Juanda dengan pajak Rp 30.000
    ];
    
    // Mengurutkan bandara asal berdasarkan nama (kunci) secara alfabetis menggunakan fungsi ksort()
    ksort($bandaraAsal);
    return $bandaraAsal;    // Mengembalikan array bandara yang sudah diurutkan
}

// Data Bandara Tujuan dan Pajak
function getBandaraTujuan() {
    // Membuat array asosiatif yang berisi nama bandara tujuan sebagai kunci dan nilai pajak bandara sebagai nilai
    $bandaraTujuan = [
        'Ngurah Rai' => 85000,            // Bandara Ngurah Rai dengan pajak Rp 85.000
        'Hasanuddin' => 70000,            // Bandara Hasanuddin dengan pajak Rp 70.000
        'Inanwatan' => 90000,             // Bandara Inanwatan dengan pajak Rp 90.000
        'Sultan Iskandar Muda' => 60000   // Bandara Sultan Iskandar Muda dengan pajak Rp 60.000
    ];
    
    // Mengurutkan bandara tujuan berdasarkan nama (kunci) secara alfabetis menggunakan fungsi ksort()
    ksort($bandaraTujuan);
    return $bandaraTujuan;  // Mengembalikan array bandara yang sudah diurutkan
}

// Data harga berdasarkan kelas
function getHargaKelas() {
    // Mengembalikan array asosiatif yang berisi kode kelas sebagai kunci dan harga tiket sebagai nilai
    return [
        'economy' => 1000000,             // Kelas Ekonomi dengan harga Rp 1.000.000
        'premium_economy' => 3500000,     // Kelas Ekonomi Premium dengan harga Rp 3.500.000
        'business' => 7000000,            // Kelas Bisnis dengan harga Rp 7.000.000
        'first' => 10000000               // Kelas First Class dengan harga Rp 10.000.000
    ];
}

// Mendapatkan nama kelas berdasarkan kode kelas
function getNamaKelas($kodeKelas) {
    // Membuat array asosiatif yang memetakan kode kelas ke nama kelas yang lebih mudah dibaca
    $namaKelas = [
        'economy' => 'Ekonomi',           // Kode 'economy' menjadi 'Ekonomi'
        'premium_economy' => 'Ekonomi Premium', // Kode 'premium_economy' menjadi 'Ekonomi Premium'
        'business' => 'Bisnis',           // Kode 'business' menjadi 'Bisnis'
        'first' => 'First Class'          // Kode 'first' menjadi 'First Class'
    ];
    
    // Memeriksa apakah kode kelas ada dalam array, jika ada kembalikan nama kelasnya, jika tidak kembalikan kode aslinya
    return isset($namaKelas[$kodeKelas]) ? $namaKelas[$kodeKelas] : $kodeKelas;
}

// Mengambil nilai pajak berdasarkan bandara asal
function getPajakAsal($bandaraAsal) {
    // Mengambil data bandara asal dan pajak dengan memanggil fungsi getBandaraAsal()
    $dataBandaraAsal = getBandaraAsal();
    
    // Memeriksa apakah bandara asal ada dalam data, jika ada kembalikan nilai pajaknya, jika tidak kembalikan 0
    return isset($dataBandaraAsal[$bandaraAsal]) ? $dataBandaraAsal[$bandaraAsal] : 0;
}

// Mengambil nilai pajak berdasarkan bandara tujuan
function getPajakTujuan($bandaraTujuan) {
    // Mengambil data bandara tujuan dan pajak dengan memanggil fungsi getBandaraTujuan()
    $dataBandaraTujuan = getBandaraTujuan();
    
    // Memeriksa apakah bandara tujuan ada dalam data, jika ada kembalikan nilai pajaknya, jika tidak kembalikan 0
    return isset($dataBandaraTujuan[$bandaraTujuan]) ? $dataBandaraTujuan[$bandaraTujuan] : 0;
}

// Menghitung total pajak per orang
function hitungTotalPajak($bandaraAsal, $bandaraTujuan) {
    // Mengambil nilai pajak bandara asal dengan memanggil fungsi getPajakAsal()
    $pajakAsal = getPajakAsal($bandaraAsal);
    
    // Mengambil nilai pajak bandara tujuan dengan memanggil fungsi getPajakTujuan()
    $pajakTujuan = getPajakTujuan($bandaraTujuan);
    
    // Menghitung total pajak dengan menjumlahkan pajak asal dan pajak tujuan
    return $pajakAsal + $pajakTujuan;
}

// Menghitung harga tiket berdasarkan kelas
function hitungHargaTiket($kelas) {
    // Mengambil data harga kelas dengan memanggil fungsi getHargaKelas()
    $hargaKelas = getHargaKelas();
    
    // Memeriksa apakah kelas penerbangan ada dalam data, jika ada kembalikan harganya, jika tidak kembalikan 0
    return isset($hargaKelas[$kelas]) ? $hargaKelas[$kelas] : 0;
}

// Fungsi format mata uang Rupiah
function formatRupiah($angka) {
    // Mengubah format angka menjadi format mata uang Rupiah
    // number_format() mengubah angka dengan pemisah ribuan berupa titik dan tanpa desimal
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

// Fungsi untuk generate kode tiket unik
function generateKodeTiket() {
    $prefix = 'AS';   // Awalan kode tiket 'AS' yang mewakili AetherSky
    
    // Membuat string acak sepanjang 6 karakter dari hasil hash MD5 yang diubah menjadi huruf kapital
    $random = strtoupper(substr(md5(uniqid(rand(), true)), 0, 6));
    
    // Mengambil tanggal saat ini dalam format tahun-bulan-tanggal (yymmdd)
    $timestamp = date('ymd');
    
    // Menggabungkan semua komponen untuk membuat kode tiket yang unik
    // Format: AS + tanggal (yymmdd) + 6 karakter acak
    return $prefix . $timestamp . $random;
}