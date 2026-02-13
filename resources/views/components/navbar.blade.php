<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PIFF 2026 - Final Navbar (Hover Only Animation)</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: "Helvetica Now", "Helvetica", sans-serif;
            background-color: #f4f4f4; 
            padding-top: 100px; 
            overflow-x: hidden;
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

        .logo-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: block;
            object-fit: cover;
            cursor: pointer;
        }

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
            background-color: red;
            transition: width 0.3s ease;
        }
        .nav-links-main li a:not(.submit-btn):hover::after {
            width: 100%;
        }

        /* --- 3. TOMBOL SUBMIT (HOVER ONLY EFFECT) --- */
        
        .submit-btn {
            position: relative;
            display: inline-block;
            
            min-width: 170px;
            text-align: center;
            padding: 15px 0;
            
            color: var(--primary-white);
            background-color: red; 
            text-decoration: none;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            
            overflow: hidden; 
            transition: 0.5s; 
            border-radius: 4px;
            border: none;
            white-space: nowrap;
        }

        .submit-btn:hover {
            background-color: darkred;
            box-shadow: 0 0 20px rgba(255, 54, 45, 0.4);
            transform: scale(1.05);
        }

        /* --- LOGIKA BARU: GANTI DISINI --- */

        /* 1. Default: Garis Sembunyi (Opacity 0) */
        .submit-btn span {
            position: absolute;
            display: block;
            opacity: 0;          /* Hilang saat tidak di-hover */
            transition: opacity 0.3s ease-in-out; /* Muncul halus */
        }

        /* 2. Hover: Garis Muncul (Opacity 1) */
        .submit-btn:hover span {
            opacity: 1;          /* Muncul saat hover */
        }

        /* --- ANIMASI (TETAP JALAN DI BACKGROUND) --- */

        .submit-btn span:nth-child(1) {
            top: 0; left: -100%; width: 100%; height: 3px;
            background: linear-gradient(90deg, transparent, white);
            animation: btn-anim1 2s linear infinite; 
        }
        @keyframes btn-anim1 {
            0% { left: -100%; }
            50%, 100% { left: 100%; }
        }

        .submit-btn span:nth-child(2) {
            top: -100%; right: 0; width: 3px; height: 100%;
            background: linear-gradient(180deg, transparent, white);
            animation: btn-anim2 2s linear infinite; 
            animation-delay: 0.5s; 
        }
        @keyframes btn-anim2 {
            0% { top: -100%; }
            50%, 100% { top: 100%; }
        }

        .submit-btn span:nth-child(3) {
            bottom: 0; right: -100%; width: 100%; height: 3px;
            background: linear-gradient(270deg, transparent, white);
            animation: btn-anim3 2s linear infinite; 
            animation-delay: 1s; 
        }
        @keyframes btn-anim3 {
            0% { right: -100%; }
            50%, 100% { right: 100%; }
        }

        .submit-btn span:nth-child(4) {
            bottom: -100%; left: 0; width: 3px; height: 100%;
            background: linear-gradient(360deg, transparent, white);
            animation: btn-anim4 2s linear infinite; 
            animation-delay: 1.5s; 
        }
        @keyframes btn-anim4 {
            0% { bottom: -100%; }
            50%, 100% { bottom: 100%; }
        }


        /* --- 4. RESPONSIVE / MOBILE STYLE --- */
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
            <img class="logo-circle" src="https://via.placeholder.com/50" alt="PIFF 2026">
        </div>

        <ul class="nav-links-main">
            <li><a href="#">HOME</a></li>
            <li><a href="#">PROGRAMS</a></li>
            <li><a href="#">TICKETS</a></li>
            
            <li>
                <a href="#" class="submit-btn">
                    <span></span> <span></span> <span></span> <span></span> SUBMIT FILMS
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