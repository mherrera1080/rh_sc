<?php
ini_set('display_errors', 1);
require('Libraries/fpdf/fpdf.php');

class PDF extends FPDF
{
    // Variables para control dinámico
    private $y_position = 50;
    private $row_height = 6;

    public function getRowHeight()
    {
        return $this->row_height;
    }

    function primer_cuadro()
    {
        $this->SetLineWidth(0.4);
        $this->RoundedRect(
            5,
            5,
            200,
            285,
            2,
            ['TL' => false, 'TR' => false, 'BL' => false, 'BR' => false],
            'D'
        );
    }

    function solicitud_fondo_info($arrData)
    {
        $this->Image('Assets/img/carso_logo.png', 6, 6, 60, 15);
        $this->SetFont('Arial', 'B', 15);
        $title = utf8_decode('NACEL DE CENTROAMÉRICA, S. A.');
        $this->Text(65, 25, $title);
        $title = utf8_decode('SECTOR DUCTOS');
        $this->Text(82, 30, $title);
        $this->SetFont('Arial', '', 13);
        $title = utf8_decode('SOLICITUD DE TRANSFERENCIA/CHEQUE');
        $this->Text(100, 40, $title);

        $this->SetFont('Arial', '', 10);
        $title = utf8_decode('FR-CG-06');
        $this->Text(180, 10, $title);
        $title = utf8_decode('REV.2');
        $this->Text(183, 15, $title);
        $title = utf8_decode('Referencia No. _______________');
        $this->Text(8, 30, $title);
        $title = utf8_decode($arrData['combustible']['contraseña']);
        $this->Text(33, 30, $title);
        $title = utf8_decode('De: ____________________');
        $this->Text(10, 40, $title);
        $title = utf8_decode('VEHICULOS');
        $this->Text(25, 40, $title);
        $title = utf8_decode('Para: Depto. de Contabilidad');
        $this->Text(10, 48, $title);
        $title = utf8_decode($arrData['combustible']['fecha_registro']);
        $this->Text(165, 48, $title);
        $title = utf8_decode('FECHA:________________________');
        $this->Text(140, 48, $title);
        $title = utf8_decode('Favor de elaborar trasnferencia a nombre de:');
        $this->Text(10, 55, $title);
    }

    function proveedor($arrData)
    {
        $this->SetXY(5, 56);
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(200, 10, $arrData['combustible']['proveedor'], "LTRB", 0, 'C');

    }

    function total_info($arrData)
    {
        $this->SetFont('Arial', '', 11);
        $title = utf8_decode('Total a Pagar:');
        $this->Text(8, 73, $title);
        $title = utf8_decode('Q___________________');
        $this->Text(8, 80, $title);
        $title = utf8_decode('_________________________________________________________________');
        $this->Text(60, 80, $title);
        $title = utf8_decode('En letras');
        $this->Text(65, 85, $title);

        $this->SetXY(18, 76);
        $this->Cell(35, 5, number_format($arrData['combustible']['total'], 2, '.', ','), 0, 0, 'R');
        $this->Cell(8, 5, null, 0, 'C');
        $this->Cell(62, 5, "( " . $arrData['monto_letras'] . " )", 0, 'C');
    }

    function listado($arrData)
    {
        $this->SetFont('Arial', '', 11);
        $this->SetTextColor(0, 0, 0);

        $x = 5;
        $y = 100;

        $this->Text($x + 30, $y - 7, utf8_decode('Por los siguientes conceptos'));

        $this->SetXY($x, $y);
        $this->Cell(150, 6, '', 'TRB', 0);
        $this->Cell(50, 6, '', 'TRB', 1);

        $this->Text($x + 2, $y + 4.5, utf8_decode('TRANSFERENCIA PARA VEHICULOS'));
        $this->Text($x + 152, $y + 4.5, 'Q. ' . number_format($arrData['combustible']['total'], 2));

        $this->SetXY($x, $y + 15);
        $this->Cell(150, 6, '', 'TRB', 0);
        $this->Cell(50, 6, '', 'TRB', 1);

        $this->Text($x + 2, $y + 20, utf8_decode('SALDO DISPONIBLE'));
        $this->Text($x + 152, $y + 20, 'Q. ' . number_format($arrData['combustible']['saldo'], 2));


        $this->SetXY($x, $y + 30);
        $this->Cell(100, 6, '', 'TRB', 0);

        $this->Text($x + 6, $y + 34.5, $arrData['rango_fecha']);
    }

