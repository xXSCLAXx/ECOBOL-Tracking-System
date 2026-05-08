<?php require __DIR__ . '/../components/head.php'; ?>
<div class="ecobol-layout">
    <?php require __DIR__ . '/../components/navbar.php'; ?>
    <div class="app-shell">
        <?php require __DIR__ . '/../components/sidebar.php'; ?>
        <main class="app-main">
            <?php require __DIR__ . '/../components/alerts.php'; ?>
            <div class="row g-4">
                <div class="col-lg-5">
                    <div class="form-card p-4">
                        <h3 class="fw-bold mb-3">Actualizar estado</h3>
                        <form method="GET" action="<?= e(base_url('frontend/index.php')) ?>" class="mb-4">
                            <input type="hidden" name="route" value="envios.estado">
                            <div class="mb-3">
                                <label class="form-label">Seleccionar envio</label>
                                <select name="id" class="form-select">
                                    <option value="">Elige un envio</option>
                                    <?php foreach ($envios as $item): ?>
                                        <option value="<?= (int) $item['id_envio'] ?>" <?= $envio && (int) $envio['id_envio'] === (int) $item['id_envio'] ? 'selected' : '' ?>><?= e($item['codigo_rastreo']) ?> - <?= e($item['remitente']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button class="btn btn-outline-primary w-100" type="submit">Cargar envio</button>
                        </form>

                        <?php if ($envio): ?>
                            <div class="p-3 rounded-4 bg-light border mb-3">
                                <div class="fw-semibold"><?= e($envio['codigo_rastreo']) ?></div>
                                <div class="small text-muted"><?= e($envio['remitente']) ?> -> <?= e($envio['destinatario']) ?></div>
                                <div class="mt-2"><span class="badge bg-info text-dark"><?= e($envio['estado_nombre']) ?></span></div>
                            </div>
                            <form method="POST" action="<?= e(base_url('frontend/index.php?route=envios.estado')) ?>" class="needs-validation" novalidate>
                                <?= csrf_field() ?>
                                <input type="hidden" name="id_envio" value="<?= (int) $envio['id_envio'] ?>">
                                <div class="mb-3">
                                    <label class="form-label">Nuevo estado</label>
                                    <select name="id_estado" class="form-select" required>
                                        <?php foreach ($estados as $estado): ?>
                                            <option value="<?= (int) $estado['id_estado'] ?>"><?= e($estado['nombre']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Observacion</label>
                                    <textarea name="observacion" class="form-control" rows="3" placeholder="Detalle de la actualizacion"></textarea>
                                </div>
                                <button class="btn btn-primary w-100" type="submit">Guardar cambio</button>
                            </form>
                        <?php else: ?>
                            <div class="alert alert-info mb-0">Selecciona un envio para ver su detalle y actualizar su estado.</div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="table-card p-4">
                        <h4 class="fw-bold mb-3">Historial de cambios</h4>
                        <?php if (!empty($historial)): ?>
                            <div class="table-responsive">
                                <table class="table table-sm align-middle">
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
                                                <td><?= e($item['estado_nombre']) ?></td>
                                                <td><?= e($item['observacion'] ?? '-') ?></td>
                                                <td><?= e($item['usuario_nombre']) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-light border mb-0">Aun no hay historial para este envio.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<?php require __DIR__ . '/../components/footer.php'; ?>
