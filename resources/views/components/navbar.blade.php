<style>
    /* --- VARIABEL WARNA (Asumsi dari kode sebelumnya) --- */
    :root {
        --black: #000000;
        --primary-white: #ffffff;
    }

    /* --- STYLING UTAMA --- */
    #navigation-bar {
        background-color: var(--black);
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 5%;
        position: relative;
        z-index: 999; /* Pastikan navbar selalu di paling atas */
    }

    .logo-container {
        z-index: 1000; /* Supaya logo tetap bisa diklik saat menu terbuka (opsional) */
    }

    .logo-circle {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: block; /* Mencegah spacing aneh pada gambar */
    }

    .nav-links-main {
        display: flex;
        list-style: none;
        align-items: center;
        gap: 30px;
        color: var(--primary-white);
        font-family: "Helvetica Now", sans-serif;
        margin: 0;
        padding: 0;
    }

    /* Hover NavbarLink */
    .nav-links-main li {
        transition: transform 0.3s ease;
    }

    /* Hanya scale di Desktop supaya tidak aneh di HP */
    @media screen and (min-width: 769px) {
        .nav-links-main li:hover {
            transform: scale(1.1);
        }
    }

    .nav-links-main li a {
        text-decoration: none;
        color: var(--primary-white);
        font-weight: bold;
        transition: all 0.3s ease;
        position: relative;
        font-size: 16px;
    }

    .nav-links-main li a:hover {
        color: orange;
        opacity: 0.8;
    }

    .nav-links-main li a::after {
        content: '';
        position: absolute;
        width: 0;
        height: 2px;
        bottom: -5px;
        left: 0;
        background-color: white;
        transition: 0.3s ease;
    }

    .nav-links-main li a:hover::after {
        width: 100%;
    }

    /* --- TOMBOL SUBMIT (Diperbaiki strukturnya) --- */
    /* Mengubah button > a menjadi a.button agar area klik lebih baik */
    .submit-films {
        background-color: #ff362d;
        padding: 10px 20px;
        color: white !important;
        border: none;
        cursor: pointer;
        border-radius: 4px; /* Opsional, biar agak smooth */
        display: inline-block;
    }

    .submit-films::after {
        display: none !important; /* Override underline effect */
    }

    .submit-films:hover {
        background-color: darkred;
        transform: scale(1.05);
        box-shadow: 0 0 10px rgba(255, 0, 0, 0.5);
    }

    /* --- HAMBURGER MENU STYLE --- */
    .burger {
        display: none; /* Sembunyikan di desktop */
        cursor: pointer;
        z-index: 1000;
    }

    .burger div {
        width: 25px;
        height: 3px;
        background-color: var(--primary-white);
        margin: 5px;
        transition: all 0.3s ease;
    }

    /* --- RESPONSIVE / MOBILE STYLE --- */
    @media screen and (max-width: 768px) {
        body {
            overflow-x: hidden; /* Mencegah scroll samping */
        }

        .nav-links-main {
            position: absolute;
            right: 0px;
            height: 100vh; /* Tinggi layar penuh */
            top: 0;
            background-color: var(--black);
            display: flex;
            flex-direction: column;
            justify-content: center; /* Konten di tengah vertikal */
            align-items: center;
            width: 50%; /* Lebar menu 50% layar */
            transform: translateX(100%); /* Sembunyikan ke kanan */
            transition: transform 0.5s ease-in-out;
            z-index: 998;
        }

        .nav-links-main li {
            margin: 20px 0;
            opacity: 0; /* Untuk animasi fade-in nanti */
        }

        /* Tampilkan burger di mobile */
        .burger {
            display: block;
        }
    }

    /* Class aktif saat menu dibuka (Ditambahkan via JS) */
    .nav-active {
        transform: translateX(0%);
    }

    /* Animasi Link Muncul satu per satu */
    @keyframes navLinkFade {
        from {
            opacity: 0;
            transform: translateX(50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    /* Animasi Hamburger jadi 'X' */
    .toggle .line1 {
        transform: rotate(-45deg) translate(-5px, 6px);
    }
    .toggle .line2 {
        opacity: 0;
    }
    .toggle .line3 {
        transform: rotate(45deg) translate(-5px, -6px);
    }

</style>

<nav class="navbar" id="navigation-bar">
    <div class="logo-container">
        <img class="logo-circle" src="data:image/png;base64,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" alt="PIFF 2026">
    </div>

    <ul class="nav-links-main">
        <li><a href="#">HOME</a></li>
        <li><a href="#">PROGRAMS</a></li>
        <li><a href="#">TICKETS</a></li>
        <li><a href="#" class="submit-films">SUBMIT FILMS</a></li>
    </ul>

    <div class="burger">
        <div class="line1"></div>
        <div class="line2"></div>
        <div class="line3"></div>
    </div>
</nav>

<script>
    // Membungkus dalam event listener agar HTML loading selesai dulu
    document.addEventListener('DOMContentLoaded', () => {
        const burger = document.querySelector('.burger');
        const nav = document.querySelector('.nav-links-main');
        const navLinks = document.querySelectorAll('.nav-links-main li');

        burger.addEventListener('click', () => {
            // 1. Toggle Nav (Buka/Tutup Menu)
            nav.classList.toggle('nav-active');

            // 2. Animasi Link (Fade in satu per satu)
            navLinks.forEach((link, index) => {
                if (link.style.animation) {
                    link.style.animation = ''; // Reset animasi saat tutup
                } else {
                    // Delay disesuaikan index agar muncul berurutan
                    link.style.animation = `navLinkFade 0.5s ease forwards ${index / 7 + 0.3}s`;
                }
            });

            // 3. Animasi Burger jadi 'X'
            burger.classList.toggle('toggle');
        });
    });
</script>