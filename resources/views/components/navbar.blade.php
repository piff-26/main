<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PIFF 2026 - FINAL FIX</title>
    <style>
        /* --- 1. RESET & BASIC --- */
        body {
            margin: 0;
            padding: 0;
            font-family: "Helvetica Now", "Helvetica", sans-serif;
            background-color: #f4f4f4; 
            padding-top: 100px; 
            overflow-x: hidden;
        }

        :root {
            --black: #000000;
            --primary-white: #ffffff;
            --main-red: #ff0000;
            --dark-red: #8b0000;
        }

        /* --- 2. STYLING NAVBAR --- */
        #navigation-bar {
            background-color: var(--black);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 5%;
            position: fixed;
            top: 0; left: 0;
            width: 100%;
            height: 90px;
            box-sizing: border-box;
            z-index: 999;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        }

        /* =========================================
           3. STYLING LOGO (PERBAIKAN: COMET & NO BLEED)
           ========================================= */
        .logo-container {
            display: flex;
            align-items: center;
            height: 100%;
        }

        .logo-wrapper {
            position: relative;
            display: flex; /* Ganti inline-block ke flex biar center presisi */
            justify-content: center;
            align-items: center;
            padding: 10px 15px; 
            text-decoration: none;
            transition: transform 0.3s ease;
            
            /* INI KUNCI SUPAYA GARIS TIDAK KELUAR NAVBAR */
            overflow: hidden; 
        }

        .logoPiff2026 {
            display: block;
            width: 150px; 
            height: auto;
        }

        .logo-wrapper:hover {
            transform: scale(1.1); 
        }

        .logo-wrapper span {
            position: absolute;
            display: block;
        }

        /* SETTING GARIS LOGO (EFEK KOMET/FADE) 
           Saya tambahkan 'transparent' lebih banyak di gradient agar buntutnya pudar
        */
        .logo-wrapper span:nth-child(2) { /* Atas */
            top: 0; left: -100%; width: 100%; height: 2px;
            background: linear-gradient(90deg, transparent, transparent 50%, var(--primary-white));
            animation: border-anim1 2s linear infinite;
        }
        .logo-wrapper span:nth-child(3) { /* Kanan */
            top: -100%; right: 0; width: 2px; height: 100%;
            background: linear-gradient(180deg, transparent, transparent 50%, var(--primary-white));
            animation: border-anim2 2s linear infinite; animation-delay: 0.5s;
        }
        .logo-wrapper span:nth-child(4) { /* Bawah */
            bottom: 0; right: -100%; width: 100%; height: 2px;
            background: linear-gradient(270deg, transparent, transparent 50%, var(--primary-white));
            animation: border-anim3 2s linear infinite; animation-delay: 1s;
        }
        .logo-wrapper span:nth-child(5) { /* Kiri */
            bottom: -100%; left: 0; width: 2px; height: 100%;
            background: linear-gradient(360deg, transparent, transparent 50%, var(--primary-white));
            animation: border-anim4 2s linear infinite; animation-delay: 1.5s;
        }


        /* =========================================
           4. STYLING MENU LINKS
           ========================================= */
        .nav-links-main {
            display: flex;
            list-style: none;
            align-items: center;
            gap: 30px;
            margin: 0;
            padding: 0;
        }

        .nav-links-main li a {
            text-decoration: none;
            color: var(--primary-white);
            font-weight: bold;
            font-size: 16px;
            position: relative;
            padding: 5px 0;
            transition: color 0.3s ease;
        }

        .nav-links-main li a:not(.submit-btn)::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 0;
            background-color: var(--main-red);
            transition: width 0.3s ease;
        }
        .nav-links-main li a:not(.submit-btn):hover::after {
            width: 100%;
        }

        /* =========================================
           5. TOMBOL SUBMIT (TIDAK DIUBAH - SESUAI REQUEST)
           ========================================= */
        .submit-btn {
            position: relative;
            display: inline-block;
            min-width: 170px;
            text-align: center;
            padding: 15px 0;
            color: var(--primary-white);
            background-color: var(--main-red); 
            text-decoration: none;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            overflow: hidden; 
            transition: transform 0.3s ease, background-color 0.3s ease, box-shadow 0.3s ease;
            border-radius: 4px;
            border: none;
            white-space: nowrap;
        }

        .submit-btn:hover {
            background-color: var(--dark-red);
            box-shadow: 0 0 25px rgba(255, 0, 0, 0.6);
            transform: scale(1.1); 
        }

        .submit-btn span {
            position: absolute;
            display: block;
        }

        /* Gradient Submit Button tetap ORIGINAL (Solid) */
        .submit-btn span:nth-child(1) { /* Atas */
            top: 0; left: -100%; width: 100%; height: 3px;
            background: linear-gradient(90deg, transparent, var(--primary-white));
            animation: border-anim1 2s linear infinite; 
        }
        .submit-btn span:nth-child(2) { /* Kanan */
            top: -100%; right: 0; width: 3px; height: 100%;
            background: linear-gradient(180deg, transparent, var(--primary-white));
            animation: border-anim2 2s linear infinite; animation-delay: 0.5s; 
        }
        .submit-btn span:nth-child(3) { /* Bawah */
            bottom: 0; right: -100%; width: 100%; height: 3px;
            background: linear-gradient(270deg, transparent, var(--primary-white));
            animation: border-anim3 2s linear infinite; animation-delay: 1s; 
        }
        .submit-btn span:nth-child(4) { /* Kiri */
            bottom: -100%; left: 0; width: 3px; height: 100%;
            background: linear-gradient(360deg, transparent, var(--primary-white));
            animation: border-anim4 2s linear infinite; animation-delay: 1.5s; 
        }

        /* KEYFRAMES (Dipakai Bersama) */
        @keyframes border-anim1 { 0% { left: -100%; } 50%, 100% { left: 100%; } }
        @keyframes border-anim2 { 0% { top: -100%; } 50%, 100% { top: 100%; } }
        @keyframes border-anim3 { 0% { right: -100%; } 50%, 100% { right: 100%; } }
        @keyframes border-anim4 { 0% { bottom: -100%; } 50%, 100% { bottom: 100%; } }


        /* --- 6. RESPONSIVE --- */
        .burger { display: none; cursor: pointer; z-index: 1001; }
        .burger div { width: 25px; height: 3px; background-color: var(--primary-white); margin: 5px; transition: all 0.3s ease; }

        @media screen and (max-width: 768px) {
            .nav-links-main {
                position: fixed; right: 0; top: 0; height: 100vh;
                width: 50%; min-width: 250px;
                background-color: var(--black);
                display: flex; flex-direction: column; justify-content: center; align-items: center;
                box-shadow: -5px 0 15px rgba(0,0,0,0.5); 
                transform: translateX(100%); transition: transform 0.4s ease-in-out; z-index: 998;
            }
            .nav-links-main li { margin: 20px 0; opacity: 0; width: 100%; text-align: center; }
            .nav-links-main li a { font-size: 20px; display: block; }
            .submit-btn { margin-top: 20px; padding: 15px 0; min-width: 200px; }
            .nav-links-main li .submit-btn { display: inline-block; width: auto; }
            .burger { display: block; }
        }

        .nav-active { transform: translateX(0%); }
        .toggle .line1 { transform: rotate(-45deg) translate(-5px, 6px); }
        .toggle .line2 { opacity: 0; }
        .toggle .line3 { transform: rotate(45deg) translate(-5px, -6px); }
        @keyframes navLinkFade { from { opacity: 0; transform: translateX(50px); } to { opacity: 1; transform: translateX(0); } }
    </style>
