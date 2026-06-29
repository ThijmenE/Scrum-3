<?php
// Naamruimte en afhankelijkheden
namespace App\Controllers;

use App\Core\Controller;

// Controller voor de nieuwspagina
class NewsController extends Controller
{
    // index(): laadt de news-view zonder extra data
    public function index(): void
    {
        $this->render('news');
    }
}
