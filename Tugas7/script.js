/**
 * script.js - File JavaScript untuk sistem akademik dengan efek modern
 */

document.addEventListener('DOMContentLoaded', function() {
    // Tambahkan partikel background
    createParticles();
    
    // Highlight form jika ada pesan error atau sukses
    const alertMessages = document.querySelectorAll('.alert');
    if (alertMessages.length > 0) {
        animateAlerts(alertMessages);
    }

    // Validasi form mahasiswa
    setupFormValidation('form-mahasiswa', 'npm', 'NPM harus berjumlah 13 karakter dan hanya angka!');
    setupFormValidation('form-matakuliah', 'kodemk', 'Kode Mata Kuliah harus berjumlah 6 karakter!');
    
    // Live search pada tabel
    setupTableSearch();
    
    // Konfirmasi hapus data dengan animasi
    setupDeleteConfirmation();
    
    // Sort tabel ketika header diklik
    setupTableSorting();
    
    // Highlight row yang baru ditambahkan/diupdate
    highlightUpdatedRows();
    
    // Inisialisasi tooltip
    initTooltips();
    
    // Filter KRS berdasarkan mahasiswa
    setupKRSFilter();
    
    // Animasi hover card
    animateCards();
    
    // Floating action button
    createFAB();
    
    // Efek parallax untuk header
    setupParallax();
});

/**
 * Membuat partikel background
 */
function createParticles() {
    const container = document.querySelector('body');
    const particleCount = 30;
    
    for (let i = 0; i < particleCount; i++) {
        const particle = document.createElement('div');
        particle.classList.add('particle');
        
        // Random properties
        const size = Math.random() * 10 + 5;
        const posX = Math.random() * 100;
        const posY = Math.random() * 100;
        const opacity = Math.random() * 0.5 + 0.1;
        const delay = Math.random() * 5;
        const duration = Math.random() * 10 + 10;
        
        particle.style.width = `${size}px`;
        particle.style.height = `${size}px`;
        particle.style.left = `${posX}%`;
        particle.style.top = `${posY}%`;
        particle.style.opacity = opacity;
        particle.style.animation = `float ${duration}s ease-in-out ${delay}s infinite alternate`;
        
        // Random gradient
        const colors = ['#8a2be2', '#ff69b4', '#e91e63', '#9c27b0'];
        const color1 = colors[Math.floor(Math.random() * colors.length)];
        const color2 = colors[Math.floor(Math.random() * colors.length)];
        particle.style.background = `linear-gradient(135deg, ${color1}, ${color2})`;
        
        container.appendChild(particle);
    }
}

/**
 * Animasi untuk alert
 */
function animateAlerts(alerts) {
    alerts.forEach((alert, index) => {
        alert.style.opacity = '0';
        alert.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            alert.style.transition = 'all 0.5s ease-out';
            alert.style.opacity = '1';
            alert.style.transform = 'translateY(0)';
            
            // Floating effect
            setInterval(() => {
                alert.style.transform = 'translateY(-5px)';
                setTimeout(() => {
                    alert.style.transform = 'translateY(0)';
                }, 1000);
            }, 2000 + index * 300);
        }, index * 200);
        
        // Highlight form terkait
        const formCard = alert.closest('.container').querySelector('.card:has(form)');
        if (formCard) {
            formCard.style.animation = 'pulse 2s';
        }
    });
}

/**
 * Setup validasi form
 */
function setupFormValidation(formId, fieldId, errorMessage) {
    const form = document.getElementById(formId);
    if (!form) return;
    
    form.addEventListener('submit', function(e) {
        const field = document.getElementById(fieldId);
        if (!field) return;
        
        let isValid = true;
        
        if (fieldId === 'npm') {
            // Validasi NPM harus berisi 13 karakter dan hanya angka
            if (field.value.length !== 13 || !/^\d+$/.test(field.value)) {
                isValid = false;
            }
        } else if (fieldId === 'kodemk') {
            // Validasi kode MK harus berisi 6 karakter
            if (field.value.length !== 6) {
                isValid = false;
            }
        }
        
        if (!isValid) {
            e.preventDefault();
            showToast(errorMessage, 'error');
            field.classList.add('is-invalid');
            
            // Animasi shake
            field.style.animation = 'shake 0.5s';
            setTimeout(() => {
                field.style.animation = '';
            }, 500);
            
            return false;
        }
    });

    // Reset validasi saat input diubah
    const field = document.getElementById(fieldId);
    if (field) {
        field.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    }
}