</head>
<body>

    <nav id="navigation-bar">
        <div class="logo-container">
            <a href="#" class="logo-wrapper">
                <img src="{{ asset('assets/logo/logo_piff.png') }}" alt="PIFF 2026" class="logoPiff2026">
                <span></span><span></span><span></span><span></span>
            </a>
        </div>

        <ul class="nav-links-main">
            <li><a href="#">HOME</a></li>
            <li><a href="#">PROGRAMS</a></li>
            <li><a href="#">TICKETS</a></li>
            
            <li>
                <a href="#" class="submit-btn">
                    <span></span><span></span><span></span><span></span> 
                    SUBMIT FILMS
                </a>
            </li>
        </ul>

        <div class="burger">
            <div class="line1"></div>
            <div class="line2"></div>
            <div class="line3"></div>
        </div>
    </nav>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const burger = document.querySelector('.burger');
            const nav = document.querySelector('.nav-links-main');
            const navLinks = document.querySelectorAll('.nav-links-main li');

            burger.addEventListener('click', () => {
                nav.classList.toggle('nav-active');
                
                navLinks.forEach((link, index) => {
                    if (link.style.animation) {
                        link.style.animation = '';
                    } else {
                        link.style.animation = `navLinkFade 0.5s ease forwards ${index / 7 + 0.3}s`;
                    }
                });
                
                burger.classList.toggle('toggle');
            });
        });
    </script>
</body>
</html>