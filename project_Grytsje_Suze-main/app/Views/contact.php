<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Paginakop: tekenset, viewport, lettertype en stijlbladen -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact — Grytsje Suze</title>
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
            <p class="font-bebas text-black text-[0.85rem] tracking-[0.3em] mb-2">✦ FEEL FREE TO REACH OUT ✨</p>
            <h1 class="font-bebas text-white text-[clamp(3rem,10vw,7rem)] leading-none">Contact</h1>
        </div>
    </div>

    <!-- Inleidende tekst boven het contactformulier -->
    <div class="bg-white px-8 py-10 border-b border-[#f0f0f0]">
        <div class="max-w-4xl mx-auto">
            <p class="text-[1.05rem] text-[#333] leading-[1.8]">Whether it's a collaboration, an idea, or a question. Feel free to reach out to me and share a few details below.</p>
        </div>
    </div>

    <!-- Contactformulier-sectie: formulier links, afbeelding rechts -->
    <section class="bg-white py-20 px-8">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-10 items-start">

            <!-- Contactformulier met validatiefouten en formuliervelden -->
            <div class="bg-black px-10 py-12">
                <h2 class="font-bebas text-white text-[2rem] mb-8">Send Inquiry</h2>

                <!-- Foutmeldingen: getoond als de server validatiefouten teruggeeft -->
                <?php if (!empty($errors)): ?>
                <div class="bg-brand px-6 py-4 mb-8">
                    <ul class="list-none p-0 text-black text-[0.9rem] leading-[1.8]">
                        <?php foreach ($errors as $err): ?>
                        <li>✦ <?= htmlspecialchars($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <!-- Formulier: verstuurt gegevens via POST naar de contactcontroller -->
                <form action="/contact" method="POST" class="flex flex-col gap-5">

                    <!-- Aanvraagtype-dropdown: dynamisch gevuld met voorgedefinieerde opties -->
                    <div class="flex flex-col gap-1">
                        <label class="font-bebas text-brand text-[0.8rem] tracking-[0.2em]">I'M REACHING OUT FOR</label>
                        <select name="inquiry_type" class="bg-[#111] text-white border-2 border-white px-4 py-3 text-[0.95rem] outline-none" required>
                            <option value="" disabled <?= empty($post['inquiry_type']) ? 'selected' : '' ?>>— Select an option —</option>
                            <?php
                            $inquiryOptions = [
                                'Collaboration (brand / campaign / film)',
                                'Commission (custom piece)',
                                'Available work inquiry',
                                'Styling / production use (film, events, shoot)',
                                'Workshop / brand experience',
                                'Other',
                            ];
                            foreach ($inquiryOptions as $opt): ?>
                            <option value="<?= htmlspecialchars($opt) ?>" <?= ($post['inquiry_type'] ?? '') === $opt ? 'selected' : '' ?>><?= htmlspecialchars($opt) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Naamveld -->
                    <div class="flex flex-col gap-1">
                        <label class="font-bebas text-brand text-[0.8rem] tracking-[0.2em]">NAME</label>
                        <input type="text" name="name" placeholder="Your name" value="<?= htmlspecialchars($post['name'] ?? '') ?>"
                               class="bg-[#111] text-white border-2 border-white px-4 py-3 text-[0.95rem] outline-none" required>
                    </div>

                    <!-- E-mailveld -->
                    <div class="flex flex-col gap-1">
                        <label class="font-bebas text-brand text-[0.8rem] tracking-[0.2em]">EMAIL</label>
                        <input type="email" name="email" placeholder="your@email.com" value="<?= htmlspecialchars($post['email'] ?? '') ?>"
                               class="bg-[#111] text-white border-2 border-white px-4 py-3 text-[0.95rem] outline-none" required>
                    </div>

                    <!-- Bedrijf/projectveld (optioneel) -->
                    <div class="flex flex-col gap-1">
                        <label class="font-bebas text-[#aaa] text-[0.8rem] tracking-[0.2em]">COMPANY / PROJECT <span class="text-[#555]">(optional)</span></label>
                        <input type="text" name="company" placeholder="Company or project name" value="<?= htmlspecialchars($post['company'] ?? '') ?>"
                               class="bg-[#111] text-white border-2 border-[#333] px-4 py-3 text-[0.95rem] outline-none">
                    </div>

                    <!-- Projectbeschrijving-tekstgebied -->
                    <div class="flex flex-col gap-1">
                        <label class="font-bebas text-brand text-[0.8rem] tracking-[0.2em]">TELL ME ABOUT YOUR PROJECT</label>
                        <p class="text-[#777] text-[0.8rem] mb-2">Please describe your idea, use case, or request in detail.</p>
                        <textarea name="project" rows="5" placeholder="Describe your idea, use case or request..."
                                  class="bg-[#111] text-white border-2 border-white px-4 py-3 text-[0.95rem] outline-none resize-y" required><?= htmlspecialchars($post['project'] ?? '') ?></textarea>
                    </div>

                    <!-- Tijdlijn-dropdown: dynamisch gevuld met beschikbare opties (optioneel) -->
                    <div class="flex flex-col gap-1">
                        <label class="font-bebas text-[#aaa] text-[0.8rem] tracking-[0.2em]">TIMELINE <span class="text-[#555]">(optional)</span></label>
                        <select name="timeline" class="bg-[#111] text-white border-2 border-[#333] px-4 py-3 text-[0.95rem] outline-none">
                            <option value="">— Select timeline —</option>
                            <?php foreach (['ASAP', '1–3 months', 'Flexible'] as $t): ?>
                            <option value="<?= $t ?>" <?= ($post['timeline'] ?? '') === $t ? 'selected' : '' ?>><?= $t ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Budgetbereik-dropdown: dynamisch gevuld met beschikbare opties (optioneel) -->
                    <div class="flex flex-col gap-1">
                        <label class="font-bebas text-[#aaa] text-[0.8rem] tracking-[0.2em]">BUDGET RANGE <span class="text-[#555]">(optional)</span></label>
                        <select name="budget" class="bg-[#111] text-white border-2 border-[#333] px-4 py-3 text-[0.95rem] outline-none">
                            <option value="">— Select budget —</option>
                            <?php foreach (['Under €2k', '€2k–€10k', '€10k–€25k', '€25k+', 'Not sure yet'] as $b): ?>
                            <option value="<?= $b ?>" <?= ($post['budget'] ?? '') === $b ? 'selected' : '' ?>><?= $b ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Verzendknop voor het formulier -->
                    <button type="submit" class="w-full text-white text-2xl px-8 py-4 transition-all duration-200 shadow-lg mt-2 bg-brand hover:bg-brand-dark font-bebas">SEND INQUIRY →</button>
                </form>
            </div>

            <!-- Afbeelding en begeleidende tekst naast het formulier -->
            <div class="flex flex-col gap-6">
                <div class="overflow-hidden min-h-125">
                    <img src="/images/magnum selfieh.jpg" alt="Grytsje Suze" class="w-full h-full object-cover block min-h-125">
                </div>
                <p class="text-[0.9rem] text-[#555] leading-[1.8] italic">Each inquiry is reviewed with care, and I'll come back to you as soon as possible.</p>
            </div>

        </div>
    </section>

</main>

<!-- Zwevende contact-knop rechtsonder in beeld -->
<a href="/contact" class="fixed bottom-8 right-8 z-50 inline-flex items-center gap-2 text-black text-lg px-6 py-3 shadow-2xl transition-all duration-200 bg-brand hover:bg-brand-dark font-bebas rounded-full">✦ Reach Out</a>

<!-- Voettekst: ingeladen via de herbruikbare footer-layout -->
<?php require ROOT_PATH . '/app/Views/layouts/footer.php'; ?>
</body>
</html>