/**
 * Setup live search untuk tabel
 */
function setupTableSearch() {
    const searchInputs = document.querySelectorAll('.search-table');
    searchInputs.forEach(input => {
        input.addEventListener('keyup', function() {
            const tableId = this.getAttribute('data-table');
            const table = document.getElementById(tableId);
            if (!table) return;
            
            const rows = table.querySelectorAll('tbody tr');
            const searchText = this.value.toLowerCase();
            
            rows.forEach((row, index) => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchText)) {
                    row.style.display = '';
                    row.style.animation = `fadeIn 0.3s ${index * 0.05}s both`;
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
}

/**
 * Setup konfirmasi hapus dengan animasi
 */
function setupDeleteConfirmation() {
    const deleteButtons = document.querySelectorAll('[onclick*="confirm"]');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.getAttribute('href');
            
            // Buat elemen konfirmasi custom
            const confirmBox = document.createElement('div');
            confirmBox.className = 'confirm-box';
            confirmBox.innerHTML = `
                <div class="confirm-content">
                    <h5>Konfirmasi Hapus</h5>
                    <p>Apakah Anda yakin ingin menghapus data ini?</p>
                    <div class="confirm-buttons">
                        <button class="btn btn-danger btn-confirm">Ya, Hapus</button>
                        <button class="btn btn-secondary btn-cancel">Batal</button>
                    </div>
                </div>
            `;
            
            document.body.appendChild(confirmBox);
            
            // Animasi muncul
            setTimeout(() => {
                confirmBox.style.opacity = '1';
                confirmBox.querySelector('.confirm-content').style.transform = 'scale(1)';
            }, 10);
            
            // Handle klik
            confirmBox.querySelector('.btn-confirm').addEventListener('click', () => {
                // Animasi sebelum redirect
                confirmBox.querySelector('.confirm-content').style.transform = 'scale(0.8)';
                confirmBox.style.opacity = '0';
                setTimeout(() => {
                    window.location.href = url;
                }, 300);
            });
            
            confirmBox.querySelector('.btn-cancel').addEventListener('click', () => {
                // Animasi hilang
                confirmBox.querySelector('.confirm-content').style.transform = 'scale(0.8)';
                confirmBox.style.opacity = '0';
                setTimeout(() => {
                    confirmBox.remove();
                }, 300);
            });
        });
    });
}

/**
 * Setup sorting tabel
 */
function setupTableSorting() {
    const sortableHeaders = document.querySelectorAll('.sortable');
    sortableHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const table = this.closest('table');
            const index = Array.from(this.parentElement.children).indexOf(this);
            const direction = this.classList.contains('sort-asc') ? 'desc' : 'asc';
            
            // Reset semua header
            table.querySelectorAll('th').forEach(th => {
                th.classList.remove('sort-asc', 'sort-desc');
            });
            
            // Set class untuk sorting
            this.classList.add(`sort-${direction}`);
            
            // Sort tabel dengan animasi
            sortTableWithAnimation(table, index, direction === 'asc');
        });
    });
}

/**
 * Sort tabel dengan animasi
 */
function sortTableWithAnimation(table, column, asc) {
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    
    // Sort rows
    rows.sort((a, b) => {
        const cellA = a.cells[column].textContent.trim();
        const cellB = b.cells[column].textContent.trim();
        
        // Cek apakah nilai berupa angka
        if (!isNaN(cellA) && !isNaN(cellB)) {
            return asc ? parseFloat(cellA) - parseFloat(cellB) : parseFloat(cellB) - parseFloat(cellA);
        }
        
        // Jika bukan angka, sort sebagai string
        return asc ? cellA.localeCompare(cellB) : cellB.localeCompare(cellA);
    });
    
    // Animasi fade out
    rows.forEach(row => {
        row.style.opacity = '0';
        row.style.transform = 'translateY(20px)';
    });
    
    // Hapus rows sekarang
    setTimeout(() => {
        while (tbody.firstChild) {
            tbody.removeChild(tbody.firstChild);
        }
        
        // Tambahkan rows yang sudah disort dengan animasi
        rows.forEach((row, index) => {
            row.style.opacity = '0';
            row.style.transform = 'translateY(20px)';
            row.style.transition = 'all 0.3s ease-out';
            tbody.appendChild(row);
            
            setTimeout(() => {
                row.style.opacity = '1';
                row.style.transform = 'translateY(0)';
            }, index * 50);
        });
    }, 300);
}

