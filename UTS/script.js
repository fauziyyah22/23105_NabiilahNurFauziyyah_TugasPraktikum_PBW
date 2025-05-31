// DATA SECTION
// Data menu kopi dengan harga lebih murah
const menuData = [
    // Setiap item menu memiliki:
    // - id: identifier unik
    // - name: nama menu
    // - description: deskripsi produk
    // - price: harga dalam format string
    // - icon: emoji representasi menu
    {
        id: 1,
        name: "Espresso Classic",
        description: "Kopi espresso murni dengan cita rasa yang kuat dan aroma yang menggoda",
        price: "Rp 15.000",
        icon: "☕"
    },
    {
        id: 2,
        name: "Cappuccino Deluxe",
        description: "Perpaduan sempurna espresso dengan susu berbusa lembut dan taburan kayu manis",
        price: "Rp 20.000",
        icon: "🥛"
    },
    {
        id: 3,
        name: "Latte Art Special",
        description: "Latte dengan seni foam art yang cantik, cocok untuk foto Instagram",
        price: "Rp 22.000",
        icon: "🎨"
    },
    {
        id: 4,
        name: "Americano Bold",
        description: "Espresso yang diencerkan dengan air panas, memberikan rasa yang bold namun smooth",
        price: "Rp 18.000",
        icon: "☕"
    },
    {
        id: 5,
        name: "Mocha Chocolate",
        description: "Kombinasi kopi espresso dengan cokelat premium dan whipped cream",
        price: "Rp 25.000",
        icon: "🍫"
    },
    {
        id: 6,
        name: "Cold Brew Urban",
        description: "Kopi dingin yang diseduh selama 12 jam dengan es batu dan gula aren",
        price: "Rp 20.000",
        icon: "🧊"
    },
    {
        id: 7,
        name: "Affogato Sweet",
        description: "Es krim vanilla yang disiram dengan espresso panas, dessert coffee yang unik",
        price: "Rp 20.000",
        icon: "🍦"
    },
    {
        id: 8,
        name: "Matcha Latte Fusion",
        description: "Perpaduan unik matcha premium dengan espresso, memberikan rasa yang eksotis",
        price: "Rp 24.000",
        icon: "🍵"
    }
];

// Data testimoni pelanggan
const testimoniData = [
    // Setiap testimoni memiliki:
    // - id: identifier unik
    // - name: nama pelanggan
    // - avatar: inisial untuk avatar
    // - text: isi testimoni
    // - rating: jumlah bintang (1-5)
    {
        id: 1,
        name: "Sarah Wijaya",
        avatar: "S",
        text: "Urban Roast benar-benar menghadirkan pengalaman kopi yang luar biasa! Rasanya autentik dan pelayanannya sangat memuaskan. Cappuccino Deluxe-nya adalah yang terbaik!",
        rating: 5
    },
    {
        id: 2,
        name: "Andi Pratama",
        avatar: "A",
        text: "Sebagai penikmat kopi sejati, saya sangat terkesan dengan kualitas Urban Roast. Cold Brew Urban-nya memiliki cita rasa yang sempurna, tidak terlalu asam dan tidak terlalu pahit.",
        rating: 5
    },
    {
        id: 3,
        name: "Maya Sari",
        avatar: "M",
        text: "Latte Art Special-nya bukan hanya cantik tapi juga enak! Perfect untuk meeting client atau hangout dengan teman. Tempatnya juga nyaman dan instagramable.",
        rating: 5
    },
    {
        id: 4,
        name: "Budi Santoso",
        avatar: "B",
        text: "Sudah langganan Urban Roast sejak 6 bulan lalu. Konsistensi rasa dan kualitasnya selalu terjaga. Espresso Classic-nya benar-benar otentik seperti di Italia!",
        rating: 5
    },
    {
        id: 5,
        name: "Dina Permata",
        avatar: "D",
        text: "Mocha Chocolate Urban Roast adalah favorit saya! Cokelatnya premium dan tidak terlalu manis. Pelayanan via WhatsApp juga cepat dan responsif.",
        rating: 4
    }
];

// STATE MANAGEMENT
let currentTestimoni = 0; // Indeks testimoni aktif
let sidebarOpen = false; // Status sidebar (buka/tutup)

