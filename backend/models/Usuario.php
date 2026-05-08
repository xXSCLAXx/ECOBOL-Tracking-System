<?php

declare(strict_types=1);

class Usuario
{
    public function __construct(private PDO $pdo)
    {
    }

    public function all(): array
    {
        $stmt = $this->pdo->query('SELECT id_usuario, nombre, email, rol, created_at FROM usuarios ORDER BY id_usuario DESC');
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT id_usuario, nombre, email, rol, created_at FROM usuarios WHERE id_usuario = :id');
        $stmt->execute(['id' => $id]);
        $usuario = $stmt->fetch();
        return $usuario ?: null;
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM usuarios WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $usuario = $stmt->fetch();
        return $usuario ?: null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->pdo->prepare('INSERT INTO usuarios (nombre, email, password_hash, rol) VALUES (:nombre, :email, :password_hash, :rol)');
        return $stmt->execute([
            'nombre' => $data['nombre'],
            'email' => $data['email'],
            'password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
            'rol' => $data['rol'],
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $fields = 'nombre = :nombre, email = :email, rol = :rol';
        $params = [
            'id' => $id,
            'nombre' => $data['nombre'],
            'email' => $data['email'],
            'rol' => $data['rol'],
        ];

        if (!empty($data['password'])) {
            $fields .= ', password_hash = :password_hash';
            $params['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $stmt = $this->pdo->prepare("UPDATE usuarios SET {$fields} WHERE id_usuario = :id");
        return $stmt->execute($params);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM usuarios WHERE id_usuario = :id');
        return $stmt->execute(['id' => $id]);
    }

    public function authenticate(string $email, string $password): ?array
    {
        $usuario = $this->findByEmail($email);
        if (!$usuario || !password_verify($password, $usuario['password_hash'])) {
            return null;
        }

        unset($usuario['password_hash']);
        return $usuario;
    }
}
