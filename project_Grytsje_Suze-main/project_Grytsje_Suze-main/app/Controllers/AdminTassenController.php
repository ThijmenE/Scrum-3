<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Tas;

class AdminTassenController extends Controller
{
    private function validateTasForm(bool $requireImage = false): array
    {
        $errors = [];

        $naam = filter_input(INPUT_POST, 'naam', FILTER_DEFAULT);
        if (empty(trim($naam ?? ''))) {
            $errors['naam'] = 'Naam is verplicht.';
        } elseif (strlen(trim($naam)) > 100) {
            $errors['naam'] = 'Naam mag maximaal 100 tekens zijn.';
        }

        $beschrijving = filter_input(INPUT_POST, 'beschrijving', FILTER_DEFAULT);
        if (empty(trim($beschrijving ?? ''))) {
            $errors['beschrijving'] = 'Beschrijving is verplicht.';
        }

        $kleurcode = filter_input(INPUT_POST, 'kleurcode', FILTER_VALIDATE_REGEXP, [
            'options' => ['regexp' => '/^#[0-9a-fA-F]{6}$/'],
        ]);
        if (!$kleurcode) {
            $errors['kleurcode'] = 'Ongeldige kleurcode (gebruik #rrggbb).';
        }

        if ($requireImage && empty($_FILES['afbeelding']['tmp_name'])) {
            $errors['afbeelding'] = 'Een hoofdafbeelding is verplicht.';
        }

        return $errors;
    }

    private function uploadMedia(): array
    {
        $media     = [];
        $videoExts = ['mp4', 'webm', 'mov', 'ogg'];

        if (empty($_FILES['media']['name'][0])) {
            return $media;
        }

        foreach ($_FILES['media']['name'] as $i => $name) {
            if ($_FILES['media']['error'][$i] !== UPLOAD_ERR_OK || empty($name)) {
                continue;
            }
            $ext      = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            $type     = in_array($ext, $videoExts) ? 'video' : 'image';
            $fileName = time() . '_' . $i . '_' . basename($name);
            move_uploaded_file(
                $_FILES['media']['tmp_name'][$i],
                ROOT_PATH . '/public/uploads/' . $fileName
            );
            $media[] = ['type' => $type, 'path' => 'uploads/' . $fileName];
        }

        return $media;
    }

    public function create(): void
    {
        $this->requireAuth();
        $this->render('admin/tassen-toevoegen');
    }

    public function store(): void
    {
        $this->requireAuth();

        $errors = $this->validateTasForm(requireImage: true);
        if ($errors) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old']    = $_POST;
            $this->redirect('/admin/tassen/toevoegen');
            return;
        }

        $fotoNaam = time() . '_' . basename($_FILES['afbeelding']['name']);
        move_uploaded_file($_FILES['afbeelding']['tmp_name'], ROOT_PATH . '/public/uploads/' . $fotoNaam);

        $modelDB = '';
        if (!empty($_FILES['model_3d']['tmp_name']) && $_FILES['model_3d']['error'] === 0) {
            $modelNaam = time() . '_' . basename($_FILES['model_3d']['name']);
            move_uploaded_file($_FILES['model_3d']['tmp_name'], ROOT_PATH . '/public/uploads/' . $modelNaam);
            $modelDB = 'uploads/' . $modelNaam;
        }

        Tas::create(
            null,
            trim(filter_input(INPUT_POST, 'naam', FILTER_DEFAULT)),
            trim(filter_input(INPUT_POST, 'beschrijving', FILTER_DEFAULT)),
            'uploads/' . $fotoNaam,
            filter_input(INPUT_POST, 'kleurcode', FILTER_DEFAULT),
            $modelDB,
            $_POST['tekstkleur'] ?? '#000000',
            $_POST['titelkleur'] ?? '#000000',
            $this->uploadMedia()
        );

        $this->redirect('/admin');
    }

    public function edit(int $id): void
    {
        $this->requireAuth();
        $tas = Tas::getById($id);
        if (!$tas) {
            $this->redirect('/admin');
            return;
        }
        $this->render('admin/tassen-edit', ['tas' => $tas]);
    }

    public function update(int $id): void
    {
        $this->requireAuth();
        $tas = Tas::getById($id);
        if (!$tas) {
            $this->redirect('/admin');
            return;
        }

        $errors = $this->validateTasForm(requireImage: false);
        if ($errors) {
            $_SESSION['errors'] = $errors;
            $this->redirect('/admin/tassen/' . $id . '/bewerken');
            return;
        }

        $fotoPath  = $tas['afbeelding'];
        $modelPath = $tas['model_3d'];

        if (!empty($_FILES['afbeelding']['tmp_name']) && $_FILES['afbeelding']['error'] === 0) {
            $n = time() . '_' . basename($_FILES['afbeelding']['name']);
            move_uploaded_file($_FILES['afbeelding']['tmp_name'], ROOT_PATH . '/public/uploads/' . $n);
            $fotoPath = 'uploads/' . $n;
        }

        if (!empty($_FILES['model_3d']['tmp_name']) && $_FILES['model_3d']['error'] === 0) {
            $m = time() . '_' . basename($_FILES['model_3d']['name']);
            move_uploaded_file($_FILES['model_3d']['tmp_name'], ROOT_PATH . '/public/uploads/' . $m);
            $modelPath = 'uploads/' . $m;
        }

        $toDelete = $_POST['delete_media'] ?? [];
        $existing = array_values(array_filter(
            $tas['media'],
            fn($m) => !in_array($m['path'], $toDelete)
        ));
        foreach ($toDelete as $path) {
            $full = ROOT_PATH . '/public/' . $path;
            if (file_exists($full)) {
                unlink($full);
            }
        }

        Tas::update(
            null,
            $id,
            trim(filter_input(INPUT_POST, 'naam', FILTER_DEFAULT)),
            trim(filter_input(INPUT_POST, 'beschrijving', FILTER_DEFAULT)),
            $fotoPath,
            filter_input(INPUT_POST, 'kleurcode', FILTER_DEFAULT),
            $modelPath,
            $_POST['tekst_kleur'] ?? '#000000',
            $_POST['titel_kleur'] ?? '#000000',
            array_merge($existing, $this->uploadMedia())
        );

        $this->redirect('/admin');
    }

    public function delete(int $id): void
    {
        $this->requireAuth();
        $tas = Tas::getById($id);

        $afbeelding = Tas::delete($id);
        if ($afbeelding !== null) {
            $imagePath = ROOT_PATH . '/public/' . $afbeelding;
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        if ($tas) {
            foreach ($tas['media'] as $m) {
                $path = ROOT_PATH . '/public/' . $m['path'];
                if (file_exists($path)) {
                    unlink($path);
                }
            }
        }

        $this->redirect('/admin');
    }
}