    function impuestos($arrData)
    {
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('Arial', '', 9);

        // Posición fija del TOTAL y fecha programada
        $yTotal = 239;
        $yFecha = 243;

        // TOTAL (posición fija)
        $this->SetY($yTotal);
        $this->SetX(5);
        $this->Cell(170, 5, "TOTAL", "LTRB", 0, 'L');
        $this->Text(176, $yTotal + 4, "Q. ");
        $this->Cell(30, 5, number_format($arrData['combustible']['total'], 2), "LTRB", 1, 'R');

        // Fecha programada de pago (posición fija)
        $this->SetXY(5, $yFecha + 1);
        $this->Cell(25, 5, "Fecha programada para Pago", 0, 0, 'L');

        $title = utf8_decode('______________');
        $this->Text(50, $yFecha + 5, $title);
        $this->SetXY(25, $yFecha + 2);
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(75, 4, $arrData['combustible']['fecha_pago'], 0, 1, 'C');
    }

    function firmas($arrData)
    {
        $firmas = $arrData['firmas']; // 🔹 Tomamos las firmas del arreglo completo

        // 🔹 Omitir la firma con orden 1
        // $firmas = array_filter($firmas, function ($f) {
        //     return isset($f['orden']) && $f['orden'] != 1;
        // });

        $this->SetFont('Arial', '', 9.5);
        $this->SetTextColor(0, 0, 0);

        $totalFirmas = 1 + count($firmas); // 1 fijo + dinámicos
        $x = 5;
        $y = 250;
        $ancho = 200 / $totalFirmas;

        // ===== DIBUJAR CUADROS =====
        for ($i = 0; $i < $totalFirmas; $i++) {
            $this->SetXY($x, $y);
            $this->Cell($ancho, 20, '', "LTRB", 0, 'C');
            $x += $ancho;
        }

        // ===== NOMBRES =====
        $x = 5;
        $this->SetXY($x, $y + 20);

        // Primer cuadro fijo
        $this->Cell($ancho, 6, utf8_decode($arrData['solicitante']['nombre_completo']), "LTRB", 0, 'C');

        // Nombres dinámicos
        foreach ($firmas as $f) {
            $this->Cell($ancho, 6, !empty($f['nombre_usuario']) ? utf8_decode($f['nombre_usuario']) : "---", "LTRB", 0, 'C');
        }

        // ===== CARGOS =====
        $x = 5;
        $this->SetXY($x, $y + 26);

        $this->Cell($ancho, 6, utf8_decode("Solicitado por:"), "LTRB", 0, 'C');

        foreach ($firmas as $f) {
            $this->Cell($ancho, 6, !empty($f['cargo_usuario']) ? utf8_decode($f['cargo_usuario']) : "---", "LTRB", 0, 'C');
        }

        // ===== PIE DE PÁGINA =====
        $this->SetDrawColor(0, 0, 0);
        $this->RoundedRect(5, 282, 200, 8, 2, ['TL' => false, 'TR' => false, 'BL' => false, 'BR' => false], 'FD');
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Arial', '', 10);
        $this->Text(15, 288, utf8_decode('UNA EMPRESA DE'));
        $this->Text(110, 288, utf8_decode('CARSO INFRAESTRUCTURA Y CONTRUCCIÓN'));
        $this->Image('Assets/img/carso_logo.png', 55, 281, 50, 9);
    }

