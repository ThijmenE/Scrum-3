<!DOCTYPE html>
<html lang="nl">

<head>
    <!-- Paginakop: tekenset, viewport, lettertype en stijlbladen -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio — Grytsje Suze</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link href="/css/output.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>

<body class="flex flex-col min-h-screen">

    <!-- Navigatiebalk: ingeladen via de herbruikbare header-layout -->
    <?php require ROOT_PATH . '/app/Views/layouts/header.php'; ?>

    <!-- Tassenlijst: doorloop alle tassen en geef ze afwisselend links/rechts weer -->
    <main class="grow">
        <?php
        $count = 0;
        foreach ($tassen as $tas):
            // Achtergrondkleur en contrasttekstklasse bepalen op basis van de kleurcode van de tas
            $bgColor = !empty($tas['kleurcode']) ? htmlspecialchars($tas['kleurcode']) : '#ffffff';
            $textColorClass = \App\Models\Tas::getContrastColor($bgColor);
            // Afwisselende layout: even tassen links, oneven tassen rechts
            $reverse = $count % 2 === 1;
        ?>
            <!-- Tas-kaart: afbeelding en info naast elkaar, klikbaar naar de detailpagina -->
            <div class="flex flex-col md:flex-row<?= $reverse ? ' md:flex-row-reverse' : '' ?> w-full min-h-[40vh] md:min-h-[60vh] border-b border-gray-300 cursor-pointer hover:opacity-95 transition-opacity"
                onclick="window.location.href='/portfolio/<?= $tas['id'] ?>'">

                <!-- Afbeeldingszijde van de kaart -->
                <div class="w-full md:w-1/2 overflow-hidden bg-gray-100">
                    <img src="/<?= htmlspecialchars($tas['afbeelding']) ?>" alt="<?= htmlspecialchars($tas['naam']) ?>"
                        class="w-full h-full object-cover">
                </div>

                <!-- Infozijde van de kaart: naam, beschrijving en customizer-link -->
                <div class="w-full md:w-1/2 flex flex-col justify-center p-6 md:p-12 <?= $textColorClass ?>"
                    style="background-color: <?= $bgColor ?>;">
                    <h2 class="font-bebas text-xl md:text-5xl font-bold mb-2 md:mb-4 uppercase">
                        <?= htmlspecialchars($tas['naam']) ?>
                    </h2>
                    <p class="text-xs md:text-lg opacity-90 leading-relaxed max-w-md mb-6">
                        <?= nl2br(htmlspecialchars($tas['beschrijving'])) ?>
                    </p>
                    <a href="/portfolio/<?= $tas['id'] ?>"
                        class="font-bebas inline-block w-full text-sm md:text-lg border border-current px-6 py-2 uppercase tracking-wider hover:bg-white hover:text-black transition-colors">
                        Bekijk Customizer <i class="fa-solid fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        <?php $count++; endforeach; ?>
    </main>

    <!-- Zwevende contact-knop rechtsonder in beeld -->
    <a href="/contact"
        class="fixed bottom-8 right-8 z-50 inline-flex items-center gap-2 text-black text-lg px-6 py-3 shadow-2xl transition-all duration-200 bg-brand hover:bg-brand-dark font-bebas rounded-full">
        Reach Out <i class="fa-solid fa-arrow-right"></i>
    </a>

    <!-- Voettekst: ingeladen via de herbruikbare footer-layout -->
    <?php require ROOT_PATH . '/app/Views/layouts/footer.php'; ?>
</body>

</html>
