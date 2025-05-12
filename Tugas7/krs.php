<?php

// Memulai session PHP untuk menyimpan pesan sementara (flash message)
session_start();
// Periksa apakah admin sudah login
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Simpan URL yang diminta untuk redirect setelah login
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    
    // Redirect ke halaman login
    header("Location: login.php");
    exit();
}

// Membuat koneksi ke database MySQL dengan parameter: host, username, password, nama_database
$conn = new mysqli("localhost", "root", "", "akademik");

// Memeriksa apakah koneksi berhasil atau gagal
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error); // Menghentikan program jika koneksi gagal
}

// Inisialisasi variabel untuk menampung pesan notifikasi
$pesan = "";

// Mengambil pesan dari session jika ada
if (isset($_SESSION['pesan'])) {
    $pesan = $_SESSION['pesan']; // Menyimpan pesan ke variabel lokal
    unset($_SESSION['pesan']); // Menghapus pesan dari session agar tidak muncul lagi saat halaman di-refresh
}

// Fungsi untuk membersihkan input dari user (mencegah SQL injection dan XSS attack)
function bersihkan_input($data) {
    $data = trim($data); // Menghapus spasi di awal dan akhir string
    $data = stripslashes($data); // Menghapus backslash
    $data = htmlspecialchars($data); // Mengubah karakter khusus HTML menjadi entitas
    return $data; // Mengembalikan data yang sudah dibersihkan
}

// PROSES CREATE - Menangani form submission untuk menambah data KRS
if (isset($_POST['tambah'])) {
    $mahasiswa_npm = bersihkan_input($_POST['mahasiswa_npm']); // Membersihkan input npm mahasiswa
    $matakuliah_kodemk = bersihkan_input($_POST['matakuliah_kodemk']); // Membersihkan input kode mata kuliah
    
    // Memeriksa apakah kombinasi mahasiswa dan mata kuliah sudah ada di database
    $cek_krs = $conn->query("SELECT id FROM krs WHERE mahasiswa_npm = '$mahasiswa_npm' AND matakuliah_kodemk = '$matakuliah_kodemk'");
    if ($cek_krs->num_rows > 0) {
        // Menampilkan pesan error jika data KRS sudah ada
        $pesan = "<div class='alert alert-danger'>Data KRS untuk mahasiswa dan mata kuliah tersebut sudah ada!</div>";
    } else {
        // Query untuk memasukkan data baru ke tabel KRS
        $query = "INSERT INTO krs (mahasiswa_npm, matakuliah_kodemk) VALUES ('$mahasiswa_npm', '$matakuliah_kodemk')";
        if ($conn->query($query) === TRUE) {
            // Menyimpan pesan sukses ke session
            $_SESSION['pesan'] = "<div class='alert alert-success'>Data KRS berhasil ditambahkan</div>";
            // Redirect ke halaman yang sama untuk reset form
            header("Location: krs.php");
            exit(); // Menghentikan eksekusi script
        } else {
            // Menampilkan pesan error jika query gagal
            $pesan = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
        }
    }
}

// PROSES UPDATE - Menangani form submission untuk mengubah data KRS
if (isset($_POST['update'])) {
    $id = bersihkan_input($_POST['id']); // Membersihkan input id KRS
    $mahasiswa_npm = bersihkan_input($_POST['mahasiswa_npm']); // Membersihkan input npm mahasiswa
    $matakuliah_kodemk = bersihkan_input($_POST['matakuliah_kodemk']); // Membersihkan input kode mata kuliah
    
    // Memeriksa apakah kombinasi mahasiswa dan mata kuliah sudah ada (selain data yang sedang diedit)
    $cek_krs = $conn->query("SELECT id FROM krs WHERE mahasiswa_npm = '$mahasiswa_npm' AND matakuliah_kodemk = '$matakuliah_kodemk' AND id != $id");
    if ($cek_krs->num_rows > 0) {
        // Menampilkan pesan error jika data KRS sudah ada
        $pesan = "<div class='alert alert-danger'>Data KRS untuk mahasiswa dan mata kuliah tersebut sudah ada!</div>";
    } else {
        // Query untuk memperbarui data KRS
        $query = "UPDATE krs SET mahasiswa_npm='$mahasiswa_npm', matakuliah_kodemk='$matakuliah_kodemk' WHERE id=$id";
        if ($conn->query($query) === TRUE) {
            // Menyimpan pesan sukses ke session
            $_SESSION['pesan'] = "<div class='alert alert-success'>Data KRS berhasil diupdate</div>";
            // Redirect ke halaman utama KRS
            header("Location: krs.php");
            exit(); // Menghentikan eksekusi script
        } else {
            // Menampilkan pesan error jika query gagal
            $pesan = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
        }
    }
}

