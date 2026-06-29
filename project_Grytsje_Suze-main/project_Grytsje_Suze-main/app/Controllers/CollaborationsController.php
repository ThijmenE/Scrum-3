<?php
// Naamruimte en afhankelijkheden
namespace App\Controllers;

use App\Core\Controller;

// Controller voor de Collaborations-pagina
class CollaborationsController extends Controller
{
    // index(): definieert de lijst met mogelijkheden en geeft deze door aan de view
    public function index(): void
    {
        // Lijst van aangeboden diensten voor samenwerkingen
        $capabilities = [
            'Prop and costume design for film, theatre and performance',
            'Custom editorial and campaign pieces',
            'Brand activations and experiential installations',
            'Styling for commercial productions and creative shoots',
            'Workshop facilitation and brand experiences',
        ];

        $this->render('collaborations', ['capabilities' => $capabilities]);
    }
}
