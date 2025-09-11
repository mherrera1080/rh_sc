<?php
ini_set('display_errors', 1);
require('Libraries/fpdf/fpdf.php');

class PDF extends FPDF
{
    // Variables para control dinámico
    private $y_position = 50; // Posición Y inicial para el listado
    private $row_height = 6;  // Altura de cada fila

    // Método público para obtener la altura de fila desde fuera de la clase
    public function getRowHeight()
    {
        return $this->row_height;
    }

    // Logo
    function AddLogo()
    {
        $this->Image('Assets/img/carso_logo.png', 10, 10, 85, 19);
        $this->SetFont('Arial', 'B', 11);
        $title = utf8_decode('NACEL DE CENTROAMÉRICA, S. A.');
        $this->SetTextColor(30, 55, 89);
        $this->Text(14, 31, $title);
        $this->SetFont('Arial', '', 10);
        $title = utf8_decode('Km. 17.5 Carretera San Jose Pinula, Granja San');
        $this->Text(14, 35, $title);
        $title = utf8_decode('Jorge, Guatemala, C.A.');
        $this->Text(14, 39, $title);
        $title = utf8_decode('PBX: (502) 6644-8000 ° 6637-2023');
        $this->Text(14, 43, $title);
    }

    // Primer Cuadro
    function primer_cuadro()
    {
        $this->SetFont('Arial', 'B', 9);
        $this->SetDrawColor(30, 55, 89);
        $this->SetLineWidth(0.8);
        $this->RoundedRect(
            10,
            10,
            190,
            35,
            2,
            ['TL' => true, 'TR' => true, 'BL' => true, 'BR' => true],
            'D'
        );
    }

    function titulo($arrData)
    {
        $this->SetLineWidth(0.2);
        $this->Cell(85, 35, null, 'R');
        $this->SetFont('Arial', 'B', 18);
        $title = utf8_decode('CONTRASEÑA');
        $this->SetTextColor(30, 55, 89);
        $this->Text(98, 18, $title);
        $this->SetFont('Times', 'B', 18);
        $title = utf8_decode('No. ' . $arrData['contraseña']['contraseña']);
        $this->SetTextColor(235, 9, 9);
        $this->Text(145, 18, $title);
        $this->SetFont('Arial', '', 12);
        $title = utf8_decode('Guatemala');
        $this->SetTextColor(30, 55, 89);
        $this->Text(98, 27, $title);
        $this->SetFont('Times', '', 10);
        $title = utf8_decode('______ de _____________ del __________');
        $this->SetTextColor(30, 55, 89);
        $this->Text(125, 27, $title);
        $this->SetFont('Arial', '', 12);
        $title = utf8_decode('Recibimos de: ');
        $this->SetTextColor(30, 55, 89);
        $this->Text(98, 35, $title);
        $this->SetFont('Times', '', 12);
        $title = utf8_decode('____________________________________________');
        $this->Text(98, 41, $title);
        $this->SetTextColor(0, 0, 0);
        $title = utf8_decode($arrData['contraseña']['dia_registro']);
        $this->Text(128, 27, $title);
        $title = utf8_decode($arrData['mes']);
        $this->Text(142, 27, $title);
        $title = utf8_decode($arrData['contraseña']['año_registro']);
        $this->Text(173, 27, $title);
        $title = utf8_decode($arrData['contraseña']['proveedor']);
        $this->Text(110, 41, $title);
    }

    function facturas($height)
    {
        $this->SetFont('Arial', 'B', 9);
        $this->SetDrawColor(30, 55, 89);
        $this->SetLineWidth(0.8);
        $this->RoundedRect(
            10,
            50,
            190,
            $height,
            2,
            ['TL' => true, 'TR' => true, 'BL' => false, 'BR' => false],
            'D'
        );
    }

    function listado($arrData)
    {
        $this->SetLineWidth(0.2);
        $this->SetXY(10, 50);
        $this->SetFillColor(30, 55, 89);

        // Encabezado de la tabla
        $this->RoundedRect(
            10,
            50,
            190,
            10,
            2,
            ['TL' => true, 'TR' => true, 'BL' => false, 'BR' => false],
            'FD'
        );

        $this->SetDrawColor(255, 255, 255);
        $this->Cell(20, 10, null, "R", 0, 'C');
        $this->Cell(40, 10, null, "R", 0, 'C');
        $this->Cell(80, 10, null, "R", 1, 'C');

        $this->SetFont('Arial', '', 12);
        $this->SetTextColor(255, 255, 255);
        $this->Text(17, 57, utf8_decode('No.'));
        $this->Text(40, 57, utf8_decode('No. Factura'));
        $this->Text(90, 57, utf8_decode('Bien / Servicio'));
        $this->Text(170, 57, utf8_decode('Valor'));

        $this->SetDrawColor(30, 55, 89);
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('Arial', '', 11);

        $y = 60; // Inicio después del encabezado
        $contador = 1;

        if (!empty($arrData['facturas'])) {
            foreach ($arrData['facturas'] as $row) {
                // Verificar si necesitamos una nueva página
                if ($y > 250) {
                    $this->AddPage();
                    $y = 20; // Reiniciar posición Y en nueva página
                }

                $this->SetXY(10, $y);
                $cellX = $this->GetX();
                $cellY = $this->GetY();

                // Dibujar celdas
                $this->Cell(20, $this->row_height, $contador, 1, 0, 'C');
                $this->Cell(40, $this->row_height, utf8_decode($row['no_factura']), 1, 0, 'C');
                $this->Cell(80, $this->row_height, null, 1, 0, 'L');
                $this->Text($cellX + 67, $cellY + 4, utf8_decode($row['bien_servicio']));
                $this->Cell(50, $this->row_height, '', 1, 0, 'R');

                $this->Text($cellX + 141, $cellY + 5, "Q.");

                $value = number_format($row['valor_documento'], 2);
                $endX = $cellX + 35 + 150;
                $textWidth = $this->GetStringWidth($value);
                $this->Text($endX - $textWidth - 2, $cellY + 4.5, $value);

                $y += $this->row_height;
                $contador++;
            }

            // Actualizar la posición Y final para cálculos posteriores
            $this->y_position = $y;
        } else {
            $this->SetXY(10, $y);
            $this->Cell(190, 8, utf8_decode('No se encontraron facturas para esta contraseña.'), 1, 1, 'C');
            $this->y_position = $y + 8;
        }

        return $this->y_position;
    }

