<?php

declare(strict_types=1);

function base_url(string $path = ''): string
{
    $base = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
    $base = $base === '/' ? '' : $base;
    $path = ltrim($path, '/');
    if (str_starts_with($path, 'frontend/')) {
        $path = substr($path, 9);
    }
    return $base . '/' . $path;
}

function asset(string $path): string
{
    return base_url('frontend/assets/' . ltrim($path, '/'));
}

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function redirect(string $route): void
{
    header('Location: ' . base_url('frontend/index.php?route=' . $route));
    exit;
}

function flash_set(string $key, string $message, string $type = 'success'): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    $_SESSION['flash'][$key] = ['message' => $message, 'type' => $type];
}

function flash_get(string $key): ?array
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    if (!isset($_SESSION['flash'][$key])) {
        return null;
    }
    $flash = $_SESSION['flash'][$key];
    unset($_SESSION['flash'][$key]);
    return $flash;
}

function flash_all(): array
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    $all = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return is_array($all) ? $all : [];
}

function old(string $key, string $default = ''): string
{
    return $_SESSION['old'][$key] ?? $default;
}

function set_old(array $data): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    $_SESSION['old'] = $data;
}

function clear_old(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    unset($_SESSION['old']);
}

function csrf_token(): string
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function verify_csrf(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $token = $_POST['csrf_token'] ?? '';
        if (!hash_equals($_SESSION['csrf_token'] ?? '', (string) $token)) {
            render_error_page(419, 'Sesion expirada', 'Token CSRF invalido. Recarga la pagina e intenta nuevamente.');
        }
    }
}

function app_debug(): bool
{
    $value = getenv('APP_DEBUG');
    if ($value === false) {
        return true;
    }
    return in_array(strtolower((string) $value), ['1', 'true', 'on', 'yes'], true);
}

function render_error_page(int $statusCode, string $title, string $message, ?string $details = null): void
{
    http_response_code($statusCode);
    $safeTitle = e($title);
    $safeMessage = e($message);
    $safeDetails = $details !== null ? e($details) : '';

    echo '<!doctype html><html lang="es"><head><meta charset="utf-8">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
    echo '<title>' . $safeTitle . ' - ECOBOL</title>';
    echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">';
    echo '<style>body{background:linear-gradient(180deg,#f7f9fd 0%,#eef4ff 100%);} .error-wrap{min-height:100vh;display:grid;place-items:center;padding:1rem;}</style>';
    echo '</head><body><div class="error-wrap"><div class="card border-0 shadow-lg" style="max-width:760px;width:100%;">';
    echo '<div class="card-body p-4 p-md-5">';
    echo '<span class="badge text-bg-danger mb-3">Error HTTP ' . $statusCode . '</span>';
    echo '<h1 class="h3 fw-bold mb-3">' . $safeTitle . '</h1>';
    echo '<p class="text-secondary mb-4">' . $safeMessage . '</p>';
    if ($safeDetails !== '' && app_debug()) {
        echo '<div class="alert alert-warning"><strong>Detalle tecnico:</strong><br>' . nl2br($safeDetails) . '</div>';
    }
    echo '<div class="d-flex gap-2">';
    echo '<a class="btn btn-primary" href="' . e(base_url('frontend/index.php')) . '">Volver al sistema</a>';
    echo '<a class="btn btn-outline-secondary" href="' . e(base_url('frontend/index.php?route=rastreo.public')) . '">Rastreo publico</a>';
    echo '</div></div></div></div></body></html>';
    exit;
}

function auth_user(): ?array
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    return $_SESSION['auth_user'] ?? null;
}

function is_logged_in(): bool
{
    return auth_user() !== null;
}

function has_role(array|string $roles): bool
{
    $user = auth_user();
    if (!$user) {
        return false;
    }
    $roles = (array) $roles;
    return in_array($user['rol'], $roles, true);
}

function format_date(?string $value): string
{
    if (!$value) {
        return '-';
    }
    return date('d/m/Y H:i', strtotime($value));
}

function money_like(float|string $value): string
{
    return number_format((float) $value, 2, ',', '.');
}
