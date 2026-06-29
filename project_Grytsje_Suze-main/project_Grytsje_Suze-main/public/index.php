<?php
define('ROOT_PATH', dirname(__DIR__));

require ROOT_PATH . '/vendor/autoload.php';

// Load .env
$envFile = ROOT_PATH . '/.env';
if (file_exists($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (str_starts_with(trim($line), '#') || !str_contains($line, '=')) continue;
        [$key, $value] = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($value);
    }
}

session_start();

use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\PortfolioController;
use App\Controllers\AboutController;
use App\Controllers\CollaborationsController;
use App\Controllers\CommissionsController;
use App\Controllers\ContactController;
use App\Controllers\NewsController;
use App\Controllers\AuthController;
use App\Controllers\AdminController;
use App\Controllers\AdminTassenController;
use App\Controllers\AdminGebruikersController;

$router = new Router();

// Public routes
$router->get('/',                [HomeController::class, 'index']);
$router->get('/portfolio',       [PortfolioController::class, 'index']);
$router->get('/portfolio/{id}',  [PortfolioController::class, 'show']);
$router->get('/about',           [AboutController::class, 'index']);
$router->get('/collaborations',  [CollaborationsController::class, 'index']);
$router->get('/commissions',     [CommissionsController::class, 'index']);
$router->get('/news',            [NewsController::class, 'index']);
$router->get('/contact',         [ContactController::class, 'index']);
$router->post('/contact',        [ContactController::class, 'send']);
$router->get('/contact/bedankt', [ContactController::class, 'bedankt']);

// Admin auth routes
$router->get('/admin/login',    [AuthController::class, 'loginForm']);
$router->post('/admin/login',   [AuthController::class, 'login']);
$router->get('/admin/uitloggen',[AuthController::class, 'logout']);

// Admin routes
$router->get('/admin',                              [AdminController::class, 'index']);
$router->get('/admin/tassen/toevoegen',             [AdminTassenController::class, 'create']);
$router->post('/admin/tassen/opslaan',              [AdminTassenController::class, 'store']);
$router->get('/admin/tassen/{id}/bewerken',         [AdminTassenController::class, 'edit']);
$router->post('/admin/tassen/{id}/opslaan',         [AdminTassenController::class, 'update']);
$router->get('/admin/tassen/{id}/verwijderen',      [AdminTassenController::class, 'delete']);
$router->get('/admin/gebruikers',                   [AdminGebruikersController::class, 'index']);
$router->post('/admin/gebruikers/aanmaken',         [AdminGebruikersController::class, 'store']);
$router->get('/admin/gebruikers/{id}/verwijderen',  [AdminGebruikersController::class, 'delete']);

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
