<?php require __DIR__ . '/../components/head.php'; ?>
<div class="ecobol-layout">
    <?php require __DIR__ . '/../components/navbar.php'; ?>
    <div class="app-shell">
        <?php require __DIR__ . '/../components/sidebar.php'; ?>
        <main class="app-main">
            <?php require __DIR__ . '/../components/alerts.php'; ?>
            <div class="form-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <p class="text-uppercase text-muted small mb-1">Modulo de envios</p>
                        <h3 class="fw-bold mb-0">Registrar envio postal</h3>
                    </div>
                    <a class="btn btn-outline-secondary" href="<?= e(base_url('frontend/index.php?route=envios.index')) ?>">Ver listado</a>
                </div>
                <form method="POST" action="<?= e(base_url('frontend/index.php?route=envios.store')) ?>" class="needs-validation" novalidate>
                    <?= csrf_field() ?>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Remitente</label>
                            <input type="text" name="remitente" class="form-control" value="<?= e(old('remitente')) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Destinatario</label>
                            <input type="text" name="destinatario" class="form-control" value="<?= e(old('destinatario')) ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Peso (kg)</label>
                            <input type="number" step="0.01" min="0.01" name="peso" class="form-control" value="<?= e(old('peso')) ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tipo de servicio</label>
                            <input type="text" name="tipo_servicio" class="form-control" value="<?= e(old('tipo_servicio')) ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Estado inicial</label>
                            <select name="id_estado" class="form-select" required>
                                <?php foreach ($estados as $estado): ?>
                                    <option value="<?= (int) $estado['id_estado'] ?>" <?= old('id_estado') == $estado['id_estado'] || $estado['nombre'] === 'Registrado' ? 'selected' : '' ?>>
                                        <?= e($estado['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Sucursal origen</label>
                            <select name="id_sucursal_origen" class="form-select" required>
                                <option value="">Selecciona una sucursal</option>
                                <?php foreach ($sucursales as $sucursal): ?>
                                    <option value="<?= (int) $sucursal['id_sucursal'] ?>" <?= old('id_sucursal_origen') == $sucursal['id_sucursal'] ? 'selected' : '' ?>><?= e($sucursal['nombre']) ?> - <?= e($sucursal['ciudad']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Sucursal destino</label>
                            <select name="id_sucursal_destino" class="form-select" required>
                                <option value="">Selecciona una sucursal</option>
                                <?php foreach ($sucursales as $sucursal): ?>
                                    <option value="<?= (int) $sucursal['id_sucursal'] ?>" <?= old('id_sucursal_destino') == $sucursal['id_sucursal'] ? 'selected' : '' ?>><?= e($sucursal['nombre']) ?> - <?= e($sucursal['ciudad']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="mt-4 d-flex gap-2">
                        <button class="btn btn-primary" type="submit">Guardar envio</button>
                        <a class="btn btn-light" href="<?= e(base_url('frontend/index.php?route=dashboard')) ?>">Cancelar</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>
<?php require __DIR__ . '/../components/footer.php'; ?>
