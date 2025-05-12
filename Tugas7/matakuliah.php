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

// Membuat koneksi ke database MySQL dengan parameter server, username, password, dan nama database
$conn = new mysqli("localhost", "root", "", "akademik");

// Memeriksa status koneksi ke database, jika gagal akan menampilkan pesan error
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Mendeklarasikan variabel untuk menyimpan pesan notifikasi (sukses/error)
$pesan = "";

// Fungsi untuk membersihkan input dari karakter berbahaya (mencegah SQL Injection dan XSS)
function bersihkan_input($data) {
    $data = trim($data);                 // Menghapus spasi di awal dan akhir string
    $data = stripslashes($data);         // Menghapus backslash
    $data = htmlspecialchars($data);     // Mengubah karakter HTML khusus menjadi entitas
    return $data;
}

// BAGIAN CREATE - Dijalankan saat tombol dengan name="tambah" ditekan
if (isset($_POST['tambah'])) {
    // Mengambil dan membersihkan data dari form
    $kodemk = bersihkan_input($_POST['kodemk']);
    $nama = bersihkan_input($_POST['nama']);
    $jumlah_sks = bersihkan_input($_POST['jumlah_sks']);
    
    // Memeriksa apakah kode mata kuliah sudah ada di database (validasi duplikasi)
    $cek_kodemk = $conn->query("SELECT kodemk FROM matakuliah WHERE kodemk = '$kodemk'");
    if ($cek_kodemk->num_rows > 0) {
        // Jika kode mata kuliah sudah ada, tampilkan pesan error
        $pesan = "<div class='alert alert-danger'>Kode mata kuliah sudah digunakan!</div>";
    } else {
        // Jika kode mata kuliah belum ada, buat query untuk insert data
        $query = "INSERT INTO matakuliah (kodemk, nama, jumlah_sks) VALUES ('$kodemk', '$nama', $jumlah_sks)";
        if ($conn->query($query) === TRUE) {
            // Jika insert berhasil, tampilkan pesan sukses
            $pesan = "<div class='alert alert-success'>Data mata kuliah berhasil ditambahkan</div>";
        } else {
            // Jika insert gagal, tampilkan pesan error
            $pesan = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
        }
    }
}

// BAGIAN UPDATE - Dijalankan saat tombol dengan name="update" ditekan
if (isset($_POST['update'])) {
    // Mengambil dan membersihkan data dari form
    $kodemk = bersihkan_input($_POST['kodemk']);
    $nama = bersihkan_input($_POST['nama']);
    $jumlah_sks = bersihkan_input($_POST['jumlah_sks']);
    
    // Buat query untuk mengupdate data berdasarkan kode mata kuliah
    $query = "UPDATE matakuliah SET nama='$nama', jumlah_sks=$jumlah_sks WHERE kodemk='$kodemk'";
    if ($conn->query($query) === TRUE) {
        // Jika update berhasil, tampilkan pesan sukses
        $pesan = "<div class='alert alert-success'>Data mata kuliah berhasil diupdate</div>";
    } else {
        // Jika update gagal, tampilkan pesan error
        $pesan = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }
}

// BAGIAN DELETE - Dijalankan saat parameter hapus dikirim melalui URL
if (isset($_GET['hapus'])) {
    // Mengambil dan membersihkan kode mata kuliah dari parameter URL
    $kodemk = bersihkan_input($_GET['hapus']);
    
    // Buat query untuk menghapus data berdasarkan kode mata kuliah
    $query = "DELETE FROM matakuliah WHERE kodemk='$kodemk'";
    if ($conn->query($query) === TRUE) {
        // Jika delete berhasil, tampilkan pesan sukses
        $pesan = "<div class='alert alert-success'>Data mata kuliah berhasil dihapus</div>";
    } else {
        // Jika delete gagal, tampilkan pesan error
        $pesan = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }
}

// Menyiapkan variabel untuk form edit
$kodemk_edit = "";
$nama_edit = "";
$jumlah_sks_edit = "";

