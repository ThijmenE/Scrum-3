<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tas Bewerken</title>
    <link rel="stylesheet" href="/css/output.css">
    <script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/3.4.0/model-viewer.min.js"></script>
</head>

<body class="bg-gray-100 font-sans">
    <nav class="bg-gray-900 border-b border-gray-800 shadow-lg h-16 flex items-center px-4">
        <div class="container mx-auto flex justify-between items-center">
            <span class="text-white text-lg font-bold tracking-wider uppercase">Admin<span class="text-blue-500">Panel</span></span>
            <a href="/admin" class="text-gray-300 hover:text-white text-sm font-medium cursor-pointer">Terug naar overzicht</a>
        </div>
    </nav>

    <div class="container mx-auto mt-10 px-4 max-w-5xl">
        <div class="bg-white shadow-md rounded-lg p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-8 border-b pb-4">Tas Aanpassen</h2>

            <form action="/admin/tassen/<?= $tas['id'] ?>/opslaan" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="space-y-6">
                    <input type="text" name="naam" value="<?= htmlspecialchars($tas['naam']) ?>"
                        class="w-full border border-gray-300 rounded-md p-3 outline-none focus:ring-2 focus:ring-blue-500">
                    <textarea name="beschrijving" rows="4"
                        class="w-full border border-gray-300 rounded-md p-3 outline-none focus:ring-2 focus:ring-blue-500"><?= htmlspecialchars($tas['beschrijving']) ?></textarea>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Huidig Model</label>
                        <model-viewer src="/<?= htmlspecialchars($tas['model_3d']) ?>" auto-rotate camera-controls shadow-intensity="1"
                            class="w-full h-48 bg-gray-50 rounded-lg"></model-viewer>
                        <input type="file" name="model_3d" accept=".glb"
                            class="mt-4 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 file:cursor-pointer">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Achtergrondkleur:</label>
                        <div class="flex items-center gap-3">
                            <input type="color" name="kleurcode" id="kleurcode" value="<?= htmlspecialchars($tas['kleurcode']) ?>" class="h-10 w-20 cursor-pointer">
                            <button type="button" id="pipet-btn" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded font-semibold text-sm transition cursor-pointer">Pipet / Tik op foto</button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Tekstkleur:</label>
                        <div class="flex items-center gap-3">
                            <input type="color" name="tekst_kleur" id="tekst_kleur" value="<?= htmlspecialchars($tas['tekst_kleur'] ?? '#000000') ?>" class="h-10 w-20 cursor-pointer">
                            <button type="button" id="pipet-tekst-kleur-btn" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded font-semibold text-sm transition cursor-pointer">Pipet / Tik op foto</button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Titelkleur:</label>
                        <div class="flex items-center gap-3">
                            <input type="color" name="titel_kleur" id="titel_kleur" value="<?= htmlspecialchars($tas['titel_kleur'] ?? '#000000') ?>" class="h-10 w-20 cursor-pointer">
                            <button type="button" id="pipet-titel-kleur-btn" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded font-semibold text-sm transition cursor-pointer">Pipet / Tik op foto</button>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col items-center">
                    <img id="preview-img" src="/<?= htmlspecialchars($tas['afbeelding']) ?>"
                        class="w-full aspect-square object-cover rounded-xl shadow-md border border-gray-200 mb-4">
                    <input type="file" name="afbeelding" id="file-input" accept="image/*" class="hidden">
                    <button type="button" onclick="document.getElementById('file-input').click();"
                        class="w-full bg-gray-800 hover:bg-black text-white font-bold py-3 rounded-lg uppercase text-xs tracking-widest cursor-pointer">📷 Nieuwe Foto Uploaden</button>
                </div>

                <div class="md:col-span-2 flex justify-end gap-3 pt-6 border-t mt-4">
                    <a href="/admin" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-6 rounded-md transition cursor-pointer">Annuleren</a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-10 rounded-md shadow-sm transition cursor-pointer">Wijzigingen Opslaan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const previewImg = document.getElementById('preview-img');
        const fileInput = document.getElementById('file-input');
        const kleurInput = document.getElementById('kleurcode');
        const tekstkleurInput = document.getElementById('tekst_kleur');
        const titelkleurInput = document.getElementById('titel_kleur');
        let isPickingColor = false;

        fileInput.addEventListener('change', e => {
            const r = new FileReader(); r.onload = ev => previewImg.src = ev.target.result;
            r.readAsDataURL(e.target.files[0]);
        });

        if ('EyeDropper' in window) {
            const ed = new EyeDropper();
            document.getElementById('pipet-btn').addEventListener('click', () => { ed.open().then(res => { kleurInput.value = res.sRGBHex; }); });
            document.getElementById('pipet-tekst-kleur-btn').addEventListener('click', () => { ed.open().then(res => { tekstkleurInput.value = res.sRGBHex; }); });
            document.getElementById('pipet-titel-kleur-btn').addEventListener('click', () => { ed.open().then(res => { titelkleurInput.value = res.sRGBHex; }); });
        }
    </script>
</body>

</html>
