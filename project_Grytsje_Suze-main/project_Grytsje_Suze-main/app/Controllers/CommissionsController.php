<?php
// Naamruimte en afhankelijkheden
namespace App\Controllers;

use App\Core\Controller;

// Controller voor de Commissions-pagina
class CommissionsController extends Controller
{
    // index(): laadt de commissions-view zonder extra data
    public function index(): void
    {
        $this->render('commissions');
    }
}
