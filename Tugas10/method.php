<?php
// Mengimpor file koneksi.php untuk mendapatkan koneksi database
require_once "koneksi.php";

// Mendefinisikan class Book untuk mengelola operasi CRUD pada tabel books
class Book
{
    // Method untuk mengambil semua data buku dari database
    public function get_books()
    {
        // Mengakses variabel global $koneksi dari file koneksi.php
        global $koneksi;
        
        // Query SQL untuk mengambil semua data dari tabel books
        $query = "SELECT * FROM books";
        
        // Membuat array kosong untuk menampung data hasil query
        $data = array();
        
        // Menjalankan query dan menyimpan hasilnya
        $result = $koneksi->query($query);
        
        // Loop untuk mengambil setiap baris data dari hasil query
        while ($row = mysqli_fetch_object($result)) {
            // Mengkonversi field price menjadi tipe data float
            $row->price = (float) $row->price;
            // Menambahkan data baris ke dalam array $data
            $data[] = $row;
        }

        // Membuat struktur response dengan status, message, dan data
        $response = array(
            'status' => 200,
            'message' => 'Success',
            'data' => $data
        );

        // Set header response sebagai JSON
        header('Content-Type: application/json');
        // Mengirim response dalam format JSON
        echo json_encode($response);
    }

    // Method untuk mengambil data buku tertentu berdasarkan ID
    public function get_book($id = 0)
    {
        // Mengakses variabel global $koneksi dari file koneksi.php
        global $koneksi;
        
        // Query dasar untuk mengambil data dari tabel books
        $query = "SELECT * FROM books";
        
        // Jika parameter ID tidak sama dengan 0, tambahkan kondisi WHERE
        if ($id != 0) {
            $query .= " WHERE id=" . $id . " LIMIT 1";
        }
        
        // Membuat array kosong untuk menampung data hasil query
        $data = array();
        
        // Menjalankan query dan menyimpan hasilnya
        $result = $koneksi->query($query);
        
        // Loop untuk mengambil setiap baris data dari hasil query
        while ($row = mysqli_fetch_object($result)) {
            // Mengkonversi field price menjadi tipe data float
            $row->price = (float) $row->price;
            // Menambahkan data baris ke dalam array $data
            $data[] = $row;
        }

        // Membuat struktur response dengan status, message, dan data
        $response = array(
            'status' => 200,
            'message' => 'Success',
            'data' => $data
        );

        // Set header response sebagai JSON
        header('Content-Type: application/json');
        // Mengirim response dalam format JSON
        echo json_encode($response);
    }

    // Method untuk menambahkan data buku baru ke database
    public function insert_book()
    {
        // Mengakses variabel global $koneksi dari file koneksi.php
        global $koneksi;
        
        // Array untuk mengecek field yang harus ada dalam data POST
        $arrcheckpost = array(
            'name' => '',
            'price' => '',
            'qty' => '',
            'author' => '',
            'publisher' => ''
        );

        // Menghitung jumlah field yang cocok antara $_POST dan $arrcheckpost
        $hitung = count(array_intersect_key($_POST, $arrcheckpost));

        // Mengecek apakah semua field yang dibutuhkan sudah ada
        if ($hitung == count($arrcheckpost)) {
            // Menjalankan query INSERT dengan data dari $_POST
            $result = mysqli_query($koneksi, "INSERT INTO books SET name = '$_POST[name]', price = '$_POST[price]', qty = '$_POST[qty]', author = '$_POST[author]', publisher = '$_POST[publisher]'");
            
            // Mengecek apakah query INSERT berhasil
            if ($result) {
                // Jika berhasil, buat response sukses
                $response = array(
                    'status' => 200,
                    'message' => 'Success'
                );
            } else {
                // Jika gagal, buat response error 500
                $response = array(
                    'status' => 500,
                    'message' => 'Internal server error.'
                );
            }
        } else {
            // Jika field tidak lengkap, buat response error 400
            $response = array(
                'status' => 400,
                'message' => 'Bad Request',
                'error' => "Form data did not match!"
            );
        }

        // Set header response sebagai JSON
        header('Content-Type: application/json');
        // Mengirim response dalam format JSON
        echo json_encode($response);
    }

    // Method untuk menghapus data buku berdasarkan ID
    function delete_book($id) {
        // Mengakses variabel global $koneksi dari file koneksi.php
        global $koneksi;
        
        // Query SQL untuk menghapus data berdasarkan ID
        $query = "DELETE FROM books WHERE id = " . $id;
        
        // Menjalankan query DELETE dan mengecek hasilnya
        if(mysqli_query($koneksi, $query)) {
            // Jika berhasil, buat response sukses
            $response = array(
                'status' => 200,
                'message' => 'Success'
            );
        } else {
            // Jika gagal, buat response error 500
            $response = array(
                'status' => 500,
                'message' => 'Internal server error'
            );
        };
        
        // Set header response sebagai JSON
        header('Content-Type: application/json');
        // Mengirim response dalam format JSON
        echo json_encode($response);
    }
}