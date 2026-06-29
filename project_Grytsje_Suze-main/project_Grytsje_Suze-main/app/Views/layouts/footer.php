<!-- Voettekst: bevat logo, navigatielinks en auteursrechtmelding -->
<footer class="border-t-4 border-black px-4 md:px-8 py-6 flex flex-col md:flex-row items-center justify-between gap-4">

    <!-- Logo met link naar de homepage -->
    <div>
        <a href="/">
            <img src="/images/GS Grytsje Suze Logo goed-02.png" alt="GS Grytsje Suze Logo" class="h-12 w-auto">
        </a>
    </div>

    <!-- Navigatielinks naar de hoofdpagina's -->
    <nav class="flex flex-wrap justify-center gap-4 md:gap-6 text-xs tracking-widest text-gray-600 font-bebas">
        <a href="/about" class="hover:text-pink-500 transition-colors">About</a>
        <a href="/portfolio" class="hover:text-pink-500 transition-colors">Work</a>
        <a href="/collaborations" class="hover:text-pink-500 transition-colors">Collaborations</a>
        <a href="/commissions" class="hover:text-pink-500 transition-colors">Commissions</a>
        <a href="/news" class="hover:text-pink-500 transition-colors">News</a>
        <a href="/contact" class="hover:text-pink-500 transition-colors">Contact</a>
        <a href="https://www.instagram.com/grytsjesuze/" target="_blank" rel="noopener noreferrer" class="hover:text-pink-500 transition-colors">Instagram</a>
    </nav>

    <!-- Auteursrechtmelding met dynamisch huidig jaar -->
    <div class="text-xs text-gray-400 uppercase tracking-widest">&copy; <?= date('Y') ?> Grytsje Suze</div>
</footer>
