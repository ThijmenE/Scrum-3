<?php
// Huidig URL-pad bepalen voor het markeren van de actieve navigatielink
$currentPath = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/') ?: '/';

// navLink(): genereert een ankerlink met actief-stijlklasse op basis van de huidige URL
function navLink(string $href, string $label, string $current): string {
    $isActive = ($current === $href || (strlen($href) > 1 && str_starts_with($current, $href)));
    $colorClass = $isActive ? 'text-brand' : 'text-white hover:text-brand';
    return '<a href="' . $href . '" class="tracking-widest transition-colors duration-200 ' . $colorClass . '">' . $label . '</a>';
}
?>
<div class="relative pt-12.5 mt-1.25">

    <!-- Logo-uitstulping: uitstekende balk boven de navigatie met het merklogo -->
    <div class="absolute top-5 left-1/2 -translate-x-1/2 w-47.5 h-25 bg-black rounded-t-[95px] flex items-end justify-center pb-0.5 z-20">
        <a href="/">
            <img src="/images/groot logo wit.png" alt="GS Grytsje Suze Logo" class="h-24 w-auto">
        </a>
    </div>

    <!-- Desktopnavigatie: zwarte balk met links aan beide kanten en logo in het midden -->
    <header class="bg-black h-18.75 relative z-10 flex items-center justify-between px-4 md:px-10">

        <!-- Linker navigatiegroep: About, Work en Collaborations -->
        <nav class="hidden md:flex items-center gap-6 lg:gap-10 font-bebas text-[1.1rem]">
            <?= navLink('/about', 'About', $currentPath) ?>
            <?= navLink('/portfolio', 'Work', $currentPath) ?>
            <?= navLink('/collaborations', 'Collaborations', $currentPath) ?>
        </nav>

        <!-- Uitlijner voor de logo-uitstulping in het midden -->
        <div class="hidden md:block w-50 shrink-0"></div>

        <!-- Rechter navigatiegroep: Commissions, News en Contact -->
        <nav class="hidden md:flex items-center gap-6 lg:gap-10 font-bebas text-[1.1rem]">
            <?= navLink('/commissions', 'Commissions', $currentPath) ?>
            <?= navLink('/news', 'News', $currentPath) ?>
            <?= navLink('/contact', 'Contact', $currentPath) ?>
        </nav>

        <!-- Mobiele menu-knop (hamburger): alleen zichtbaar op kleine schermen -->
        <button
            class="md:hidden text-white ml-auto z-30 relative"
            onclick="document.getElementById('mob-menu').classList.toggle('hidden')"
            aria-label="Toggle menu">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

    </header>

    <!-- Mobiel uitklapmenu: verborgen totdat de hamburger-knop wordt ingedrukt -->
    <div id="mob-menu" class="hidden bg-[#111] px-8 py-6 relative z-15">
        <nav class="flex flex-col gap-5 font-bebas">
            <a href="/about" class="text-white text-2xl tracking-widest hover:text-brand transition-colors">About</a>
            <a href="/portfolio" class="text-white text-2xl tracking-widest hover:text-brand transition-colors">Work</a>
            <a href="/collaborations" class="text-white text-2xl tracking-widest hover:text-brand transition-colors">Collaborations</a>
            <a href="/commissions" class="text-white text-2xl tracking-widest hover:text-brand transition-colors">Commissions</a>
            <a href="/news" class="text-white text-2xl tracking-widest hover:text-brand transition-colors">News</a>
            <a href="/contact" class="text-white text-2xl tracking-widest hover:text-brand transition-colors">Contact</a>
        </nav>
    </div>

</div>
