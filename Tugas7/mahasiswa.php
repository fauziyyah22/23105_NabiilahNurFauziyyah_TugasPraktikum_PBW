<?php
session_start();
// Periksa apakah admin sudah login
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Simpan URL yang diminta untuk redirect setelah login
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    
    // Redirect ke halaman login
    header("Location: login.php");
    exit();
}

// Membuat koneksi ke database MySQL dengan parameter host, username, password, dan nama database
$conn = new mysqli("localhost", "root", "", "akademik");

// Memeriksa apakah koneksi berhasil, jika gagal akan menampilkan pesan error dan menghentikan eksekusi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Inisialisasi variabel untuk menyimpan pesan notifikasi (sukses/error)
$pesan = "";

// Mendefinisikan fungsi untuk membersihkan input dari karakter yang tidak diinginkan
// Fungsi ini penting untuk mencegah serangan XSS (Cross-Site Scripting) dan SQL Injection
function bersihkan_input($data) {
    $data = trim($data);                 // Menghapus spasi di awal dan akhir string
    $data = stripslashes($data);         // Menghapus backslash (\)
    $data = htmlspecialchars($data);     // Mengkonversi karakter khusus HTML menjadi entity
    return $data;
}

// PROSES TAMBAH DATA (CREATE)
// Cek apakah tombol 'tambah' pada form telah ditekan
if (isset($_POST['tambah'])) {
    // Mengambil dan membersihkan nilai dari form
    $npm = bersihkan_input($_POST['npm']);
    $nama = bersihkan_input($_POST['nama']);
    $jurusan = bersihkan_input($_POST['jurusan']);
    $alamat = bersihkan_input($_POST['alamat']);
    
    // Verifikasi apakah NPM sudah ada di database untuk mencegah duplikasi data
    $cek_npm = $conn->query("SELECT npm FROM mahasiswa WHERE npm = '$npm'");
    if ($cek_npm->num_rows > 0) {
        // Jika NPM sudah ada, tampilkan pesan error
        $pesan = "<div class='alert alert-danger'>NPM sudah digunakan!</div>";
    } else {
        // Jika NPM belum ada, lakukan insert data ke tabel mahasiswa
        $query = "INSERT INTO mahasiswa (npm, nama, jurusan, alamat) VALUES ('$npm', '$nama', '$jurusan', '$alamat')";
        if ($conn->query($query) === TRUE) {
            // Jika query berhasil, tampilkan pesan sukses
            $pesan = "<div class='alert alert-success'>Data mahasiswa berhasil ditambahkan</div>";
        } else {
            // Jika query gagal, tampilkan pesan error
            $pesan = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
        }
    }
}

// PROSES PEMBARUAN DATA (UPDATE)
// Cek apakah tombol 'update' pada form telah ditekan
if (isset($_POST['update'])) {
    // Mengambil dan membersihkan nilai dari form
    $npm = bersihkan_input($_POST['npm']);
    $nama = bersihkan_input($_POST['nama']);
    $jurusan = bersihkan_input($_POST['jurusan']);
    $alamat = bersihkan_input($_POST['alamat']);
    
    // Membuat dan mengeksekusi query update
    $query = "UPDATE mahasiswa SET nama='$nama', jurusan='$jurusan', alamat='$alamat' WHERE npm='$npm'";
    if ($conn->query($query) === TRUE) {
        // Jika query berhasil, tampilkan pesan sukses
        $pesan = "<div class='alert alert-success'>Data mahasiswa berhasil diupdate</div>";
    } else {
        // Jika query gagal, tampilkan pesan error
        $pesan = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }
}

