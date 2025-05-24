<?php
// Menentukan alamat host database (server database)
// "localhost" berarti database berada di server yang sama dengan aplikasi
$db_host = "localhost";

// Menentukan username untuk koneksi ke database MySQL
// "root" adalah user default dengan privilese penuh di MySQL
$db_user = "root";

// Menentukan password untuk user database
// String kosong "" berarti tidak ada password (konfigurasi development)
$db_pass = "";

// Menentukan nama database yang akan digunakan
// "book_store" adalah nama database yang berisi tabel-tabel untuk aplikasi toko buku
$db_name = "book_store";

// Membuat koneksi ke database MySQL menggunakan class mysqli
// Parameter: host, username, password, nama database
// Objek koneksi ini akan digunakan untuk menjalankan query database
$koneksi = new mysqli($db_host, $db_user, $db_pass, $db_name);