/**
 * Highlight row yang baru diupdate
 */
function highlightUpdatedRows() {
    if (window.location.search.includes('status=success')) {
        const tableRows = document.querySelectorAll('tbody tr');
        if (tableRows.length > 0) {
            tableRows[0].classList.add('highlight-row');
            
            // Tambahkan icon
            const icon = document.createElement('i');
            icon.className = 'bi bi-check-circle-fill text-success ms-2';
            tableRows[0].querySelector('td:last-child').appendChild(icon);
        }
    }
}

/**
 * Inisialisasi tooltip
 */
function initTooltips() {
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltips.forEach(tooltip => {
            new bootstrap.Tooltip(tooltip);
        });
    }
}

/**
 * Setup filter KRS
 */
function setupKRSFilter() {
    const mahasiswaFilter = document.getElementById('filter-mahasiswa');
    if (!mahasiswaFilter) return;
    
    mahasiswaFilter.addEventListener('change', function() {
        const npm = this.value;
        const rows = document.querySelectorAll('#krs-table tbody tr');
        
        rows.forEach(row => {
            if (npm === '' || row.getAttribute('data-npm') === npm) {
                row.style.display = '';
                row.style.animation = 'fadeIn 0.5s';
            } else {
                row.style.display = 'none';
            }
        });
    });
}

/**
 * Animasi card hover
 */
function animateCards() {
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-10px) rotateZ(1deg)';
            card.style.boxShadow = '0 15px 30px rgba(138, 43, 226, 0.3)';
        });
        
        card.addEventListener('mouseleave', () => {
            card.style.transform = '';
            card.style.boxShadow = '';
        });
    });
}

/**
 * Buat floating action button
 */
