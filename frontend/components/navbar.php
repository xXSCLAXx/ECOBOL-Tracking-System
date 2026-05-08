<?php $user = auth_user(); ?>
<nav class="navbar navbar-expand-lg navbar-dark ecobol-navbar px-3 py-3">
    <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="<?= e(base_url('frontend/index.php?route=dashboard')) ?>">
        <span class="brand-mark"><i class="bi bi-truck"></i></span>
        <span>ECOBOL Tracking</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topbarNav">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="topbarNav">
        <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
            <li class="nav-item"><a class="nav-link" href="<?= e(base_url('frontend/index.php?route=dashboard')) ?>">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= e(base_url('frontend/index.php?route=envios.index')) ?>">Envios</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= e(base_url('frontend/index.php?route=rastreo.public')) ?>">Rastreo publico</a></li>
            <?php if ($user && $user['rol'] === 'administrador'): ?>
                <li class="nav-item"><a class="nav-link" href="<?= e(base_url('frontend/index.php?route=reportes.index')) ?>">Reportes</a></li>
            <?php endif; ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                    <?= e($user['nombre'] ?? 'Usuario') ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><span class="dropdown-item-text text-muted small"><?= e($user['rol'] ?? '') ?></span></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="<?= e(base_url('frontend/index.php?route=logout')) ?>" class="px-3 py-1">
                            <?= csrf_field() ?>
                            <button type="submit" class="dropdown-item px-0 text-danger">
                                Cerrar sesion
                            </button>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
