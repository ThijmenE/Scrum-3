<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nieuwe Tas Toevoegen</title>
    <link rel="stylesheet" href="/css/output.css">
    <script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/3.4.0/model-viewer.min.js"></script>
</head>

<body class="bg-gray-100 font-sans">
    <nav class="bg-gray-900 border-b border-gray-800 shadow-lg">
        <div class="container mx-auto px-4 flex items-center justify-between h-16">
            <span class="text-white text-lg font-bold tracking-wider uppercase">Admin<span class="text-blue-500">Panel</span></span>
            <a href="/admin" class="text-gray-300 hover:text-white text-sm font-medium cursor-pointer">Terug naar overzicht</a>
        </div>
    </nav>

    <div class="container mx-auto mt-10 px-4 max-w-5xl">
        <div class="bg-white shadow-md rounded-lg p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-8">Nieuwe Tas Toevoegen</h2>

            <form action="/admin/tassen/opslaan" method="POST" enctype="multipart/form-data"
                class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Naam</label>
                        <input type="text" name="naam" required
                            class="w-full border border-gray-300 rounded-md p-3 outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Beschrijving:</label>
                        <textarea name="beschrijving" rows="4" required
                            class="w-full border border-gray-300 rounded-md p-3 outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Achtergrondkleur:</label>
                        <div class="flex items-center gap-3">
                            <input type="color" name="kleurcode" id="kleurcode" value="#3b82f6" class="h-10 w-20 cursor-pointer">
                            <button type="button" id="pipet-btn" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded font-semibold text-sm transition cursor-pointer">Pipet / Tik op foto</button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Tekstkleur:</label>
                        <div class="flex items-center gap-3">
                            <input type="color" name="tekstkleur" id="tekstkleur" value="#000000" class="h-10 w-20 cursor-pointer">
                            <button type="button" id="pipet-tekstkleur-btn" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded font-semibold text-sm transition cursor-pointer">Pipet / Tik op foto</button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Titelkleur:</label>
                        <div class="flex items-center gap-3">
                            <input type="color" name="titelkleur" id="titelkleur" value="#000000" class="h-10 w-20 cursor-pointer">
                            <button type="button" id="pipet-titelkleur-btn" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded font-semibold text-sm transition cursor-pointer">Pipet / Tik op foto</button>
                        </div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg border border-dashed border-gray-300">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">3D Model (.glb)</label>
                        <input type="file" name="model_3d" id="model-input" accept=".glb"
                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 file:cursor-pointer">
                    </div>
                </div>

                <div class="flex flex-col items-center">
                    <div class="w-full aspect-square max-w-100 mb-4">
                        <img id="preview-img" src="https://via.placeholder.com/400?text=Voorbeeld"
                            class="w-full h-full object-cover rounded-xl shadow-inner border border-gray-200">
                    </div>
                    <div class="w-full">
                        <input type="file" name="afbeelding" id="file-input" accept="image/*" required class="hidden">
                        <button type="button" onclick="document.getElementById('file-input').click();"
                            class="w-full bg-gray-800 hover:bg-black text-white font-bold py-3 px-6 rounded-lg transition uppercase text-xs tracking-widest cursor-pointer">📷 Upload Foto</button>
                    </div>
                </div>

                <div class="md:col-span-2 flex justify-end gap-3 pt-6 border-t mt-4">
                    <a href="/admin" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-6 rounded-md transition cursor-pointer">Annuleren</a>
                    <button type="submit" name="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-10 rounded-md shadow-sm transition cursor-pointer">Opslaan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const pipetBtn = document.getElementById('pipet-btn');
        const kleurInput = document.getElementById('kleurcode');
        const previewImg = document.getElementById('preview-img');
        const fileInput = document.getElementById('file-input');
        let isPickingColor = false;

        fileInput.addEventListener('change', e => {
            const r = new FileReader(); r.onload = ev => previewImg.src = ev.target.result;
            r.readAsDataURL(e.target.files[0]);
        });

        if ('EyeDropper' in window) {
            const ed = new EyeDropper();
            pipetBtn.addEventListener('click', () => { ed.open().then(res => { kleurInput.value = res.sRGBHex; }); });
            document.getElementById('pipet-tekstkleur-btn').addEventListener('click', () => {
                ed.open().then(res => { document.getElementById('tekstkleur').value = res.sRGBHex; });
            });
            document.getElementById('pipet-titelkleur-btn').addEventListener('click', () => {
                ed.open().then(res => { document.getElementById('titelkleur').value = res.sRGBHex; });
            });
        } else {
            pipetBtn.addEventListener('click', () => {
                isPickingColor = !isPickingColor;
                if (isPickingColor) {
                    pipetBtn.textContent = "Klik nu op de foto...";
                    previewImg.style.cursor = "crosshair";
                    previewImg.style.outline = "4px solid #3b82f6";
                } else { resetPipet(); }
            });
            function resetPipet() {
                isPickingColor = false;
                pipetBtn.textContent = "Pipet / Tik op foto";
                previewImg.style.cursor = "default";
                previewImg.style.outline = "none";
            }
            previewImg.addEventListener('click', e => {
                if (isPickingColor) {
                    const canvas = document.createElement('canvas'); const ctx = canvas.getContext('2d');
                    canvas.width = previewImg.naturalWidth; canvas.height = previewImg.naturalHeight;
                    ctx.drawImage(previewImg, 0, 0, canvas.width, canvas.height);
                    const rect = previewImg.getBoundingClientRect();
                    const x = (e.clientX - rect.left) * (canvas.width / rect.width);
                    const y = (e.clientY - rect.top) * (canvas.height / rect.height);
                    const p = ctx.getImageData(x, y, 1, 1).data;
                    kleurInput.value = "#" + ((1 << 24) + (p[0] << 16) + (p[1] << 8) + p[2]).toString(16).slice(1);
                    resetPipet();
                }
            });
        }
    </script>
</body>

</html>
