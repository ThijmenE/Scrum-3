<?php
// Naamruimte en afhankelijkheden
namespace App\Controllers;

use App\Core\Controller;

// Controller voor de About-pagina
class AboutController extends Controller
{
    // index(): laadt de about-view zonder extra data
    public function index(): void
    {
        $this->render('about');
    }
}