    function total_facturas($arrData, $start_y)
    {
        $this->SetFont('Arial', '', 11);
        $this->SetDrawColor(30, 55, 89);
        $this->SetLineWidth(0.8);

        // Dibujar el rectángulo redondeado
        $this->RoundedRect(
            10,
            $start_y,
            190,
            6,
            2,
            ['TL' => false, 'TR' => false, 'BL' => true, 'BR' => true],
            'D'
        );

        // Posicionarse en el inicio del cuadro
        $this->SetXY(10, $start_y);
        $this->SetLineWidth(0.2);

        // Celda de TOTAL
        $this->Cell(140, 6, "TOTAL", "R", 0, 'C');

        // Celda vacía para el valor (con borde derecho)
        $this->Cell(50, 6, "", 0, 0, 'R');

        // ===== Texto alineado a la derecha =====
        $value = number_format($arrData['contraseña']['monto_total'], 2);

        // borde derecho de la celda TOTAL (10 de inicio + 140 + 50 = 200, pero ancho es 190)
        $endX = 200; // punto donde debe terminar el texto
        $textWidth = $this->GetStringWidth($value);

        // dibujar texto alineado a la derecha
        $this->Text($endX - $textWidth - 7, $start_y + 4.5, $value);
        $this->Text($endX - 49, $start_y + 4.5, "Q.");
        // el -5 da margen interno al borde
    }

    function cuadro_final($arrData, $start_y)
    {
        $this->SetFont('Arial', 'B', 9);
        $this->SetDrawColor(30, 55, 89);
        $this->SetLineWidth(0.8);

        // Cuadro izquierdo (Valor en letras)
        $this->RoundedRect(
            10,
            $start_y + 15,
            90,
            20,
            2,
            ['TL' => true, 'TR' => true, 'BL' => true, 'BR' => true],
            'D'
        );

        // Cuadro derecho (Departamento)
        $this->RoundedRect(
            110,
            $start_y + 15,
            90,
            20,
            2,
            ['TL' => true, 'TR' => true, 'BL' => true, 'BR' => true],
            'D'
        );

        $this->SetFont('Arial', '', 11);
        $this->SetTextColor(30, 55, 89);

        // Texto "Valor en letras"
        $this->Text(10, $start_y + 11, utf8_decode('Valor en letras: __________________________________________________________________________.'));
        // Líneas y texto dentro de los cuadros
        $this->Text(20, $start_y + 28, utf8_decode('________________________________'));
        $this->Text(41, $start_y + 32, utf8_decode('Fecha de Pago'));

        $this->Text(120, $start_y + 28, utf8_decode('________________________________'));
        $this->Text(128, $start_y + 32, utf8_decode('Departamento de ' . $arrData['contraseña']['area']));

        $this->SetTextColor(0, 0, 0);
        $this->Text(40, $start_y + 11, $arrData['monto_letras']);
        // Línea decorativa al final
        $this->SetFillColor(255, 104, 48);
        $this->SetDrawColor(255, 104, 48);
        $this->RoundedRect(
            10,
            $start_y + 39,
            190,
            5,
            2,
            ['TL' => false, 'TR' => false, 'BL' => false, 'BR' => false],
            'FD'
        );
    }

    // Función auxiliar para esquinas redondeadas (sin cambios)
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
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times', '', 12);

// Agregar elementos fijos
$pdf->AddLogo();
$pdf->primer_cuadro();
$pdf->titulo($arrData);

// Primero calcular la altura necesaria para el listado
$altura_listado = 10; // Altura del encabezado
if (!empty($arrData['facturas'])) {
    // Usar el método público para obtener la altura de fila
    $altura_listado += count($arrData['facturas']) * $pdf->getRowHeight();
} else {
    $altura_listado += 8; // Altura del mensaje "No se encontraron facturas"
}

// Dibujar el cuadro de facturas con la altura calculada
$pdf->facturas($altura_listado);

// Generar el listado y obtener la posición Y final
$final_y = $pdf->listado($arrData);

$pdf->total_facturas($arrData, $final_y);


// Agregar elementos finales en la posición calculada
$pdf->cuadro_final($arrData, $final_y);

$nombreArchivo = utf8_decode('Contraseña_Pago_' . $arrData['contraseña']['contraseña'] . '.pdf');
$pdf->Output('I', $nombreArchivo);
exit();