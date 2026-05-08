<?php

declare(strict_types=1);

class EstadoController
{
    public function __construct(private PDO $pdo)
    {
    }

    public function all(): array
    {
        return (new Estado($this->pdo))->all();
    }

    public function findByName(string $name): ?array
    {
        return (new Estado($this->pdo))->findByName($name);
    }
}
