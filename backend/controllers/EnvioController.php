<?php

declare(strict_types=1);

class EnvioController
{
    public function __construct(private PDO $pdo)
    {
    }

    public function index(): void
    {
        require_role(['administrador', 'empleado']);

        $envioModel = new Envio($this->pdo);
        $sucursalModel = new Sucursal($this->pdo);
        $estadoModel = new Estado($this->pdo);
        $historialModel = new HistorialEstado($this->pdo);

        $envios = $envioModel->all();
        $sucursales = $sucursalModel->all();
        $estados = $estadoModel->all();

        $pageTitle = 'Envios registrados';
        require __DIR__ . '/../../frontend/pages/lista_envios.php';
    }

    public function create(): void
    {
        require_role(['administrador', 'empleado']);

        $sucursalModel = new Sucursal($this->pdo);
        $estadoModel = new Estado($this->pdo);
        $sucursales = $sucursalModel->all();
        $estados = $estadoModel->all();
        $pageTitle = 'Registrar envio';
        require __DIR__ . '/../../frontend/pages/registrar_envio.php';
    }

    public function store(): void
    {
        require_role(['administrador', 'empleado']);
        verify_csrf();

        $data = [
            'remitente' => trim((string) ($_POST['remitente'] ?? '')),
            'destinatario' => trim((string) ($_POST['destinatario'] ?? '')),
            'peso' => (string) ($_POST['peso'] ?? ''),
            'tipo_servicio' => trim((string) ($_POST['tipo_servicio'] ?? '')),
            'id_sucursal_origen' => (int) ($_POST['id_sucursal_origen'] ?? 0),
            'id_sucursal_destino' => (int) ($_POST['id_sucursal_destino'] ?? 0),
            'id_estado' => (int) ($_POST['id_estado'] ?? 0),
        ];

        set_old($data);

        if (in_array('', [$data['remitente'], $data['destinatario'], $data['peso'], $data['tipo_servicio']], true)) {
            flash_set('error', 'Completa todos los campos obligatorios.', 'danger');
            redirect('envios.create');
        }

        if (!is_numeric($data['peso']) || (float) $data['peso'] <= 0) {
            flash_set('error', 'El peso debe ser numerico y mayor que cero.', 'danger');
            redirect('envios.create');
        }

        $envioModel = new Envio($this->pdo);
        $estadoModel = new Estado($this->pdo);
        $historialModel = new HistorialEstado($this->pdo);

        $estadoRegistrado = $estadoModel->findByName('Registrado');
        if (!$estadoRegistrado) {
            flash_set('error', 'No se encontro el estado inicial Registrado.', 'danger');
            redirect('envios.create');
        }

        $codigo = $this->generateUniqueCode($envioModel);

        $idEnvio = $envioModel->create([
            'codigo_rastreo' => $codigo,
            'remitente' => $data['remitente'],
            'destinatario' => $data['destinatario'],
            'peso' => (float) $data['peso'],
            'tipo_servicio' => $data['tipo_servicio'],
            'id_sucursal_origen' => $data['id_sucursal_origen'],
            'id_sucursal_destino' => $data['id_sucursal_destino'],
            'id_estado' => $estadoRegistrado['id_estado'],
        ]);

        $historialModel->create($idEnvio, (int) $estadoRegistrado['id_estado'], (int) (auth_user()['id_usuario'] ?? 0), 'Registro inicial del envio');

        clear_old();
        flash_set('success', 'Envio registrado correctamente con codigo ' . $codigo . '.', 'success');
        redirect('envios.index');
    }

    public function editEstado(): void
    {
        require_role(['administrador', 'empleado']);

        $idEnvio = (int) ($_GET['id'] ?? 0);
        $envioModel = new Envio($this->pdo);
        $historialModel = new HistorialEstado($this->pdo);
        $estadoModel = new Estado($this->pdo);
        $sucursalModel = new Sucursal($this->pdo);

        $envio = $idEnvio > 0 ? $envioModel->findById($idEnvio) : null;
        $historial = $idEnvio > 0 ? $historialModel->byEnvio($idEnvio) : [];
        $estados = $estadoModel->all();
        $sucursales = $sucursalModel->all();
        $envios = $envioModel->all();

        $pageTitle = 'Actualizar estado';
        require __DIR__ . '/../../frontend/pages/actualizar_estado.php';
    }

    public function updateEstado(): void
    {
        require_role(['administrador', 'empleado']);
        verify_csrf();

        $idEnvio = (int) ($_POST['id_envio'] ?? 0);
        $idEstado = (int) ($_POST['id_estado'] ?? 0);
        $observacion = trim((string) ($_POST['observacion'] ?? ''));

        if ($idEnvio <= 0 || $idEstado <= 0) {
            flash_set('error', 'Debes seleccionar un envio y un estado valido.', 'danger');
            redirect('envios.estado');
        }

        $envioModel = new Envio($this->pdo);
        $historialModel = new HistorialEstado($this->pdo);
        $envio = $envioModel->findById($idEnvio);

        if (!$envio) {
            flash_set('error', 'El envio seleccionado no existe.', 'danger');
            redirect('envios.estado');
        }

        $envioModel->updateEstado($idEnvio, $idEstado);
        $historialModel->create($idEnvio, $idEstado, (int) (auth_user()['id_usuario'] ?? 0), $observacion !== '' ? $observacion : null);

        flash_set('success', 'Estado actualizado correctamente.', 'success');
        redirect('envios.estado&id=' . $idEnvio);
    }

    public function rastreoPublico(): void
    {
        $codigo = trim((string) ($_GET['codigo'] ?? ''));
        $envio = null;
        $historial = [];

        if ($codigo !== '') {
            $envioModel = new Envio($this->pdo);
            $historialModel = new HistorialEstado($this->pdo);
            $envio = $envioModel->findByCodigo($codigo);
            $historial = $envio ? $historialModel->byEnvio((int) $envio['id_envio']) : [];
            if (!$envio) {
                flash_set('error', 'No existe un envio con ese codigo.', 'danger');
            }
        }

        $pageTitle = 'Rastreo publico';
        require __DIR__ . '/../../frontend/pages/rastreo_publico.php';
    }

    private function generateUniqueCode(Envio $envioModel): string
    {
        for ($i = 0; $i < 20; $i++) {
            $codigo = generar_codigo_rastreo();
            if (!$envioModel->findByCodigo($codigo)) {
                return $codigo;
            }
        }

        throw new RuntimeException('No fue posible generar un codigo unico.');
    }
}
