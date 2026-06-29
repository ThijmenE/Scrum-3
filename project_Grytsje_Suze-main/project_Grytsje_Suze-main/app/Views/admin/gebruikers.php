<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Beheer</title>
    <link rel="stylesheet" href="/css/output.css">
</head>

<body class="bg-gray-100 font-sans">

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

    <div class="container mx-auto mt-10 px-4 max-w-4xl">

        <div class="bg-white shadow-md rounded-lg p-6 mb-10">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Nieuwe Admin Aanmaken</h2>
            <form action="/admin/gebruikers/aanmaken" method="POST" class="flex flex-col md:flex-row gap-4">
                <input type="email" name="email" placeholder="E-mailadres" required
                    class="flex-1 border border-gray-300 rounded-md p-2 focus:ring-blue-500">
                <input type="password" name="wachtwoord" placeholder="Wachtwoord" required
                    class="flex-1 border border-gray-300 rounded-md p-2 focus:ring-blue-500">
                <button type="submit" name="add_admin"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded transition">Toevoegen</button>
            </form>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-left text-sm uppercase font-semibold">
                        <th class="px-6 py-3 border-b border-gray-200">Email</th>
                        <th class="px-6 py-3 border-b border-gray-200">Rol</th>
                        <th class="px-6 py-3 border-b border-gray-200 text-right">Acties</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    <?php foreach ($users as $u): ?>
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="px-6 py-4"><?= htmlspecialchars($u['email']) ?></td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded text-xs font-bold <?= $u['rol'] === 'owner' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' ?>">
                                    <?= strtoupper($u['rol']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <?php if ($u['rol'] !== 'owner'): ?>
                                    <button onclick="openDeleteUserModal(<?= $u['id'] ?>)"
                                        class="text-red-600 hover:text-red-900 font-semibold text-sm">Verwijderen</button>
                                <?php else: ?>
                                    <span class="text-gray-400 text-xs italic">Beschermd</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="deleteUserModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-xl w-96">
            <h3 class="text-lg font-bold mb-4">Admin verwijderen?</h3>
            <p class="text-sm text-gray-500 mb-6">Deze admin verliest direct toegang tot het panel.</p>
            <div class="flex justify-end gap-3">
                <button onclick="closeDeleteUserModal()" class="px-4 py-2 bg-gray-200 rounded">Annuleren</button>
                <a id="confirmDeleteUserBtn" href="#" class="px-4 py-2 bg-red-600 text-white rounded">Verwijderen</a>
            </div>
        </div>
    </div>

    <script>
        function openDeleteUserModal(id) {
            document.getElementById('confirmDeleteUserBtn').href = '/admin/gebruikers/' + id + '/verwijderen';
            document.getElementById('deleteUserModal').classList.remove('hidden');
        }
        function closeDeleteUserModal() {
            document.getElementById('deleteUserModal').classList.add('hidden');
        }
    </script>
</body>

</html>
