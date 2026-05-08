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
                        <h3 class="fw-bold mb-3"><?= $usuarioEditar ? 'Editar usuario' : 'Nuevo usuario' ?></h3>
                        <form method="POST" action="<?= e(base_url('frontend/index.php?route=' . ($usuarioEditar ? 'usuarios.update' : 'usuarios.store'))) ?>" class="needs-validation" novalidate>
                            <?= csrf_field() ?>
                            <?php if ($usuarioEditar): ?>
                                <input type="hidden" name="id_usuario" value="<?= (int) $usuarioEditar['id_usuario'] ?>">
                            <?php endif; ?>
                            <div class="mb-3">
                                <label class="form-label">Nombre</label>
                                <input type="text" name="nombre" class="form-control" value="<?= e($usuarioEditar['nombre'] ?? old('nombre')) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="<?= e($usuarioEditar['email'] ?? old('email')) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Contraseña <?= $usuarioEditar ? '(opcional)' : '' ?></label>
                                <input type="password" name="password" class="form-control" minlength="6" <?= $usuarioEditar ? '' : 'required' ?>>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Rol</label>
                                <select name="rol" class="form-select" required>
                                    <option value="administrador" <?= ($usuarioEditar['rol'] ?? old('rol')) === 'administrador' ? 'selected' : '' ?>>Administrador</option>
                                    <option value="empleado" <?= ($usuarioEditar['rol'] ?? old('rol')) === 'empleado' ? 'selected' : '' ?>>Empleado</option>
                                </select>
                            </div>
                            <button class="btn btn-primary w-100" type="submit"><?= $usuarioEditar ? 'Actualizar' : 'Guardar' ?></button>
                        </form>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="table-card p-4">
                        <h4 class="fw-bold mb-3">Usuarios registrados</h4>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Rol</th>
                                        <th>Creado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($usuarios as $usuario): ?>
                                        <tr>
                                            <td><?= e($usuario['nombre']) ?></td>
                                            <td><?= e($usuario['email']) ?></td>
                                            <td><span class="badge badge-soft"><?= e($usuario['rol']) ?></span></td>
                                            <td><?= e(format_date($usuario['created_at'])) ?></td>
                                            <td class="d-flex gap-2">
                                                <a href="<?= e(base_url('frontend/index.php?route=usuarios.index&edit=' . $usuario['id_usuario'])) ?>" class="btn btn-sm btn-outline-primary">Editar</a>
                                                <form method="POST" action="<?= e(base_url('frontend/index.php?route=usuarios.delete')) ?>" onsubmit="return confirm('¿Eliminar usuario?');">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="id_usuario" value="<?= (int) $usuario['id_usuario'] ?>">
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
