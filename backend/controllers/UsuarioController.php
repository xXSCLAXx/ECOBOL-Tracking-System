<?php

declare(strict_types=1);

class UsuarioController
{
    public function __construct(private PDO $pdo)
    {
    }

    public function index(): void
    {
        require_role('administrador');

        $usuarioModel = new Usuario($this->pdo);
        $usuarios = $usuarioModel->all();
        $usuarioEditar = null;
        if (!empty($_GET['edit'])) {
            $usuarioEditar = $usuarioModel->findById((int) $_GET['edit']);
        }

        $pageTitle = 'Usuarios';
        require __DIR__ . '/../../frontend/pages/usuarios.php';
    }

    public function store(): void
    {
        require_role('administrador');
        verify_csrf();

        $data = $this->collectData();
        set_old($data);

        if ($error = $this->validate($data)) {
            flash_set('error', $error, 'danger');
            redirect('usuarios.index');
        }

        $usuarioModel = new Usuario($this->pdo);
        if ($usuarioModel->findByEmail($data['email'])) {
            flash_set('error', 'Ya existe un usuario con ese email.', 'danger');
            redirect('usuarios.index');
        }

        $usuarioModel->create($data);
        clear_old();
        flash_set('success', 'Usuario registrado correctamente.', 'success');
        redirect('usuarios.index');
    }

    public function update(): void
    {
        require_role('administrador');
        verify_csrf();

        $id = (int) ($_POST['id_usuario'] ?? 0);
        $data = $this->collectData();
        set_old($data);

        if ($id <= 0) {
            flash_set('error', 'Usuario invalido.', 'danger');
            redirect('usuarios.index');
        }

        if ($error = $this->validate($data, true)) {
            flash_set('error', $error, 'danger');
            redirect('usuarios.index&edit=' . $id);
        }

        $usuarioModel = new Usuario($this->pdo);
        $existente = $usuarioModel->findByEmail($data['email']);
        if ($existente && (int) $existente['id_usuario'] !== $id) {
            flash_set('error', 'Ya existe un usuario con ese email.', 'danger');
            redirect('usuarios.index&edit=' . $id);
        }

        $usuarioModel->update($id, $data);
        clear_old();
        flash_set('success', 'Usuario actualizado correctamente.', 'success');
        redirect('usuarios.index');
    }

    public function delete(): void
    {
        require_role('administrador');
        verify_csrf();

        $id = (int) ($_POST['id_usuario'] ?? 0);
        if ($id <= 0) {
            flash_set('error', 'Usuario invalido.', 'danger');
            redirect('usuarios.index');
        }

        if ((int) (auth_user()['id_usuario'] ?? 0) === $id) {
            flash_set('error', 'No puedes eliminar tu propio usuario.', 'danger');
            redirect('usuarios.index');
        }

        (new Usuario($this->pdo))->delete($id);
        flash_set('success', 'Usuario eliminado correctamente.', 'success');
        redirect('usuarios.index');
    }

    private function collectData(): array
    {
        return [
            'nombre' => trim((string) ($_POST['nombre'] ?? '')),
            'email' => trim((string) ($_POST['email'] ?? '')),
            'password' => (string) ($_POST['password'] ?? ''),
            'rol' => (string) ($_POST['rol'] ?? 'empleado'),
        ];
    }

    private function validate(array $data, bool $update = false): ?string
    {
        if ($data['nombre'] === '' || $data['email'] === '' || $data['rol'] === '') {
            return 'Completa todos los campos obligatorios.';
        }
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return 'El email no es valido.';
        }
        if (!$update && strlen($data['password']) < 6) {
            return 'La contraseña debe tener al menos 6 caracteres.';
        }
        if ($data['password'] !== '' && strlen($data['password']) < 6) {
            return 'La contraseña debe tener al menos 6 caracteres.';
        }
        if (!in_array($data['rol'], ['administrador', 'empleado'], true)) {
            return 'El rol seleccionado no es valido.';
        }

        return null;
    }
}
