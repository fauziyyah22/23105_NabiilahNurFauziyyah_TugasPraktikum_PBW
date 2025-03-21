// Fungsi untuk menghasilkan deret Fibonacci hingga jumlah angka tertentu
function fibonacci(n) {
    // Inisialisasi array dengan dua angka pertama dalam deret Fibonacci
    let fib = [0, 1]; 

    // Perulangan untuk menghitung angka Fibonacci berikutnya hingga n angka
    for (let i = 2; i < n; i++) {
        fib[i] = fib[i - 1] + fib[i - 2]; // Menjumlahkan dua angka sebelumnya
    }

    return fib; // Mengembalikan deret Fibonacci dalam bentuk array
}

// Menampilkan hasil Fibonacci untuk 10 angka pertama
console.log("Deret Fibonacci untuk 10 angka pertama:");
console.log(fibonacci(10).join(', '));
