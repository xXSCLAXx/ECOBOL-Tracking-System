<?php

declare(strict_types=1);

class AuthController
{
    public function __construct(private PDO $pdo)
    {
    }

    public function showLogin(): void
    {
        if (is_logged_in()) {
            redirect('dashboard');
        }

        $pageTitle = 'Inicio de sesion';
        require __DIR__ . '/../../frontend/pages/login.php';
    }

    public function login(): void
    {
        verify_csrf();
        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        set_old(['email' => $email]);

        if ($email === '' || $password === '') {
            flash_set('error', 'Completa email y contraseña.', 'danger');
            redirect('login');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash_set('error', 'El email no es valido.', 'danger');
            redirect('login');
        }

        $usuarioModel = new Usuario($this->pdo);
        $usuario = $usuarioModel->authenticate($email, $password);

        if (!$usuario) {
            flash_set('error', 'Credenciales incorrectas.', 'danger');
            redirect('login');
        }

        $_SESSION['auth_user'] = $usuario;
        clear_old();
        flash_set('success', 'Bienvenido al sistema ECOBOL.', 'success');
        redirect('dashboard');
    }

    public function logout(): void
    {
        verify_csrf();

        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION = [];

            if (ini_get('session.use_cookies')) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], (bool) $params['secure'], (bool) $params['httponly']);
            }

            session_destroy();
        }

        session_start();
        session_regenerate_id(true);
        flash_set('success', 'Sesion cerrada correctamente.', 'success');
        redirect('login');
    }
}
