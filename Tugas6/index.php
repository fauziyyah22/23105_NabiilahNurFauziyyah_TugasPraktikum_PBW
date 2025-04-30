<?php
// File ini merupakan halaman utama aplikasi web AetherSky untuk pendaftaran rute penerbangan

include 'fungsi.php'; // Mengimpor file yang berisi fungsi-fungsi pendukung aplikasi pendaftaran rute penerbangan AetherSky
include 'sesi.php'; // Mengimpor file pengelolaan sesi untuk menyimpan data user saat menggunakan aplikasi
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AetherSky - Pendaftaran Rute Penerbangan</title>
    <!-- Judul halaman yang akan muncul di tab browser -->
    <link rel="stylesheet" href="style.css">
    <!-- Menghubungkan ke file CSS lokal untuk styling halaman -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Menggunakan library Font Awesome versi 6.4.0 untuk ikon-ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- Menggunakan library Animate.css untuk efek animasi elemen -->
</head>
<body>
    <!-- Bagian body berisi konten utama yang akan ditampilkan ke pengguna -->
    <div class="airplane-animation">
        <!-- Container untuk animasi pesawat yang bergerak -->
        <div class="airplane">
            <!-- Elemen pesawat yang akan dianimasikan -->
            <i class="fas fa-plane"></i>
            <!-- Ikon pesawat dari Font Awesome -->
        </div>
    </div>
    
    <div class="container">
        <!-- Container utama yang membungkus seluruh konten halaman -->
        <header class="sticky-header">
            <!-- Header yang akan tetap menempel di atas halaman saat di-scroll -->
            <nav>
                <!-- Navigasi utama website -->
                <div class="logo animate__animated animate__fadeIn">
                    <!-- Logo dengan efek animasi fade in -->
                    <i class="fas fa-plane"></i>
                    <!-- Ikon pesawat sebagai bagian dari logo -->
                    <h1>AetherSky</h1>
                    <!-- Nama perusahaan/website sebagai heading level 1 -->
                </div>
                <ul class="animate__animated animate__fadeIn">
                    <!-- Daftar menu dengan efek animasi fade in -->
                    <li><a href="#beranda" class="active">Melayang di Langit</a></li>
                    <!-- Link menu beranda dengan status aktif -->
                    <li><a href="#layanan">Layanan</a></li>
                    <!-- Link menu layanan -->
                    <li><a href="#kontak">Kontak</a></li>
                    <!-- Link menu kontak -->
                </ul>
            </nav>
        </header>

        <section id="beranda" class="hero">
            <!-- Bagian hero/beranda dengan ID beranda untuk target navigasi -->
            <div class="hero-content animate__animated animate__fadeInUp">
                <!-- Konten hero dengan animasi fade in dari bawah -->
                <h2>Melayang di Langit, Menyusun Cerita</h2>
                <!-- Judul utama dengan slogan perusahaan -->
                <p>Penerbangan bukan sekadar perjalanan, tapi pengalaman yang tertinggal di ingatan.</p>
                <!-- Deskripsi pertama yang memberikan nilai produk -->
                <p class="tagline">Biar langit jadi saksi, ke mana kakimu ingin pergi.</p>
                <!-- Tagline kedua dengan gaya puitis -->
                <!-- <img src="images/plane-sky.jpg" alt="Langit dan Pesawat" class="hero-image"> -->
                <!-- Gambar yang dinonaktifkan (mungkin akan diaktifkan nanti) -->
            </div>
        </section>

        <section id="layanan" class="main-content">
            <!-- Bagian layanan dengan ID layanan untuk target navigasi -->
            <div class="form-container animate__animated animate__fadeIn">
                <!-- Container form dengan animasi fade in -->
                <h3>Pendaftaran Rute Penerbangan</h3>
                <!-- Judul form pendaftaran -->
                <form action="javascript:void(0);" method="post" id="flightForm">
                    <!-- Form dengan action javascript:void(0); untuk diproses dengan JS tanpa refresh halaman -->
                    <div class="form-group">
                        <!-- Grup input untuk maskapai -->
                        <label for="maskapai">Maskapai</label>
                        <!-- Label untuk field maskapai -->
                        <i class="fas fa-plane"></i>
                        <!-- Ikon pesawat dekoratif di field -->
                        <select id="maskapai" name="maskapai" required>
                            <!-- Dropdown untuk pilihan maskapai dengan atribut required -->
                            <option value="">Pilih Maskapai</option>
                            <!-- Opsi default yang kosong -->
                            <option value="Garuda Indonesia">Garuda Indonesia</option>
                            <!-- Opsi maskapai Garuda Indonesia -->
                            <option value="Lion Air">Lion Air</option>
                            <!-- Opsi maskapai Lion Air -->
                            <option value="Citilink">Citilink</option>
                            <!-- Opsi maskapai Citilink -->
                            <option value="Batik Air">Batik Air</option>
                            <!-- Opsi maskapai Batik Air -->
                            <option value="AirAsia">AirAsia</option>
                            <!-- Opsi maskapai AirAsia -->
                        </select>
                    </div>
                    <div class="form-group">
                        <!-- Grup input untuk bandara asal -->
                        <label for="asal">Bandara Asal:</label>
                        <!-- Label untuk field bandara asal -->
                        <select id="asal" name="asal" required>
                            <!-- Dropdown untuk pilihan bandara asal dengan atribut required -->
                            <option value="" disabled selected>Pilih Bandara Asal</option>
                            <!-- Opsi default yang dinonaktifkan dan terpilih otomatis -->
                            <?php
                            // Mulai blok PHP untuk mengisi opsi bandara asal secara dinamis
                            $bandaraAsal = getBandaraAsal(); // Memanggil fungsi getBandaraAsal() dari fungsi.php
                            foreach($bandaraAsal as $bandara => $pajak) { // Melakukan loop untuk setiap bandara dan nilai pajaknya
                                echo "<option value=\"$bandara\">$bandara</option>"; // Menampilkan opsi bandara
                            }
                            // Akhir blok PHP
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <!-- Grup input untuk bandara tujuan -->
                        <label for="tujuan">Bandara Tujuan:</label>
                        <!-- Label untuk field bandara tujuan -->
                        <select id="tujuan" name="tujuan" required>
                            <!-- Dropdown untuk pilihan bandara tujuan dengan atribut required -->
                            <option value="" disabled selected>Pilih Bandara Tujuan</option>
                            <!-- Opsi default yang dinonaktifkan dan terpilih otomatis -->
                            <?php
                            // Mulai blok PHP untuk mengisi opsi bandara tujuan secara dinamis
                            $bandaraTujuan = getBandaraTujuan(); // Memanggil fungsi getBandaraTujuan() dari fungsi.php
                            foreach($bandaraTujuan as $bandara => $pajak) { // Melakukan loop untuk setiap bandara dan nilai pajaknya
                                echo "<option value=\"$bandara\">$bandara</option>"; // Menampilkan opsi bandara
                            }
                            // Akhir blok PHP
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <!-- Grup input untuk kelas penerbangan -->
                        <label for="kelas">Kelas Penerbangan:</label>
                        <!-- Label untuk field kelas penerbangan -->
                        <select id="kelas" name="kelas" required>
                            <!-- Dropdown untuk pilihan kelas penerbangan dengan atribut required -->
                            <option value="" disabled selected>Pilih Kelas Penerbangan</option>
                            <!-- Opsi default yang dinonaktifkan dan terpilih otomatis -->
                            <option value="economy">Ekonomi (Rp 1.000.000)</option>
                            <!-- Opsi kelas ekonomi dengan harga -->
                            <option value="premium_economy">Ekonomi Premium (Rp 3.500.000)</option>
                            <!-- Opsi kelas ekonomi premium dengan harga -->
                            <option value="business">Bisnis (Rp 7.000.000)</option>
                            <!-- Opsi kelas bisnis dengan harga -->
                            <option value="first">First Class (Rp 10.000.000)</option>
                            <!-- Opsi kelas first class dengan harga -->
                        </select>
                    </div>

                    <div class="form-group">
                        <!-- Grup input untuk jumlah penumpang -->
                        <label for="jumlah_penumpang">Jumlah Penumpang:</label>
                        <!-- Label untuk field jumlah penumpang -->
                        <input type="number" id="jumlah_penumpang" name="jumlah_penumpang" required min="1" max="10" value="1">
                        <!-- Input numerik dengan nilai minimum 1, maksimum 10, dan nilai default 1 -->
                    </div>

                    <div class="form-group">
                        <!-- Grup input untuk harga tiket -->
                        <label for="harga">Harga Tiket (Rp):</label>
                        <!-- Label untuk field harga tiket -->
                        <input type="text" id="harga" name="harga" required readonly>
                        <!-- Input teks yang hanya bisa dibaca (readonly), akan diisi oleh JavaScript -->
                    </div>

                    <div class="submit-group">
                        <!-- Grup tombol submit dan reset -->
                        <button type="submit" class="btn-submit">
                            <!-- Tombol submit untuk mengirim form -->
                            <i class="fas fa-ticket-alt"></i> Pesan Tiket
                            <!-- Ikon tiket dan teks tombol -->
                        </button>
                        <button type="reset" class="btn-reset">
                            <!-- Tombol reset untuk mengosongkan form -->
                            <i class="fas fa-undo"></i> Reset
                            <!-- Ikon undo dan teks tombol -->
                        </button>
                    </div>
                </form>
            </div>
        </section>

        <section id="hasil-penerbangan" class="result-section">
            <!-- Bagian hasil penerbangan untuk menampilkan data yang telah diinput -->
            <div class="result-container animate__animated animate__fadeIn" id="resultContainer">
                <!-- Container hasil dengan animasi fade in -->
                <h3>Data Penerbangan</h3>
                <!-- Judul bagian data penerbangan -->
                <div class="result-empty" id="resultEmpty">
                    <!-- Tampilan saat belum ada data -->
                    <i class="fas fa-search"></i>
                    <!-- Ikon pencarian untuk indikasi visual -->
                    <p>Belum ada data penerbangan yang terdaftar</p>
                    <!-- Pesan saat belum ada data -->
                </div>
                <div class="result-table" id="resultTable" style="display: none;">
                    <!-- Tampilan tabel data yang awalnya disembunyikan -->
                    <table>
                        <!-- Tabel untuk menampilkan data penerbangan -->
                        <thead>
                            <!-- Bagian header tabel -->
                            <tr>
                                <!-- Baris header -->
                                <th>No</th>
                                <!-- Kolom nomor urut -->
                                <th>Tanggal</th>
                                <!-- Kolom tanggal pemesanan -->
                                <th>Maskapai</th>
                                <!-- Kolom nama maskapai -->
                                <th>Asal</th>
                                <!-- Kolom bandara asal -->
                                <th>Tujuan</th>
                                <!-- Kolom bandara tujuan -->
                                <th>Kelas</th>
                                <!-- Kolom kelas penerbangan -->
                                <th>Jumlah Penumpang</th>
                                <!-- Kolom jumlah penumpang -->
                                <th>Harga Tiket</th>
                                <!-- Kolom harga tiket -->
                                <th>Pajak</th>
                                <!-- Kolom pajak -->
                                <th>Total</th>
                                <!-- Kolom total harga -->
                                <th>Metode Pembayaran</th>
                                <!-- Kolom metode pembayaran -->
                                <th>Kode Tiket</th>
                                <!-- Kolom kode tiket -->
                                <th>Aksi</th>
                                <!-- Kolom untuk tombol aksi -->
                            </tr>
                        </thead>
                        <tbody id="flightData">
                            <!-- Bagian body tabel, akan diisi dengan JavaScript -->
                            <!-- Data akan ditampilkan di sini -->
                        </tbody>
                    </table>
                    <div class="action-buttons">
                        <!-- Container untuk tombol aksi global -->
                        <button id="deleteAllBtn" class="btn-delete-all"><i class="fas fa-trash-alt"></i> Hapus Semua Data</button>
                        <!-- Tombol untuk menghapus semua data dengan ikon trash -->
                    </div>
                </div>
            </div>
        </section>

        <div id="paymentModal" class="modal">
            <!-- Modal dialog untuk memilih metode pembayaran -->
            <div class="modal-content animate__animated animate__zoomIn">
                <!-- Konten modal dengan animasi zoom in -->
                <span class="close">&times;</span>
                <!-- Tombol tutup (×) untuk modal -->
                <h3>Pilih Metode Pembayaran</h3>
                <!-- Judul modal -->
                <div class="payment-options">
                    <!-- Container untuk opsi-opsi pembayaran -->
                    <div class="payment-option" data-method="qris">
                        <!-- Opsi pembayaran QRIS -->
                        <i class="fas fa-qrcode"></i>
                        <!-- Ikon QR code -->
                        <span>QRIS</span>
                        <!-- Teks metode pembayaran -->
                    </div>
                    <div class="payment-option" data-method="bca">
                        <!-- Opsi pembayaran BCA -->
                        <i class="fas fa-university"></i>
                        <!-- Ikon bank -->
                        <span>BCA</span>
                        <!-- Teks metode pembayaran -->
                    </div>
                    <div class="payment-option" data-method="mandiri">
                        <!-- Opsi pembayaran Mandiri -->
                        <i class="fas fa-university"></i>
                        <!-- Ikon bank -->
                        <span>Mandiri</span>
                        <!-- Teks metode pembayaran -->
                    </div>
                    <div class="payment-option" data-method="bni">
                        <!-- Opsi pembayaran BNI -->
                        <i class="fas fa-university"></i>
                        <!-- Ikon bank -->
                        <span>BNI</span>
                        <!-- Teks metode pembayaran -->
                    </div>
                </div>
                <button id="processPayment" class="btn-submit">Proses Pembayaran</button>
                <!-- Tombol untuk memproses pembayaran -->
            </div>
        </div>

        <footer>
            <!-- Footer website -->
            <div id="kontak" class="footer-content">
                <!-- Konten footer dengan ID kontak sebagai target navigasi -->
                <div class="footer-section">
                    <!-- Bagian footer pertama -->
                    <h4>AetherSky</h4>
                    <!-- Judul bagian -->
                    <p>Temukan penerbangan terbaik dengan harga terjangkau dan layanan terpercaya.</p>
                    <!-- Deskripsi layanan -->
                </div>
                <div class="footer-section">
                    <!-- Bagian footer kedua -->
                    <h4>Kontak</h4>
                    <!-- Judul bagian -->
                    <p><i class="fas fa-envelope"></i> info@aethersky.com</p>
                    <!-- Email kontak dengan ikon -->
                    <p><i class="fas fa-phone"></i> +62 856 0733 9710</p>
                    <!-- Nomor telepon kontak dengan ikon -->
                </div>
                <div class="footer-section">
                    <!-- Bagian footer ketiga -->
                    <h4>Media Sosial</h4>
                    <!-- Judul bagian -->
                    <div class="social-icons">
                        <!-- Container untuk ikon-ikon sosial media -->
                        <a href="https://api.whatsapp.com/send/?phone=6285607339710" target="_blank" class="contact-icon">
                            <!-- Link WhatsApp dengan target blank (buka di tab baru) -->
                            <i class="fab fa-whatsapp"></i>
                            <!-- Ikon WhatsApp -->
                        </a>
                        <a href="https://www.instagram.com/fz.nabil" target="_blank" class="contact-icon">
                            <!-- Link Instagram dengan target blank (buka di tab baru) -->
                            <i class="fab fa-instagram"></i>
                            <!-- Ikon Instagram -->
                        </a>
                        <a href="mailto:2310631170105@student.unsika.ac.id" class="contact-icon">
                            <!-- Link email dengan protokol mailto -->
                            <i class="fas fa-envelope"></i>
                            <!-- Ikon email -->
                        </a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <!-- Bagian bawah footer -->
                <p>&copy; 2025 AetherSky. Hak Cipta Dilindungi.</p>
                <!-- Teks copyright dengan simbol © -->
            </div>
        </footer>
    </div>

    <script src="script.js"></script>
    <!-- Menghubungkan ke file JavaScript untuk fungsionalitas dinamis halaman -->
</body>
</html>