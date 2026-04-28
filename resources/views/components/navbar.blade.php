<style>
    body {
        padding-top: 100px;
    }

    #navigation-bar {
        background-color: #000000;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 5%;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 90px;
        box-sizing: border-box;
        z-index: 999;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        transition: transform 0.8s ease;
    }

    #navigation-bar.nav-hidden {
        transform: translateY(-100%);
    }

    .logo-container {
        display: flex;
        align-items: center;
        height: 100%;
        transition: transform 0.8s ease, opacity 0.4s ease;
    }

    @media screen and (max-width: 768px) {
        .logo-container.hide {
            transform: translateY(-100%);
            opacity: 0;
        }
    }

    .logo-wrapper {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 10px 15px;
        text-decoration: none;
        transition: transform 0.3s ease;
        overflow: hidden;
    }

    .logoPiff2026 {
        display: block;
        width: 150px;
        height: auto;
    }


    .logo-wrapper span {
        position: absolute;
        display: block;
    }

    .logo-wrapper span:nth-child(2) {
        top: 0;
        left: -100%;
        width: 100%;
        height: 2px;
        background: linear-gradient(90deg, transparent, transparent 50%, #ffffff);

        animation: border-anim1 4s linear infinite;
    }

    /* --- KANAN (Right) --- */
    .logo-wrapper span:nth-child(3) {
        top: -100%;
        right: 0;
        width: 2px;
        height: 100%;
        background: linear-gradient(180deg, transparent, transparent 50%, #ffffff);
        animation: border-anim2 4s linear infinite;
        animation-delay: 1s;
    }

    .logo-wrapper span:nth-child(4) {
        bottom: 0;
        right: -100%;
        width: 100%;
        height: 2px;
        background: linear-gradient(270deg, transparent, transparent 50%, #ffffff);

        animation: border-anim3 4s linear infinite;
        animation-delay: 2s;
    }

    .logo-wrapper span:nth-child(5) {
        bottom: -100%;
        left: 0;
        width: 2px;
        height: 100%;
        background: linear-gradient(360deg, transparent, transparent 50%, #ffffff);

        animation: border-anim4 4s linear infinite;
        animation-delay: 3s;
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
        color: #ffffff;
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
        background-color: #ff0000;
        transition: width 0.3s ease;
    }

    .nav-links-main li a:not(.submit-btn):hover::after {
        width: 100%;
    }

    .submit-btn {
        position: relative;
        display: inline-block;
        min-width: 170px;
        text-align: center;
        padding: 15px 0;

        background: transparent;
        color: #ffffff;

        text-decoration: none;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 2px;
        border: none;

        z-index: 1;
    }

    .submit-btn::after {
        content: '';
        position: absolute;

        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        width: 100%;
        height: 100%;

        background-color: #ff0000;
        border-radius: 6px;

        z-index: -1;
        transition: transform 0.3s ease, background-color 0.3s ease;
    }

    .submit-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: #ffffff;
        border-radius: 4px;
        z-index: -2;

        opacity: 0;
        filter: blur(15px);
        transition: opacity 0.6s ease-out, filter 0.6s ease-out, transform 0.6s ease-out;
    }

    .submit-btn:hover::before {
        opacity: 0.5;
        filter: blur(8px);
        transform: scale(1.1);
    }

    /* KEYFRAMES */
    @keyframes border-anim1 {
        0% {
            left: -100%;
        }

        50%,
        100% {
            left: 100%;
        }
    }

    @keyframes border-anim2 {
        0% {
            top: -100%;
        }

        50%,
        100% {
            top: 100%;
        }
    }

    @keyframes border-anim3 {
        0% {
            right: -100%;
        }

        50%,
        100% {
            right: 100%;
        }
    }

    @keyframes border-anim4 {
        0% {
            bottom: -100%;
        }

        50%,
        100% {
            bottom: 100%;
        }
    }


    /* --- 6. RESPONSIVE --- */
    .burger {
        display: none;
        cursor: pointer;
        z-index: 1001;
    }

    .burger div {
        width: 25px;
        height: 3px;
        background-color: #ffffff;
        margin: 5px;
        transition: all 0.3s ease;
    }

    .nav-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        background-color: rgba(0, 0, 0, 0.8);
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.4s ease, visibility 0.4s ease;
        z-index: 997;
    }

    .nav-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    @media screen and (max-width: 768px) {
        .nav-links-main {
            position: fixed;
            right: 0;
            top: 0;
            height: 100vh;
            width: 50%;
            min-width: 250px;
            background-color: #000000;
            /* Manual Black */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            box-shadow: -5px 0 15px rgba(0, 0, 0, 0.5);
            transform: translateX(100%);
            transition: transform 0.4s ease-in-out;
            z-index: 998;
        }

        .nav-links-main li {
            margin: 20px 0;
            opacity: 0;
            width: 100%;
            text-align: center;
        }

        .nav-links-main li a {
            font-size: 20px;
            display: block;
        }

        .submit-btn {
            margin-top: 20px;
            padding: 15px 0;
            min-width: 200px;
        }

        .nav-links-main li .submit-btn {
            display: inline-block;
            width: auto;
        }

        .burger {
            display: block;
        }
    }

    .nav-active {
        transform: translateX(0%);
    }

    .toggle .line1 {
        transform: rotate(-45deg) translate(-5px, 6px);
    }

    .toggle .line2 {
        opacity: 0;
    }

    .toggle .line3 {
        transform: rotate(45deg) translate(-5px, -6px);
    }

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
</style>

<div class="nav-overlay" id="navOverlay"></div>

<nav id="navigation-bar">
    <div class="logo-container">
        <a href="{{ route('user.home') }}" class="logo-wrapper">
            <img src="{{ asset('assets/logo/logo_piff.png') }}" alt="PIFF 2026" class="logoPiff2026">
            <span></span><span></span><span></span><span></span>
        </a>
    </div>

    <ul class="nav-links-main">
        <li><a href="{{ route('user.home') }}#title">HOME</a></li>
        <li><a href="{{ route('user.home') }}#submission">PROGRAMS</a></li>
        <li><a href="{{ route('user.home') }}#ticket">TICKETS</a></li>

        <li>
            <a href="{{ route('user.submit') }}" class="submit-btn">
                SUBMIT FILMS
            </a>
        </li>
    </ul>

    <div class="burger" role="button" aria-label="Toggle navigation menu" aria-expanded="false" tabindex="0">
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
        const navbar = document.getElementById('navigation-bar');
        const overlay = document.getElementById('navOverlay');
        const logoContainer = document.querySelector('.logo-container');
        let lastScroll = 0;

        function toggleMenu() {
            nav.classList.toggle('nav-active');
            overlay.classList.toggle('active');
            logoContainer.classList.toggle('hide');
            
            const isActive = nav.classList.contains('nav-active');
            burger.setAttribute('aria-expanded', isActive);

            if (isActive) {
                document.body.style.overflow = 'hidden';
                document.documentElement.style.overflow = 'hidden';
                if (typeof window.disableLenis === 'function') {
                    window.disableLenis();
                }
            } else {
                document.body.style.overflow = '';
                document.documentElement.style.overflow = '';
                if (typeof window.enableLenis === 'function') {
                    window.enableLenis();
                }
            }

            navLinks.forEach((link, index) => {
                if (link.style.animation) {
                    link.style.animation = '';
                } else {
                    link.style.animation = `navLinkFade 0.5s ease forwards ${index / 7 + 0.3}s`;
                }
            });

            burger.classList.toggle('toggle');
        }

        burger.addEventListener('click', toggleMenu);
        burger.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                toggleMenu();
            }
        });
        overlay.addEventListener('click', toggleMenu);

        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (nav.classList.contains('nav-active')) {
                    toggleMenu();
                }
            });
        });

        window.addEventListener('scroll', () => {
            const currentScroll = window.pageYOffset;

            if (currentScroll <= 0) {
                navbar.classList.remove('nav-hidden');
                return;
            }

            if (currentScroll > lastScroll && currentScroll > 100) {
                navbar.classList.add('nav-hidden');
            } else {
                navbar.classList.remove('nav-hidden');
            }

            lastScroll = currentScroll;
        });
    });
</script>
