<?php

declare(strict_types=1);

class DashboardController
{
    public function __construct(private PDO $pdo)
    {
    }

    public function index(): void
    {
        require_role(['administrador', 'empleado']);

        $envioModel = new Envio($this->pdo);
        $estadoModel = new Estado($this->pdo);
        $sucursalModel = new Sucursal($this->pdo);

        $stats = $envioModel->dashboardStats();
        $porSucursal = $envioModel->countBySucursal();
        $porEstado = $envioModel->countByEstado();
        $envios = $envioModel->all();
        $estados = $estadoModel->all();
        $sucursales = $sucursalModel->all();

        $pageTitle = 'Dashboard';
        require __DIR__ . '/../../frontend/pages/dashboard.php';
    }
}
