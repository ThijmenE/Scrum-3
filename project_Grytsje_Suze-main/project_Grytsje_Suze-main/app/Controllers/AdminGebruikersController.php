<?php
// Naamruimte en afhankelijkheden
namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

// Controller voor het beheren van admin-gebruikers
class AdminGebruikersController extends Controller
{
    // index(): haalt alle gebruikers op en toont het gebruikersoverzicht
    public function index(): void
    {
        $this->requireAuth();
        $users = User::getAll();
        $this->render('admin/gebruikers', ['users' => $users]);
    }

    // store(): maakt een nieuwe gebruiker aan op basis van POST-gegevens
    public function store(): void
    {
        $this->requireAuth();
        User::create($_POST['email'] ?? '', $_POST['wachtwoord'] ?? '');
        $this->redirect('/admin/gebruikers');
    }

    // delete(): verwijdert een gebruiker op basis van het opgegeven ID
    public function delete(int $id): void
    {
        $this->requireAuth();
        User::delete($id);
        $this->redirect('/admin/gebruikers');
    }
}
