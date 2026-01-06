<?php
ini_set('display_errors', 1);
require('Libraries/fpdf/fpdf.php');

class PDF extends FPDF
{
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
        $title = utf8_decode('SOLICITUD DE TRANSFERENCIA / CHEQUE');
        $this->Text(100, 40, $title);

        $this->SetFont('Arial', '', 10);
        $title = utf8_decode('FR-CG-06');
        $this->Text(180, 10, $title);
        $title = utf8_decode('REV.2');
        $this->Text(183, 15, $title);
        $title = utf8_decode('Referencia No. _________');
        $this->Text(10, 30, $title);
        $title = utf8_decode($arrData['contraseña']['contraseña']);
        $this->Text(37, 30, $title);
        $title = utf8_decode('De: ____________________');
        $this->Text(10, 40, $title);
        $title = utf8_decode('VEHICULOS');
        $this->Text(18, 40, $title);
        $title = utf8_decode('Para: Depto. de Contabilidad');
        $this->Text(10, 48, $title);
        $title = utf8_decode($arrData['contraseña']['fecha_registro']);
        $this->Text(165, 48, $title);
        $title = utf8_decode('FECHA:________________________');
        $this->Text(140, 48, $title);
        $title = utf8_decode('Favor de elaborar transferencia a nombre de:');
        $this->Text(10, 55, $title);
    }

    function proveedor($arrData)
    {
        $this->SetXY(5, 56);
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(200, 10, $arrData['contraseña']['proveedor'], "LTRB", 0, 'C');

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
        $this->Cell(35, 5, number_format($arrData['inpuestos']['total'], 2, '.', ','), 0, 0, 'R');
        $this->Cell(8, 5, null, 0, 'C');
        $monto_letras = utf8_decode($arrData['monto_letras']);
        $this->Cell(62, 5, "( " . $monto_letras . " )", 0, 'C');
    }
    function listado($arrData)
    {
        $this->SetFont('Arial', '', 11);
        $this->SetTextColor(0, 0, 0);

        $this->Text(35, 93, utf8_decode('Por los siguientes conceptos'));

        $x = 5;
        $y = 100;
        $rowH = 6;
        $padX = 3;
        $padY = 1.5;

        $rentasAgrupadas = [];

        foreach ($arrData['rentas'] as $row) {
            $factura = $row['no_factura'];

            if (!isset($rentasAgrupadas[$factura])) {
                $rentasAgrupadas[$factura] = [
                    'placas' => [],
                    'monto' => $row['valor_documento']
                ];
            }

            $rentasAgrupadas[$factura]['placas'][] = $row['placa'];
        }

        $totalFacturas = count($rentasAgrupadas);

        if ($totalFacturas === 1) {

            $factura = array_key_first($rentasAgrupadas);
            $placas = $rentasAgrupadas[$factura]['placas'];
            $monto = $rentasAgrupadas[$factura]['monto'];

            // Encabezado
            $this->SetXY($x, $y);
            $this->Cell(140, $rowH, '', 'TRB', 0);
            $this->Cell(60, $rowH, '', 'TRB', 1);

            $this->SetXY($x + $padX, $y + $padY);
            $this->SetFont('Arial', '', 11);
            $this->Cell(134, $rowH - 2, utf8_decode('ARRENDAMIENTO DE VEHICULOS'), 0, 0, 'L');

            $this->SetXY($x + 140 + $padX, $y + $padY);
            $this->Cell(54, $rowH - 2, 'Q. ' . number_format($monto, 2), 0, 1, 'L');

            $y += $rowH;

            // Placas en línea
            $this->SetFont('Arial', '', 10);
            $usableW = 140 - ($padX * 2);
            $line = '';

            foreach ($placas as $placa) {
                $text = ($line === '') ? $placa : ', ' . $placa;

                if ($this->GetStringWidth($line . $text) <= $usableW) {
                    $line .= $text;
                } else {
                    $this->SetXY($x, $y);
                    $this->Cell(140, $rowH, '', 'LR', 0);
                    $this->Cell(60, $rowH, '', 'LR', 1);

                    $this->SetXY($x + $padX, $y + $padY);
                    $this->Cell($usableW, $rowH - 2, utf8_decode($line), 0, 0, 'L');

                    $y += $rowH;
                    $line = $placa;
                }
            }

            if ($line !== '') {
                $this->SetXY($x, $y);
                $this->Cell(140, $rowH, '', 'LR', 0);
                $this->Cell(60, $rowH, '', 'LR', 1);

                $this->SetXY($x + $padX, $y + $padY);
                $this->Cell($usableW, $rowH - 2, utf8_decode($line), 0, 0, 'L');

                $y += $rowH;
            }

            // Línea inferior
            $this->SetXY($x, $y);
            $this->Cell(140, 0, '', 'B', 0);
            $this->Cell(60, 0, '', 'B', 1);

            // Texto final
            $this->SetXY($x + $padX, $y + 5);
            $this->SetFont('Arial', 'B', 11);
            $this->Cell(
                134,
                $rowH,
                utf8_decode(
                    strtoupper($arrData['mes_renta']) . ' FACT. ' . $factura
                ),
                0,
                0,
                'L'
            );

            return $y;
        } else {

            $this->SetXY($x + $padX, $y);
            $this->SetFont('Arial', 'B', 11);
            $this->Cell(
                134,
                $rowH,
                utf8_decode(strtoupper($arrData['mes_renta'])),
                0,
                1,
                'L'
            );

            $y += $rowH + 2;

            $this->SetFont('Arial', '', 10);

            foreach ($rentasAgrupadas as $factura => $data) {

                $texto = 'RENTA DE VEHICULO '
                    . implode(', ', $data['placas'])
                    . ' FACT. ' . $factura;

                $this->SetXY($x, $y);

                $this->Cell(140, $rowH, utf8_decode($texto), 'TRB', 0, 'L');

                $this->Cell(
                    60,
                    $rowH,
                    'Q. ' . number_format($data['monto'], 2),
                    'TRB',
                    1,
                    'R'
                );

                $y += $rowH;
            }

            $this->SetXY($x, $y);
            $this->Cell(140, 0, '', 'B', 0);
            $this->Cell(60, 0, '', 'B', 1);

            return $y;
        }
    }

    function impuestos($arrData)
    {
        $this->SetTextColor(0, 0, 0);

        $lineHeight = 5;
        $yTotal = 239;
        $yFecha = 243;

        $aplicaIVA = !empty($arrData['inpuestos']['aplica_iva']) &&
            $arrData['inpuestos']['aplica_iva'] == 1;

        $aplicaISR = !empty($arrData['inpuestos']['aplica_isr']) &&
            $arrData['inpuestos']['aplica_isr'] == 1;

        $hayCredito = !empty($arrData['inpuestos']['monto_credito']) &&
            $arrData['inpuestos']['monto_credito'] > 0;

        /* -------- CÁLCULO DE FILAS -------- */
        $totalFilas = 1; // SUBTOTAL

        if ($aplicaIVA) {
            $totalFilas += count($arrData['retenciones']);
        }

        if ($aplicaISR) {
            $totalFilas += count($arrData['retenciones']);
        }

        if ($hayCredito) {
            $totalFilas += 1; // línea de crédito
        }

        $alturaBloque = $totalFilas * $lineHeight;
        $y = $yTotal - $alturaBloque;

        /* -------- SUBTOTAL -------- */
        $this->SetFont('Arial', 'B', 9);
        $this->SetY($y);
        $this->SetX(5);

        $this->Cell(110, 5, null, 0, 0, 'L');
        $this->Cell(60, 5, "SUBTOTAL", 0, 0, 'L');
        $this->Text(176, $y + 4, "Q. ");
        $this->Cell(
            30,
            5,
            number_format($arrData['inpuestos']['subtotal'], 2),
            0,
            1,
            'R'
        );

        $y += $lineHeight;

        $this->SetFont('Arial', '', 9);

        /* -------- RETENCIONES -------- */
        foreach ($arrData['retenciones'] as $ret) {

            if ($aplicaIVA) {
                $this->SetY($y);
                $this->SetX(5);

                $this->Cell(110, 5, null, 0, 0, 'L');
                $this->Cell(
                    50,
                    5,
                    utf8_decode('(-) RET. IVA FACT. ' . $ret['no_factura']),
                    0,
                    0,
                    'L'
                );

                $this->Text(176, $y + 4, "Q. ");
                $this->Cell(
                    40,
                    5,
                    number_format($ret['reten_iva'], 2),
                    0,
                    1,
                    'R'
                );

                $y += $lineHeight;
            }

            if ($aplicaISR) {
                $this->SetY($y);
                $this->SetX(5);

                $this->Cell(110, 5, null, 0, 0, 'L');
                $this->Cell(
                    50,
                    5,
                    utf8_decode('(-) RET. ISR FACT. ' . $ret['no_factura']),
                    0,
                    0,
                    'L'
                );

                $this->Text(176, $y + 4, "Q. ");
                $this->Cell(
                    40,
                    5,
                    number_format($ret['reten_isr'], 2),
                    0,
                    1,
                    'R'
                );

                $y += $lineHeight;
            }
        }

        /* -------- CRÉDITO -------- */
        if ($hayCredito) {
            $this->SetFont('Arial', '', 9);
            $this->SetY($y);
            $this->SetX(5);

            $this->Cell(110, 5, null, 0, 0, 'L');
            $this->Cell(
                50,
                5,
                utf8_decode('(-) NOTA DE CREDITO: ' . $arrData['inpuestos']['factura_credito']),
                0,
                0,
                'L'
            );

            $this->Text(176, $y + 4, "Q. ");
            $this->Cell(
                40,
                5,
                number_format($arrData['inpuestos']['monto_credito'], 2),
                0,
                1,
                'R'
            );

            $y += $lineHeight;
        }

        /* -------- TOTAL -------- */
        $this->SetFont('Arial', 'B', 9);
        $this->SetY($yTotal);
        $this->SetX(5);

        $this->Cell(170, 5, "TOTAL", "LTRB", 0, 'L');
        $this->Text(176, $yTotal + 4, "Q. ");
        $this->Cell(
            30,
            5,
            number_format($arrData['inpuestos']['total'], 2),
            "LTRB",
            1,
            'R'
        );

        /* -------- FECHA DE PAGO -------- */
        $this->SetFont('Arial', 'B', 9);
        $this->SetXY(5, $yFecha + 1);
        $this->Cell(25, 5, "Fecha programada para Pago", 0, 0, 'L');

        $this->Text(50, $yFecha + 5, utf8_decode('______________'));

        $this->SetXY(25, $yFecha + 2);
        $this->Cell(
            75,
            4,
            $arrData['inpuestos']['fecha_pago'],
            0,
            1,
            'C'
        );
    }


    function firmas($arrData)
    {
        $firmas = $arrData['firmas'];

        $this->SetFont('Arial', '', 9.5);
        $this->SetTextColor(0, 0, 0);

        $totalFirmas = 1 + count($firmas);
        $x = 5;
        $y = 250;
        $ancho = 200 / $totalFirmas;

        for ($i = 0; $i < $totalFirmas; $i++) {
            $this->SetXY($x, $y);
            $this->Cell($ancho, 20, '', "LTRB", 0, 'C');
            $x += $ancho;
        }

        $x = 5;
        $this->SetXY($x, $y + 20);

        $this->Cell($ancho, 6, utf8_decode($arrData['solicitante']['nombre_completo']), "LTRB", 0, 'C');

        foreach ($firmas as $f) {
            $this->Cell($ancho, 6, !empty($f['nombre_usuario']) ? utf8_decode($f['nombre_usuario']) : "---", "LTRB", 0, 'C');
        }

        $x = 5;
        $this->SetXY($x, $y + 26);

        $this->Cell($ancho, 6, utf8_decode("Solicitado por:"), "LTRB", 0, 'C');

        foreach ($firmas as $f) {
            $this->Cell($ancho, 6, !empty($f['cargo_usuario']) ? utf8_decode($f['cargo_usuario']) : "---", "LTRB", 0, 'C');
        }

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

$nombreArchivo = utf8_decode('Solicitud_Fondos_' . $arrData['contraseña']['contraseña'] . '.pdf');
$pdf->Output('I', $nombreArchivo);
exit();

