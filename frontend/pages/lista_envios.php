<?php require __DIR__ . '/../components/head.php'; ?>
<div class="ecobol-layout">
    <?php require __DIR__ . '/../components/navbar.php'; ?>
    <div class="app-shell">
        <?php require __DIR__ . '/../components/sidebar.php'; ?>
        <main class="app-main">
            <?php require __DIR__ . '/../components/alerts.php'; ?>
            <div class="table-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <p class="text-uppercase text-muted small mb-1">Gestion operativa</p>
                        <h3 class="fw-bold mb-0">Lista de envios</h3>
                    </div>
                    <a class="btn btn-primary" href="<?= e(base_url('frontend/index.php?route=envios.create')) ?>">Registrar envio</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Codigo</th>
                                <th>Remitente</th>
                                <th>Destinatario</th>
                                <th>Origen</th>
                                <th>Destino</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($envios as $envio): ?>
                                <tr>
                                    <td class="fw-semibold"><?= e($envio['codigo_rastreo']) ?></td>
                                    <td><?= e($envio['remitente']) ?></td>
                                    <td><?= e($envio['destinatario']) ?></td>
                                    <td><?= e($envio['origen_nombre']) ?></td>
                                    <td><?= e($envio['destino_nombre']) ?></td>
                                    <td><span class="badge bg-info text-dark"><?= e($envio['estado_nombre']) ?></span></td>
                                    <td><?= e(format_date($envio['fecha_registro'])) ?></td>
                                    <td>
                                        <a href="<?= e(base_url('frontend/index.php?route=envios.estado&id=' . $envio['id_envio'])) ?>" class="btn btn-sm btn-outline-primary">Actualizar</a>
                                        <a href="<?= e(base_url('frontend/index.php?route=rastreo.public&codigo=' . urlencode($envio['codigo_rastreo']))) ?>" class="btn btn-sm btn-outline-secondary">Ver</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>
<?php require __DIR__ . '/../components/footer.php'; ?>
