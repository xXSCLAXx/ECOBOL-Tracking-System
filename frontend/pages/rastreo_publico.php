<?php $bodyClass = 'login-page'; ?>
<?php require __DIR__ . '/../components/head.php'; ?>
<div class="login-shell">
    <div class="login-card p-4 p-lg-5">
        <div class="row g-4 align-items-center">
            <div class="col-lg-5">
                <div class="hero-panel h-100">
                    <p class="text-uppercase text-white-50 small">Consulta publica</p>
                    <h1 class="fw-bold mb-3">Rastrea tu envio ECOBOL</h1>
                    <p class="mb-0 text-white-75">Ingresa el codigo de rastreo para ver el estado actual y el historial completo de movimientos.</p>
                </div>
            </div>
            <div class="col-lg-7">
                <?php require __DIR__ . '/../components/alerts.php'; ?>
                <form method="GET" action="<?= e(base_url('frontend/index.php')) ?>" class="mb-4">
                    <input type="hidden" name="route" value="rastreo.public">
                    <label class="form-label fw-semibold">Codigo de rastreo</label>
                    <div class="input-group input-group-lg">
                        <input type="text" name="codigo" class="form-control" placeholder="ECO-2026-AB1234" value="<?= e($codigo) ?>" required>
                        <button class="btn btn-primary" type="submit">Buscar</button>
                    </div>
                </form>

                <?php if ($envio): ?>
                    <div class="page-card p-4 mb-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="text-muted small">Codigo</div>
                                <div class="fw-bold fs-5"><?= e($envio['codigo_rastreo']) ?></div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small">Estado actual</div>
                                <div class="fw-bold fs-5 text-primary"><?= e($envio['estado_nombre']) ?></div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small">Remitente</div>
                                <div><?= e($envio['remitente']) ?></div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small">Destinatario</div>
                                <div><?= e($envio['destinatario']) ?></div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small">Origen</div>
                                <div><?= e($envio['origen_nombre']) ?></div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small">Destino</div>
                                <div><?= e($envio['destino_nombre']) ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="table-card p-4">
                        <h5 class="fw-bold mb-3">Historial completo</h5>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                        <th>Observacion</th>
                                        <th>Usuario</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($historial as $item): ?>
                                        <tr>
                                            <td><?= e(format_date($item['fecha_hora'])) ?></td>
                                            <td><span class="badge bg-info text-dark"><?= e($item['estado_nombre']) ?></span></td>
                                            <td><?= e($item['observacion'] ?? '-') ?></td>
                                            <td><?= e($item['usuario_nombre']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-light border">Ingresa un codigo valido para consultar el estado del envio.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php require __DIR__ . '/../components/footer.php'; ?>
