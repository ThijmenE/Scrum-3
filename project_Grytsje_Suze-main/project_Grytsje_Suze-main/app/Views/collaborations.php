<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Paginakop: tekenset, viewport, lettertype en stijlbladen -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collaborations — Grytsje Suze</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link href="/css/output.css" rel="stylesheet">
</head>
<body class="flex flex-col min-h-screen">

<!-- Navigatiebalk: ingeladen via de herbruikbare header-layout -->
<?php require ROOT_PATH . '/app/Views/layouts/header.php'; ?>

<main class="flex-1">

    <!-- Paginatitel: grote koptekst op zwarte achtergrond -->
    <div class="bg-black pt-12 px-8 pb-10">
        <div class="max-w-7xl mx-auto">
            <h1 class="font-bebas text-white text-[clamp(3rem,10vw,8rem)] leading-none">Collaborations</h1>
        </div>
    </div>

    <!-- Inleiding: beschrijving van de samenwerkingspraktijk -->
    <section class="bg-white py-20 px-8">
        <div class="max-w-4xl mx-auto">
            <p class="text-[1.15rem] text-[#111] leading-[1.9] mb-6">Grytsje Suze works as a conceptual designer and creative consultant, collaborating with brands, agencies and creative teams to develop leather-based objects, experiences and visual systems for fashion campaigns, film, editorial work and brand activations.</p>
            <p class="text-[1.15rem] text-[#111] leading-[1.9]">Each project is developed in close dialogue. Translating ideas, narratives and identities into sculptural leather objects and experiential formats that function as visual and physical extensions of a brand's story.</p>
        </div>
    </section>

    <!-- Mogelijkheden-sectie: dynamische lijst met vaardigheden uit de controller -->
    <section class="bg-[#f5f5f5] py-20 px-8">
        <div class="max-w-4xl mx-auto">
            <div class="w-full h-0.75 bg-brand mb-12"></div>
            <p class="font-bebas text-brand text-[0.85rem] tracking-[0.3em] mb-6">CAPABILITIES</p>
            <ul class="list-none p-0 flex flex-col gap-3">
                <!-- Doorloop de mogelijkheden en geef elk item weer als lijstelement -->
                <?php foreach ($capabilities as $cap): ?>
                <li class="text-base text-[#111] py-[0.85rem] px-5 border-l-4 border-brand bg-white">
                    ✦ <?= htmlspecialchars($cap) ?>
                </li>
                <?php endforeach; ?>
            </ul>
            <div class="w-full h-0.75 bg-brand mt-12"></div>
        </div>
    </section>

    <!-- Werkwijze-sectie: uitleg over process, schaal en toepassingsgebieden -->
    <section class="bg-white py-20 px-8">
        <div class="max-w-4xl mx-auto">
            <div class="w-full h-0.75 bg-brand mb-12"></div>
            <p class="font-bebas text-brand text-[0.85rem] tracking-[0.3em] mb-6">PROCESS</p>
            <p class="text-[1.1rem] text-[#111] leading-[1.9] mb-12">Each project begins with an idea, narrative or visual direction. Through conversation, experimentation and development, Grytsje Suze translates concepts into objects or experiential formats that align with the story, identity or atmosphere of a project. She works independently or in collaboration with specialist makers and production partners.</p>

            <div class="w-full h-0.75 bg-brand mb-12"></div>
            <p class="font-bebas text-brand text-[0.85rem] tracking-[0.3em] mb-6">SCALE</p>
            <p class="text-[1.1rem] text-[#111] leading-[1.9] mb-12">Grytsje Suze operates across different scales. From singular leather statement pieces and conversation objects to limited series, workshops, brand experiences and larger visual systems for campaigns, launches and activations.</p>

            <div class="w-full h-0.75 bg-brand mb-12"></div>
            <p class="font-bebas text-brand text-[0.85rem] tracking-[0.3em] mb-6">APPLICATION &amp; IMPACT</p>
            <p class="text-[1.1rem] text-[#111] leading-[1.9] mb-6">Her work is used to strengthen visual identity and create distinction within competitive visual environments. Leather objects and experiences function as engagement tools, conversation starters and focal points within campaigns.</p>
            <p class="text-[1.1rem] text-[#111] leading-[1.9] mb-6">They are applied in brand activations, product launches, workshops and experiential settings, contributing to a more tangible and immersive brand experience.</p>
            <p class="text-[1.1rem] text-[#111] leading-[1.9] mb-6">Her work is also available for use in film, television and editorial productions. These works function as props, wearable statements or visual elements within storytelling contexts.</p>
            <p class="text-[1.1rem] text-[#111] leading-[1.9]">In addition, her work includes bespoke gifting — custom-designed leather objects for corporate gifting, VIP relations and special campaigns.</p>
            <div class="w-full h-0.75 bg-brand mt-12"></div>
        </div>
    </section>

    <!-- Uitgelichte samenwerking: Magnum Show showcase met afbeelding en portfoliolink -->
    <section class="bg-black py-20 px-8">
        <div class="max-w-7xl mx-auto">
            <p class="font-bebas text-[#aaa] text-[0.85rem] tracking-[0.3em] text-center mb-6">FEATURED COLLABORATION</p>
            <div class="bg-[#111] p-12 text-center">
                <p class="font-bebas text-white text-[clamp(1.5rem,4vw,2.5rem)] mb-2">Magnum Show</p>
                <p class="text-[#aaa] text-[0.9rem] mb-8">Film by Fourcreating</p>
                <div class="max-w-200 mx-auto overflow-hidden">
                    <img src="/images/Magnum Bags.jpg" alt="Magnum Show — Grytsje Suze" class="w-full h-auto block">
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
            <p class="font-bebas text-black text-[0.85rem] tracking-[0.3em] mb-4">COLLABORATION</p>
            <h2 class="font-bebas text-[clamp(1.8rem,4vw,3rem)] text-black mb-8">For collaborations, production use, workshops or bespoke commissions</h2>
            <a href="/contact" class="inline-block text-white text-2xl px-12 py-5 transition-all duration-200 bg-black hover:bg-[#333] font-bebas">Feel free to contact me →</a>
        </div>
    </section>

</main>

<!-- Zwevende contact-knop rechtsonder in beeld -->
<a href="/contact" class="fixed bottom-8 right-8 z-50 inline-flex items-center gap-2 text-black text-lg px-6 py-3 shadow-2xl transition-all duration-200 bg-brand hover:bg-brand-dark font-bebas rounded-full">
    ✦ Reach Out
</a>

<!-- Voettekst: ingeladen via de herbruikbare footer-layout -->
<?php require ROOT_PATH . '/app/Views/layouts/footer.php'; ?>

<!-- JavaScript voor interactieve pagina-elementen -->
<script src="/js/script.js"></script>
</body>
</html>
