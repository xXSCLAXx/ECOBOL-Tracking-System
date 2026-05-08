<?php require __DIR__ . '/../components/head.php'; ?>
<?php $user = auth_user(); ?>
<?php $totalPorSucursal = array_sum(array_map(fn($item) => (int) $item['total'], $porSucursal)); ?>
<div class="ecobol-layout">
    <?php require __DIR__ . '/../components/navbar.php'; ?>
    <div class="app-shell">
        <?php require __DIR__ . '/../components/sidebar.php'; ?>
        <main class="app-main">
            <?php require __DIR__ . '/../components/alerts.php'; ?>

            <section class="hero-panel mb-4">
                <div class="row align-items-center g-4">
                    <div class="col-lg-8">
                        <p class="text-uppercase text-white-50 small mb-2">Panel administrativo ECOBOL</p>
                        <h1 class="fw-bold mb-3">Bienvenido, <?= e($user['nombre'] ?? 'Usuario') ?></h1>
                        <p class="mb-0 text-white-75">Gestiona envios, monitorea estados en tiempo real y revisa reportes operativos desde un panel claro y responsive.</p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <div class="badge bg-light text-dark px-3 py-2 rounded-pill">Rol: <?= e($user['rol'] ?? '') ?></div>
                    </div>
                </div>
            </section>

            <div class="row g-3 mb-4">
                <div class="col-md-6 col-xl-3">
                    <div class="metric-card h-100">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-muted small">Total envios</div>
                                <div class="fs-2 fw-bold"><?= (int) $stats['total'] ?></div>
                            </div>
                            <div class="metric-icon"><i class="bi bi-box-seam"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="metric-card h-100">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-muted small">Entregados</div>
                                <div class="fs-2 fw-bold text-success"><?= (int) $stats['entregados'] ?></div>
                            </div>
                            <div class="metric-icon"><i class="bi bi-check2-circle"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="metric-card h-100">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-muted small">Pendientes</div>
                                <div class="fs-2 fw-bold text-warning"><?= (int) $stats['pendientes'] ?></div>
                            </div>
                            <div class="metric-icon"><i class="bi bi-hourglass-split"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="metric-card h-100">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-muted small">Retrasados</div>
                                <div class="fs-2 fw-bold text-danger"><?= (int) $stats['retrasados'] ?></div>
                            </div>
                            <div class="metric-icon"><i class="bi bi-exclamation-triangle"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-lg-7">
                    <div class="page-card p-4 chart-card h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold mb-0">Envios por estado</h5>
                            <span class="badge badge-soft">Actualizado</span>
                        </div>
                        <canvas id="estadoChart" height="140"></canvas>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="page-card p-4 chart-card h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold mb-0">Envios por sucursal</h5>
                            <span class="badge badge-soft">Origen</span>
                        </div>
                        <canvas id="sucursalChart" height="140"></canvas>
                    </div>
                </div>
            </div>

            <div class="table-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">Envios recientes</h5>
                    <a href="<?= e(base_url('frontend/index.php?route=envios.create')) ?>" class="btn btn-primary btn-sm">Nuevo envio</a>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Codigo</th>
                                <th>Remitente</th>
                                <th>Destinatario</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_slice($envios, 0, 8) as $envio): ?>
                                <tr>
                                    <td class="fw-semibold"><?= e($envio['codigo_rastreo']) ?></td>
                                    <td><?= e($envio['remitente']) ?></td>
                                    <td><?= e($envio['destinatario']) ?></td>
                                    <td><span class="badge bg-info text-dark"><?= e($envio['estado_nombre']) ?></span></td>
                                    <td><?= e(format_date($envio['fecha_registro'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const estadoCtx = document.getElementById('estadoChart');
if (estadoCtx) {
    new Chart(estadoCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_map(fn($item) => $item['nombre'], $porEstado), JSON_UNESCAPED_UNICODE) ?>,
            datasets: [{
                label: 'Envios',
                data: <?= json_encode(array_map(fn($item) => (int) $item['total'], $porEstado)) ?>,
                backgroundColor: ['#1d5cff', '#22b8cf', '#0f2747', '#f6c453']
            }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
    });
}
const sucursalCtx = document.getElementById('sucursalChart');
if (sucursalCtx) {
    new Chart(sucursalCtx, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode(array_map(fn($item) => $item['nombre'], $porSucursal), JSON_UNESCAPED_UNICODE) ?>,
            datasets: [{
                data: <?= json_encode(array_map(fn($item) => (int) $item['total'], $porSucursal)) ?>,
                backgroundColor: ['#1d5cff', '#22b8cf', '#0f2747', '#f6c453', '#5a7cff']
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });
}
</script>
<?php require __DIR__ . '/../components/footer.php'; ?>
