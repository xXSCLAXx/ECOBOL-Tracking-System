<?php

declare(strict_types=1);

function generar_codigo_rastreo(): string
{
    return sprintf('ECO-%s-%s', date('Y'), strtoupper(bin2hex(random_bytes(2)) . substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 2)));
}
