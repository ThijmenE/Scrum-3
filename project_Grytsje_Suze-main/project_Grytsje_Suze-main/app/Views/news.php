<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Paginakop: tekenset, viewport, lettertype en stijlbladen -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News — Grytsje Suze</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link href="/css/output.css" rel="stylesheet">
</head>
<body class="flex flex-col min-h-screen">

<!-- Navigatiebalk: ingeladen via de herbruikbare header-layout -->
<?php require ROOT_PATH . '/app/Views/layouts/header.php'; ?>

<main class="flex-1">

    <!-- Paginatitel: grote koptekst met beschrijving op zwarte achtergrond -->
    <div class="bg-black pt-12 px-8 pb-10">
        <div class="max-w-7xl mx-auto">
            <h1 class="font-bebas text-white text-[clamp(3.5rem,12vw,9rem)] leading-none">News</h1>
            <p class="text-[#aaa] text-base mt-4">Agenda, upcoming &amp; recent expositions, projects, articles and studio updates.</p>
        </div>
    </div>

    <!-- Nieuwsoverzicht: raster met artikelen over exposities, projecten en studio-updates -->
    <section class="bg-white py-20 px-8">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                <!-- Artikel: aankomende expositie -->
                <article class="border-t-4 border-brand pt-6">
                    <p class="font-bebas text-brand text-[0.8rem] tracking-[0.25em] mb-2">EXPOSITION</p>
                    <h3 class="font-bebas text-[1.6rem] text-black mb-3 leading-[1.2]">Upcoming: Title to be announced</h3>
                    <p class="text-[0.9rem] text-[#777] mb-4">Date · Location</p>
                    <p class="text-[0.95rem] text-[#333] leading-[1.7]">More information to follow. Stay tuned for updates on upcoming expositions and events.</p>
                </article>

                <!-- Artikel: recent project (Magnum Show) -->
                <article class="border-t-4 border-brand pt-6">
                    <p class="font-bebas text-brand text-[0.8rem] tracking-[0.25em] mb-2">PROJECT</p>
                    <h3 class="font-bebas text-[1.6rem] text-black mb-3 leading-[1.2]">Recent Work: Magnum Show</h3>
                    <p class="text-[0.9rem] text-[#777] mb-4">Film by Fourcreating</p>
                    <p class="text-[0.95rem] text-[#333] leading-[1.7]">Leather bags and wearable objects created for the Magnum Show — a film production by Fourcreating.</p>
                    <a href="/portfolio" class="inline-block mt-4 font-bebas text-brand text-[0.9rem] tracking-widest">View in Portfolio →</a>
                </article>

                <!-- Artikel: studio-update over nieuw werk in ontwikkeling -->
                <article class="border-t-4 border-brand pt-6">
                    <p class="font-bebas text-brand text-[0.8rem] tracking-[0.25em] mb-2">STUDIO UPDATE</p>
                    <h3 class="font-bebas text-[1.6rem] text-black mb-3 leading-[1.2]">New Work in Progress</h3>
                    <p class="text-[0.9rem] text-[#777] mb-4">Studio, 2025</p>
                    <p class="text-[0.95rem] text-[#333] leading-[1.7]">New collections and commissioned objects currently in development. More to be shared soon.</p>
                </article>

            </div>
        </div>
    </section>

    <!-- Contact-uitnodiging sectie onderaan de pagina -->
    <section class="bg-black py-16 px-8 text-center">
        <div class="max-w-3xl mx-auto">
            <p class="text-[#aaa] text-base mb-6">Want to stay informed or collaborate on a project?</p>
            <a href="/contact" class="inline-block text-black text-xl px-10 py-4 transition-all duration-200 bg-brand hover:bg-brand-dark font-bebas">Reach Out →</a>
        </div>
    </section>

</main>

<!-- Zwevende contact-knop rechtsonder in beeld -->
<a href="/contact" class="fixed bottom-8 right-8 z-50 inline-flex items-center gap-2 text-black text-lg px-6 py-3 shadow-2xl transition-all duration-200 bg-brand hover:bg-brand-dark font-bebas rounded-full">✦ Reach Out</a>

<!-- Voettekst: ingeladen via de herbruikbare footer-layout -->
<?php require ROOT_PATH . '/app/Views/layouts/footer.php'; ?>

<!-- JavaScript voor interactieve pagina-elementen -->
<script src="/js/script.js"></script>
</body>
</html>
