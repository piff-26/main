<nav id="sidenav-8"
    class="fixed left-0 top-0 z-[1035] h-full min-h-[100vh] w-60 -translate-x-full overflow-hidden bg-white shadow-[0_4px_12px_0_rgba(0,0,0,0.07),_0_2px_4px_rgba(0,0,0,0.05)] data-[te-sidenav-hidden='false']:translate-x-0 invisible md:visible"
    data-te-sidenav-init data-te-sidenav-hidden="false" data-te-sidenav-position="fixed" data-te-sidenav-mode="side"
    data-te-sidenav-accordion="true">
    <a class="mb-3 flex flex-col items-center justify-center border-b-2 border-solid border-gray-100 py-6 outline-none"
        href="#" data-te-ripple-init data-te-ripple-color="primary">
        <div class="flex items-center justify-center space-x-3 mb-3">
            {{-- <img src="{{ asset('assets/utils/icons/logo.png') }}" class="h-8" alt="pce" loading="lazy" /> --}}
        </div>
        <span class="text-center font-bold">Petra International Film Festival <br>2026</span>
    </a>
    <ul class="relative m-0 list-none px-[0.2rem] pb-12" data-te-sidenav-menu-ref>
        <li class="relative">
            <a id="overview"
                class="flex cursor-pointer items-center truncate rounded-[5px] px-6 py-[0.45rem] text-[0.85rem] text-gray-600 outline-none transition duration-300 ease-linear hover:bg-slate-50 hover:text-inherit hover:outline-none focus:bg-slate-50 focus:text-inherit focus:outline-none active:bg-slate-50 active:text-inherit active:outline-none data-[te-sidenav-state-active]:text-inherit data-[te-sidenav-state-focus]:outline-none motion-reduce:transition-none"
                data-te-sidenav-link-ref href="">
                <span>Dashboard</span>
            </a>
        </li>




        <li class="relative pt-6">
            <span class="px-6 py-4 text-[0.6rem] font-bold uppercase text-gray-600 ">Logout</span>
            <a href="{{ route('logout') }}"
                class="flex cursor-pointer items-center truncate rounded-[5px] px-6 py-[0.45rem] text-[0.85rem] text-gray-600 outline-none transition duration-300 ease-linear hover:bg-slate-50 hover:text-inherit hover:outline-none focus:bg-slate-50 focus:text-inherit focus:outline-none active:bg-slate-50 active:text-inherit active:outline-none data-[te-sidenav-state-active]:text-inherit data-[te-sidenav-state-focus]:outline-none motion-reduce:transition-none"
                data-te-sidenav-link-ref>
                <span>Logout</span>
            </a>
        </li>
    </ul>
</nav>

{{-- NAVBAR --}}
<!-- Main navigation container -->
<nav
    class="flex-no-wrap relative flex w-full items-center justify-between bg-[#FBFBFB] py-2 shadow-md shadow-black/5  lg:flex-wrap lg:justify-start lg:py-4 block md:hidden">
    <div class="flex w-full flex-wrap items-center justify-between px-3">
        <!-- Hamburger button for mobile view -->
        <button
            class="block border-0 bg-transparent px-2 text-neutral-500 hover:no-underline hover:shadow-none focus:no-underline focus:shadow-none focus:outline-none focus:ring-0  sm:hidden"
            type="button" data-te-collapse-init data-te-target="#navbarSupportedContent12"
            aria-controls="navbarSupportedContent12" aria-expanded="false" aria-label="Toggle navigation">
            <!-- Hamburger icon -->
            <span class="[&>svg]:w-7">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-7 w-7">
                    <path fill-rule="evenodd"
                        d="M3 6.75A.75.75 0 013.75 6h16.5a.75.75 0 010 1.5H3.75A.75.75 0 013 6.75zM3 12a.75.75 0 01.75-.75h16.5a.75.75 0 010 1.5H3.75A.75.75 0 013 12zm0 5.25a.75.75 0 01.75-.75h16.5a.75.75 0 010 1.5H3.75a.75.75 0 01-.75-.75z"
                        clip-rule="evenodd" />
                </svg>
            </span>
        </button>

        <!-- Collapsible navigation container -->
        <div class="!visible hidden flex-grow basis-[100%] items-center sm:!flex sm:basis-auto"
            id="navbarSupportedContent12" data-te-collapse-item>
            <!-- Left navigation links -->
            <ul class="list-style-none mr-auto flex flex-col pl-0 sm:flex-row" data-te-navbar-nav-ref>
                {{-- Dashboard --}}
                <li class="relative pt-4">
                    <span class="px-6 py-4 text-[0.6rem] font-bold uppercase text-gray-600 ">Overview</span>
                    <a class="flex cursor-pointer items-center truncate rounded-[5px] px-6 py-[0.45rem] text-[0.85rem] text-gray-600 outline-none transition duration-300 ease-linear hover:bg-slate-50 hover:text-inherit hover:outline-none focus:bg-slate-50 focus:text-inherit focus:outline-none active:bg-slate-50 active:text-inherit active:outline-none data-[te-sidenav-state-active]:text-inherit data-[te-sidenav-state-focus]:outline-none motion-reduce:transition-none"
                        href="" data-te-nav-link-ref>
                        <span>Dashboard</span>
                    </a>
                </li>

            </ul>
        </div>

        <!-- Right elements -->
        <div class="relative flex items-center">
            <!-- Logout Icon -->
            <a class="pl-2 my-auto sm:mb-0 sm:mr-4 text-secondary-500 transition duration-200 hover:text-secondary-400 hover:ease-in-out focus:text-secondary-400 disabled:text-black/30 motion-reduce:transition-none"
                href="">
                <span class="[&>svg]:w-5">
                    <svg class="w-[24px] h-[24px] fill-[#ff6b6b]" viewBox="0 0 512 512"
                        xmlns="http://www.w3.org/2000/svg">

                        <!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                        <path
                            d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z">
                        </path>
                    </svg>
                </span>
            </a>
        </div>
    </div>
</nav>
