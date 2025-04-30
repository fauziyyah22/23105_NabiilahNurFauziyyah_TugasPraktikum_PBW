// Jalankan kode setelah DOM (struktur HTML) selesai dimuat
document.addEventListener('DOMContentLoaded', function() {
    // Mengambil referensi ke elemen-elemen penting di halaman
    const flightForm = document.getElementById('flightForm'); // Form pemesanan tiket
    const resultContainer = document.getElementById('resultContainer'); // Wadah untuk menampilkan hasil
    const resultEmpty = document.getElementById('resultEmpty'); // Pesan ketika tidak ada data
    const resultTable = document.getElementById('resultTable'); // Tabel untuk menampilkan data penerbangan
    const flightData = document.getElementById('flightData'); // Tempat data penerbangan akan ditampilkan
    const deleteAllBtn = document.getElementById('deleteAllBtn'); // Tombol untuk menghapus semua data
    const paymentModal = document.getElementById('paymentModal'); // Modal pembayaran
    const closeModal = document.querySelector('.close'); // Tombol untuk menutup modal
    const paymentOptions = document.querySelectorAll('.payment-option'); // Opsi metode pembayaran
    const processPayment = document.getElementById('processPayment'); // Tombol untuk memproses pembayaran
    
    // Variabel untuk menyimpan metode pembayaran yang dipilih pengguna
    let selectedPaymentMethod = '';
    
    // Memuat data form yang sebelumnya disimpan di session
    loadFormData();
    
    // Mengambil data penerbangan dari server
    loadFlightsData();
    
    // Fungsi untuk memperbarui harga tiket berdasarkan kelas dan jumlah penumpang
    function updateTicketPrice() {
        const klasSelect = document.getElementById('kelas'); // Pilihan kelas
        const hargaInput = document.getElementById('harga'); // Input harga
        const jumlahPenumpang = document.getElementById('jumlah_penumpang').value || 1; // Jumlah penumpang
        
        // Daftar harga untuk setiap kelas penerbangan
        const klasHarga = {
            'economy': 1000000, // Ekonomi: 1 juta rupiah
            'premium_economy': 3500000, // Ekonomi premium: 3,5 juta rupiah
            'business': 7000000, // Bisnis: 7 juta rupiah
            'first': 10000000 // First class: 10 juta rupiah
        };
        
        // Hitung harga berdasarkan kelas yang dipilih
        if (klasSelect.value) {
            const hargaPerPenumpang = klasHarga[klasSelect.value]; // Harga per penumpang
            const totalHarga = hargaPerPenumpang * jumlahPenumpang; // Total harga
            hargaInput.value = formatRupiah(totalHarga); // Tampilkan harga dalam format rupiah
        } else {
            hargaInput.value = ''; // Kosongkan input harga jika kelas belum dipilih
        }
    }
    
    // Tambahkan event listener untuk perubahan kelas dan jumlah penumpang
    document.getElementById('kelas').addEventListener('change', updateTicketPrice);
    document.getElementById('jumlah_penumpang').addEventListener('input', updateTicketPrice);
    
    // Fungsi untuk memformat angka menjadi format Rupiah
    function formatRupiah(angka) {
        const formatter = new Intl.NumberFormat('id-ID', {
            style: 'currency', // Format mata uang
            currency: 'IDR', // Mata uang Indonesia Rupiah
            minimumFractionDigits: 0 // Tanpa desimal
        });
        return formatter.format(angka);
    }
    
    // Fungsi untuk animasi pesawat di halaman
    function animateAirplane() {
        const airplane = document.querySelector('.airplane');
        airplane.style.left = '-150px'; // Posisi awal pesawat di luar layar sebelah kiri
        
        // Fungsi untuk membuat beberapa pesawat dengan ukuran berbeda
        const createMultiplePlanes = () => {
            const planeContainer = document.querySelector('.airplane-animation');
            const planeColors = ['#3b82f6', '#60a5fa', '#2563eb', '#93c5fd', '#1d4ed8']; // Warna-warna pesawat
            
            // Buat 3 pesawat dengan properti acak
            for (let i = 0; i < 3; i++) {
                const newPlane = document.createElement('div'); // Buat elemen div baru
                newPlane.className = 'airplane airplane-' + (i+1); // Beri class
                newPlane.innerHTML = '<i class="fas fa-plane"></i>'; // Tambahkan ikon pesawat
                newPlane.style.top = Math.random() * 500 + 'px'; // Posisi vertikal acak
                newPlane.style.color = planeColors[Math.floor(Math.random() * planeColors.length)]; // Warna acak
                newPlane.style.fontSize = (30 + Math.random() * 30) + 'px'; // Ukuran acak
                newPlane.style.opacity = (0.5 + Math.random() * 0.5); // Transparansi acak
                planeContainer.appendChild(newPlane); // Tambahkan ke container
                
                // Animasikan pesawat dengan jeda
                setTimeout(() => {
                    animateSinglePlane(newPlane);
                }, i * 5000); // Jeda 5 detik antar pesawat
            }
        };
        
        // Fungsi untuk menganimasi satu pesawat
        const animateSinglePlane = (plane) => {
            plane.style.left = '-150px'; // Mulai dari luar layar sebelah kiri
            
            setTimeout(() => {
                // Animasi pesawat dari kiri ke kanan
                plane.style.transition = 'left 15s linear, top 7s ease-in-out';
                plane.style.left = '120%'; // Bergerak ke kanan hingga keluar layar
                plane.style.top = (100 + Math.random() * -200) + 'px'; // Bergerak naik secara acak
                
                // Reset posisi setelah animasi selesai untuk animasi berikutnya
                setTimeout(() => {
                    plane.style.transition = 'none'; // Nonaktifkan transisi 
                    plane.style.left = '-150px'; // Kembalikan ke posisi awal
                    setTimeout(() => animateSinglePlane(plane), Math.random() * 5000 + 3000); // Ulangi dengan jeda acak
                }, 15000);
            }, 100);
        };
        
        createMultiplePlanes(); // Buat beberapa pesawat
        animateSinglePlane(airplane); // Animasikan pesawat utama
    }
    
    // Mulai animasi pesawat setelah halaman dimuat
    setTimeout(animateAirplane, 2000);
    
    // Fungsi untuk menyimpan data form ke session PHP melalui AJAX
    function saveFormData() {
        const formData = new FormData(); // Buat objek FormData untuk mengirim data
        formData.append('action', 'saveFormData'); // Tentukan aksi yang akan dilakukan server
        formData.append('maskapai', document.getElementById('maskapai').value); // Simpan maskapai
        formData.append('asal', document.getElementById('asal').value); // Simpan bandara asal
        formData.append('tujuan', document.getElementById('tujuan').value); // Simpan bandara tujuan
        formData.append('kelas', document.getElementById('kelas').value); // Simpan kelas
        formData.append('jumlah_penumpang', document.getElementById('jumlah_penumpang').value); // Simpan jumlah penumpang
        
        // Kirim data form ke server melalui fetch API
        fetch('sesi.php', {
            method: 'POST', // Gunakan metode POST
            body: formData // Kirim data form
        })
        .then(response => response.text()) // Proses response sebagai text
        .then(data => {
            console.log('Form data saved to session'); // Tampilkan pesan di console
        })
        .catch(error => {
            console.error('Error saving form data:', error); // Tampilkan error jika gagal
        });
    }
    
    // Fungsi untuk memuat data form dari session PHP
    function loadFormData() {
        // Kirim request AJAX ke server untuk mengambil data form dari session
        fetch('sesi.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded', // Format data yang dikirim
            },
            body: 'action=getFormData' // Parameter untuk mengambil data form
        })
        .then(response => response.text()) // Proses response sebagai text
        .then(data => {
            if (data) {
                // Parse data yang diterima dari server
                const formData = new DOMParser().parseFromString(data, 'text/html').body.textContent;
                const formValues = formData.split('|'); // Data dipisahkan dengan karakter |
                
                // Isi form dengan data yang diterima jika ada
                if (formValues.length >= 5) {
                    document.getElementById('maskapai').value = formValues[0] || '';
                    document.getElementById('asal').value = formValues[1] || '';
                    document.getElementById('tujuan').value = formValues[2] || '';
                    document.getElementById('kelas').value = formValues[3] || '';
                    document.getElementById('jumlah_penumpang').value = formValues[4] || '';
                    
                    // Update harga tiket setelah form diisi
                    updateTicketPrice();
                }
            }
        })
        .catch(error => {
            console.error('Error loading form data:', error); // Tampilkan error jika gagal
        });
    }
    
    // Fungsi untuk memuat data penerbangan dari server
    function loadFlightsData() {
        // Kirim request AJAX ke server untuk mengambil data penerbangan
        fetch('sesi.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=getAll' // Parameter untuk mengambil semua data penerbangan
        })
        .then(response => response.text())
        .then(data => {
            try {
                // Cek apakah ada data penerbangan
                if (data && data.trim() !== '') {
                    // Parse data HTML yang diterima
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(data, 'text/html');
                    const flightsTable = doc.querySelector('table');
                    
                    if (flightsTable) {
                        // Tampilkan data penerbangan di tabel
                        document.getElementById('flightTableBody').innerHTML = flightsTable.querySelector('tbody').innerHTML;
                        
                        // Tampilkan tabel hasil
                        resultTable.style.display = 'block';
                        resultEmpty.style.display = 'none';
                        deleteAllBtn.style.display = 'block';
                    } else {
                        // Tampilkan pesan kosong jika tidak ada data
                        resultTable.style.display = 'none';
                        resultEmpty.style.display = 'block';
                        deleteAllBtn.style.display = 'none';
                    }
                } else {
                    // Tidak ada data
                    resultTable.style.display = 'none';
                    resultEmpty.style.display = 'block';
                    deleteAllBtn.style.display = 'none';
                }
            } catch (e) {
                console.error('Error parsing flights data:', e); // Tampilkan error parsing
                showAlert('Terjadi kesalahan saat memuat data penerbangan.', 'error'); // Tampilkan alert error
            }
        })
        .catch(error => {
            console.error('Error:', error); // Tampilkan error fetch
            showAlert('Terjadi kesalahan saat memuat data penerbangan.', 'error'); // Tampilkan alert error
        });
    }
    
    // Fungsi untuk menampilkan data penerbangan di tabel
    function displayFlightData(flights) {
        flightData.innerHTML = ''; // Kosongkan tabel terlebih dahulu
        
        // Loop melalui setiap data penerbangan
        flights.forEach((flight, index) => {
            const row = document.createElement('tr'); // Buat baris baru
            row.className = 'animate__animated animate__fadeIn'; // Tambah kelas untuk animasi
            
            // Format tanggal
            const date = new Date(flight.tanggal);
            const formattedDate = formatDate(flight.tanggal);
            
            // Buat isi baris dengan data penerbangan
            row.innerHTML = `
                <td>${index + 1}</td> <!-- Nomor urut -->
                <td>${formattedDate}</td> <!-- Tanggal -->
                <td>${flight.maskapai}</td> <!-- Maskapai -->
                <td>${flight.asal}</td> <!-- Bandara asal -->
                <td>${flight.tujuan}</td> <!-- Bandara tujuan -->
                <td>${flight.kelas_nama || flight.nama_kelas}</td> <!-- Kelas -->
                <td>${flight.jumlah_penumpang}</td> <!-- Jumlah penumpang -->
                <td>${formatRupiah(flight.harga_tiket || flight.total_harga_tiket)}</td> <!-- Harga tiket -->
                <td>${formatRupiah(flight.pajak || flight.total_pajak)}</td> <!-- Pajak -->
                <td>${formatRupiah(flight.total)}</td> <!-- Total biaya -->
                <td>${getPaymentMethodName(flight.metode_pembayaran)}</td> <!-- Metode pembayaran -->
                <td><span class="ticket-code">${flight.kode_tiket}</span></td> <!-- Kode tiket -->
                <td>
                    <button class="btn-print" data-index="${index}"><i class="fas fa-print"></i></button> <!-- Tombol cetak -->
                    <button class="btn-delete" data-kode="${flight.kode_tiket}"><i class="fas fa-trash"></i></button> <!-- Tombol hapus -->
                </td>
            `;
            
            flightData.appendChild(row); // Tambahkan baris ke tabel
        });
        
        // Tambahkan event listener untuk tombol cetak
        document.querySelectorAll('.btn-print').forEach((button, index) => {
            button.addEventListener('click', function() {
                printTicket(flights[index]); // Cetak tiket saat tombol diklik
            });
        });
        
        // Tambahkan event listener untuk tombol hapus
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function() {
                const kodeTicket = this.getAttribute('data-kode'); // Ambil kode tiket
                deleteFlight(kodeTicket); // Hapus penerbangan saat tombol diklik
            });
        });
    }
    
    // Fungsi untuk mendapatkan nama metode pembayaran yang lebih ramah pengguna
    function getPaymentMethodName(method) {
        const methods = {
            'qris': 'QRIS',
            'bca': 'Bank BCA',
            'mandiri': 'Bank Mandiri',
            'bni': 'Bank BNI',
            'QRIS': 'QRIS',
            'BCA': 'Bank BCA',
            'MANDIRI': 'Bank Mandiri',
            'BNI': 'Bank BNI'
        };
        return methods[method] || method; // Kembalikan nama metode yang sesuai atau nilai aslinya
    }
    
    // Fungsi untuk menghapus data penerbangan
    function deleteFlight(kodeTicket) {
        if (confirm('Apakah Anda yakin ingin menghapus data penerbangan ini?')) {
            // Kirim request AJAX ke server untuk menghapus data
            fetch('sesi.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=delete&kode_tiket=${kodeTicket}` // Parameter untuk menghapus data
            })
            .then(response => response.text())
            .then(data => {
                if (data.includes('success')) {
                    // Jika berhasil, muat ulang data penerbangan
                    loadFlightsData();
                    showAlert('Data penerbangan berhasil dihapus.', 'success'); // Tampilkan pesan sukses
                } else {
                    showAlert('Gagal menghapus data penerbangan.', 'error'); // Tampilkan pesan error
                }
            })
        }
    }
    
    // Event listener untuk tombol hapus semua
    deleteAllBtn.addEventListener('click', function() {
        if (confirm('Apakah Anda yakin ingin menghapus semua data penerbangan?')) {
            // Kirim request AJAX ke server untuk menghapus semua data
            fetch('sesi.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=deleteAll' // Parameter untuk menghapus semua data
            })
            .then(response => response.text())
            .then(data => {
                if (data.includes('success')) {
                    // Jika berhasil, muat ulang data penerbangan
                    loadFlightsData();
                    showAlert('Semua data penerbangan berhasil dihapus.', 'success'); // Tampilkan pesan sukses
                } else {
                    showAlert('Gagal menghapus data penerbangan.', 'error'); // Tampilkan pesan error
                }
            })
            .catch(error => {
                console.error('Error:', error); // Tampilkan error
                showAlert('Terjadi kesalahan saat menghapus data.', 'error'); // Tampilkan pesan error
            });
        }
    });
    
    // Event listener untuk form submission
    flightForm.addEventListener('submit', function(e) {
        e.preventDefault(); // Cegah form melakukan submit normal
        
        // Ambil nilai dari form
        const maskapai = document.getElementById('maskapai').value;
        const asal = document.getElementById('asal').value;
        const tujuan = document.getElementById('tujuan').value;
        const kelas = document.getElementById('kelas').value;
        const jumlahPenumpang = document.getElementById('jumlah_penumpang').value;
        
        // Validasi sederhana
        if(!maskapai || !asal || !tujuan || !kelas || !jumlahPenumpang) {
            showAlert('Semua field harus diisi!', 'error'); // Tampilkan pesan error jika ada field kosong
            return;
        }
        
        if(asal === tujuan) {
            showAlert('Bandara asal dan tujuan tidak boleh sama!', 'error'); // Tampilkan pesan error jika asal = tujuan
            return;
        }
        
        // Simpan data form ke session
        saveFormData();
        
        // Tampilkan modal pembayaran
        paymentModal.style.display = 'block';
    });
    
    // Tutup modal saat klik tombol close
    closeModal.addEventListener('click', function() {
        paymentModal.style.display = 'none'; // Sembunyikan modal
    });
    
    // Tutup modal saat klik di luar modal
    window.addEventListener('click', function(event) {
        if (event.target === paymentModal) {
            paymentModal.style.display = 'none'; // Sembunyikan modal
        }
    });
    
    // Pilih metode pembayaran
    paymentOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Hapus kelas active dari semua opsi
            paymentOptions.forEach(opt => opt.classList.remove('active'));
            // Tambah kelas active ke opsi yang dipilih
            this.classList.add('active');
            // Simpan metode pembayaran yang dipilih
            selectedPaymentMethod = this.getAttribute('data-method');
        });
    });
    
    // Event listener untuk tombol proses pembayaran
    processPayment.addEventListener('click', function() {
        if (!selectedPaymentMethod) {
            showAlert('Silakan pilih metode pembayaran!', 'error'); // Tampilkan pesan error jika metode belum dipilih
            return;
        }
        
        // Tambahkan animasi loading
        const paymentButton = this;
        const originalText = paymentButton.innerHTML;
        paymentButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...'; // Ganti teks tombol
        paymentButton.disabled = true; // Nonaktifkan tombol
        
        // Simulasikan loading selama 1.5 detik
        setTimeout(() => {
            // Ambil data dari form
            const maskapai = document.getElementById('maskapai').value;
            const asal = document.getElementById('asal').value;
            const tujuan = document.getElementById('tujuan').value;
            const kelas = document.getElementById('kelas').value;
            const kelasNama = document.getElementById('kelas').options[document.getElementById('kelas').selectedIndex].text.split(' ')[0];
            const jumlahPenumpang = parseInt(document.getElementById('jumlah_penumpang').value);
            
            // Hitung harga tiket berdasarkan kelas
            let hargaTiket = 0;
            switch(kelas) {
                case 'economy':
                    hargaTiket = 1000000; // Ekonomi: 1 juta
                    break;
                case 'premium_economy':
                    hargaTiket = 3500000; // Ekonomi premium: 3,5 juta
                    break;
                case 'business':
                    hargaTiket = 7000000; // Bisnis: 7 juta
                    break;
                case 'first':
                    hargaTiket = 10000000; // First class: 10 juta
                    break;
            }
            
            // Hitung pajak berdasarkan bandara asal
            let pajakAsal = 0;
            switch(asal) {
                case 'Soekarna Hatta':
                    pajakAsal = 65000; // Pajak Soekarna Hatta: 65rb
                    break;
                case 'Husein Sastranegara':
                    pajakAsal = 50000; // Pajak Husein Sastranegara: 50rb
                    break;
                case 'Abdul Rachman Saleh':
                    pajakAsal = 40000; // Pajak Abdul Rachman Saleh: 40rb
                    break;
                case 'Juanda':
                    pajakAsal = 30000; // Pajak Juanda: 30rb
                    break;
            }
            
            // Hitung pajak berdasarkan bandara tujuan
            let pajakTujuan = 0;
            switch(tujuan) {
                case 'Ngurah Rai':
                    pajakTujuan = 85000; // Pajak Ngurah Rai: 85rb
                    break;
                case 'Hasanuddin':
                    pajakTujuan = 70000; // Pajak Hasanuddin: 70rb
                    break;
                case 'Inanwatan':
                    pajakTujuan = 90000; // Pajak Inanwatan: 90rb
                    break;
                case 'Sultan Iskandar Muda':
                    pajakTujuan = 60000; // Pajak Sultan Iskandar Muda: 60rb
                    break;
            }
            
            // Hitung total pajak dan total harga
            const totalPajak = (pajakAsal + pajakTujuan) * jumlahPenumpang;
            const totalHarga = (hargaTiket * jumlahPenumpang) + totalPajak;
            
            // Generate kode tiket unik
            const date = new Date();
            const prefix = 'AS'; // Awalan kode
            const timestamp = `${date.getFullYear().toString().substr(-2)}${String(date.getMonth() + 1).padStart(2, '0')}${String(date.getDate()).padStart(2, '0')}`;
            const random = Math.random().toString(36).substring(2, 8).toUpperCase(); // Karakter acak
            const kodeTiket = prefix + timestamp + random; // Gabungkan menjadi kode tiket
            
            // Siapkan data untuk disimpan
            const flightDataToSave = {
                action: 'save', // Aksi penyimpanan
                maskapai: maskapai,
                asal: asal,
                tujuan: tujuan,
                kelas: kelas,
                kelas_nama: kelasNama,
                nama_kelas: kelasNama, // untuk kompatibilitas dengan kedua kode
                jumlah_penumpang: jumlahPenumpang,
                harga_tiket: hargaTiket * jumlahPenumpang,
                total_harga_tiket: hargaTiket * jumlahPenumpang, // untuk kompatibilitas dengan kedua kode
                pajak: totalPajak,
                total_pajak: totalPajak, // untuk kompatibilitas dengan kedua kode
                total: totalHarga,
                metode_pembayaran: selectedPaymentMethod,
                kode_tiket: kodeTiket,
                tanggal: new Date().toISOString() // Tanggal saat ini
            };
            
            // Kirim data ke server dengan fetch API
            fetch('sesi.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(flightDataToSave).toString() // Konversi objek ke URL encoded string
            })
            .then(response => response.text())
            .then(data => {
                if (data.includes('success')) {
                    // Tutup modal pembayaran
                    paymentModal.style.display = 'none';
                    
                    // Reset form
                    flightForm.reset();
                    
                    // Hapus data form dari session
                    fetch('sesi.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'action=clearFormData' // Parameter untuk menghapus data form
                    });

                    // Reset metode pembayaran yang dipilih
                    selectedPaymentMethod = '';
                    paymentOptions.forEach(opt => opt.classList.remove('active'));
                    
                    // Reload data penerbangan
                    loadFlightsData();
                    
                    // Tampilkan pesan sukses
                    showAlert('Pembayaran berhasil! Data penerbangan telah ditambahkan.', 'success');
                    
                    // Scroll ke hasil
                    document.getElementById('resultContainer').scrollIntoView({
                        behavior: 'smooth', // Scroll halus
                        block: 'start' // Posisi elemen di atas
                    });
                } else {
                    // Tampilkan pesan error
                    const errorMsg = data.includes('message:') ?
                        data.split('message')[1].trim() : 'Proses pembayaran gagal.';
                    showAlert(errorMsg, 'error');

                }
                
                // Reset tombol setelah selesai
                paymentButton.innerHTML = originalText;
                paymentButton.disabled = false;
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Terjadi kesalahan. Silakan coba lagi.', 'error');
                
                // Reset tombol jika terjadi error
                paymentButton.innerHTML = originalText;
                paymentButton.disabled = false;
            });
        }, 1500); // Delay 1.5 detik untuk simulasi proses
    });
    
    // Fungsi untuk mencetak tiket
    function printTicket(flightData) {
        // Buka jendela baru untuk mencetak
        const printWindow = window.open('', '_blank', 'width=800,height=600');
        // Tulis HTML tiket ke jendela baru
        printWindow.document.write(`
            <!DOCTYPE html>
            <html lang="id">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>E-Tiket AetherSky - ${flightData.kode_tiket}</title>
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
                <style>
                    body {
                        font-family: 'Arial', sans-serif;
                        line-height: 1.6;
                        color: #333;
                        padding: 20px;
                        background: #f0f9ff;
                    }
                    .ticket-container {
                        max-width: 800px;
                        margin: 0 auto;
                        border: none;
                        border-radius: 20px;
                        overflow: hidden;
                        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
                        background: white;
                    }
                    .ticket-header {
                        background: linear-gradient(135deg, #3b82f6, #93c5fd);
                        color: white;
                        padding: 30px;
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        position: relative;
                        overflow: hidden;
                    }
                    .ticket-header::before {
                        content: '';
                        position: absolute;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><path d="M0,0 L100,100 M20,0 L100,80 M40,0 L100,60 M60,0 L100,40 M80,0 L100,20 M0,20 L80,100 M0,40 L60,100 M0,60 L40,100 M0,80 L20,100" stroke="rgba(255,255,255,0.1)" stroke-width="1"></path></svg>');
                        opacity: 0.1;
                    }
                    .logo {
                        font-size: 24px;
                        font-weight: bold;
                    }
                    .ticket-code {
                        font-size: 20px;
                        background-color: white;
                        color: #3b82f6;
                        padding: 5px 10px;
                        border-radius: 5px;
                    }
                    .ticket-body {
                        padding: 20px;
                    }
                    .flight-info {
                        display: flex;
                        justify-content: space-between;
                        border-bottom: 1px dashed #ccc;
                        padding-bottom: 20px;
                        margin-bottom: 20px;
                    }
                    .flight-route {
                        font-size: 20px;
                        font-weight: bold;
                    }
                    .flight-date {
                        color: #666;
                    }
                    .passenger-info, .payment-info {
                        margin-bottom: 20px;
                    }
                    .info-row {
                        display: flex;
                        margin-bottom: 10px;
                    }
                    .info-label {
                        width: 200px;
                        font-weight: bold;
                    }
                    .barcode {
                        text-align: center;
                        margin: 20px 0;
                    }
                    .barcode img {
                        max-width: 300px;
                    }
                    .footer {
                        background-color: #f0f9ff;
                        padding: 15px;
                        text-align: center;
                        font-size: 14px;
                    }
                    @media print {
                        .no-print {
                            display: none;
                        }
                    }
                </style>
            </head>
            <body>
                <div class="ticket-container">
                    <div class="ticket-header">
                        <div class="logo">
                            <i class="fas fa-plane"></i> AetherSky
                        </div>
                        <div class="ticket-code">
                            ${flightData.kode_tiket} <!-- Menampilkan kode tiket dari data penerbangan -->
                        </div>
                    </div>
                    <div class="ticket-body">
                        <div class="flight-info">
                            <div>
                                <div class="flight-route">${flightData.asal} → ${flightData.tujuan}</div> <!-- Menampilkan rute penerbangan (asal ke tujuan) -->
                                <div class="flight-date">${formatDate(flightData.tanggal)}</div> <!-- Menampilkan tanggal penerbangan yang sudah diformat -->
                            </div>
                            <div>
                                <div class="flight-airline">${flightData.maskapai}</div> <!-- Menampilkan nama maskapai penerbangan -->
                                <div class="flight-class">${flightData.kelas_nama || flightData.nama_kelas}</div> <!-- Menampilkan kelas penerbangan, mendukung dua format nama variabel -->
                            </div>
                        </div>
                    
                        <div class="passenger-info">
                            <h3>Informasi Penerbangan</h3>
                            <div class="info-row">
                                <div class="info-label">Jumlah Penumpang</div>
                                <div>${flightData.jumlah_penumpang} orang</div> <!-- Menampilkan jumlah penumpang -->
                            </div>
                            <div class="info-row">
                                <div class="info-label">Harga per Tiket</div>
                                <div>${formatRupiah((flightData.harga_tiket || flightData.total_harga_tiket) / flightData.jumlah_penumpang)}</div> <!-- Menampilkan harga per tiket dengan format Rupiah -->
                            </div>
                            <div class="info-row">
                                <div class="info-label">Total Harga Tiket</div>
                                <div>${formatRupiah(flightData.harga_tiket || flightData.total_harga_tiket)}</div> <!-- Menampilkan total harga tiket dengan format Rupiah -->
                            </div>
                            <div class="info-row">
                                <div class="info-label">Pajak</div>
                                <div>${formatRupiah(flightData.pajak || flightData.total_pajak)}</div> <!-- Menampilkan biaya pajak dengan format Rupiah -->
                            </div>
                            <div class="info-row">
                                <div class="info-label">Total Pembayaran</div>
                                <div><strong>${formatRupiah(flightData.total)}</strong></div> <!-- Menampilkan total pembayaran dengan format Rupiah -->
                            </div>
                        </div>

                        <div class="payment-info">
                            <h3>Informasi Pembayaran</h3>
                            <div class="info-row">
                                <div class="info-label">Metode Pembayaran</div>
                                <div>${getPaymentMethodName(flightData.metode_pembayaran)}</div> <!-- Menampilkan nama metode pembayaran yang digunakan -->
                            </div>
                            <div class="info-row">
                                <div class="info-label">Status Pembayaran</div>
                                <div><span style="color: green; font-weight: bold;">LUNAS</span></div> <!-- Menampilkan status pembayaran -->
                            </div>
                        </div>

                        <div class="barcode">
                            <svg width="300" height="80">
                                <rect x="10" y="10" width="280" height="60" fill="white" stroke="#000" stroke-width="1" />
                                ${generateBarcode(flightData.kode_tiket)} <!-- Membuat barcode dari kode tiket -->
                            </svg>
                        </div>
                    </div>
                    <div class="footer">
                        <p>Harap tiba di bandara minimal 2 jam sebelum keberangkatan. Terima kasih telah memilih AetherSky.</p>
                        <p>Untuk informasi lebih lanjut hubungi customer service kami di +62 856 0733 9710.</p>
                    </div>
                </div>
                <div class="no-print" style="text-align: center; margin-top: 20px;">
                    <button onclick="window.print()" style="padding: 10px 20px; background-color: #3b82f6; color: white; border: none; border-radius: 5px; cursor: pointer;">
                        Cetak Tiket
                    </button> <!-- Tombol untuk mencetak tiket, tidak akan muncul saat dicetak -->
                </div>
            </body>
            </html>
        `);

        // Menutup dokumen pada jendela baru
        printWindow.document.close();
    }
    
    // Fungsi untuk membuat barcode sederhana berdasarkan kode tiket
    function generateBarcode(code) {
        let barcodeHtml = '';
        const chars = code.split(''); // Memecah kode tiket menjadi array karakter
        const width = 280 / chars.length; // Menghitung lebar setiap balok barcode
        
        // Iterasi melalui setiap karakter dalam kode tiket
        chars.forEach((char, index) => {
            const x = 10 + (index * width); // Posisi horizontal dari balok barcode
            const height = 20 + (char.charCodeAt(0) % 40);  // Menghitung tinggi balok berdasarkan kode ASCII karakter
            barcodeHtml += `<rect x="${x}" y="${40 - height/2}" width="${width/2}" height="${height}" fill="black" />`; // Membuat elemen SVG untuk balok barcode
        });
        
        return barcodeHtml; // Mengembalikan HTML untuk barcode
    }

    // Fungsi untuk menampilkan pesan alert dengan animasi
    function showAlert(message, type) {
        // Memeriksa apakah sudah ada alert yang ditampilkan sebelumnya
        const existingAlert = document.querySelector('.alert');
        if(existingAlert) {
            existingAlert.remove(); // Menghapus alert lama jika ada
        }
        
        // Membuat elemen alert baru
        const alertElement = document.createElement('div');
        alertElement.className = `alert alert-${type} animate__animated animate__fadeIn`; // Menambahkan kelas untuk animasi
        alertElement.textContent = message; // Mengatur pesan alert
        
        // Memasukkan alert ke dalam container
        const container = document.querySelector('.form-container');
        container.insertBefore(alertElement, flightForm); // Menempatkan alert sebelum form
        
        // Menghapus alert setelah 3 detik dengan animasi fadeOut
        setTimeout(() => {
            alertElement.classList.remove('animate__fadeIn');
            alertElement.classList.add('animate__fadeOut');
            setTimeout(() => {
                alertElement.remove(); // Menghapus elemen alert setelah animasi selesai
            }, 500);
        }, 3000);
    }

    // Fungsi untuk memformat tanggal ke format Indonesia
    function formatDate(dateString) {
        const date = new Date(dateString); // Membuat objek tanggal dari string tanggal
        date.setHours(date.getHours() + 5); // Menambahkan 5 jam ke waktu (mungkin untuk menyesuaikan zona waktu)
        const options = { 
            year: 'numeric', // Format tahun lengkap (misal: 2023)
            month: 'long',   // Nama bulan lengkap (misal: April)
            day: 'numeric',  // Tanggal (misal: 15)
            hour: '2-digit', // Jam dalam format 2 digit (misal: 09)
            minute: '2-digit' // Menit dalam format 2 digit (misal: 05)
        };
        return date.toLocaleDateString('id-ID', options); // Mengembalikan tanggal dalam format Indonesia
    }

    // Event listener untuk saat form direset
    flightForm.addEventListener('reset', function() {
        setTimeout(() => {
            // Menghapus data form dari localStorage
            localStorage.removeItem('flightFormData');
            
            // Mereset semua elemen select ke default (index 0)
            const selects = flightForm.querySelectorAll('select');
            selects.forEach(select => {
                select.selectedIndex = 0;
            });
            
            // Mengosongkan field harga
            document.getElementById('harga').value = '';
        }, 0); // Timeout 0 untuk memastikan reset default selesai terlebih dahulu
    });

    // Mengatur validasi form
    const inputs = flightForm.querySelectorAll('input, select'); // Mengambil semua input dan select dalam form
    inputs.forEach(input => {
        // Event listener untuk validasi saat input nilai berubah
        input.addEventListener('input', function() {
            validateInput(this);
        });
        
        // Event listener untuk validasi saat input kehilangan fokus
        input.addEventListener('blur', function() {
            validateInput(this);
        });
        
        // Event listener untuk menyimpan data form saat nilai berubah
        input.addEventListener('change', saveFormData);
    });

    // Fungsi validasi input
    function validateInput(input) {
        if (!input.value && input.hasAttribute('required')) {
            // Jika input kosong dan wajib diisi, tandai sebagai tidak valid
            input.classList.add('invalid');
            input.classList.remove('valid');
        } else {
            // Jika input terisi atau tidak wajib, tandai sebagai valid
            input.classList.remove('invalid');
            input.classList.add('valid');
        }
    }

    // Mengatur animasi scroll halus untuk link internal
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault(); // Mencegah navigasi default
            
            const targetId = this.getAttribute('href');
            if (targetId === '#') return; // Jika link hanya '#', jangan lakukan apa-apa
            
            // Mencari elemen target
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                // Scroll halus ke elemen target
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Fungsi untuk animasi elemen saat scrolling
    const animateOnScroll = () => {
        // Mencari semua elemen dengan kelas animate__animated tapi belum memiliki animate__fadeIn
        const elements = document.querySelectorAll('.animate__animated:not(.animate__fadeIn)');
        
        elements.forEach(element => {
            // Mendapatkan posisi elemen relatif terhadap viewport
            const elementPosition = element.getBoundingClientRect().top;
            const windowHeight = window.innerHeight;
            
            // Jika elemen sudah masuk viewport (dengan offset 100px)
            if (elementPosition < windowHeight - 100) {
                element.classList.add('animate__fadeIn'); // Memulai animasi fadeIn
            }
        });
    };

    // Menjalankan animasi saat scrolling
    window.addEventListener('scroll', animateOnScroll);

    // Memicu animasi pertama kali halaman dimuat, setelah menunggu 500ms
    setTimeout(animateOnScroll, 500);
});