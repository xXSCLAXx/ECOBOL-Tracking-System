<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/../backend/config/database.php';
require_once __DIR__ . '/../backend/utils/helpers.php';
require_once __DIR__ . '/../backend/utils/generar_codigo.php';
require_once __DIR__ . '/../backend/middleware/auth.php';
require_once __DIR__ . '/../backend/middleware/role.php';
require_once __DIR__ . '/../backend/models/Usuario.php';
require_once __DIR__ . '/../backend/models/Sucursal.php';
require_once __DIR__ . '/../backend/models/Estado.php';
require_once __DIR__ . '/../backend/models/Envio.php';
require_once __DIR__ . '/../backend/models/HistorialEstado.php';
require_once __DIR__ . '/../backend/controllers/AuthController.php';
require_once __DIR__ . '/../backend/controllers/DashboardController.php';
require_once __DIR__ . '/../backend/controllers/EnvioController.php';
require_once __DIR__ . '/../backend/controllers/EstadoController.php';
require_once __DIR__ . '/../backend/controllers/ReporteController.php';
require_once __DIR__ . '/../backend/controllers/UsuarioController.php';
require_once __DIR__ . '/../backend/controllers/SucursalController.php';

try {
    $pdo = (new Database())->connect();
    $route = (string) ($_GET['route'] ?? '');

    $authController = new AuthController($pdo);
    $dashboardController = new DashboardController($pdo);
    $envioController = new EnvioController($pdo);
    $estadoController = new EstadoController($pdo);
    $reporteController = new ReporteController($pdo);
    $usuarioController = new UsuarioController($pdo);
    $sucursalController = new SucursalController($pdo);

    if ($route === '') {
        $route = is_logged_in() ? 'dashboard' : 'login';
    }

    switch ($route) {
        case 'login':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $authController->login();
            }
            $authController->showLogin();
            break;

        case 'logout':
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                redirect('login');
            }
            $authController->logout();
            break;

        case 'dashboard':
            $dashboardController->index();
            break;

        case 'envios.index':
            $envioController->index();
            break;

        case 'envios.create':
            $envioController->create();
            break;

        case 'envios.store':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $envioController->store();
                break;
            }
            redirect('envios.create');
            break;

        case 'envios.estado':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $envioController->updateEstado();
                break;
            }
            $envioController->editEstado();
            break;

        case 'rastreo.public':
            $envioController->rastreoPublico();
            break;

        case 'reportes.index':
            $reporteController->index();
            break;

        case 'reportes.csv':
            $reporteController->exportCsv();
            break;

        case 'reportes.pdf':
            $reporteController->exportPdf();
            break;

        case 'usuarios.index':
            $usuarioController->index();
            break;

        case 'usuarios.store':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $usuarioController->store();
                break;
            }
            redirect('usuarios.index');
            break;

        case 'usuarios.update':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $usuarioController->update();
                break;
            }
            redirect('usuarios.index');
            break;

        case 'usuarios.delete':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $usuarioController->delete();
                break;
            }
            redirect('usuarios.index');
            break;

        case 'sucursales.index':
            $sucursalController->index();
            break;

        case 'sucursales.store':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $sucursalController->store();
                break;
            }
            redirect('sucursales.index');
            break;

        case 'sucursales.update':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $sucursalController->update();
                break;
            }
            redirect('sucursales.index');
            break;

        case 'sucursales.delete':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $sucursalController->delete();
                break;
            }
            redirect('sucursales.index');
            break;

        default:
            render_error_page(404, 'Ruta no encontrada', 'La ruta solicitada no existe en el sistema.');
    }
} catch (Throwable $exception) {
    render_error_page(500, 'Error interno del sistema', 'Ocurrio un error inesperado al procesar la solicitud.', $exception->getMessage());
}
