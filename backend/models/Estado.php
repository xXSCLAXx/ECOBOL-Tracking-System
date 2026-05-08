<?php

declare(strict_types=1);

class Estado
{
    public function __construct(private PDO $pdo)
    {
    }

    public function all(): array
    {
        return $this->pdo->query('SELECT * FROM estados ORDER BY id_estado ASC')->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM estados WHERE id_estado = :id');
        $stmt->execute(['id' => $id]);
        $estado = $stmt->fetch();
        return $estado ?: null;
    }

    public function findByName(string $name): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM estados WHERE nombre = :nombre LIMIT 1');
        $stmt->execute(['nombre' => $name]);
        $estado = $stmt->fetch();
        return $estado ?: null;
    }
}
