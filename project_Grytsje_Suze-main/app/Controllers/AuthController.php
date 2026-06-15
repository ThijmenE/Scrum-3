<?php
// Naamruimte en afhankelijkheden
namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

// Controller voor authenticatie: inloggen en uitloggen van admin-gebruikers
class AuthController extends Controller
{
    // loginForm(): toont het inlogformulier, stuurt door als de gebruiker al is ingelogd
    public function loginForm(): void
    {
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/admin');
        }
        $this->render('admin/login', ['error' => '']);
    }

    // login(): controleert inloggegevens en start een sessie bij een correcte combinatie
    public function login(): void
    {
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/admin');
        }

        $user = User::findByCredentials($_POST['email'] ?? '', $_POST['password'] ?? '');

        // Sla gebruikersgegevens op in de sessie bij een succesvolle inlog
        if ($user) {
            $_SESSION['user_id']    = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_rol']   = $user['rol'];
            $this->redirect('/admin');
        }

        $this->render('admin/login', ['error' => 'Onjuist e-mailadres of wachtwoord.']);
    }

    // logout(): vernietigt de sessie en stuurt door naar de inlogpagina
    public function logout(): void
    {
        session_destroy();
        $this->redirect('/admin/login');
    }
}
