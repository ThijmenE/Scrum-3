<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Tassen Beheer</title>
    <link rel="stylesheet" href="/css/output.css">
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <nav class="bg-gray-900 border-b border-gray-800 shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-2">
                    <div class="bg-blue-600 p-1.5 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <span class="text-white text-lg font-bold tracking-wider uppercase">Admin<span class="text-blue-500">Panel</span></span>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="/admin" class="text-gray-300 hover:text-white hover:bg-gray-800 px-3 py-2 rounded-md text-sm font-medium transition duration-200 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        Tassen
                    </a>
                    <a href="/admin/gebruikers" class="text-gray-300 hover:text-white hover:bg-gray-800 px-3 py-2 rounded-md text-sm font-medium transition duration-200 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        Admins
                    </a>
                    <div class="h-6 w-px bg-gray-700 mx-2"></div>
                    <a href="/admin/uitloggen" class="text-red-400 hover:text-red-300 hover:bg-red-900/20 px-3 py-2 rounded-md text-sm font-medium transition duration-200">Uitloggen</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto mt-6 md:mt-10 px-4 max-w-6xl">

        <div class="flex flex-row justify-between items-center mb-6 gap-4">
            <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Tassen Beheer</h1>
            <a href="/admin/tassen/toevoegen" class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-3 sm:px-4 rounded shadow-sm transition flex items-center text-sm sm:text-base whitespace-nowrap">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span class="hidden sm:inline">Nieuwe Tas Toevoegen</span>
                <span class="sm:hidden">Toevoegen</span>
            </a>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 text-left text-sm uppercase tracking-wider hidden sm:table-row">
                            <th class="px-6 py-3 border-b-2 border-gray-200 font-semibold">Afbeelding</th>
                            <th class="px-6 py-3 border-b-2 border-gray-200 font-semibold">Naam</th>
                            <th class="px-6 py-3 border-b-2 border-gray-200 font-semibold">Beschrijving</th>
                            <th class="px-6 py-3 border-b-2 border-gray-200 font-semibold text-center">Acties</th>
                        </tr>
                        <tr class="bg-gray-50 text-gray-600 text-left text-xs uppercase tracking-wider sm:hidden">
                            <th class="px-4 py-3 border-b-2 border-gray-200 font-semibold">Afbeelding</th>
                            <th class="px-4 py-3 border-b-2 border-gray-200 font-semibold">Details & Acties</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 text-sm">
                        <?php if (count($tassen) > 0): ?>
                            <?php foreach ($tassen as $tas): ?>
                                <tr class="hover:bg-gray-50 border-b border-gray-200 transition">
                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap w-20 sm:w-auto align-top sm:align-middle">
                                        <img class="h-16 w-16 sm:h-12 sm:w-12 rounded object-cover border border-gray-300"
                                             src="/<?= htmlspecialchars($tas['afbeelding']) ?>"
                                             alt="<?= htmlspecialchars($tas['naam']) ?>">
                                    </td>
                                    <td class="sm:hidden px-4 py-4 align-top">
                                        <div class="font-bold text-gray-900 mb-1 text-base"><?= htmlspecialchars($tas['naam']) ?></div>
                                        <div class="text-sm text-gray-600 mb-3"><?= nl2br(htmlspecialchars($tas['beschrijving'])) ?></div>
                                        <div class="flex gap-2">
                                            <a href="/admin/tassen/<?= $tas['id'] ?>/bewerken" class="bg-blue-500 hover:bg-blue-600 text-white py-1.5 px-3 rounded text-xs">Bewerk</a>
                                            <button onclick="openDeleteModal(<?= $tas['id'] ?>)" class="bg-red-500 hover:bg-red-600 text-white py-1.5 px-3 rounded text-xs">Verwijder</button>
                                        </div>
                                    </td>
                                    <td class="hidden sm:table-cell px-6 py-4 whitespace-nowrap font-medium text-gray-900"><?= htmlspecialchars($tas['naam']) ?></td>
                                    <td class="hidden sm:table-cell px-6 py-4 text-sm text-gray-600"><?= nl2br(htmlspecialchars($tas['beschrijving'])) ?></td>
                                    <td class="hidden sm:table-cell px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex justify-center gap-3">
                                            <a href="/admin/tassen/<?= $tas['id'] ?>/bewerken" class="cursor-pointer bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded text-sm font-medium transition">Bewerk</a>
                                            <button onclick="openDeleteModal(<?= $tas['id'] ?>)" class="cursor-pointer bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded text-sm font-medium transition">Verwijder</button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="px-6 py-8 text-center text-gray-500">Geen tassen gevonden.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="px-4 sm:px-6 py-4 bg-gray-50 border-t border-gray-200">
                <span class="text-xs sm:text-sm text-gray-600">Totaal: <?= count($tassen) ?> tassen</span>
            </div>
        </div>
    </div>

    <!-- DELETE MODAL -->
    <div id="deleteModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
        <div class="relative p-6 border w-96 shadow-xl rounded-lg bg-white">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Verwijderen bevestigen</h3>
                <p class="text-sm text-gray-500 mt-2">Weet je zeker dat je deze tas wilt verwijderen? Dit kan niet ongedaan worden gemaakt.</p>
                <div class="mt-6 flex justify-center gap-3">
                    <button onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-semibold rounded-md transition">Annuleren</button>
                    <a id="confirmDeleteBtn" href="#" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-md transition">Verwijderen</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openDeleteModal(id) {
            document.getElementById('confirmDeleteBtn').href = '/admin/tassen/' + id + '/verwijderen';
            document.getElementById('deleteModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        window.onclick = function(event) {
            const modal = document.getElementById('deleteModal');
            if (event.target == modal) closeDeleteModal();
        }
    </script>

</body>
</html>
