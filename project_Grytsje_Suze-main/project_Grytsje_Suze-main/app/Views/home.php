<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Paginakop: tekenset, viewport, lettertype en stijlbladen -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grytsje Suze — Design made to be seen, meant to be felt</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link href="/css/output.css" rel="stylesheet">
</head>

<body class="flex flex-col min-h-screen">

<!-- Navigatiebalk: ingeladen via de herbruikbare header-layout -->
<?php require ROOT_PATH . '/app/Views/layouts/header.php'; ?>

<main class="flex-1">

    <!-- Hero-slogan: grote openingszin op zwarte achtergrond -->
    <section class="bg-black pt-16 px-8 pb-8 text-center">
        <h1 class="font-bebas text-white text-[clamp(2.5rem,8vw,7rem)] leading-[1.05] tracking-[0.02em]">
            Design made to be seen,<br><span class="text-brand">meant to be felt.</span>
        </h1>
    </section>

    <!-- Hero-carrousel: scrollende afbeeldingenrij met portfoliofoto's -->
    <div class="overflow-hidden w-full bg-black py-3">
        <div class="flex w-max will-change-transform" id="catalogueTrack">
            <!-- Doorloop de afbeeldingen en geef elk weer als een schuifbare kaart -->
            <?php foreach ($images as $img): ?>
            <div class="shrink-0 w-175 h-212.5 mx-3 overflow-hidden border-4 border-white">
                <img src="/images/<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars(pathinfo($img, PATHINFO_FILENAME)) ?>" class="w-full h-full object-cover block">
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Portfolioknop: doorverwijzing naar de volledige portfoliopagina -->
    <div class="flex justify-center py-8 bg-black">
        <a href="/portfolio" class="text-white text-2xl px-16 py-4 inline-block transition-all duration-200 shadow-lg bg-brand hover:bg-brand-dark font-bebas">See More of My Work →</a>
    </div>

    <!-- Mini-about-sectie: korte introductie van het studio-concept -->
    <section class="bg-white py-20 px-8">
        <div class="max-w-4xl mx-auto">
            <p class="font-bebas text-brand text-[0.85rem] tracking-[0.3em] mb-4">✦ WHAT'S IN MY BAG</p>
            <h2 class="font-bebas text-[clamp(2rem,5vw,4rem)] leading-[1.1] mb-8 text-black">
                Bags that carry more than objects.<br>They carry presence.
            </h2>
            <p class="text-[1.05rem] text-[#333] leading-[1.9] max-w-170">
                Leather as a medium for expression. Sculptural, bold and playful. Not silent accessories — but statements and conversation pieces that invite interaction and interpretation.
            </p>
            <div class="mt-8">
                <a href="/about" class="text-white text-xl px-10 py-4 inline-block transition-all duration-200 bg-black hover:bg-brand font-bebas">About the Studio →</a>
            </div>
        </div>
    </section>

    <!-- Manieren van samenwerken: keuze tussen collaborations en commissions -->
    <section class="bg-[#f5f5f5] py-20 px-8">
        <div class="max-w-5xl mx-auto">
            <p class="font-bebas text-brand text-[0.85rem] tracking-[0.3em] mb-2 text-center">✦ WAYS TO WORK TOGETHER</p>
            <h2 class="font-bebas text-[clamp(2rem,5vw,3.5rem)] text-center mb-4 text-black">
                My work moves between brand collaborations<br class="hidden md:inline"> and private commissions.
            </h2>
            <p class="text-center text-[#555] mb-12 text-base">Choose the context that fits your project:</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-black px-10 py-12">
                    <p class="font-bebas text-brand text-[0.85rem] tracking-[0.3em] mb-4">✦ COLLABORATIONS</p>
                    <p class="text-white text-base leading-[1.8] mb-8">For brands, campaigns, film and creative productions.</p>
                    <a href="/collaborations" class="inline-block text-black text-xl px-8 py-3 transition-all duration-200 bg-brand hover:bg-brand-dark font-bebas">→ Explore Collaborations</a>
                </div>
                <div class="bg-brand px-10 py-12">
                    <p class="font-bebas text-black text-[0.85rem] tracking-[0.3em] mb-4">✦ COMMISSIONS</p>
                    <p class="text-black text-base leading-[1.8] mb-8">For bespoke pieces, collectors and private clients.</p>
                    <a href="/commissions" class="inline-block text-white text-xl px-8 py-3 transition-all duration-200 bg-black hover:bg-[#222] font-bebas">→ Explore Commissions</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Nieuws-teaser: korte aankondiging met doorverwijzing naar de nieuwspagina -->
    <section class="bg-black py-20 px-8">
        <div class="max-w-4xl mx-auto">
            <p class="font-bebas text-brand text-[0.85rem] tracking-[0.3em] mb-2">✦ NEWS</p>
            <h2 class="font-bebas text-[clamp(2rem,5vw,3.5rem)] text-white mb-4">Latest Updates</h2>
            <p class="text-[#aaa] text-base leading-[1.8] mb-10">Studio updates, expositions, recent work and projects.</p>
            <a href="/news" class="inline-block text-black text-xl px-10 py-4 transition-all duration-200 bg-brand hover:bg-brand-dark font-bebas">View News →</a>
        </div>
    </section>

    <!-- Contact-uitnodiging sectie onderaan de pagina -->
    <section class="bg-white py-20 px-8">
        <div class="max-w-3xl mx-auto">
            <p class="font-bebas text-brand text-[0.85rem] tracking-[0.3em] mb-2">✦ GET IN TOUCH</p>
            <h2 class="font-bebas text-[clamp(2rem,5vw,3.5rem)] text-black mb-4">Feel free to reach out.</h2>
            <p class="text-[#555] text-base mb-10">For more information, collaborations, commissions or just to say hello.</p>
            <a href="/contact" class="inline-block text-white text-xl px-10 py-4 transition-all duration-200 bg-black hover:bg-brand font-bebas">Send Inquiry →</a>
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
