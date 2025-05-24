<?php
// Mengimpor file method.php yang  mendefinisikan class Book
require_once "method.php";

// Membuat objek baru dari class Book
$obj_book = new Book();

// Mengambil method HTTP request (GET, POST, DELETE) dari server
$request_method = $_SERVER["REQUEST_METHOD"];

// Menggunakan switch statement untuk menangani berbagai jenis HTTP method
switch ($request_method) {
    case 'GET':
        // Jika method adalah GET, cek apakah ada parameter 'id' di URL
        if (!empty($_GET["id"])) {
            // Jika ada parameter 'id', konversi ke integer untuk keamanan
            $id = intval($_GET["id"]);
            // Panggil method get_book untuk mengambil data buku berdasarkan ID tertentu
            $obj_book->get_book($id);
        } else {
            // Jika tidak ada parameter 'id', panggil method get_books untuk mengambil semua data buku
            $obj_book->get_books();
        }
        break;

    case 'POST':
        // Jika method adalah POST, cek apakah ada parameter 'id' di URL
        if(!empty($_GET['id'])){
            // Jika ada parameter 'id', konversi ke integer
            $id = intval($_GET['id']);
            // Panggil method insert_book dengan parameter ID (mungkin untuk update data)
            $obj_book->insert_book($id);
        }else{
            // Jika tidak ada parameter 'id', panggil method insert_book tanpa parameter (untuk insert data baru)
            $obj_book->insert_book();
        }

        break;

    case 'DELETE':
        // Jika method adalah DELETE, cek apakah parameter 'id' kosong
        if(empty($_GET['id'])){
        // Jika parameter 'id' tidak ada, buat response error 400 (Bad Request)
        $response = [
            'status' => 400,
            'message' => 'bad request'
        ];
        // Set header response sebagai JSON
        header('Content-Type: application/json');
        // Kirim response error dalam format JSON
        echo json_encode($response);
    }else{
        // Jika parameter 'id' ada, konversi ke integer
        $id = intval($_GET['id']);
        // Panggil method delete_book untuk menghapus data buku berdasarkan ID
        $obj_book->delete_book($id);
    }

        break;
        
    default:
        // Jika method HTTP tidak dikenali (bukan GET, POST, atau DELETE)
        // Set header response sebagai JSON
        header("Content-Type: application/json");
        // Kirim response error 405 (Method Not Allowed)
        echo json_encode([
            'status' => 405,
            'message' => 'Method Not Allowed'
        ]);
        break;
}