// DOM Elements
const sidebar = document.getElementById('sidebar');
const sidebarToggle = document.getElementById('sidebarToggle');
const menuGrid = document.getElementById('menuGrid');
const testimoniSlider = document.getElementById('testimoniSlider');
const loadingScreen = document.getElementById('loadingScreen');
const kontakForm = document.getElementById('kontakForm');

// INISIALISASI
document.addEventListener('DOMContentLoaded', function() {
    initializeApp();
});

function initializeApp() {
    // Menampilkan loading screen
    showLoadingScreen();
    
    // Inisialisasi komponen setelah loading
    setTimeout(() => {
        hideLoadingScreen();
        initializeMenu(); // Memuat menu kopi
        initializeTestimoni(); // Memuat testimoni
        initializeEventListeners(); // Setup event listeners
        initializeAnimations(); // Setup animasi
        initializeScrollAnimations(); // Setup animasi scroll
    }, 3000); // Delay 3 detik untuk simulasi loading
}

// FUNGSI LOADING SCREEN
function showLoadingScreen() {
    // Menampilkan loading screen dengan animasi
    gsap.set(loadingScreen, { display: 'flex', opacity: 1 });
}

function hideLoadingScreen() {
    // Menyembunyikan loading screen dengan animasi fade out
    gsap.to(loadingScreen, {
        opacity: 0,
        duration: 0.5,
        ease: "power2.out",
        onComplete: () => {
            loadingScreen.style.display = 'none';
        }
    });
}

// FUNGSI MENU
function initializeMenu() {
    // Mengosongkan grid menu
    if (!menuGrid) return;
    
    menuGrid.innerHTML = '';
    
    // Membuat elemen menu untuk setiap item
    menuData.forEach((item, index) => {
        const menuItem = createMenuItemElement(item);
        menuGrid.appendChild(menuItem);
    });
}

function createMenuItemElement(item) {
    // Membuat elemen DOM untuk satu item menu
    const menuItem = document.createElement('div');
    menuItem.className = 'menu-item';
    menuItem.innerHTML = `
        <div class="menu-item-image">
            <span style="font-size: 3rem;">${item.icon}</span>
        </div>
        <div class="menu-item-content">
            <h3 class="menu-item-title">${item.name}</h3>
            <p class="menu-item-description">${item.description}</p>
            <div class="menu-item-footer">
                <span class="menu-item-price">${item.price}</span>
                <button class="order-button" onclick="orderWhatsApp('${item.name}')">
                    <i class="fab fa-whatsapp"></i>
                    Order
                </button>
            </div>
        </div>
    `;
    return menuItem;
}

// FUNGSI TESTIMONI
function initializeTestimoni() {
    if (!testimoniSlider) return;

    // Mengisi slider testimoni
    testimoniSlider.innerHTML = `
        <div class="testimoni-track" id="testimoniTrack">
            ${testimoniData.map(item => createTestimoniElement(item)).join('')}
        </div>
    `;
    
    // Auto slide testimoni
    setInterval(nextTestimoni, 5000);
}

function createTestimoniElement(item) {
    // Membuat elemen DOM untuk satu testimoni
    const stars = '★'.repeat(item.rating) + '☆'.repeat(5 - item.rating);
    return `
        <div class="testimoni-item">
            <div class="testimoni-avatar">${item.avatar}</div>
            <p class="testimoni-text">"${item.text}"</p>
            <h4 class="testimoni-name">${item.name}</h4>
            <div class="testimoni-rating">${stars}</div>
        </div>
    `;
}

function nextTestimoni() {
    // Geser ke testimoni berikutnya
    currentTestimoni = (currentTestimoni + 1) % testimoniData.length;
    updateTestimoniSlider();
}

function prevTestimoni() {
    // Geser ke testimoni sebelumnya
    currentTestimoni = currentTestimoni === 0 ? testimoniData.length - 1 : currentTestimoni - 1;
    updateTestimoniSlider();
}

function updateTestimoniSlider() {
    // Animasi perpindahan slider testimoni
    const track = document.getElementById('testimoniTrack');
    if (track) {
        gsap.to(track, {
            x: `-${currentTestimoni * 100}%`,
            duration: 0.5,
            ease: "power2.inOut"
        });
    }
}

