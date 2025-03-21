// Arrow function untuk melakukan operasi matematika dengan operator tertentu
const calculate = (operator, ...numbers) => {
    // Menggunakan reduce untuk menerapkan operasi matematika ke semua angka dalam array
    return numbers.reduce((acc, num) => {
        switch (operator) { // Mengecek operator yang diberikan
            case '+': return acc + num; // Penjumlahan
            case '-': return acc - num; // Pengurangan
            case '*': return acc * num; // Perkalian
            case '/': return acc / num; // Pembagian
            case '%': return acc % num; // Modulus (sisa bagi)
            default: return 'Operator tidak valid'; // Jika operator tidak dikenali
        }
    });
};

// Contoh pemanggilan fungsi dengan berbagai operator
console.log(`Hasil Penjumlahan (5 + 7 + 3) = ${calculate('+', 5, 7, 3)}`);    // Output: 15
console.log(`Hasil Pengurangan (50 - 25 - 5) = ${calculate('-', 50, 25, 5)}`);    // Output: 20
console.log(`Hasil Perkalian (5 * 2 * 10) = ${calculate('*', 5, 2, 10)}`);        // Output: 100
console.log(`Hasil Pembagian (30 / 10 / 3) = ${calculate('/', 30, 10, 3)}`);    // Output: 1
console.log(`Hasil Modulus (20 % 11) = ${calculate('%', 20, 11)}`);               // Output: 9
