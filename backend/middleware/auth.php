<?php

declare(strict_types=1);

function require_auth(): void
{
    if (!is_logged_in()) {
        flash_set('error', 'Debes iniciar sesion para continuar.', 'danger');
        redirect('login');
    }
}