// PROSES HAPUS DATA (DELETE)
// Cek apakah ada parameter 'hapus' pada URL
if (isset($_GET['hapus'])) {
    // Mengambil dan membersihkan nilai NPM dari parameter URL
    $npm = bersihkan_input($_GET['hapus']);
    
    // Membuat dan mengeksekusi query delete
    $query = "DELETE FROM mahasiswa WHERE npm='$npm'";
    if ($conn->query($query) === TRUE) {
        // Jika query berhasil, tampilkan pesan sukses
        $pesan = "<div class='alert alert-success'>Data mahasiswa berhasil dihapus</div>";
    } else {
        // Jika query gagal, tampilkan pesan error
        $pesan = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }
}

// Inisialisasi variabel untuk form edit
$npm_edit = "";
$nama_edit = "";
$jurusan_edit = "";
$alamat_edit = "";

// PROSES PENGAMBILAN DATA UNTUK EDIT
// Cek apakah ada parameter 'edit' pada URL
if (isset($_GET['edit'])) {
    // Mengambil dan membersihkan nilai NPM dari parameter URL
    $npm = bersihkan_input($_GET['edit']);
    // Membuat query untuk mengambil data mahasiswa berdasarkan NPM
    $query = "SELECT * FROM mahasiswa WHERE npm='$npm'";
    $result = $conn->query($query);
    if ($result->num_rows == 1) {
        // Jika data ditemukan, simpan ke variabel untuk ditampilkan di form
        $row = $result->fetch_assoc();
        $npm_edit = $row['npm'];
        $nama_edit = $row['nama'];
        $jurusan_edit = $row['jurusan'];
        $alamat_edit = $row['alamat'];
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Data Mahasiswa</title>
    
    <!-- Mengimpor Bootstrap CSS dari CDN untuk styling tampilan -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    
    <!-- Mengimpor Font Awesome untuk ikon-ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <!-- Mengimpor Material Icons dari Google (opsional) -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <!-- Mengimpor CSS kustom untuk styling tambahan -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-4">
        <!-- Judul halaman -->
        <h1 class="text-center mb-4">Kelola Data Mahasiswa</h1>
        <!-- Tombol untuk kembali ke menu utama -->
        <a href="index.php" class="btn btn-secondary mb-3">Kembali ke Menu Utama</a>
        
        <!-- Menampilkan pesan notifikasi (sukses/error) -->
        <?php echo $pesan; ?>
        
        <div class="row">
            <!-- Kolom kiri untuk form tambah/edit data -->
            <div class="col-md-4">
                <div class="card form-card">
                    <div class="card-header text-white">
                        <!-- Menampilkan judul form sesuai operasi (tambah/edit) -->
                        <?php echo isset($_GET['edit']) ? "Edit Data Mahasiswa" : "Tambah Data Mahasiswa"; ?>
                    </div>
                    <div class="card-body">
                        <!-- Form untuk input data mahasiswa -->
                        <form method="post" action="" id="form-mahasiswa">
                            <div class="mb-3">
                                <label for="npm" class="form-label">NPM (13 Karakter)</label>
                                <!-- Input untuk NPM dengan validasi maksimal 13 karakter -->
                                <input type="text" class="form-control" id="npm" name="npm" maxlength="13" 
                                    value="<?php echo $npm_edit; ?>" <?php echo isset($_GET['edit']) ? "readonly" : ""; ?> required>
                            </div>
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Lengkap</label>
                                <!-- Input untuk nama dengan validasi maksimal 50 karakter -->
                                <input type="text" class="form-control" id="nama" name="nama" maxlength="50" 
                                    value="<?php echo $nama_edit; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="jurusan" class="form-label">Jurusan</label>
                                <!-- Dropdown untuk pilihan jurusan -->
                                <select class="form-select" id="jurusan" name="jurusan" required>
                                    <option value="">Pilih Jurusan</option>
                                    <!-- Opsi jurusan dengan pengecekan untuk nilai terpilih saat mode edit -->
                                    <option value="Teknik Informatika" <?php echo ($jurusan_edit == "Teknik Informatika") ? "selected" : ""; ?>>Teknik Informatika</option>
                                    <option value="Sistem Operasi" <?php echo ($jurusan_edit == "Sistem Operasi") ? "selected" : ""; ?>>Sistem Operasi</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <!-- Textarea untuk alamat -->
                                <textarea class="form-control" id="alamat" name="alamat" rows="3" required><?php echo $alamat_edit; ?></textarea>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <!-- Menampilkan tombol yang berbeda berdasarkan mode (tambah/edit) -->
                                <?php if (isset($_GET['edit'])) : ?>
                                    <!-- Tombol update untuk mode edit -->
                                    <button type="submit" name="update" class="btn btn-warning">
                                        <i class="material-icons">save</i> Update
                                    </button>
                                    <!-- Tombol batal untuk kembali ke mode tambah -->
                                    <a href="mahasiswa.php" class="btn btn-secondary">
                                        <i class="material-icons">cancel</i> Batal
                                    </a>
                                <?php else : ?>
                                    <!-- Tombol simpan untuk mode tambah -->
                                    <button type="submit" name="tambah" class="btn btn-primary">
                                        <i class="material-icons">save</i> Simpan
                                    </button>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Kolom kanan untuk menampilkan data mahasiswa -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        Daftar Mahasiswa
                    </div>
                    <div class="card-body">
                        <!-- Input pencarian untuk filter data tabel -->
                        <div class="mb-3">
                            <input type="text" class="form-control search-table" placeholder="Cari data..." data-table="table-data">
                        </div>
                        <!-- Tabel data mahasiswa dengan fitur responsif -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="table-data">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>NPM</th>
                                        <th>Nama</th>
                                        <th>Jurusan</th>
                                        <th>Alamat</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Query untuk mengambil semua data mahasiswa, diurutkan berdasarkan nama
                                    $query = "SELECT * FROM mahasiswa ORDER BY nama ASC";
                                    $result = $conn->query($query);
                                    
                                    if ($result->num_rows > 0) {
                                        // Jika ada data, tampilkan semua data dalam bentuk tabel
                                        $no = 1;
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . $no++ . "</td>";
                                            echo "<td>" . $row['npm'] . "</td>";
                                            echo "<td>" . $row['nama'] . "</td>";
                                            echo "<td>" . $row['jurusan'] . "</td>";
                                            echo "<td>" . $row['alamat'] . "</td>";
                                            echo "<td class='text-nowrap'>
                                                    <!-- Tombol edit dengan ikon -->
                                                    <a href='mahasiswa.php?edit=" . $row['npm'] . "' class='btn btn-edit btn-sm btn-action' title='Edit'>
                                                    <i class='fas fa-edit'></i>
                                                    </a>
                                                    <!-- Tombol hapus dengan ikon dan konfirmasi -->
                                                    <a href='mahasiswa.php?hapus=" . $row['npm'] . "' class='btn btn-delete btn-sm btn-action' title='Hapus' onclick='return confirm(\"Apakah Anda yakin ingin menghapus data ini?\")'>
                                                    <i class='fas fa-trash-alt'></i>
                                                    </a>
                                                </td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        // Jika tidak ada data, tampilkan pesan
                                        echo "<tr><td colspan='6' class='text-center'>Belum ada data mahasiswa</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Mengimpor file JavaScript Bootstrap dari CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <!-- Mengimpor file JavaScript Bootstrap lagi (duplikasi yang tidak perlu - kesalahan koding) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <!-- Mengimpor file JavaScript kustom -->
    <script src="script.js"></script>

    <!-- Container untuk notifikasi toast (popup) -->
    <div id="toast-container" class="toast-container position-fixed bottom-0 end-0 p-3"></div>

    <!-- Footer halaman -->
    <footer class="footer">
        <div class="container">
            <!-- Menampilkan tahun saat ini secara dinamis -->
            <p>&copy; <?php echo date('Y'); ?> Sistem Informasi Akademik</p>
        </div>
    </footer>
</body>
</html>

<?php
// Menutup koneksi database untuk menghemat sumber daya server
$conn->close();
?>