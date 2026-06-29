<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Paginakop: tekenset, viewport, lettertype en stijlbladen -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About — Grytsje Suze</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link href="/css/output.css" rel="stylesheet">
</head>
<body class="flex flex-col min-h-screen">

<!-- Navigatiebalk: ingeladen via de herbruikbare header-layout -->
<?php require ROOT_PATH . '/app/Views/layouts/header.php'; ?>

<main class="flex-1">

    <!-- Paginatitel: grote koptekst op merkkleur achtergrond -->
    <div class="bg-brand pt-12 px-8 pb-10">
        <div class="max-w-7xl mx-auto">
            <h1 class="font-bebas text-white text-[clamp(3.5rem,12vw,9rem)] leading-none">About</h1>
        </div>
    </div>

    <!-- Bio-sectie: tekst over de studio links, portretafbeelding rechts -->
    <section class="bg-white py-20 px-8">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-12 items-start">
            <div>
                <p class="text-[1.1rem] text-[#111] leading-[1.9] mb-6">Grytsje Suze creates bags and leather objects that exist between fashion, art and visual storytelling.</p>
                <p class="text-[1.1rem] text-[#111] leading-[1.9] mb-6">Starting from a concept, emotion or image, she develops expressive pieces designed to carry presence: to be seen, felt and remembered.</p>
                <p class="text-[1.1rem] text-[#111] leading-[1.9] mb-6">Her practice moves between thinking and making. Ideas are shaped into form through an intuitive process that blends conceptual development with hands-on exploration. Depending on the project, she works independently or in collaboration with specialist makers and production partners to bring ideas into physical reality.</p>
                <p class="text-[1.1rem] text-[#111] leading-[1.9] mb-6">Her work is driven by visual energy, material contrast and a strong sense of character. With a focus on intentional design and materials that last, craftsmanship, quality and meaning are central. Both visually and physically.</p>
                <p class="text-[1.1rem] text-[#111] leading-[1.9] mb-6">Through experimentation with material, shape and interaction, her process remains innovative and explorative. Many pieces are modular or adaptable, shifting in meaning depending on how they are worn, placed or experienced. In this way, each design becomes part of a larger narrative; a moment, a mood, a statement, or an extension of identity.</p>
                <p class="text-[1.1rem] text-[#111] leading-[1.9] mb-6">Leather is used as a medium for expression: sculptural, bold and playful. Her objects are not silent accessories, but statements and conversation pieces that invite interaction and interpretation.</p>
                <p class="text-[1.1rem] text-[#111] leading-[1.9] mb-6">Her practice spans bags, wearable objects and spatial concepts, developed for fashion campaigns, brand activations, film and editorial productions. Within collaborations, she operates as a conceptual designer and creative partner, translating ideas into striking visual form.</p>
                <p class="text-[1.1rem] text-[#111] leading-[1.9]">Her work gives ideas form, and allows them to exist within the visual world.</p>
            </div>
            <div class="overflow-hidden min-h-150">
                <img src="/images/magnum selfieh.jpg" alt="Grytsje Suze at work with Magnum bags" class="w-full h-full object-cover block min-h-150">
            </div>
        </div>
    </section>

    <!-- Samenwerking-sectie: keuze tussen collaborations en private work -->
    <section class="bg-black py-20 px-8">
        <div class="max-w-5xl mx-auto">
            <p class="font-bebas text-brand text-[0.85rem] tracking-[0.3em] mb-2 text-center">✦ WORK WITH ME</p>
            <h2 class="font-bebas text-[clamp(2rem,5vw,3.5rem)] text-white text-center mb-4">My work moves between brand collaborations<br class="hidden md:inline"> and private commissions.</h2>
            <p class="text-[#aaa] text-center mb-12 text-base">Choose the context that fits your project:</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="border-2 border-brand p-10">
                    <p class="font-bebas text-brand text-[0.85rem] tracking-[0.3em] mb-4">✦ COLLABORATIONS</p>
                    <p class="text-white text-base leading-[1.8] mb-8">For brands, campaigns, film, activations and creative projects.</p>
                    <a href="/collaborations" class="inline-block text-black text-xl px-8 py-3 transition-all duration-200 bg-brand hover:bg-brand-dark font-bebas">→ Explore Collaborations</a>
                </div>
                <div class="border-2 border-white p-10">
                    <p class="font-bebas text-white text-[0.85rem] tracking-[0.3em] mb-4">✦ PRIVATE WORK</p>
                    <p class="text-white text-base leading-[1.8] mb-8">For bespoke commissions, collectible objects and styling use.</p>
                    <a href="/commissions" class="inline-block text-white text-xl px-8 py-3 transition-all duration-200 border-2 border-white hover:bg-white hover:text-black font-bebas">→ Explore Private Work</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact-uitnodiging sectie onderaan de pagina -->
    <section class="bg-brand py-16 px-8 text-center">
        <a href="/contact" class="inline-block text-white text-2xl px-12 py-5 transition-all duration-200 bg-black hover:bg-[#333] font-bebas">Feel free to reach out →</a>
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
