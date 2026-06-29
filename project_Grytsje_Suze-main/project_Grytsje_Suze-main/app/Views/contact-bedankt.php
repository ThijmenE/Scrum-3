<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Paginakop: tekenset, viewport, lettertype en stijlbladen -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank you — Grytsje Suze</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link href="/css/output.css" rel="stylesheet">
</head>
<body class="flex flex-col min-h-screen">

<!-- Navigatiebalk: ingeladen via de herbruikbare header-layout -->
<?php require ROOT_PATH . '/app/Views/layouts/header.php'; ?>

<!-- Bedankboodschap: gecentreerd weergegeven na het verzenden van het contactformulier -->
<main class="flex-1 flex items-center justify-center bg-white py-24 px-8">
    <div class="text-center max-w-2xl mx-auto">
        <p class="font-bebas text-brand text-[0.85rem] tracking-[0.3em] mb-4">✦ MESSAGE RECEIVED</p>
        <h1 class="font-bebas text-[clamp(3rem,10vw,6rem)] leading-[1.05] text-black mb-8">
            Thank you for<br>reaching out.
        </h1>
        <p class="text-[1.1rem] text-[#333] leading-[1.8] mb-4">Your request has been received.</p>
        <p class="text-base text-[#777] leading-[1.8] mb-12">Each inquiry is reviewed with care, and I'll come back to you as soon as possible.</p>

        <!-- Terugknop naar de homepage -->
        <a href="/" class="inline-block text-white text-2xl px-12 py-5 transition-all duration-200 bg-black hover:bg-brand font-bebas">Return to Homepage →</a>
    </div>
</main>

<!-- Voettekst: ingeladen via de herbruikbare footer-layout -->
<?php require ROOT_PATH . '/app/Views/layouts/footer.php'; ?>

</body>
</html>
