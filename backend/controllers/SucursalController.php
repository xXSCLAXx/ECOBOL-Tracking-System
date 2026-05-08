<?php

declare(strict_types=1);

class SucursalController
{
    public function __construct(private PDO $pdo)
    {
    }

    public function index(): void
    {
        require_role('administrador');

        $sucursalModel = new Sucursal($this->pdo);
        $sucursales = $sucursalModel->all();
        $sucursalEditar = null;
        if (!empty($_GET['edit'])) {
            $sucursalEditar = $sucursalModel->findById((int) $_GET['edit']);
        }

        $pageTitle = 'Sucursales';
        require __DIR__ . '/../../frontend/pages/sucursales.php';
    }

    public function store(): void
    {
        require_role('administrador');
        verify_csrf();

        $data = $this->collectData();
        set_old($data);
        if ($error = $this->validate($data)) {
            flash_set('error', $error, 'danger');
            redirect('sucursales.index');
        }

        (new Sucursal($this->pdo))->create($data);
        clear_old();
        flash_set('success', 'Sucursal registrada correctamente.', 'success');
        redirect('sucursales.index');
    }

    public function update(): void
    {
        require_role('administrador');
        verify_csrf();

        $id = (int) ($_POST['id_sucursal'] ?? 0);
        $data = $this->collectData();
        set_old($data);

        if ($id <= 0) {
            flash_set('error', 'Sucursal invalida.', 'danger');
            redirect('sucursales.index');
        }

        if ($error = $this->validate($data)) {
            flash_set('error', $error, 'danger');
            redirect('sucursales.index&edit=' . $id);
        }

        (new Sucursal($this->pdo))->update($id, $data);
        clear_old();
        flash_set('success', 'Sucursal actualizada correctamente.', 'success');
        redirect('sucursales.index');
    }

    public function delete(): void
    {
        require_role('administrador');
        verify_csrf();

        $id = (int) ($_POST['id_sucursal'] ?? 0);
        if ($id <= 0) {
            flash_set('error', 'Sucursal invalida.', 'danger');
            redirect('sucursales.index');
        }

        (new Sucursal($this->pdo))->delete($id);
        flash_set('success', 'Sucursal eliminada correctamente.', 'success');
        redirect('sucursales.index');
    }

    private function collectData(): array
    {
        return [
            'nombre' => trim((string) ($_POST['nombre'] ?? '')),
            'ciudad' => trim((string) ($_POST['ciudad'] ?? '')),
            'direccion' => trim((string) ($_POST['direccion'] ?? '')),
            'telefono' => trim((string) ($_POST['telefono'] ?? '')),
        ];
    }

    private function validate(array $data): ?string
    {
        foreach ($data as $value) {
            if ($value === '') {
                return 'Completa todos los campos de la sucursal.';
            }
        }
        return null;
    }
}
