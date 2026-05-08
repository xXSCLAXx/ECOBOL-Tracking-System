<?php require __DIR__ . '/../components/head.php'; ?>
<div class="ecobol-layout">
    <?php require __DIR__ . '/../components/navbar.php'; ?>
    <div class="app-shell">
        <?php require __DIR__ . '/../components/sidebar.php'; ?>
        <main class="app-main">
            <?php require __DIR__ . '/../components/alerts.php'; ?>
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="form-card p-4">
                        <h3 class="fw-bold mb-3"><?= $sucursalEditar ? 'Editar sucursal' : 'Nueva sucursal' ?></h3>
                        <form method="POST" action="<?= e(base_url('frontend/index.php?route=' . ($sucursalEditar ? 'sucursales.update' : 'sucursales.store'))) ?>" class="needs-validation" novalidate>
                            <?= csrf_field() ?>
                            <?php if ($sucursalEditar): ?>
                                <input type="hidden" name="id_sucursal" value="<?= (int) $sucursalEditar['id_sucursal'] ?>">
                            <?php endif; ?>
                            <div class="mb-3">
                                <label class="form-label">Nombre</label>
                                <input type="text" name="nombre" class="form-control" value="<?= e($sucursalEditar['nombre'] ?? old('nombre')) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Ciudad</label>
                                <input type="text" name="ciudad" class="form-control" value="<?= e($sucursalEditar['ciudad'] ?? old('ciudad')) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Direccion</label>
                                <input type="text" name="direccion" class="form-control" value="<?= e($sucursalEditar['direccion'] ?? old('direccion')) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Telefono</label>
                                <input type="text" name="telefono" class="form-control" value="<?= e($sucursalEditar['telefono'] ?? old('telefono')) ?>" required>
                            </div>
                            <button class="btn btn-primary w-100" type="submit"><?= $sucursalEditar ? 'Actualizar' : 'Guardar' ?></button>
                        </form>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="table-card p-4">
                        <h4 class="fw-bold mb-3">Sucursales registradas</h4>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Ciudad</th>
                                        <th>Direccion</th>
                                        <th>Telefono</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($sucursales as $sucursal): ?>
                                        <tr>
                                            <td><?= e($sucursal['nombre']) ?></td>
                                            <td><?= e($sucursal['ciudad']) ?></td>
                                            <td><?= e($sucursal['direccion']) ?></td>
                                            <td><?= e($sucursal['telefono']) ?></td>
                                            <td class="d-flex gap-2">
                                                <a href="<?= e(base_url('frontend/index.php?route=sucursales.index&edit=' . $sucursal['id_sucursal'])) ?>" class="btn btn-sm btn-outline-primary">Editar</a>
                                                <form method="POST" action="<?= e(base_url('frontend/index.php?route=sucursales.delete')) ?>" onsubmit="return confirm('¿Eliminar sucursal?');">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="id_sucursal" value="<?= (int) $sucursal['id_sucursal'] ?>">
                                                    <button class="btn btn-sm btn-outline-danger" type="submit">Eliminar</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<?php require __DIR__ . '/../components/footer.php'; ?>
