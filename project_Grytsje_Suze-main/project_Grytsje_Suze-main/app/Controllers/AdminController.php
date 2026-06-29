<?php
// Naamruimte en afhankelijkheden
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Tas;

// Controller voor het admin-dashboard (overzicht van tassen)
class AdminController extends Controller
{
    // index(): vereist authenticatie, haalt alle tassen op en toont het admin-overzicht
    public function index(): void
    {
        $this->requireAuth();
        $tassen = Tas::getAll();
        $this->render('admin/tassen', ['tassen' => $tassen]);
    }
}
