<?php $bodyClass = 'login-page'; ?>
<?php require __DIR__ . '/../components/head.php'; ?>
<div class="login-shell">
    <div class="login-card row g-0">
        <div class="col-lg-5 login-brand d-flex flex-column justify-content-between">
            <div class="mark mb-4"><i class="bi bi-truck"></i></div>
        </div>
        <div class="col-lg-7 p-4 p-lg-5 bg-white">
            <h2 class="fw-bold mb-4">Iniciar sesion</h2>

            <?php require __DIR__ . '/../components/alerts.php'; ?>

            <form method="POST" action="<?= e(base_url('frontend/index.php?route=login')) ?>" class="needs-validation" novalidate>
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control form-control-lg" value="<?= e(old('email')) ?>" required>
                    <div class="invalid-feedback">Ingresa un email valido.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-control form-control-lg" minlength="6" required>
                    <div class="invalid-feedback">La contraseña es obligatoria.</div>
                </div>
                <button type="submit" class="btn btn-primary btn-lg w-100">Entrar al sistema</button>
            </form>
        </div>
    </div>
</div>
<?php require __DIR__ . '/../components/footer.php'; ?>