function createFAB() {
    const fab = document.createElement('a');
    fab.className = 'fab';
    fab.href = '#';
    fab.innerHTML = '<i class="bi bi-arrow-up"></i>';
    fab.title = 'Kembali ke Atas';
    
    fab.addEventListener('click', (e) => {
        e.preventDefault();
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
    
    document.body.appendChild(fab);
    
    // Tampilkan hanya saat scroll ke bawah
    window.addEventListener('scroll', () => {
        if (window.scrollY > 300) {
            fab.style.opacity = '1';
            fab.style.pointerEvents = 'auto';
        } else {
            fab.style.opacity = '0';
            fab.style.pointerEvents = 'none';
        }
    });
}

/**
 * Efek parallax untuk header
 */
function setupParallax() {
    const header = document.querySelector('.page-header');
    if (!header) return;
    
    window.addEventListener('scroll', () => {
        const scrollPosition = window.scrollY;
        header.style.backgroundPositionY = `${scrollPosition * 0.5}px`;
    });
}

/**
 * Fungsi untuk menampilkan toast notification
 */
function showToast(message, type = 'info') {
    const toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        const container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
        document.body.appendChild(container);
    }
    
    const toastEl = document.createElement('div');
    toastEl.className = `toast show align-items-center text-white bg-${type === 'error' ? 'danger' : type}`;
    toastEl.setAttribute('role', 'alert');
    toastEl.setAttribute('aria-live', 'assertive');
    toastEl.setAttribute('aria-atomic', 'true');
    
    // Warna berbeda untuk toast
    const colors = {
        success: 'linear-gradient(135deg, #4caf50, #2e7d32)',
        error: 'linear-gradient(135deg, #f44336, #c62828)',
        info: 'linear-gradient(135deg, #8a2be2, #6a5acd)',
        warning: 'linear-gradient(135deg, #ff9800, #ff5722)'
    };
    
    toastEl.innerHTML = `
        <div class="d-flex" style="background: ${colors[type] || colors.info}; border-radius: 8px;">
            <div class="toast-body d-flex align-items-center">
                <i class="bi ${type === 'success' ? 'bi-check-circle-fill' : 
                               type === 'error' ? 'bi-exclamation-circle-fill' : 
                               type === 'warning' ? 'bi-exclamation-triangle-fill' : 
                               'bi-info-circle-fill'} me-2"></i>
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-3 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    `;
    
    document.getElementById('toast-container').appendChild(toastEl);
    
    // Auto hide setelah 3 detik
    setTimeout(() => {
        toastEl.classList.remove('show');
        toastEl.classList.add('hide');
        setTimeout(() => {
            toastEl.remove();
        }, 300);
    }, 3000);
    
    // Bisa di-close manual
    toastEl.querySelector('[data-bs-dismiss="toast"]').addEventListener('click', () => {
        toastEl.classList.remove('show');
        toastEl.classList.add('hide');
        setTimeout(() => {
            toastEl.remove();
        }, 300);
    });
}

/**
 * Fungsi untuk mencetak data dengan style khusus
 */
function printData(divId) {
    const printContents = document.getElementById(divId).outerHTML;
    const originalContents = document.body.innerHTML;
    
    document.body.innerHTML = `
        <div class="container">
            <div class="print-header text-center mb-4">
                <h2 style="color: #8a2be2; font-weight: 700;">Laporan Sistem Akademik</h2>
                <p style="color: #666;">Tanggal Cetak: ${new Date().toLocaleDateString('id-ID', { 
                    weekday: 'long', 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric' 
                })}</p>
                <hr style="border-top: 2px solid #8a2be2; opacity: 0.3;">
            </div>
            ${printContents}
            <div class="text-center mt-4">
                <button class="btn btn-primary no-print" onclick="window.print()">
                    <i class="bi bi-printer"></i> Cetak
                </button>
                <button class="btn btn-secondary no-print" onclick="window.close()">
                    <i class="bi bi-x-circle"></i> Tutup
                </button>
            </div>
        </div>
        <style>
            body { 
                padding: 20px; 
                background: white !important;
                color: #333 !important;
            }
            .print-header { 
                margin-bottom: 20px; 
            }
            table { 
                width: 100%; 
                border-collapse: collapse; 
                margin-bottom: 20px;
            }
            th { 
                background: linear-gradient(135deg, #8a2be2 0%, #ff69b4 100%) !important; 
                color: white !important;
                padding: 10px !important;
                text-align: left;
            }
            td { 
                padding: 8px 10px !important;
                border-bottom: 1px solid #eee;
            }
            tr:nth-child(even) { 
                background-color: #f9f9f9 !important; 
            }
            .no-print { 
                display: none; 
            }
            @media print {
                .no-print { 
                    display: none !important; 
                }
                body { 
                    padding: 0 !important; 
                }
                .container { 
                    max-width: 100% !important; 
                }
            }
        </style>
    `;
    
    window.print();
    document.body.innerHTML = originalContents;
}

// Tambahkan style untuk animasi tambahan
const style = document.createElement('style');
style.textContent = `
    @keyframes pulse {
        0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(138, 43, 226, 0.7); }
        70% { transform: scale(1.02); box-shadow: 0 0 0 10px rgba(138, 43, 226, 0); }
        100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(138, 43, 226, 0); }
    }
    
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
        20%, 40%, 60%, 80% { transform: translateX(5px); }
    }
    
    @keyframes float {
        0% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(5deg); }
        100% { transform: translateY(0) rotate(0deg); }
    }
    
    .confirm-box {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        opacity: 0;
        transition: opacity 0.3s;
    }
    
    .confirm-content {
        background: white;
        padding: 25px;
        border-radius: 15px;
        max-width: 400px;
        width: 90%;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        transform: scale(0.8);
        transition: transform 0.3s;
    }
    
    .confirm-content h5 {
        color: #8a2be2;
        margin-bottom: 15px;
    }
    
    .confirm-buttons {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }
    
    .btn-confirm, .btn-cancel {
        flex: 1;
    }
`;
document.head.appendChild(style);

// Validasi form
(function () {
  'use strict'
  
  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  var forms = document.querySelectorAll('.needs-validation')
  
  // Loop over them and prevent submission
  Array.prototype.slice.call(forms)
    .forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }
        
        form.classList.add('was-validated')
      }, false)
    })
})();

// Fungsi untuk menampilkan toast
function showToast(message, type = 'success') {
  const toastContainer = document.getElementById('toast-container');
  const toast = document.createElement('div');
  
  toast.className = `toast show align-items-center text-white bg-${type}`;
  toast.setAttribute('role', 'alert');
  toast.setAttribute('aria-live', 'assertive');
  toast.setAttribute('aria-atomic', 'true');
  
  toast.innerHTML = `
    <div class="d-flex">
      <div class="toast-body">
        ${message}
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  `;
  
  toastContainer.appendChild(toast);
  
  // Auto hide setelah 5 detik
  setTimeout(() => {
    toast.remove();
  }, 5000);
}

// Inisialisasi tooltip
document.addEventListener('DOMContentLoaded', function() {
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
  tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });
});