// EVENT LISTENER
function initializeEventListeners() {
    // Toggle Sidebar
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', toggleSidebar);
    }
    
    // Navigation Links
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const section = link.getAttribute('data-section');
            scrollToSection(section);
            updateActiveNav(link);
            
            // Tutup sidebar di mobile
            if (window.innerWidth <= 768) {
                closeSidebar();
            }
        });
    });
    
    // Scroll Indicator
    const scrollIndicator = document.querySelector('.scroll-indicator');
    if (scrollIndicator) {
        scrollIndicator.addEventListener('click', () => {
            scrollToSection('menu');
        });
    }
    
    // Formulir Kontak
    if (kontakForm) {
        kontakForm.addEventListener('submit', handleContactFormSubmit);
    }
    
    // Tutup sidebar saat mengklik di luar (sidebar)
    document.addEventListener('click', (e) => {
        if (sidebarOpen && !sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
            closeSidebar();
        }
    });
    
    // Intersection Observer untuk animasi saat menggulir (scroll)    
        const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, observerOptions);
    
    // Mengamati elemen-elemen untuk animasi saat menggulir
    const animatedElements = document.querySelectorAll('.fade-in, .slide-in-left, .slide-in-right');
    animatedElements.forEach(el => observer.observe(el));
}

// Sidebar Functions
function toggleSidebar() {
    if (sidebarOpen) {
        closeSidebar();
    } else {
        openSidebar();
    }
}

function openSidebar() {
    sidebar.classList.add('active');
    sidebarOpen = true;
    
    gsap.to(sidebarToggle, {
        rotation: 90,
        duration: 0.3
    });
}

function closeSidebar() {
    sidebar.classList.remove('active');
    sidebarOpen = false;
    
    gsap.to(sidebarToggle, {
        rotation: 0,
        duration: 0.3
    });
}

// Navigation Functions
function scrollToSection(sectionId) {
    const section = document.getElementById(sectionId);
    if (section) {
        section.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
}

function updateActiveNav(activeLink) {
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => link.classList.remove('active'));
    activeLink.classList.add('active');
}

// WhatsApp Order Function (untuk tombol order di menu)
function orderWhatsApp(productName) {
    const phoneNumber = '6285174200556'; 
    const message = `Halo Urban Roast! Saya tertarik untuk memesan ${productName}. Mohon informasi lebih lanjut.`;
    const whatsappUrl = `https://wa.me/${phoneNumber}?text=${encodeURIComponent(message)}`;
    window.open(whatsappUrl, '_blank');
}

// Function untuk membuat elemen menu item
function createMenuItemElement(item) {
    const menuItem = document.createElement('div');
    menuItem.className = 'menu-item';
    menuItem.innerHTML = `
        <div class="menu-item-image">
            <span style="font-size: 3rem;">${item.icon}</span>
        </div>
        <div class="menu-item-content">
            <h3 class="menu-item-title">${item.name}</h3>
            <p class="menu-item-description">${item.description}</p>
            <div class="menu-item-footer">
                <span class="menu-item-price">${item.price}</span>
                <button class="order-button" onclick="orderWhatsApp('${item.name}')">
                    <i class="fab fa-whatsapp"></i>
                    Order
                </button>
            </div>
        </div>
    `;
    return menuItem;
}

// Function untuk populate dropdown menu
function populateMenuDropdown() {
    const selectElement = document.getElementById('pesanan');
    
    if (!selectElement) return; // Jika element tidak ada, keluar dari function
    
    selectElement.innerHTML = '<option value="">-- Pilih Menu --</option>';
    
    menuData.forEach(item => {
        const option = document.createElement('option');
        option.value = item.name;
        option.textContent = `${item.name} - ${item.price}`;
        selectElement.appendChild(option);
    });
}

// Contact Form Handler (untuk form kontak yang lebih sederhana jika ada)
function handleContactFormSubmit(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const nama = formData.get('nama');
    const nomor = formData.get('nomor');
    const pesan = formData.get('pesan');
    
    if (!nama || !nomor || !pesan) {
        alert('Mohon lengkapi semua field!');
        return;
    }
    
    const phoneNumber = '6285174200556'; 
    const message = `Halo Urban Roast!

Nama: ${nama}
Nomor: ${nomor}
Pesan: ${pesan}

Terima kasih!`;
    
    const whatsappUrl = `https://wa.me/${phoneNumber}?text=${encodeURIComponent(message)}`;
    window.open(whatsappUrl, '_blank');
    
    // Reset form
    e.target.reset();
    
    // Menampilkan pesan berhasil
    showNotification('Pesan berhasil dikirim! Anda akan diarahkan ke WhatsApp.', 'success');
}

