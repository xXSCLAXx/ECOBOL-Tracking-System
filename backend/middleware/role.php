<?php

declare(strict_types=1);

function require_role(array|string $roles): void
{
    require_auth();
    if (!has_role($roles)) {
        render_error_page(403, 'Acceso denegado', 'No tienes permisos para acceder a este recurso.');
    }
}
