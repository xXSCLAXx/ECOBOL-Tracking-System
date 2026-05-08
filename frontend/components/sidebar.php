<aside class="ecobol-sidebar">
    <div class="sidebar-section">
        <div class="sidebar-label">Menu principal</div>
        <a href="<?= e(base_url('frontend/index.php?route=dashboard')) ?>" class="sidebar-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="<?= e(base_url('frontend/index.php?route=envios.create')) ?>" class="sidebar-link"><i class="bi bi-box-seam"></i> Registrar envio</a>
        <a href="<?= e(base_url('frontend/index.php?route=envios.index')) ?>" class="sidebar-link"><i class="bi bi-list-ul"></i> Lista de envios</a>
        <a href="<?= e(base_url('frontend/index.php?route=envios.estado')) ?>" class="sidebar-link"><i class="bi bi-arrow-repeat"></i> Actualizar estado</a>
        <a href="<?= e(base_url('frontend/index.php?route=reportes.index')) ?>" class="sidebar-link"><i class="bi bi-file-earmark-bar-graph"></i> Reportes</a>
    </div>
    <?php if (($user = auth_user()) && $user['rol'] === 'administrador'): ?>
        <div class="sidebar-section mt-4">
            <div class="sidebar-label">Administracion</div>
            <a href="<?= e(base_url('frontend/index.php?route=usuarios.index')) ?>" class="sidebar-link"><i class="bi bi-people"></i> Usuarios</a>
            <a href="<?= e(base_url('frontend/index.php?route=sucursales.index')) ?>" class="sidebar-link"><i class="bi bi-building"></i> Sucursales</a>
        </div>
    <?php endif; ?>
</aside>