// Function untuk handle form submission (form kontak detail dengan dropdown menu)
function handleDetailedFormSubmission() {
    const form = document.getElementById('kontakForm');
    const nomorError = document.getElementById('nomor-error');
    
    if (!form) return; // Jika form tidak ada, keluar dari function
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get form data
        const nama = document.getElementById('nama').value;
        const nomor = document.getElementById('nomor').value;
        const pesanan = document.getElementById('pesanan').value;
        const jumlah = document.getElementById('jumlah').value;
        const catatan = document.getElementById('catatan').value;
        
        // Validasi nomor telepon
        const phoneRegex = /^[0-9]+$/;
        if (!phoneRegex.test(nomor)) {
            if (nomorError) {
                nomorError.style.display = 'block';
            }
            return;
        } else {
            if (nomorError) {
                nomorError.style.display = 'none';
            }
        }
        
        // Membuat pesan WhatsApp
        const phoneNumber = '6285174200556';
        let message = `Halo Urban Roast!\n\n`;
        message += `Nama: ${nama}\n`;
        message += `Nomor WhatsApp: ${nomor}\n`;
        message += `Pesanan: ${pesanan}\n`;
        message += `Jumlah: ${jumlah}\n`;
        
        if (catatan) {
            message += `Catatan: ${catatan}\n`;
        }
        
        message += `\nMohon konfirmasi ketersediaan dan total harga. Terima kasih!`;
        
        // Buka WhatsApp
        const whatsappUrl = `https://wa.me/${phoneNumber}?text=${encodeURIComponent(message)}`;
        window.open(whatsappUrl, '_blank');
        
        // Reset formulir
        form.reset();
    });
}

// Function untuk menampilkan menu items (jika diperlukan di bagian lain)
function initializeMenu() {
    const menuContainer = document.querySelector('.menu-grid');
    if (menuContainer) {
        menuContainer.innerHTML = '';
        menuData.forEach(item => {
            const menuElement = createMenuItemElement(item);
            menuContainer.appendChild(menuElement);
        });
    }
}

