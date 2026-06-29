<?php
// Naamruimte en afhankelijkheden
namespace App\Controllers;

use App\Core\Controller;

// Controller voor de homepage
class HomeController extends Controller
{
    // index(): leest de afbeeldingenmap uit, filtert op extensie en geeft een willekeurige volgorde door aan de view
    public function index(): void
    {
        $imgDir = ROOT_PATH . '/public/images/';

        // Logo- en huisstijlbestanden die niet in de carrousel mogen verschijnen
        $excluded = ['groot logo wit.png', 'GS Grytsje Suze Logo goed-01.png', 'GS Grytsje Suze Logo goed-02.png', 'GSLOGOWIT'];
        $extensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $images = [];

        // Doorloop de map en voeg ondersteunde afbeeldingen toe aan de lijst
        foreach (scandir($imgDir) as $file) {
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (in_array($ext, $extensions) && !in_array($file, $excluded)) {
                $images[] = $file;
            }
        }

        // Schud de volgorde willekeurig voor afwisseling in de carrousel
        shuffle($images);

        $this->render('home', ['images' => $images]);
    }
}
