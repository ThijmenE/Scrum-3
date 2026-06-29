<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Paginakop: tekenset, viewport, lettertype en stijlbladen -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commissions — Grytsje Suze</title>
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
            <h1 class="font-bebas text-black text-[clamp(3rem,10vw,8rem)] leading-none">Commissions</h1>
        </div>
    </div>

    <!-- Inleiding: beschrijving van het private work-aanbod -->
    <section class="bg-white py-20 px-8">
        <div class="max-w-4xl mx-auto">
            <p class="font-bebas text-brand text-[0.85rem] tracking-[0.3em] mb-6">✦ PRIVATE WORK</p>
            <p class="text-[1.15rem] text-[#111] leading-[1.9] mb-6">Private work includes one-of-a-kind leather objects developed for individuals, collectors, creative professionals and independent founders. Each piece exists at the intersection of fashion, art and identity.</p>
            <p class="text-[1.15rem] text-[#111] leading-[1.9]">The practice moves between commissioned work, available objects and temporary use in visual contexts, offering different ways of engaging with each piece.</p>
        </div>
    </section>

    <!-- Maatwerk-sectie: uitleg over de bespoke commissioning-praktijk -->
    <section class="bg-[#f5f5f5] py-20 px-8">
        <div class="max-w-4xl mx-auto">
            <div class="w-full h-0.75 bg-brand mb-12"></div>
            <p class="font-bebas text-brand text-[0.85rem] tracking-[0.3em] mb-6">✦ BESPOKE COMMISSIONS</p>
            <p class="text-[1.1rem] text-[#111] leading-[1.9] mb-6">Bespoke work includes one-of-a-kind leather objects developed for private clients, collectors, creative professionals and independent founders.</p>
            <p class="text-[1.1rem] text-[#111] leading-[1.9] mb-6">Each commission begins with a concept, emotion or visual idea, translated into a singular object through dialogue and material exploration.</p>
            <p class="text-[1.1rem] text-[#111] leading-[1.9] mb-6">These works are fully custom-made and shaped in close exchange with the client. Resulting in unique objects that can function as wearable statements, sculptural pieces or symbolic design objects.</p>
            <p class="text-[1.1rem] text-[#111] leading-[1.9]">Each commission is created with attention to material, detail and longevity.</p>
            <div class="w-full h-0.75 bg-brand mt-12"></div>
        </div>
    </section>

    <!-- Beschikbare objecten-sectie: collectible stukken die te verkrijgen zijn -->
    <section class="bg-white py-20 px-8">
        <div class="max-w-4xl mx-auto">
            <div class="w-full h-0.75 bg-brand mb-12"></div>
            <p class="font-bebas text-brand text-[0.85rem] tracking-[0.3em] mb-6">✦ AVAILABLE OBJECTS</p>
            <p class="text-[1.1rem] text-[#111] leading-[1.9] mb-6">Alongside commissioned work, selected pieces from Grytsje Suze's practice are occasionally available as collectible objects.</p>
            <p class="text-[1.1rem] text-[#111] leading-[1.9] mb-6">These works can be acquired as existing pieces or developed into made-to-order variations, depending on material availability and context.</p>
            <p class="text-[1.1rem] text-[#111] leading-[1.9]">Availability is limited and changes according to ongoing studio work and material exploration.</p>
            <div class="w-full h-0.75 bg-brand mt-12"></div>
        </div>
    </section>

    <!-- Styling en visueel gebruik-sectie: tijdelijk gebruik in producties -->
    <section class="bg-[#f5f5f5] py-20 px-8">
        <div class="max-w-4xl mx-auto">
            <div class="w-full h-0.75 bg-brand mb-12"></div>
            <p class="font-bebas text-brand text-[0.85rem] tracking-[0.3em] mb-6">✦ STYLING &amp; VISUAL USE</p>
            <p class="text-[1.1rem] text-[#111] leading-[1.9] mb-6">Selected objects are also available for temporary use within styling, events and visual productions.</p>
            <p class="text-[1.1rem] text-[#111] leading-[1.9] mb-6">These pieces can be used in fashion shoots, film, editorial work, exhibitions and brand activations.</p>
            <p class="text-[1.1rem] text-[#111] leading-[1.9]">This allows the work to exist within different visual contexts without altering its original identity.</p>
            <div class="w-full h-0.75 bg-brand mt-12"></div>
        </div>
    </section>

    <!-- Voorbeeldproject-sectie: showcase van een eerdere opdracht met portfoliolink -->
    <section class="bg-black py-20 px-8">
        <div class="max-w-7xl mx-auto">
            <p class="font-bebas text-[#aaa] text-[0.85rem] tracking-[0.3em] text-center mb-6">EXAMPLE PROJECT</p>
            <div class="bg-[#111] p-12 text-center">
                <div class="w-full max-w-200 mx-auto overflow-hidden">
                    <img src="/images/Magnum Bags.jpg" alt="Custom commission — Grytsje Suze" class="w-full h-auto block">
                </div>
                <div class="mt-8">
                    <a href="/portfolio" class="inline-block text-black text-xl px-10 py-4 transition-all duration-200 bg-brand hover:bg-brand-dark font-bebas">View Full Portfolio →</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact-uitnodiging sectie onderaan de pagina -->
    <section class="bg-brand py-20 px-8 text-center">
        <div class="max-w-3xl mx-auto">
            <div class="w-full h-0.5 bg-black mb-12"></div>
            <p class="font-bebas text-black text-[0.85rem] tracking-[0.3em] mb-4">✦ ENQUIRIES</p>
            <h2 class="font-bebas text-[clamp(1.8rem,4vw,3rem)] text-black mb-8">For bespoke commissions, available pieces or styling &amp; production use</h2>
            <a href="/contact" class="inline-block text-white text-2xl px-12 py-5 transition-all duration-200 bg-black hover:bg-[#333] font-bebas">Get in touch →</a>
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
