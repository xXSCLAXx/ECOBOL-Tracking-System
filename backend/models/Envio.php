<?php

declare(strict_types=1);

class Envio
{
    public function __construct(private PDO $pdo)
    {
    }

    public function all(): array
    {
        $stmt = $this->pdo->query(
            'SELECT e.*, so.nombre AS origen_nombre, sd.nombre AS destino_nombre, es.nombre AS estado_nombre
             FROM envios e
             INNER JOIN sucursales so ON so.id_sucursal = e.id_sucursal_origen
             INNER JOIN sucursales sd ON sd.id_sucursal = e.id_sucursal_destino
             INNER JOIN estados es ON es.id_estado = e.id_estado
             ORDER BY e.fecha_registro DESC'
        );
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT e.*, so.nombre AS origen_nombre, sd.nombre AS destino_nombre, es.nombre AS estado_nombre
             FROM envios e
             INNER JOIN sucursales so ON so.id_sucursal = e.id_sucursal_origen
             INNER JOIN sucursales sd ON sd.id_sucursal = e.id_sucursal_destino
             INNER JOIN estados es ON es.id_estado = e.id_estado
             WHERE e.id_envio = :id'
        );
        $stmt->execute(['id' => $id]);
        $envio = $stmt->fetch();
        return $envio ?: null;
    }

    public function findByCodigo(string $codigo): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT e.*, so.nombre AS origen_nombre, sd.nombre AS destino_nombre, es.nombre AS estado_nombre
             FROM envios e
             INNER JOIN sucursales so ON so.id_sucursal = e.id_sucursal_origen
             INNER JOIN sucursales sd ON sd.id_sucursal = e.id_sucursal_destino
             INNER JOIN estados es ON es.id_estado = e.id_estado
             WHERE e.codigo_rastreo = :codigo'
        );
        $stmt->execute(['codigo' => $codigo]);
        $envio = $stmt->fetch();
        return $envio ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO envios (codigo_rastreo, remitente, destinatario, peso, tipo_servicio, id_sucursal_origen, id_sucursal_destino, id_estado)
             VALUES (:codigo_rastreo, :remitente, :destinatario, :peso, :tipo_servicio, :id_sucursal_origen, :id_sucursal_destino, :id_estado)'
        );
        $stmt->execute($data);
        return (int) $this->pdo->lastInsertId();
    }

    public function updateEstado(int $idEnvio, int $idEstado): bool
    {
        $stmt = $this->pdo->prepare('UPDATE envios SET id_estado = :id_estado WHERE id_envio = :id_envio');
        return $stmt->execute([
            'id_envio' => $idEnvio,
            'id_estado' => $idEstado,
        ]);
    }

    public function dashboardStats(): array
    {
        $total = (int) $this->pdo->query('SELECT COUNT(*) FROM envios')->fetchColumn();
        $entregados = (int) $this->pdo->query("SELECT COUNT(*) FROM envios WHERE id_estado = (SELECT id_estado FROM estados WHERE nombre = 'Entregado' LIMIT 1)")->fetchColumn();
        $pendientes = (int) $this->pdo->query("SELECT COUNT(*) FROM envios WHERE id_estado <> (SELECT id_estado FROM estados WHERE nombre = 'Entregado' LIMIT 1)")->fetchColumn();
        $retrasados = (int) $this->pdo->query("SELECT COUNT(*) FROM envios WHERE fecha_registro < DATE_SUB(NOW(), INTERVAL 5 DAY) AND id_estado <> (SELECT id_estado FROM estados WHERE nombre = 'Entregado' LIMIT 1)")->fetchColumn();

        return compact('total', 'entregados', 'pendientes', 'retrasados');
    }

    public function countBySucursal(): array
    {
        $stmt = $this->pdo->query(
            'SELECT s.nombre, COUNT(e.id_envio) AS total
             FROM sucursales s
             LEFT JOIN envios e ON e.id_sucursal_origen = s.id_sucursal
             GROUP BY s.id_sucursal
             ORDER BY total DESC'
        );
        return $stmt->fetchAll();
    }

    public function countByEstado(): array
    {
        $stmt = $this->pdo->query(
            'SELECT es.nombre, COUNT(e.id_envio) AS total
             FROM estados es
             LEFT JOIN envios e ON e.id_estado = es.id_estado
             GROUP BY es.id_estado
             ORDER BY es.id_estado ASC'
        );
        return $stmt->fetchAll();
    }

    public function reportByDateRange(string $desde, string $hasta): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT e.*, es.nombre AS estado_nombre, so.nombre AS origen_nombre, sd.nombre AS destino_nombre
             FROM envios e
             INNER JOIN estados es ON es.id_estado = e.id_estado
             INNER JOIN sucursales so ON so.id_sucursal = e.id_sucursal_origen
             INNER JOIN sucursales sd ON sd.id_sucursal = e.id_sucursal_destino
             WHERE DATE(e.fecha_registro) BETWEEN :desde AND :hasta
             ORDER BY e.fecha_registro DESC'
        );
        $stmt->execute(['desde' => $desde, 'hasta' => $hasta]);
        return $stmt->fetchAll();
    }

    public function reportBySucursal(?int $idSucursal = null): array
    {
        if ($idSucursal) {
            $stmt = $this->pdo->prepare(
                'SELECT e.*, es.nombre AS estado_nombre, so.nombre AS origen_nombre, sd.nombre AS destino_nombre
                 FROM envios e
                 INNER JOIN estados es ON es.id_estado = e.id_estado
                 INNER JOIN sucursales so ON so.id_sucursal = e.id_sucursal_origen
                 INNER JOIN sucursales sd ON sd.id_sucursal = e.id_sucursal_destino
                 WHERE e.id_sucursal_origen = :id OR e.id_sucursal_destino = :id
                 ORDER BY e.fecha_registro DESC'
            );
            $stmt->execute(['id' => $idSucursal]);
            return $stmt->fetchAll();
        }

        return $this->all();
    }

    public function reportByEstado(?int $idEstado = null): array
    {
        if ($idEstado) {
            $stmt = $this->pdo->prepare(
                'SELECT e.*, es.nombre AS estado_nombre, so.nombre AS origen_nombre, sd.nombre AS destino_nombre
                 FROM envios e
                 INNER JOIN estados es ON es.id_estado = e.id_estado
                 INNER JOIN sucursales so ON so.id_sucursal = e.id_sucursal_origen
                 INNER JOIN sucursales sd ON sd.id_sucursal = e.id_sucursal_destino
                 WHERE e.id_estado = :id_estado
                 ORDER BY e.fecha_registro DESC'
            );
            $stmt->execute(['id_estado' => $idEstado]);
            return $stmt->fetchAll();
        }

        return $this->all();
    }
}
