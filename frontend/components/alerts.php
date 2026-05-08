<?php
$flashes = flash_all();
foreach ($flashes as $flash):
    $type = $flash['type'] ?? 'info';
    if (!in_array($type, ['success', 'danger', 'warning', 'info'], true)) {
        $type = 'info';
    }
?>
    <div class="alert alert-<?= e($type) ?> alert-dismissible fade show" role="alert">
        <?= e($flash['message'] ?? '') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endforeach; ?>
