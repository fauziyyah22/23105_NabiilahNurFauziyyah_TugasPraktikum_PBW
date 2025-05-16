<?php

// Mendefinisikan kelas Book untuk menyimpan informasi buku
class Book {
    // Deklarasi property dengan access modifier private
    // - code_book: kode buku dengan format BB00
    // - name: nama buku
    // - qty: jumlah buku dalam stok
    private $code_book;
    private $name;
    private $qty;

    /**
     * Constructor untuk inisialisasi objek Book
     * Memanggil setter private untuk validasi input
     * 
     * @param string $code_book Kode buku format BB00
     * @param string $name Nama buku
     * @param int $qty Jumlah buku (harus positif)
     */
    public function __construct($code_book, $name, $qty) {
        // Memanggil setter private untuk memvalidasi dan menetapkan nilai
        $this->setCodeBook($code_book);
        $this->name = $name; // Set langsung tanpa validasi
        $this->setQty($qty);
    }

    /**
     * Setter private untuk code_book
     * Memvalidasi format kode buku: 2 huruf kapital diikuti 2 angka
     * 
     * @param string $code_book Kode buku yang akan divalidasi
     */
    private function setCodeBook($code_book) {
        // Validasi format dengan regex:
        // ^ - awal string
        // [A-Z]{2} - tepat 2 huruf kapital
        // [0-9]{2} - tepat 2 angka
        // $ - akhir string
        if (preg_match('/^[A-Z]{2}[0-9]{2}$/', $code_book)) {
            $this->code_book = $code_book;
        } else {
            // Tampilkan pesan kesalahan jika format tidak sesuai
            echo "Error: Format kode buku tidak valid. Format harus BB00 (2 huruf kapital diikuti 2 angka).\n";
        }
    }

    /**
     * Getter untuk code_book
     * 
     * @return string Nilai code_book
     */
    public function getCodeBook() {
        // Kembalikan nilai code_book tanpa pemrosesan tambahan
        return $this->code_book;
    }

    /**
     * Setter private untuk qty
     * Memastikan nilai qty berupa integer positif
     * 
     * @param int $qty Jumlah buku yang akan divalidasi
     */
    private function setQty($qty) {
        // Periksa apakah nilai berupa integer dan lebih dari 0
        if (is_int($qty) && $qty > 0) {
            $this->qty = $qty;
        } else {
            // Tampilkan pesan kesalahan jika qty tidak valid
            echo "Error: Jumlah buku harus berupa bilangan bulat positif.\n";
        }
    }

    /**
     * Getter untuk qty
     * 
     * @return int Nilai qty
     */
    public function getQty() {
        // Kembalikan nilai qty tanpa pemrosesan tambahan
        return $this->qty;
    }

    /**
     * Getter untuk name
     * 
     * @return string Nilai name
     */
    public function getName() {
        // Kembalikan nilai name tanpa pemrosesan tambahan
        return $this->name;
    }
}

// Contoh penggunaan kelas Book
// Membuat objek buku dengan format valid
$buku1 = new Book("NF22", "PHP OOP Dasar", 10);
echo "Kode Buku: " . $buku1->getCodeBook() . "\n";
echo "Nama Buku: " . $buku1->getName() . "\n";
echo "Jumlah: " . $buku1->getQty() . "\n";

// Contoh dengan format kode buku tidak valid
$buku2 = new Book("abcd", "PHP Lanjutan", 5);

// Contoh dengan qty tidak valid
$buku3 = new Book("CD34", "Database MySQL", -2);