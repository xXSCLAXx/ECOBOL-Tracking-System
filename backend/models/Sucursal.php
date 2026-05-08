<?php

declare(strict_types=1);

class Sucursal
{
    public function __construct(private PDO $pdo)
    {
    }

    public function all(): array
    {
        return $this->pdo->query('SELECT * FROM sucursales ORDER BY nombre ASC')->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM sucursales WHERE id_sucursal = :id');
        $stmt->execute(['id' => $id]);
        $sucursal = $stmt->fetch();
        return $sucursal ?: null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->pdo->prepare('INSERT INTO sucursales (nombre, ciudad, direccion, telefono) VALUES (:nombre, :ciudad, :direccion, :telefono)');
        return $stmt->execute($data);
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare('UPDATE sucursales SET nombre = :nombre, ciudad = :ciudad, direccion = :direccion, telefono = :telefono WHERE id_sucursal = :id');
        $data['id'] = $id;
        return $stmt->execute($data);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM sucursales WHERE id_sucursal = :id');
        return $stmt->execute(['id' => $id]);
    }
}
