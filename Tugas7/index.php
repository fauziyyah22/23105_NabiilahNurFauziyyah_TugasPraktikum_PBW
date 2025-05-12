<?php
session_start();

// Periksa apakah admin belum login
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Simpan URL yang diminta untuk redirect setelah login
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    
    // Set pesan error jika mencoba akses langsung
    $_SESSION['message'] = ['type' => 'error', 'text' => 'Anda harus login terlebih dahulu'];
    
    // Redirect ke halaman login
    header("Location: login.php");
    exit();
}

$title = "Dashboard Admin";
require_once 'admin_header.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <!-- Deklarasi tipe dokumen dan bahasa indonesia -->
    <meta charset="UTF-8">
    <!-- Pengaturan viewport untuk responsivitas di perangkat mobile -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Judul halaman web -->
    <title>Kelola Data Mahasiswa</title>
    
    <!-- 
        Impor Bootstrap CSS 
        - Kerangka styling responsif 
        - Memudahkan desain antarmuka 
        - Versi 5.3.0 untuk dukungan modern 
    -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    
    <!-- 
        Impor Font Awesome 
        - Pustaka ikon profesional 
        - Versi 5.15.4 
        - Menambah visual pada tombol dan elemen 
    -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <!-- 
        CSS Kustom 
        - Styling tambahan spesifik aplikasi 
        - Memungkinkan penyesuaian desain 
    -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-4">
        <!-- 
            Header Halaman 
            - Judul utama sistem 
            - Terpusat dengan kelas Bootstrap 
        -->
        <header class="page-header">
            <div class="container">
                <h1 class="text-center">Sistem Informasi Akademik</h1>
            </div>
        </header>        

        <!-- 
            Baris Kartu Navigasi 
            - Grid responsif dengan 3 kolom 
            - Setiap kolom memiliki kartu dengan fungsi berbeda 
        -->
        <div class="row mb-4">
            <!-- Kartu Manajemen Mahasiswa -->
            <div class="col-md-4">
                <div class="card">
                    <!-- Header kartu dengan warna primer -->
                    <div class="card-header bg-primary text-white">
                        Data Mahasiswa
                    </div>
                    <div class="card-body">
                        <!-- Deskripsi singkat fungsionalitas -->
                        <p>Kelola data mahasiswa (NPM, Nama, Jurusan, Alamat)</p>
                        <!-- Tombol navigasi ke halaman manajemen mahasiswa -->
                        <a href="mahasiswa.php" class="btn btn-primary">Kelola Mahasiswa</a>
                    </div>
                </div>
            </div>
            
            <!-- Kartu Manajemen Mata Kuliah -->
            <div class="col-md-4">
                <div class="card">
                    <!-- Header kartu dengan warna sukses (hijau) -->
                    <div class="card-header bg-success text-white">
                        Data Mata Kuliah
                    </div>
                    <div class="card-body">
                        <!-- Deskripsi singkat fungsionalitas -->
                        <p>Kelola data mata kuliah (Kode, Nama, Jumlah SKS)</p>
                        <!-- Tombol navigasi ke halaman manajemen mata kuliah -->
                        <a href="matakuliah.php" class="btn btn-success">Kelola Mata Kuliah</a>
                    </div>
                </div>
            </div>
            
            <!-- Kartu Manajemen KRS -->
            <div class="col-md-4">
                <div class="card">
                    <!-- Header kartu dengan warna info (biru muda) -->
                    <div class="card-header bg-info text-white">
                        Data KRS
                    </div>
                    <div class="card-body">
                        <!-- Deskripsi singkat fungsionalitas -->
                        <p>Kelola data KRS (Mahasiswa dan Mata Kuliah)</p>
                        <!-- Tombol navigasi ke halaman manajemen KRS -->
                        <a href="krs.php" class="btn btn-info text-white">Kelola KRS</a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- 
            Kartu Daftar KRS 
            - Menampilkan tabel data KRS 
            - Menggunakan PHP untuk mengambil data dari database 
        -->
        <div class="card">
            <!-- Header kartu dengan warna gelap -->
            <div class="card-header bg-dark text-white">
                Daftar KRS Mahasiswa
            </div>
            <div class="card-body">
                <!-- 
                    Tabel data KRS 
                    - Menggunakan kelas Bootstrap untuk styling 
                    - Bordered dan striped untuk keterbacaan 
                -->
                <table id="table-data" class="table table-bordered table-striped">
                    <!-- Header tabel -->
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Lengkap</th>
                            <th>Mata Kuliah</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <!-- Isi tabel dihasilkan oleh PHP -->
                    <tbody>
                        <?php
                        // 
                        // Koneksi ke database MySQL
                        // Menggunakan mysqli untuk konektivitas 
                        //
                        
                        // Membuat koneksi dengan parameter:
                        // host, username, password, nama_database
                        $conn = new mysqli("localhost", "root", "", "akademik");
                        
                        // Memeriksa keberhasilan koneksi
                        // Jika koneksi gagal, script akan berhenti dan menampilkan pesan error
                        if ($conn->connect_error) {
                            die("Koneksi gagal: " . $conn->connect_error);
                        }
                        
                        // 
                        // Query SQL untuk mengambil data KRS 
                        // Menggunakan JOIN untuk menggabungkan data dari 3 tabel:
                        // - krs (tabel hubungan)
                        // - mahasiswa (data mahasiswa)
                        // - matakuliah (data mata kuliah)
                        //
                        $query = "SELECT k.id, m.npm, m.nama AS nama_mahasiswa, mk.kodemk, 
                                mk.nama AS nama_matakuliah, mk.jumlah_sks
                                FROM krs k
                                JOIN mahasiswa m ON k.mahasiswa_npm = m.npm
                                JOIN matakuliah mk ON k.matakuliah_kodemk = mk.kodemk
                                ORDER BY m.npm ASC";
                        
                        // Eksekusi query
                        $result = $conn->query($query);
                        
                        // Memeriksa apakah ada data yang dikembalikan
                        if ($result->num_rows > 0) {
                            // Variabel penomoran baris
                            $no = 1;
                            // Mengulang setiap baris hasil query
                            while ($row = $result->fetch_assoc()) {
                                // Membuat baris tabel untuk setiap record
                                echo "<tr>";
                                // Nomor urut
                                echo "<td>" . $no++ . "</td>";
                                // Nama mahasiswa dan NPM (dengan format sub-teks)
                                echo "<td>" . $row['nama_mahasiswa'] . "<br><small class='text-muted'>NPM: " . $row['npm'] . "</small></td>";
                                // Nama mata kuliah dan kode (dengan format sub-teks)
                                echo "<td>" . $row['nama_matakuliah'] . "<br><small class='text-muted'>Kode: " . $row['kodemk'] . "</small></td>";
                                // Keterangan detail (dengan pewarnaan)
                                echo "<td><span class='text-danger'>" . $row['nama_mahasiswa'] . "</span> Mengambil Mata Kuliah <span class='text-danger'>" . $row['nama_matakuliah'] . "</span> (" . $row['jumlah_sks'] . " SKS)</td>";
                                echo "</tr>";
                            }
                        } else {
                            // Jika tidak ada data, tampilkan pesan
                            echo "<tr><td colspan='4' class='text-center'>Belum ada data KRS</td></tr>";
                        }
                        
                        // Menutup koneksi database
                        $conn->close();
                        ?>
                    </tbody>
                </table>
                
                <!-- 
                    Kontainer untuk tombol cetak 
                    - Menggunakan flexbox untuk perataan kanan 
                -->
                <div class="card-body" id="krs-content">
                <div class="text-end mt-3">
                    <!-- 
                        Tombol cetak 
                        - Menggunakan ikon dari Font Awesome 
                        - Memanggil fungsi JavaScript untuk mencetak 
                    -->
                    <button class="btn btn-print" onclick="printData('table-data')">
                        <i class="fas fa-print"></i> Cetak Data KRS
                    </button>
                </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- 
        Impor JavaScript Bootstrap 
        - Diperlukan untuk fungsionalitas komponen Bootstrap 
        - Versi 5.3.0 
    -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    
    <!-- Catatan: Bootstrap JS diimpor dua kali (mungkin kesalahan) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    
    <!-- 
        Script kustom 
        - Berisi fungsi-fungsi JavaScript tambahan 
    -->
    <script src="script.js"></script>
    
    <script>
    // 
    // Fungsi untuk mencetak data tabel
    // Parameter: ID tabel yang akan dicetak
    //
    function printData(tableId) {
        // Ambil konten HTML dari tabel
        const tableContent = document.getElementById(tableId).outerHTML;
        
        // Buka jendela cetak baru
        const printWindow = window.open('', '_blank');
        
        // Tulis konten HTML ke jendela cetak
        printWindow.document.write(`
            <html>
            <head>
                <title>Cetak Data KRS</title>
                <!-- Impor CSS Bootstrap untuk styling cetak -->
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
                <style>
                    /* CSS khusus untuk halaman cetak */
                    body { padding: 20px; }
                    h2 { text-align: center; margin-bottom: 20px; }
                    .print-header { margin-bottom: 20px; text-align: center; }
                    /* Sembunyikan elemen non-cetak */
                    @media print {
                        .no-print { display: none; }
                    }
                </style>
            </head>
            <body>
                <!-- Header cetak dengan informasi tambahan -->
                <div class="print-header" style="text-align: center; width: 100%;">
                    <h2>Laporan Data KRS Mahasiswa</h2>
                    <p>Sistem Informasi Akademik | Dicetak pada: ${new Date().toLocaleDateString('id-ID', { 
                        weekday: 'long', 
                        year: 'numeric', 
                        month: 'long', 
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    })}</p>
                </div>  
                
                <!-- Sisipkan konten tabel -->
                ${tableContent}
                
                <!-- Tombol untuk mencetak dan menutup -->
                <div class="text-center mt-4 no-print">
                    <button class="btn btn-primary" onclick="window.print()">Print</button>
                    <button class="btn btn-secondary" onclick="window.close()">Tutup</button>
                </div>
            </body>
            </html>
        `);
        
        // Tutup dokumen
        printWindow.document.close();
    }

    // 
    // Cek parameter URL untuk pencetakan otomatis
    //
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('cetak_krs')) {
        // Jika parameter 'cetak_krs' ada, panggil fungsi cetak
        printData('table-data');
    }
    </script>

    <!-- 
        Kontainer untuk notifikasi toast 
        - Komponen Bootstrap untuk pesan pop-up 
    -->
    <div id="toast-container" class="toast-container position-fixed bottom-0 end-0 p-3"></div>

</body>
</html>

<?php require_once 'admin_footer.php'; ?>