// Function untuk show notification (jika diperlukan)
function showNotification(message, type = 'info') {
    // Buat element notification sederhana
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#25d366' : '#d4af37'};
        color: white;
        padding: 15px 20px;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        z-index: 9999;
        font-weight: 500;
    `;
    
    document.body.appendChild(notification);
    
    // Hapus notification setelah 3 detik
    setTimeout(() => {
        if (notification.parentElement) {
            notification.parentElement.removeChild(notification);
        }
    }, 3000);
}

// Inisialisasi saat DOM telah dimuat
document.addEventListener('DOMContentLoaded', function() {
    // Mengisi dropdown menu
    populateMenuDropdown();
    
    // Menangani pengiriman form detail (form dengan dropdown menu)
    handleDetailedFormSubmission();
    
    // Inisialisasi tampilan menu
    initializeMenu();
    
    // Menangani form kontak sederhana jika ada
    const simpleContactForm = document.querySelector('form[onsubmit*="handleContactFormSubmit"]');
    if (simpleContactForm) {
        simpleContactForm.addEventListener('submit', handleContactFormSubmit);
    }
});


// Fungsi-fungsi Animasi
function initializeAnimations() {
    // Animasi pada bagian hero (bagian utama di atas halaman)
    gsap.from('.hero-title .title-line', {
        duration: 1,
        y: 50,
        opacity: 0,
        stagger: 0.2,
        ease: "power3.out"
    });

    gsap.from('.hero-description', {
        duration: 1,
        y: 30,
        opacity: 0,
        delay: 0.6,
        ease: "power2.out"
    });

    gsap.from('.hero-buttons', {
        duration: 1,
        y: 30,
        opacity: 0,
        delay: 0.8,
        ease: "power2.out"
    });

    gsap.to('.coffee-cup', {
        y: -20,
        duration: 3,
        repeat: -1,
        yoyo: true,
        ease: "sine.inOut"
    });

    // Animasi saat bagian-bagian halaman muncul ketika digulir
    const sections = document.querySelectorAll('section');
    sections.forEach((section, index) => {
        if (index > 0) { 
            gsap.from(section, {
                scrollTrigger: {
                    trigger: section,
                    start: "top 80%",
                    toggleActions: "play none none none"
                },
                y: 100,
                opacity: 0,
                duration: 1,
                ease: "power3.out"
            });
        }
    });

    // Animasi untuk item-item menu
    gsap.from('.menu-item', {
        scrollTrigger: {
            trigger: '.menu-section',
            start: "top 70%",
            toggleActions: "play none none none"
        },
        y: 50,
        opacity: 0,
        stagger: 0.1,
        duration: 0.8,
        ease: "back.out(1.7)"
    });

    // Animasi untuk kartu promo
    gsap.from('.promo-card', {
        scrollTrigger: {
            trigger: '.promo-section',
            start: "top 70%",
            toggleActions: "play none none none"
        },
        y: 100,
        stagger: 0.2,
        duration: 0.8,
        ease: "power3.out"
    });

    // Animasi untuk bagian testimoni
    gsap.from('.testimoni-item', {
        scrollTrigger: {
            trigger: '.testimoni-section',
            start: "top 70%",
            toggleActions: "play none none none"
        },
        y: 50,
        opacity: 0,
        duration: 1,
        ease: "power3.out"
    });

    // Animasi untuk formulir kontak
    gsap.from('.kontak-form', {
        scrollTrigger: {
            trigger: '.kontak-section',
            start: "top 70%",
            toggleActions: "play none none none"
        },
        x: -100,
        opacity: 0,
        duration: 1,
        ease: "power3.out"
    });

    gsap.from('.info-card', {
        scrollTrigger: {
            trigger: '.kontak-section',
            start: "top 70%",
            toggleActions: "play none none none"
        },
        x: 100,
        stagger: 0.2,
        duration: 1,
        ease: "power3.out"
    });
}

// Animasi saat menggulir halaman
function initializeScrollAnimations() {
    // Menjalankan animasi saat pengguna menggulir
    gsap.registerPlugin(ScrollTrigger);

    //Efek paralaks untuk latar belakang bagian hero
    gsap.to('.coffee-beans', {
        scrollTrigger: {
            trigger: '.hero-section',
            start: "top top",
            end: "bottom top",
            scrub: true
        },
        y: 100,
        ease: "none"
    });

    // Elemen memudar masuk saat muncul di tampilan 
    const fadeElements = document.querySelectorAll('.fade-in');
    fadeElements.forEach(el => {
        gsap.from(el, {
            scrollTrigger: {
                trigger: el,
                start: "top 80%",
                toggleActions: "play none none none"
            },
            opacity: 0,
            y: 50,
            duration: 1,
            ease: "power3.out"
        });
    });

    // Masuk dari kiri
    const slideLeftElements = document.querySelectorAll('.slide-in-left');
    slideLeftElements.forEach(el => {
        gsap.from(el, {
            scrollTrigger: {
                trigger: el,
                start: "top 80%",
                toggleActions: "play none none none"
            },
            x: -100,
            opacity: 0,
            duration: 1,
            ease: "power3.out"
        });
    });

    // Masuk dari kanan
    const slideRightElements = document.querySelectorAll('.slide-in-right');
    slideRightElements.forEach(el => {
        gsap.from(el, {
            scrollTrigger: {
                trigger: el,
                start: "top 80%",
                toggleActions: "play none none none"
            },
            x: 100,
            opacity: 0,
            duration: 1,
            ease: "power3.out"
        });
    });
}

// Menyorot link navigasi aktif saat menggulir
function updateNavOnScroll() {
    const sections = document.querySelectorAll('section');
    const navLinks = document.querySelectorAll('.nav-link');

    sections.forEach(section => {
        const sectionTop = section.offsetTop;
        const sectionHeight = section.clientHeight;
        const sectionId = section.getAttribute('id');

        if (window.scrollY >= sectionTop - 200 && window.scrollY < sectionTop + sectionHeight - 200) {
            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('data-section') === sectionId) {
                    link.classList.add('active');
                }
            });
        }
    });
}

// Pengiriman form newsletter
const newsletterForm = document.querySelector('footer form');
if (newsletterForm) {
    newsletterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const emailInput = this.querySelector('input[type="email"]');
        if (emailInput.value) {
            alert('Terima kasih telah berlangganan newsletter kami!');
            emailInput.value = '';
        } else {
            alert('Silakan masukkan alamat email Anda');
        }
    });
}

// Inisialisasi pendeteksi scroll untuk update navigasi
window.addEventListener('scroll', updateNavOnScroll);