    function RoundedRect($x, $y, $w, $h, $r, $corners = ['TL' => true, 'TR' => true, 'BL' => true, 'BR' => true], $style = '')
    {
        $k = $this->k;
        $hp = $this->h;
        if ($style == 'F')
            $op = 'f';
        elseif ($style == 'FD' || $style == 'DF')
            $op = 'B';
        else
            $op = 'S';
        $MyArc = 4 / 3 * (sqrt(2) - 1);

        $this->_out(sprintf('%.2F %.2F m', ($x + ($corners['TL'] ? $r : 0)) * $k, ($hp - $y) * $k));
        $this->_out(sprintf('%.2F %.2F l', ($x + $w - ($corners['TR'] ? $r : 0)) * $k, ($hp - $y) * $k));

        if ($corners['TR']) {
            $xc = $x + $w - $r;
            $yc = $y + $r;
            $this->_Arc($xc + $r * $MyArc, $yc - $r, $xc + $r, $yc - $r * $MyArc, $xc + $r, $yc);
        } else {
            $this->_out(sprintf('%.2F %.2F l', ($x + $w) * $k, ($hp - $y) * $k));
        }

        $this->_out(sprintf('%.2F %.2F l', ($x + $w) * $k, ($hp - ($y + $h - ($corners['BR'] ? $r : 0))) * $k));

        if ($corners['BR']) {
            $xc = $x + $w - $r;
            $yc = $y + $h - $r;
            $this->_Arc($xc + $r, $yc + $r * $MyArc, $xc + $r * $MyArc, $yc + $r, $xc, $yc + $r);
        } else {
            $this->_out(sprintf('%.2F %.2F l', ($x + $w) * $k, ($hp - ($y + $h)) * $k));
        }

        $this->_out(sprintf('%.2F %.2F l', ($x + ($corners['BL'] ? $r : 0)) * $k, ($hp - ($y + $h)) * $k));

        if ($corners['BL']) {
            $xc = $x + $r;
            $yc = $y + $h - $r;
            $this->_Arc($xc - $r * $MyArc, $yc + $r, $xc - $r, $yc + $r * $MyArc, $xc - $r, $yc);
        } else {
            $this->_out(sprintf('%.2F %.2F l', $x * $k, ($hp - ($y + $h)) * $k));
        }

        $this->_out(sprintf('%.2F %.2F l', $x * $k, ($hp - ($y + ($corners['TL'] ? $r : 0))) * $k));

        if ($corners['TL']) {
            $xc = $x + $r;
            $yc = $y + $r;
            $this->_Arc($xc - $r, $yc - $r * $MyArc, $xc - $r * $MyArc, $yc - $r, $xc, $yc - $r);
        } else {
            $this->_out(sprintf('%.2F %.2F l', $x * $k, ($hp - $y) * $k));
        }

        $this->_out($op);
    }

    function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
    {
        $h = $this->h;
        $this->_out(sprintf(
            '%.2F %.2F %.2F %.2F %.2F %.2F c',
            $x1 * $this->k,
            ($h - $y1) * $this->k,
            $x2 * $this->k,
            ($h - $y2) * $this->k,
            $x3 * $this->k,
            ($h - $y3) * $this->k
        ));
    }
}

// Crear el PDF
$pdf = new PDF();
$pdf->SetAutoPageBreak(false);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times', '', 12);


$pdf->primer_cuadro();
$pdf->solicitud_fondo_info($arrData);
$pdf->proveedor($arrData);
$pdf->total_info($arrData);
$pdf->listado($arrData);

$pdf->impuestos($arrData);
$pdf->firmas($arrData);


$nombreArchivo = utf8_decode('Solicitud_Fondos_' . $arrData['combustible']['contraseña'] . '.pdf');
$pdf->Output('I', $nombreArchivo);
exit();

