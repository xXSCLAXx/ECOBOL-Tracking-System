<?php

declare(strict_types=1);

class HistorialEstado
{
    public function __construct(private PDO $pdo)
    {
    }

    public function byEnvio(int $idEnvio): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT h.*, e.nombre AS estado_nombre, u.nombre AS usuario_nombre
             FROM historial_estados h
             INNER JOIN estados e ON e.id_estado = h.id_estado
             INNER JOIN usuarios u ON u.id_usuario = h.actualizado_por
             WHERE h.id_envio = :id_envio
             ORDER BY h.fecha_hora DESC'
        );
        $stmt->execute(['id_envio' => $idEnvio]);
        return $stmt->fetchAll();
    }

    public function create(int $idEnvio, int $idEstado, int $usuarioId, ?string $observacion = null): bool
    {
        $stmt = $this->pdo->prepare('INSERT INTO historial_estados (id_envio, id_estado, observacion, actualizado_por) VALUES (:id_envio, :id_estado, :observacion, :actualizado_por)');
        return $stmt->execute([
            'id_envio' => $idEnvio,
            'id_estado' => $idEstado,
            'observacion' => $observacion,
            'actualizado_por' => $usuarioId,
        ]);
    }
}
