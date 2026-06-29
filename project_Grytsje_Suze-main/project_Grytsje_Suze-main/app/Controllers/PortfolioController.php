<?php
// Naamruimte en afhankelijkheden
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Tas;

// Controller voor de portfoliopagina en de detailweergave van een individuele tas
class PortfolioController extends Controller
{
    // index(): haalt alle tassen op en geeft ze door aan de portfolioweergave
    public function index(): void
    {
        $tassen = Tas::getAll();
        $this->render('portfolio', ['tassen' => $tassen]);
    }

    // show(): laadt een specifieke tas op basis van het ID, bouwt de galerij op en berekent thema-kleuren
    public function show(int $id): void
    {
        $tas = Tas::getById($id);

        // Geef een 404-fout als de tas niet bestaat
        if (!$tas) {
            http_response_code(404);
            echo '<h1>404 - Tas niet gevonden</h1>';
            return;
        }

        // Bouw de galerij op uit de uploads-map
        $imgDir = ROOT_PATH . '/public/uploads/';
        $extensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $galerij = [];

        if (is_dir($imgDir)) {
            foreach (scandir($imgDir) as $file) {
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                if (in_array($ext, $extensions)) {
                    $galerij[] = 'uploads/' . $file;
                }
            }
        }

        // Bereken de achtergrondkleur en bepaal via de YIQ-formule of tekst licht of donker moet zijn
        $bgColor = $tas['kleurcode'] ?? '#ffffff';
        $hex = ltrim($bgColor, '#');
        $r   = hexdec(substr($hex, 0, 2));
        $g   = hexdec(substr($hex, 2, 2));
        $b   = hexdec(substr($hex, 4, 2));
        $yiq = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;
        $isDark     = $yiq < 128;
        $overlayRgb = "$r, $g, $b";

        // Geef alle berekende data door aan de portfolio-show-view
        $this->render('portfolio-show', [
            'tas'        => $tas,
            'galerij'    => $galerij,
            'bgColor'    => $bgColor,
            'isDark'     => $isDark,
            'overlayRgb' => $overlayRgb,
        ]);
    }
}