// BAGIAN EDIT - Dijalankan saat parameter edit dikirim melalui URL
if (isset($_GET['edit'])) {
    // Mengambil dan membersihkan kode mata kuliah dari parameter URL
    $kodemk = bersihkan_input($_GET['edit']);
    // Buat query untuk mengambil data berdasarkan kode mata kuliah
    $query = "SELECT * FROM matakuliah WHERE kodemk='$kodemk'";
    $result = $conn->query($query);
    if ($result->num_rows == 1) {
        // Jika data ditemukan, ambil data dan simpan ke variabel untuk diisi di form
        $row = $result->fetch_assoc();
        $kodemk_edit = $row['kodemk'];
        $nama_edit = $row['nama'];
        $jumlah_sks_edit = $row['jumlah_sks'];
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Data Mahasiswa</title>
    
    <!-- Memuat file CSS Bootstrap dari CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    
    <!-- Memuat Font Awesome untuk ikon-ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <!-- Memuat Material Icons dari Google (opsional) -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <!-- Memuat file CSS kustom -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-4">
        <!-- Judul halaman -->
        <h1 class="text-center mb-4">Kelola Data Mata Kuliah</h1>
        <!-- Tombol kembali ke menu utama -->
        <a href="index.php" class="btn btn-secondary mb-3">Kembali ke Menu Utama</a>
        
        <!-- Menampilkan pesan notifikasi (sukses/error) -->
        <?php echo $pesan; ?>
        
        <div class="row">
            <!-- Bagian form input/edit data (lebar 4 kolom) -->
            <div class="col-md-4">
                <div class="card">
                    <!-- Header card yang menampilkan judul sesuai mode (tambah/edit) -->
                    <div class="card-header bg-success text-white">
                        <?php echo isset($_GET['edit']) ? "Edit Data Mata Kuliah" : "Tambah Data Mata Kuliah"; ?>
                    </div>
                    <div class="card-body">
                        <!-- Form untuk input/edit data mata kuliah dengan validasi -->
                        <form method="post" action="" id="form-matakuliah" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label for="kodemk" class="form-label">Kode Mata Kuliah (6 Karakter)</label>
                                <!-- Input kode mata kuliah, readonly jika dalam mode edit -->
                                <input type="text" class="form-control" id="kodemk" name="kodemk" maxlength="6" value="<?php echo $kodemk_edit; ?>" <?php echo isset($_GET['edit']) ? "readonly" : ""; ?> required>
                            </div>
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Mata Kuliah</label>
                                <!-- Input nama mata kuliah -->
                                <input type="text" class="form-control" id="nama" name="nama" maxlength="50" value="<?php echo $nama_edit; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="jumlah_sks" class="form-label">Jumlah SKS</label>
                                <!-- Input jumlah sks dengan batasan minimal 1 dan maksimal 6 -->
                                <input type="number" class="form-control" id="jumlah_sks" name="jumlah_sks" min="1" max="6" value="<?php echo $jumlah_sks_edit; ?>" required>
                            </div>
                            <?php if (isset($_GET['edit'])) : ?>
                                <!-- Tombol update untuk mode edit -->
                                <button type="submit" name="update" class="btn btn-warning">
                                    <i class="material-icons">save</i> Update
                                </button>
                                <!-- Tombol batal untuk mode edit -->
                                <a href="matakuliah.php" class="btn btn-secondary">
                                    <i class="material-icons">cancel</i> Batal
                                </a>
                            <?php else : ?>
                                <!-- Tombol simpan untuk mode tambah -->
                                <button type="submit" name="tambah" class="btn btn-success">
                                    <i class="material-icons">save</i> Simpan
                                </button>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Bagian tabel daftar data (lebar 8 kolom) -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        Daftar Mata Kuliah
                    </div>
                    <div class="card-body">
                        <!-- Kotak pencarian untuk tabel -->
                        <div class="mb-3">
                            <input type="text" class="form-control search-table" placeholder="Cari data..." data-table="table-data">
                        </div>
                        <!-- Container tabel dengan fitur responsive -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="table-data">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Kode MK</th>
                                        <th>Nama Mata Kuliah</th>
                                        <th>Jumlah SKS</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Query untuk mengambil semua data mata kuliah dan diurutkan berdasarkan nama
                                    $query = "SELECT * FROM matakuliah ORDER BY nama ASC";
                                    $result = $conn->query($query);
                                    
                                    // Cek apakah ada data yang ditemukan
                                    if ($result->num_rows > 0) {
                                        $no = 1; // Inisialisasi nomor urut
                                        // Looping untuk menampilkan setiap baris data
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . $no++ . "</td>"; // Menampilkan nomor urut
                                            echo "<td>" . $row['kodemk'] . "</td>"; // Menampilkan kode MK
                                            echo "<td>" . $row['nama'] . "</td>"; // Menampilkan nama mata kuliah
                                            echo "<td>" . $row['jumlah_sks'] . "</td>"; // Menampilkan jumlah SKS
                                            // Kolom aksi dengan tombol edit dan hapus
                                            echo "<td class='text-nowrap'>
                                                    <a href='matakuliah.php?edit=" . $row['kodemk'] . "' class='btn btn-edit btn-sm btn-action' title='Edit'>
                                                    <i class='fas fa-edit'></i>
                                                    </a>
                                                    <a href='matakuliah.php?hapus=" . $row['kodemk'] . "' class='btn btn-delete btn-sm btn-action' title='Hapus' onclick='return confirm(\"Apakah Anda yakin ingin menghapus data ini?\")'>
                                                    <i class='fas fa-trash-alt'></i>
                                                    </a>
                                                </td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        // Tampilkan pesan jika tidak ada data
                                        echo "<tr><td colspan='5' class='text-center'>Belum ada data mata kuliah</td></tr>";
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
    
    <!-- Memuat file JavaScript Bootstrap dari CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <!-- File JavaScript Bootstrap diload 2 kali (error duplikasi) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <!-- Memuat file JavaScript kustom -->
    <script src="script.js"></script>

    <!-- Container untuk notifikasi toast yang akan ditampilkan -->
    <div id="toast-container" class="toast-container position-fixed bottom-0 end-0 p-3"></div>

    <!-- Bagian footer website -->
    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Sistem Informasi Akademik</p>
        </div>
    </footer>
</body>
</html>

<?php
// Menutup koneksi ke database
$conn->close();
?>