<?php

declare(strict_types=1);

class ReporteController
{
    public function __construct(private PDO $pdo)
    {
    }

    public function index(): void
    {
        require_role('administrador');

        $envioModel = new Envio($this->pdo);
        $sucursalModel = new Sucursal($this->pdo);
        $estadoModel = new Estado($this->pdo);

        $desde = (string) ($_GET['desde'] ?? date('Y-m-01'));
        $hasta = (string) ($_GET['hasta'] ?? date('Y-m-d'));
        $idSucursal = (int) ($_GET['id_sucursal'] ?? 0);
        $idEstado = (int) ($_GET['id_estado'] ?? 0);

        $reporte = $envioModel->reportByDateRange($desde, $hasta);
        if ($idSucursal > 0) {
            $reporte = $envioModel->reportBySucursal($idSucursal);
        }
        if ($idEstado > 0) {
            $reporte = $envioModel->reportByEstado($idEstado);
        }

        $sucursales = $sucursalModel->all();
        $estados = $estadoModel->all();
        $pageTitle = 'Reportes';
        require __DIR__ . '/../../frontend/pages/reportes.php';
    }

    public function exportCsv(): void
    {
        require_role('administrador');
        $rows = $this->resolveReportRows();

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="reporte-ecobol.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Codigo', 'Remitente', 'Destinatario', 'Peso', 'Servicio', 'Origen', 'Destino', 'Estado', 'Fecha']);
        foreach ($rows as $row) {
            fputcsv($output, [
                $row['codigo_rastreo'],
                $row['remitente'],
                $row['destinatario'],
                $row['peso'],
                $row['tipo_servicio'],
                $row['origen_nombre'],
                $row['destino_nombre'],
                $row['estado_nombre'],
                $row['fecha_registro'],
            ]);
        }
        fclose($output);
        exit;
    }

    public function exportPdf(): void
    {
        require_role('administrador');
        $rows = $this->resolveReportRows();
        $pdf = $this->buildSimplePdf('Reporte ECOBOL', $rows);

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="reporte-ecobol.pdf"');
        echo $pdf;
        exit;
    }

    private function resolveReportRows(): array
    {
        $envioModel = new Envio($this->pdo);
        $desde = (string) ($_GET['desde'] ?? date('Y-m-01'));
        $hasta = (string) ($_GET['hasta'] ?? date('Y-m-d'));
        $idSucursal = (int) ($_GET['id_sucursal'] ?? 0);
        $idEstado = (int) ($_GET['id_estado'] ?? 0);

        if ($idSucursal > 0) {
            return $envioModel->reportBySucursal($idSucursal);
        }
        if ($idEstado > 0) {
            return $envioModel->reportByEstado($idEstado);
        }
        return $envioModel->reportByDateRange($desde, $hasta);
    }

    private function buildSimplePdf(string $title, array $rows): string
    {
        $lines = [strtoupper($title), ''];
        foreach ($rows as $row) {
            $lines[] = sprintf('%s | %s | %s | %s | %s | %s | %s | %s',
                $this->ascii($row['codigo_rastreo'] ?? ''),
                $this->ascii($row['remitente'] ?? ''),
                $this->ascii($row['destinatario'] ?? ''),
                $this->ascii((string) ($row['peso'] ?? '')),
                $this->ascii($row['tipo_servicio'] ?? ''),
                $this->ascii($row['origen_nombre'] ?? ''),
                $this->ascii($row['destino_nombre'] ?? ''),
                $this->ascii($row['estado_nombre'] ?? '')
            );
        }

        $stream = "BT\n/F1 11 Tf\n50 780 Td\n";
        foreach ($lines as $index => $line) {
            $escaped = $this->escapePdfText($line);
            if ($index === 0) {
                $stream .= "($escaped) Tj\n";
            } else {
                $stream .= "T* ($escaped) Tj\n";
            }
        }
        $stream .= "ET";

        $objects = [];
        $objects[] = '1 0 obj << /Type /Catalog /Pages 2 0 R >> endobj';
        $objects[] = '2 0 obj << /Type /Pages /Kids [3 0 R] /Count 1 >> endobj';
        $objects[] = '3 0 obj << /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >> endobj';
        $objects[] = '4 0 obj << /Type /Font /Subtype /Type1 /BaseFont /Helvetica >> endobj';
        $objects[] = '5 0 obj << /Length ' . strlen($stream) . ' >> stream\n' . $stream . '\nendstream endobj';

        $pdf = "%PDF-1.4\n";
        $offsets = [0];
        foreach ($objects as $object) {
            $offsets[] = strlen($pdf);
            $pdf .= $object . "\n";
        }

        $xrefPosition = strlen($pdf);
        $pdf .= "xref\n0 " . (count($objects) + 1) . "\n";
        $pdf .= "0000000000 65535 f \n";
        for ($i = 1; $i <= count($objects); $i++) {
            $pdf .= sprintf('%010d 00000 n ', $offsets[$i]) . "\n";
        }
        $pdf .= "trailer << /Size " . (count($objects) + 1) . " /Root 1 0 R >>\n";
        $pdf .= "startxref\n{$xrefPosition}\n%%EOF";

        return $pdf;
    }

    private function escapePdfText(string $text): string
    {
        return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $this->ascii($text));
    }

    private function ascii(string $text): string
    {
        $converted = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
        return $converted !== false ? $converted : $text;
    }
}
