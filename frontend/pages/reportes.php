<?php require __DIR__ . '/../components/head.php'; ?>
<div class="ecobol-layout">
    <?php require __DIR__ . '/../components/navbar.php'; ?>
    <div class="app-shell">
        <?php require __DIR__ . '/../components/sidebar.php'; ?>
        <main class="app-main">
            <?php require __DIR__ . '/../components/alerts.php'; ?>
            <div class="page-card p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <p class="text-uppercase text-muted small mb-1">Analitica y exportacion</p>
                        <h3 class="fw-bold mb-0">Reportes operativos</h3>
                    </div>
                    <div class="d-flex gap-2">
                        <a class="btn btn-outline-success" href="<?= e(base_url('frontend/index.php?route=reportes.csv&desde=' . urlencode($desde) . '&hasta=' . urlencode($hasta) . '&id_sucursal=' . (int) $idSucursal . '&id_estado=' . (int) $idEstado)) ?>">Exportar CSV</a>
                        <a class="btn btn-outline-danger" href="<?= e(base_url('frontend/index.php?route=reportes.pdf&desde=' . urlencode($desde) . '&hasta=' . urlencode($hasta) . '&id_sucursal=' . (int) $idSucursal . '&id_estado=' . (int) $idEstado)) ?>">Exportar PDF</a>
                    </div>
                </div>

                <form method="GET" action="<?= e(base_url('frontend/index.php')) ?>" class="row g-3 align-items-end">
                    <input type="hidden" name="route" value="reportes.index">
                    <div class="col-md-3">
                        <label class="form-label">Desde</label>
                        <input type="date" name="desde" class="form-control" value="<?= e($desde) ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Hasta</label>
                        <input type="date" name="hasta" class="form-control" value="<?= e($hasta) ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Sucursal</label>
                        <select name="id_sucursal" class="form-select">
                            <option value="0">Todas</option>
                            <?php foreach ($sucursales as $sucursal): ?>
                                <option value="<?= (int) $sucursal['id_sucursal'] ?>" <?= $idSucursal === (int) $sucursal['id_sucursal'] ? 'selected' : '' ?>><?= e($sucursal['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Estado</label>
                        <select name="id_estado" class="form-select">
                            <option value="0">Todos</option>
                            <?php foreach ($estados as $estado): ?>
                                <option value="<?= (int) $estado['id_estado'] ?>" <?= $idEstado === (int) $estado['id_estado'] ? 'selected' : '' ?>><?= e($estado['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12 d-flex gap-2">
                        <button class="btn btn-primary" type="submit">Filtrar</button>
                        <a class="btn btn-light" href="<?= e(base_url('frontend/index.php?route=reportes.index')) ?>">Limpiar</a>
                    </div>
                </form>
            </div>

            <div class="table-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">Resultados</h5>
                    <span class="badge badge-soft"><?= count($reporte) ?> registros</span>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Codigo</th>
                                <th>Remitente</th>
                                <th>Destinatario</th>
                                <th>Origen</th>
                                <th>Destino</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reporte as $row): ?>
                                <tr>
                                    <td class="fw-semibold"><?= e($row['codigo_rastreo']) ?></td>
                                    <td><?= e($row['remitente']) ?></td>
                                    <td><?= e($row['destinatario']) ?></td>
                                    <td><?= e($row['origen_nombre']) ?></td>
                                    <td><?= e($row['destino_nombre']) ?></td>
                                    <td><span class="badge bg-info text-dark"><?= e($row['estado_nombre']) ?></span></td>
                                    <td><?= e(format_date($row['fecha_registro'])) ?></td>
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