// PROSES DELETE - Menangani permintaan untuk menghapus data KRS
if (isset($_GET['hapus'])) {
    $id = bersihkan_input($_GET['hapus']); // Membersihkan input id KRS yang akan dihapus
    
    // Query untuk menghapus data KRS berdasarkan id
    $query = "DELETE FROM krs WHERE id=$id";
    if ($conn->query($query) === TRUE) {
        // Menyimpan pesan sukses ke session
        $_SESSION['pesan'] = "<div class='alert alert-success'>Data KRS berhasil dihapus</div>";
        // Redirect ke halaman yang sama
        header("Location: krs.php");
        exit(); // Menghentikan eksekusi script
    } else {
        // Menyimpan pesan error ke session
        $_SESSION['pesan'] = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
        header("Location: krs.php");
        exit(); // Menghentikan eksekusi script
    }
}

// Inisialisasi variabel untuk form edit
$id_edit = "";
$mahasiswa_npm_edit = "";
$matakuliah_kodemk_edit = "";

// Mengisi variabel form edit jika ada parameter edit di URL
if (isset($_GET['edit'])) {
    $id = bersihkan_input($_GET['edit']); // Membersihkan input id KRS yang akan diedit
    $query = "SELECT * FROM krs WHERE id=$id"; // Query untuk mendapatkan data KRS berdasarkan id
    $result = $conn->query($query);
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc(); // Mengambil data sebagai array asosiatif
        $id_edit = $row['id']; // Menyimpan id KRS
        $mahasiswa_npm_edit = $row['mahasiswa_npm']; // Menyimpan npm mahasiswa
        $matakuliah_kodemk_edit = $row['matakuliah_kodemk']; // Menyimpan kode mata kuliah
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"> <!-- Mendefinisikan encoding karakter -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Mengatur viewport untuk responsive design -->
    <title>Kelola Data Mahasiswa</title> <!-- Judul halaman web -->
    
    <!-- Impor Bootstrap CSS dari CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    
    <!-- Impor Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <!-- Impor Material Icons dari Google (tambahan) -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <!-- Referensi ke CSS custom lokal -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-4"> <!-- Container dengan margin top -->
        <h1 class="text-center mb-4">Kelola Data KRS</h1> <!-- Judul halaman dengan styling -->
        <a href="index.php" class="btn btn-secondary mb-3">Kembali ke Menu Utama</a> <!-- Tombol kembali ke menu utama -->
        
        <?php echo $pesan; ?> <!-- Menampilkan pesan notifikasi jika ada -->
        
        <div class="row"> <!-- Layout baris untuk struktur grid -->
            <div class="col-md-4"> <!-- Kolom kiri untuk form input/edit -->
                <div class="card"> <!-- Card untuk form -->
                    <div class="card-header bg-info text-white"> <!-- Header card dengan warna latar biru dan teks putih -->
                        <?php echo isset($_GET['edit']) ? "Edit Data KRS" : "Tambah Data KRS"; ?> <!-- Header dinamis sesuai mode -->
                    </div>
                    <div class="card-body"> <!-- Isi card -->
                        <form method="post" action="" id="form-krs"> <!-- Form dengan method POST -->
                            <?php if (isset($_GET['edit'])) : ?> <!-- Conditionally showing hidden field for edit mode -->
                                <input type="hidden" name="id" value="<?php echo $id_edit; ?>"> <!-- Input hidden untuk id KRS yang diedit -->
                            <?php endif; ?>
                            
                            <div class="mb-3"> <!-- Form group dengan margin bottom -->
                                <label for="mahasiswa_npm" class="form-label">Pilih Mahasiswa</label> <!-- Label untuk dropdown mahasiswa -->
                                <select class="form-select" id="mahasiswa_npm" name="mahasiswa_npm" required> <!-- Dropdown mahasiswa dengan validasi required -->
                                    <option value="">Pilih Mahasiswa</option> <!-- Option default -->
                                    <?php
                                    // Query untuk mengambil data mahasiswa dari database
                                    $query_mahasiswa = "SELECT npm, nama FROM mahasiswa ORDER BY nama ASC";
                                    $result_mahasiswa = $conn->query($query_mahasiswa);
                                    
                                    // Menampilkan daftar mahasiswa dalam bentuk option dropdown
                                    if ($result_mahasiswa->num_rows > 0) {
                                        while ($row_mahasiswa = $result_mahasiswa->fetch_assoc()) {
                                            $selected = ($mahasiswa_npm_edit == $row_mahasiswa['npm']) ? "selected" : ""; // Menandai option yang dipilih jika mode edit
                                            echo "<option value='" . $row_mahasiswa['npm'] . "' $selected>" . $row_mahasiswa['nama'] . " (" . $row_mahasiswa['npm'] . ")</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3"> <!-- Form group dengan margin bottom -->
                                <label for="matakuliah_kodemk" class="form-label">Pilih Mata Kuliah</label> <!-- Label untuk dropdown mata kuliah -->
                                <select class="form-select" id="matakuliah_kodemk" name="matakuliah_kodemk" required> <!-- Dropdown mata kuliah dengan validasi required -->
                                    <option value="">Pilih Mata Kuliah</option> <!-- Option default -->
                                    <?php
                                    // Query untuk mengambil data mata kuliah dari database
                                    $query_matakuliah = "SELECT kodemk, nama, jumlah_sks FROM matakuliah ORDER BY nama ASC";
                                    $result_matakuliah = $conn->query($query_matakuliah);
                                    
                                    // Menampilkan daftar mata kuliah dalam bentuk option dropdown
                                    if ($result_matakuliah->num_rows > 0) {
                                        while ($row_matakuliah = $result_matakuliah->fetch_assoc()) {
                                            $selected = ($matakuliah_kodemk_edit == $row_matakuliah['kodemk']) ? "selected" : ""; // Menandai option yang dipilih jika mode edit
                                            echo "<option value='" . $row_matakuliah['kodemk'] . "' $selected>" . $row_matakuliah['nama'] . " (" . $row_matakuliah['jumlah_sks'] . " SKS)</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <?php if (isset($_GET['edit'])) : ?> <!-- Menampilkan tombol mode edit -->
                                <button type="submit" name="update" class="btn btn-warning"> <!-- Tombol update -->
                                    <i class="material-icons">save</i> Update <!-- Icon dan teks tombol -->
                                </button>
                                <a href="krs.php" class="btn btn-secondary"> <!-- Tombol batal -->
                                    <i class="material-icons">cancel</i> Batal <!-- Icon dan teks tombol -->
                                </a>
                            <?php else : ?> <!-- Menampilkan tombol mode tambah -->
                                <button type="submit" name="tambah" class="btn btn-info text-white"> <!-- Tombol simpan -->
                                    <i class="material-icons">save</i> Simpan <!-- Icon dan teks tombol -->
                                </button>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-8"> <!-- Kolom kanan untuk daftar KRS -->
                <div class="card"> <!-- Card untuk daftar -->
                    <div class="card-header bg-info text-white"> <!-- Header card dengan warna latar biru dan teks putih -->
                        Daftar KRS
                    </div>
                    <div class="card-body"> <!-- Isi card -->
                        <div class="mb-3"> <!-- Input group dengan margin bottom -->
                            <input type="text" class="form-control search-table" placeholder="Cari data..." data-table="table-data"> <!-- Input untuk pencarian -->
                        </div>
                        <div class="table-responsive"> <!-- Container untuk tabel dengan scroll horizontal jika perlu -->
                            <table class="table table-bordered table-striped" id="table-data"> <!-- Tabel dengan border dan warna selang-seling -->
                                <thead class="table-dark"> <!-- Header tabel dengan warna gelap -->
                                    <tr> <!-- Baris header -->
                                        <th>No</th> <!-- Kolom nomor urut -->
                                        <th>Nama Mahasiswa</th> <!-- Kolom nama mahasiswa -->
                                        <th>Mata Kuliah</th> <!-- Kolom mata kuliah -->
                                        <th>SKS</th> <!-- Kolom jumlah SKS -->
                                        <th>Aksi</th> <!-- Kolom aksi/tombol -->
                                    </tr>
                                </thead>
                                <tbody> <!-- Isi tabel -->
                                    <?php
                                    // Query untuk menampilkan data KRS dengan join ke tabel mahasiswa dan mata kuliah
                                    $query = "SELECT k.id, m.npm, m.nama AS nama_mahasiswa, mk.kodemk, 
                                             mk.nama AS nama_matakuliah, mk.jumlah_sks
                                             FROM krs k
                                             JOIN mahasiswa m ON k.mahasiswa_npm = m.npm
                                             JOIN matakuliah mk ON k.matakuliah_kodemk = mk.kodemk
                                             ORDER BY m.nama, mk.nama";
                                    $result = $conn->query($query);
                                    
                                    // Menampilkan data KRS dalam bentuk tabel
                                    if ($result->num_rows > 0) {
                                        $no = 1; // Inisialisasi nomor urut
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>"; // Membuka baris
                                            echo "<td>" . $no++ . "</td>"; // Kolom nomor urut
                                            echo "<td>" . $row['nama_mahasiswa'] . " (" . $row['npm'] . ")</td>"; // Kolom nama mahasiswa dan NPM
                                            echo "<td>" . $row['nama_matakuliah'] . " (" . $row['kodemk'] . ")</td>"; // Kolom nama mata kuliah dan kode
                                            echo "<td>" . $row['jumlah_sks'] . "</td>"; // Kolom jumlah SKS
                                            echo "<td class='text-nowrap'>
                                                    <a href='krs.php?edit=" . $row['id'] . "' class='btn btn-edit btn-sm btn-action' title='Edit'> <!-- Tombol edit -->
                                                    <i class='fas fa-edit'></i> <!-- Icon edit -->
                                                    </a>
                                                    <a href='krs.php?hapus=" . $row['id'] . "' class='btn btn-delete btn-sm btn-action' title='Hapus' onclick='return confirm(\"Apakah Anda yakin ingin menghapus data ini?\")'>
                                                    <!-- Tombol hapus dengan konfirmasi -->
                                                    <i class='fas fa-trash-alt'></i> <!-- Icon hapus -->
                                                    </a>
                                                </td>";
                                            echo "</tr>"; // Menutup baris
                                        }
                                    } else {
                                        echo "<tr><td colspan='5' class='text-center'>Belum ada data KRS</td></tr>"; // Pesan jika tidak ada data
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-end mt-3"> <!-- Container dengan rata kanan dan margin top -->
                            <button onclick="cetakTabelIndex()" class="btn btn-success btn-sm"> <!-- Tombol cetak dengan event handler onclick -->
                                <i class="fas fa-print"></i> Cetak KRS <!-- Icon dan teks tombol -->
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Impor JavaScript Bootstrap untuk fitur interaktif -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
    // Fungsi untuk pencarian di tabel
    document.addEventListener('DOMContentLoaded', function() {
        const searchInputs = document.querySelectorAll('.search-table'); // Mengambil semua input pencarian
        searchInputs.forEach(input => {
            input.addEventListener('keyup', function() { // Menambahkan event listener untuk keyup
                const tableId = this.getAttribute('data-table'); // Mendapatkan id tabel target
                const table = document.getElementById(tableId); // Mengambil elemen tabel
                const rows = table.getElementsByTagName('tr'); // Mengambil semua baris tabel
                const filter = this.value.toLowerCase(); // Mengubah input menjadi lowercase untuk pencarian case-insensitive
                
                // Loop melalui semua baris tabel (mulai dari indeks 1 untuk melewati header)
                for (let i = 1; i < rows.length; i++) {
                    let found = false; // Flag untuk menandai apakah kata kunci ditemukan
                    const cells = rows[i].getElementsByTagName('td'); // Mengambil semua sel dalam baris
                    
                    // Loop melalui semua sel dalam baris
                    for (let j = 0; j < cells.length; j++) {
                        const cellText = cells[j].innerText.toLowerCase(); // Mengambil teks sel dan mengubah menjadi lowercase
                        if (cellText.indexOf(filter) > -1) { // Memeriksa apakah teks mengandung kata kunci
                            found = true;
                            break; // Keluar dari loop jika ditemukan
                        }
                    }
                    
                    rows[i].style.display = found ? '' : 'none'; // Menampilkan atau menyembunyikan baris
                }
            });
        });
    });

    // Fungsi untuk mencetak tabel KRS
    function cetakTabelIndex() {
        // Membuat iframe tersembunyi untuk mengambil data
        const iframe = document.createElement('iframe');
        iframe.style.display = 'none';
        document.body.appendChild(iframe);
        
        // Event handler saat iframe selesai dimuat
        iframe.onload = function() {
            // Mengambil tabel dari iframe
            const table = iframe.contentDocument.getElementById('table-data');
            // Membuat konten HTML untuk halaman cetak
            const printContent = `
                <html>
                <head>
                    <title>Cetak Data KRS</title>
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
                    <style>
                        body { padding: 20px; }
                        h2 { text-align: center; margin-bottom: 20px; }
                        .print-header { margin-bottom: 20px; text-align: center; }
                        @media print {
                            .no-print { display: none; } /* Menyembunyikan elemen saat mencetak */
                        }
                    </style>
                </head>
                <body>
                    <div class="print-header">
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
                    ${table.outerHTML} <!-- Menyisipkan tabel KRS -->
                    <div class="text-center mt-4 no-print">
                        <button class="btn btn-primary" onclick="window.print()">Print</button> <!-- Tombol cetak -->
                        <button class="btn btn-secondary" onclick="window.close()">Tutup</button> <!-- Tombol tutup -->
                    </div>
                    <script>
                        // Auto focus pada tombol print saat halaman terbuka
                        window.onload = function() {
                            document.querySelector('.btn-primary').focus();
                        };
                    <\/script>
                </body>
                </html>
            `;
            
            // Membuka window baru untuk cetak
            const printWindow = window.open('', '_blank');
            printWindow.document.write(printContent); // Menulis konten HTML ke window baru
            printWindow.document.close(); // Menutup document stream
            
            // Menghapus iframe
            document.body.removeChild(iframe);
        };
        
        // Mengatur sumber iframe ke index.php
        iframe.src = 'index.php';
    }
    </script>

    <!-- Container untuk notifikasi toast -->
    <div id="toast-container" class="toast-container position-fixed bottom-0 end-0 p-3"></div>

    <!-- Footer halaman -->
    <footer class="footer mt-5"> <!-- Footer dengan margin top -->
        <div class="container">
            <p class="text-center">&copy; <?php echo date('Y'); ?> Sistem Informasi Akademik</p> <!-- Copyright dengan tahun dinamis -->
        </div>
    </footer>
</body>
</html>

<?php
// Menutup koneksi database
$conn->close();
?>