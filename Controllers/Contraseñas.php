<?php
session_start();

class Contraseñas extends Controllers
{
    public function __construct()
    {
        parent::__construct();

        if (empty($_SESSION['login'])) {
            header('Location: ' . base_url() . '/login');
            die();
        }
    }

    public function Contraseñas()
    {
        $data['page_id'] = CONTABILIDAD;
        $data['page_tag'] = "Contraseñas";
        $data['page_title'] = "Contraseñas";
        $data['page_name'] = "Contraseñas";
        $data['page_functions_js'] = "functions_contraseñas.js";

        $this->views->getView($this, "Contraseñas", $data);
    }

    public function Recepcion()
    {
        $data['page_id'] = RECEPCION;
        $data['page_tag'] = "Recepcion";
        $data['page_title'] = "Recepcion";
        $data['page_name'] = "Recepcion";
        $data['page_functions_js'] = "functions_recepcion.js";

        $this->views->getView($this, "Recepcion", $data);
    }

    public function Facturas()
    {
        $data['page_id'] = CONTABILIDAD;
        $data['page_tag'] = "Facturas";
        $data['page_title'] = "Facturas";
        $data['page_name'] = "Facturas";
        $data['page_functions_js'] = "functions_facturas.js";

        $this->views->getView($this, "Facturas", $data);
    }

    public function getDetalles()
    {
        $arrData = $this->model->selectDetalles();

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron registros previos.');
        } else {
            $arrResponse = array('status' => true, 'data' => $arrData);
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function Contabilidad()
    {
        $data['page_id'] = DASHBOARD;
        $data['page_tag'] = "Contabilidad";
        $data['page_title'] = "Contabilidad";
        $data['page_name'] = "Contabilidad";
        $data['page_functions_js'] = "functions_contabilidad.js";

        $this->views->getView($this, "Contabilidad", $data);
    }

    public function Detalles($contraseña)
    {
        $facturas = $this->model->getContraseña($contraseña);
        $id_solicitud = $facturas["anticipo"];
        $anticipoinfo = $this->model->getAnticipoInfo($id_solicitud);

        $data['facturas'] = $facturas;
        $data['anticipoinfo'] = $anticipoinfo; // booleano
        $data['page_id'] = 'INFO';
        $data['page_tag'] = "Detalles";
        $data['page_title'] = "Detalles";
        $data['page_name'] = "Detalles";
        $data['page_functions_js'] = "functions_contraseña_detalles.js";

        $this->views->getView($this, "Detalles", $data);
    }

    public function getFacturas($contraseña)
    {
        $arrData = $this->model->getFacturasbyContra($contraseña);
        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron registros previos.');
        } else {
            $arrResponse = array('status' => true, 'data' => $arrData);
        }

        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getAllContraseña($contraseña)
    {
        $arrData = $this->model->getAllContraseña($contraseña);

        // Verificar si se encontraron datos
        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron registros previos.');
        } else {
            // Transformar las fechas y valores a arrays
            $arrData['no_factura'] = explode(",", $arrData['no_factura']);
            $arrData['bien_servicio'] = explode(",", $arrData['bien_servicio']);
            $arrData['valor_documento'] = explode(',', $arrData['valor_documento']);
            $arrData['observacion'] = explode(',', $arrData['observacion']);
            $arrData['estado'] = explode(',', $arrData['estado']);
            $arrResponse = array('status' => true, 'data' => $arrData);
        }

        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }


    public function usuariosAreas($id_area)
    {

        $arrData = $this->model->getUsuariosPorArea($id_area);

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron registros previos.');
        } else {
            $arrResponse = array('status' => true, 'data' => $arrData);
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function contraseñasRecepcion()
    {
        $arrData = $this->model->selectRecepcion();

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron registros previos.');
        } else {
            $arrResponse = array('status' => true, 'data' => $arrData);
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function registroContra()
    {
        $arrData = $this->model->selectContras();

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron registros previos.');
        } else {
            $arrResponse = array('status' => true, 'data' => $arrData);
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function areaContabilidad()
    {
        $arrData = $this->model->contrasContabilidad();

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron registros previos.');
        } else {
            $arrResponse = array('status' => true, 'data' => $arrData);
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function lastPassword()
    {
        $arrData = $this->model->selectLastContraseña();

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron registros previos.');
        } else {
            $arrResponse = array('status' => true, 'data' => $arrData);
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getSelectProveedor()
    {
        $htmlOptions = "<option selected disabled>Seleccione un Proveedor...</option>";
        $arrData = $this->model->selectProveedores();
        if (count($arrData) > 0) {
            for ($i = 0; $i < count($arrData); $i++) {
                $htmlOptions .= '<option value="' . $arrData[$i]['id_proveedor'] . '">' . $arrData[$i]['nombre_proveedor'] . " | " . $arrData[$i]['nombre_social'] . '</option>';
            }
        }
        echo $htmlOptions;
        die();
    }

    public function getSelectAreas()
    {
        $htmlOptions = "<option selected disabled>Seleccione una Area ...</option>";
        $arrData = $this->model->selectAreas();
        if (count($arrData) > 0) {
            for ($i = 0; $i < count($arrData); $i++) {
                $htmlOptions .= '<option value="' . $arrData[$i]['id_area'] . '">' . $arrData[$i]['nombre_area'] . '</option>';
            }
        }
        echo $htmlOptions;
        die();
    }
    public function generarContraseña(int $contraseña)
    {
        if ($contraseña) {
            $informe = $this->model->getContraseña($contraseña);
            $facturas = $this->model->getFacturasbyContra($contraseña);

            if (empty($informe)) {
                $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
                return;
            }

            $monto_total = $informe['monto_total'];
            $monto_letras = $this->numeroALetras($monto_total, 'asNumber', 2);

            switch ($informe['mes_registro']) {
                case '1':
                    $mes = "Enero";
                    break;
                case '2':
                    $mes = "Febrero";
                    break;
                case '3':
                    $mes = "Marzo";
                    break;
                case '4':
                    $mes = "Abril";
                    break;
                case '5':
                    $mes = "Mayo";
                    break;
                case '6':
                    $mes = "Junio";
                    break;
                case '7':
                    $mes = "Julio";
                    break;
                case '8':
                    $mes = "Agosto";
                    break;
                case '9':
                    $mes = "Septiembre";
                    break;
                case '10':
                    $mes = "Octubre";
                    break;
                case '11':
                    $mes = "Noviembre";
                    break;
                case '12':
                    $mes = "Diciembre";
                    break;
                default:
                    $mes = "Mes no identificado";
                    break;
            }

            $ruta_pdf = 'Views/Template/PDF/Contraseña.php';
            $arrData['contraseña'] = $informe;
            $arrData['facturas'] = $facturas;
            $arrData['mes'] = $mes;
            $arrData['monto'] = $monto_total;
            $arrData['monto_letras'] = $monto_letras;

            if (empty($arrData)) {
                $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
            } else {
                require_once $ruta_pdf;
                exit();
            }

        } else {
            $arrResponse = array('status' => false, 'msg' => 'Seleccione Uniforme');
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
    }
    private function numeroALetras($numero, $modo = 'asNumber', $maxFractionDigits = null)
    {
        // Requiere ext-intl (NumberFormatter)
        if (!extension_loaded('intl')) {
            // Fallback simple: devolver el número como string si intl no está disponible
            return (string) $numero;
        }

        // Normalizar entrada: aceptar comas o puntos como separador decimal
        $numeroStr = trim((string) $numero);
        $numeroStr = str_replace(',', '.', $numeroStr);

        // Manejo de signo
        $sign = '';
        if (substr($numeroStr, 0, 1) === '-') {
            $sign = 'menos ';
            $numeroStr = substr($numeroStr, 1);
        }

        if (!is_numeric($numeroStr)) {
            return $sign . $numeroStr;
        }

        // Separar parte entera y decimal
        $parts = explode('.', $numeroStr, 2);
        $intPart = $parts[0] === '' ? '0' : $parts[0];
        $origFraction = $parts[1] ?? '';

        // Ajustar cantidad de decimales si se pide (ej. 2 para centavos)
        if ($maxFractionDigits !== null && $maxFractionDigits >= 0) {
            $fraction = substr(str_pad($origFraction, $maxFractionDigits, '0'), 0, $maxFractionDigits);
            // si quedan todos ceros, tratar como ausencia de fracción
            if (preg_match('/^0*$/', $fraction)) {
                $fraction = '';
            }
        } else {
            // eliminar ceros finales que no aportan (opcional)
            $fraction = rtrim($origFraction, '0');
            if ($fraction === '')
                $fraction = $origFraction; // si todo eran ceros, conservar original
        }

        $fmt = new NumberFormatter('es', NumberFormatter::SPELLOUT);

        // Convertir parte entera
        // Usamos intval para evitar problemas con floats muy grandes; NumberFormatter maneja hasta cierto límite.
        $intValue = (int) $intPart;
        $intWords = $fmt->format($intValue);
        $intWords = $intWords === '' ? 'cero' : trim($intWords);

        // Si no hay decimales -> devolver solo la parte entera
        if ($fraction === '' || $fraction === null) {
            return ucfirst($sign . $intWords);
        }

        // Mapeo dígitos
        $digitMap = ['0' => 'cero', '1' => 'uno', '2' => 'dos', '3' => 'tres', '4' => 'cuatro', '5' => 'cinco', '6' => 'seis', '7' => 'siete', '8' => 'ocho', '9' => 'nueve'];

        if ($modo === 'digits') {
            $digits = preg_split('//u', $fraction, -1, PREG_SPLIT_NO_EMPTY);
            $words = array_map(function ($d) use ($digitMap) {
                return $digitMap[$d] ?? $d;
            }, $digits);
            return ucfirst($sign . $intWords . ' punto ' . implode(' ', $words));
        }

        if ($modo === 'asNumber') {
            // Si la fracción comienza con 0 (ej. 05) preservamos ceros usando modo dígitos
            if (strlen($fraction) > 0 && $fraction[0] === '0') {
                $digits = preg_split('//u', $fraction, -1, PREG_SPLIT_NO_EMPTY);
                $words = array_map(function ($d) use ($digitMap) {
                    return $digitMap[$d] ?? $d;
                }, $digits);
                return ucfirst($sign . $intWords . ' punto ' . implode(' ', $words));
            } else {
                // Convertir la fracción como número (25 -> veinticinco)
                $fractionNumber = (int) $fraction;
                $fractionWords = $fmt->format($fractionNumber);
                return ucfirst($sign . $intWords . ' punto ' . trim($fractionWords));
            }
        }

        if ($modo === 'currency') {
            // Usar siempre 2 dígitos para centavos
            $centavos = substr(str_pad($origFraction, 2, '0'), 0, 2);
            return ucfirst($sign . $intWords . ' con ' . $centavos . '/100');
        }

        // Modo no reconocido -> devolver número tal cual
        return ucfirst($sign . $intWords . ' punto ' . $fraction);
    }
    public function guardarContraseña()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $realizador = $_POST['realizador'];
            $contraseña = htmlspecialchars($_POST['contraseña']);
            $fecha_registro = $_POST['fecha_registro'];
            $fecha_pago = $_POST['fecha_pago'];
            $proveedor_recibimiento = intval($_POST['proveedor_recibimiento']);
            $area = intval($_POST['area']);

            $factura = $_POST['factura'];
            $bien = $_POST['bien'];
            $valor = $_POST['valor'];
            $errores = [];

            if (empty($realizador) || empty($factura) || empty($bien) || empty($valor)) {
                echo json_encode(["status" => false, "message" => "Datos incompletos."]);
                exit;
            }
            // Validar facturas ya registradas con el mismo proveedor
            foreach ($factura as $index => $facturaItem) {
                $facturaItem = trim($facturaItem);
                $existe = $this->model->existeFacturaConProveedor($facturaItem, $proveedor_recibimiento);
                if ($existe) {
                    $errores[] = "La factura {$facturaItem} ya está registrada con este proveedor.";
                }
            }

            if (count($factura) !== count(array_unique($factura))) {
                $errores[] = "Hay facturas duplicadas en la solicitud.";
            }

            if (!empty($errores)) {
                echo json_encode([
                    "status" => false,
                    "message" => $errores,
                    "errors" => $errores
                ]);
                exit;
            }

            // Crear la contraseña
            $contraseñas = $this->model->crearContraseña(
                $realizador,
                $contraseña,
                $fecha_registro,
                $fecha_pago,
                $proveedor_recibimiento,
                $area
            );

            if (!$contraseñas) {
                echo json_encode(["status" => false, "message" => "Error al crear contraseña."]);
                exit;
            }
            // Insertar detalles
            foreach ($factura as $index => $facturaItem) {
                $valorBien = isset($bien[$index]) ? htmlspecialchars($bien[$index]) : null;
                $valorValor = isset($valor[$index]) ? floatval($valor[$index]) : null;
                $estado = 'Pendiente';

                if (!$facturaItem || !$valorBien || !$valorValor) {
                    $errores[] = "Error: Datos incompletos para la factura {$facturaItem}.";
                    continue;
                }

                if (!$this->model->insertDetalleSolicitud($facturaItem, $contraseña, $valorBien, $valorValor, $estado)) {
                    $errores[] = "Error al insertar la factura {$facturaItem}.";
                }
            }

            if (!empty($errores)) {
                echo json_encode([
                    "status" => false,
                    "message" => $errores,
                    "errors" => $errores
                ]);
                exit;
            }

            log_Actividad(
                $_SESSION['PersonalData']['no_empleado'],
                $_SESSION['PersonalData']['nombre_completo'],
                "Contraseñas",
                "Registro de contraseña"
            );

            $categoria = "Contraseña";
            $arrData = [
                'contraseña' => $this->model->getContraseña($contraseña),
                'correos' => $this->model->getCorreosArea($area, $estado, $categoria)
            ];

            $sendcorreoEmpleado = 'Views/Template/Email/createContraseña.php';
            try {
                ob_start();
                require $sendcorreoEmpleado;
                ob_end_clean();
            } catch (Exception $e) {
                echo json_encode([
                    "status" => false,
                    "message" => "Error al enviar el correo: " . $e->getMessage()
                ]);
                exit;
            }

            echo json_encode([
                "status" => true,
                "message" => "Contraseña registrada."
            ]);
        }
    }

    public function setDetalle()
    {
        if ($_POST) {
            $no_factura = $_POST['no_factura'];
            $contraseña = $_POST['contraseña'];
            $bien_servicio = $_POST['bien_servicio'];
            $valor_documento = $_POST['valor_documento'];
            $estado = "Validado";

            if (empty($no_factura) || empty($bien_servicio) || empty($valor_documento)) {
                $arrResponse = ["status" => false, "msg" => "Todos los campos son obligatorios"];
            } else {
                $requestInsert = $this->model->insertDetalleSolicitud(
                    $no_factura,
                    $contraseña,
                    $bien_servicio,
                    $valor_documento,
                    $estado
                );

                if ($requestInsert > 0) {
                    $arrResponse = ["status" => true, "msg" => "Detalle agregado correctamente"];
                } else {
                    $arrResponse = ["status" => false, "msg" => "Error al insertar el detalle"];
                }
            }

            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function updateFactura()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_factura = intval($_POST['edit_id']);
            $bien = $_POST['edit_servicio'];
            $valor = $_POST['edit_documento'];
            $estado = $_POST['edit_estado'];
            $errores = [];

            // Crear la contraseña
            $this->model->updateFactura(
                $id_factura,
                $bien,
                $valor,
                $estado
            );

            if (!empty($errores)) {
                echo json_encode([
                    "status" => false,
                    "message" => $errores,
                    "errors" => $errores
                ]);
                exit;
            }

            echo json_encode(["status" => true, "message" => "Contraseña registrada correctamente."]);
        }
    }

    public function actualizarContraseña()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $contraseña = $_POST['contraseña']; // ID o código de la contraseña
            $realizador = $_POST['realizador'];
            $fecha_registro = $_POST['fecha_registro'];
            $fecha_pago = $_POST['fecha_pago'];
            $proveedor_recibimiento = intval($_POST['proveedor_recibimiento']);
            $area = intval($_POST['area']);

            $facturas = $_POST['factura']; // Array de números de factura
            $bienes = $_POST['bien'];      // Array de bienes
            $valores = $_POST['valor'];    // Array de valores

            $errores = [];

            // Validación básica
            if (empty($contraseña)) {
                echo json_encode(["status" => false, "message" => "Datos incompletos."]);
                exit;
            }

            // Validar facturas duplicadas en el formulario
            if (count($facturas) !== count(array_unique($facturas))) {
                echo json_encode(["status" => false, "message" => "Hay facturas duplicadas en el formulario."]);
                exit;
            }

            // Validar si alguna factura ya está registrada con el mismo proveedor (evitar duplicados globales)
            foreach ($facturas as $facturaItem) {
                $existe = $this->model->existeFacturaConProveedor($facturaItem, $proveedor_recibimiento, $contraseña);
                if ($existe) {
                    $errores[] = "La factura {$facturaItem} ya está registrada con este proveedor.";
                }
            }

            if (!empty($errores)) {
                echo json_encode(["status" => false, "message" => $errores]);
                exit;
            }

            // Actualizar datos generales de la contraseña
            $updateContraseña = $this->model->updateContraseña(
                $contraseña,
                $realizador,
                $fecha_registro,
                $fecha_pago,
                $proveedor_recibimiento,
                $area
            );

            if (!$updateContraseña) {
                echo json_encode(["status" => false, "message" => "Error al actualizar los datos principales."]);
                exit;
            }

            // Obtener facturas existentes en la BD para esta contraseña
            $detallesExistentes = $this->model->getDetallesPorContraseña($contraseña);
            $facturasExistentes = array_column($detallesExistentes, 'no_factura');

            // Manejar facturas nuevas o actualizadas
            foreach ($facturas as $index => $facturaItem) {
                $bienItem = $bienes[$index] ?? null;
                $valorItem = $valores[$index] ?? null;
                $estado = 'Pendiente';

                if (!$facturaItem || !$bienItem || !$valorItem) {
                    $errores[] = "Datos incompletos en la factura {$facturaItem}.";
                    continue;
                }

                if (in_array($facturaItem, $facturasExistentes)) {
                    // Si ya existe, actualizar
                    if (!$this->model->updateDetalleFactura($contraseña, $facturaItem, $bienItem, $valorItem)) {
                        $errores[] = "Error al actualizar la factura {$facturaItem}.";
                    }
                } else {
                    // Si no existe, insertar
                    if (!$this->model->insertDetalleSolicitud($facturaItem, $contraseña, $bienItem, $valorItem, $estado)) {
                        $errores[] = "Error al insertar la factura {$facturaItem}.";
                    }
                }
            }

            if (!empty($errores)) {
                echo json_encode(["status" => false, "message" => $errores, "errors" => $errores]);
                exit;
            }

            log_Actividad(
                $_SESSION['PersonalData']['no_empleado'],
                $_SESSION['PersonalData']['nombre_completo'],
                "Contraseñas",
                "Actualizacion de contraseña: " . $contraseña
            );


            $arrData = [
                'contraseña' => $this->model->getContraseña($contraseña),
            ];

            $sendcorreoEmpleado = 'Views/Template/Email/sendContraseña.php';
            try {
                ob_start();
                require $sendcorreoEmpleado;
                ob_end_clean();
            } catch (Exception $e) {
                echo json_encode([
                    "status" => false,
                    "message" => "Error al enviar el correo: " . $e->getMessage()
                ]);
                exit;
            }


            echo json_encode(["status" => true, "message" => "Contraseña actualizada correctamente."]);
        }
    }

    public function eliminarFactura()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $contraseña = $_POST['contraseña'];
            $factura = $_POST['factura'];

            if (empty($contraseña) || empty($factura)) {
                echo json_encode(["status" => false, "message" => "Datos incompletos."]);
                exit;
            }

            // Llamar al modelo para eliminar el detalle
            $result = $this->model->deleteDetallesFactura($contraseña, $factura);

            log_Actividad(
                $_SESSION['PersonalData']['no_empleado'],
                $_SESSION['PersonalData']['nombre_completo'],
                "Contraseñas",
                "Se elimino la factura" . $factura
            );

            if ($result) {
                echo json_encode(["status" => true, "message" => "Factura eliminada correctamente."]);
            } else {
                echo json_encode(["status" => false, "message" => "Error al eliminar la factura."]);
            }
        }
    }

    public function corregirContraseña()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $contraseña = $_POST['contraseña'];
            $area = $_POST['area'];
            $correciones = $_POST['correciones'];
            $estado = $_POST['respuesta'];
            $errores = [];

            if (empty($contraseña) || empty($correciones)) {
                echo json_encode(["status" => false, "msg" => "Problemas al obtener datos."]);
                exit;
            }

            $facturas = $this->model->getFacturasbyContra($contraseña);

            foreach ($facturas as $factura) {
                if ($factura['estado'] == 'Pendiente') {
                    echo json_encode(["status" => false, "msg" => "Tienes facturas pendientes, favor de revisarlas."]);
                    exit;
                }
            }

            // Crear la contraseña
            $this->model->estadoContraseña(
                $contraseña,
                $correciones,
                $estado
            );

            if (!empty($errores)) {
                echo json_encode([
                    "status" => false,
                    "message" => $errores,
                    "errors" => $errores
                ]);
                exit;
            }

            log_Actividad(
                $_SESSION['PersonalData']['no_empleado'],
                $_SESSION['PersonalData']['nombre_completo'],
                "Contraseñas",
                "Se mando a corregir la contraseña: " . $contraseña
            );

            $categoria = "Contraseña";
            $arrData = [
                'contraseña' => $this->model->getContraseña($contraseña),
                'correos' => $this->model->getCorreosArea($area, $estado, $categoria)
            ];

            $sendcorreoEmpleado = 'Views/Template/Email/sendContraseña.php';
            try {
                ob_start();
                require $sendcorreoEmpleado;
                ob_end_clean();
            } catch (Exception $e) {
                echo json_encode([
                    "status" => false,
                    "message" => "Error al enviar el correo: " . $e->getMessage()
                ]);
                exit;
            }

            echo json_encode([
                "status" => true,
                "message" => "Contraseña Corregida y correo enviado correctamente."
            ]);
        }
    }

    public function correccionContraseña()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $contraseña = $_POST['contraseña'] ?? null;
            $fecha_pago = $_POST['fecha_pago'] ?? null;
            $proveedor_recibimiento = intval($_POST['proveedor'] ?? 0);
            $area = $_POST['area'] ?? null;
            $facturas = $_POST['factura'] ?? [];
            $bienes = $_POST['bien'] ?? [];
            $valores = $_POST['valor'] ?? [];

            $errores = [];

            if (empty($contraseña)) {
                echo json_encode(["status" => false, "message" => "Datos incompletos."]);
                exit;
            }

            if (!empty($facturas)) {
                // Validar duplicados en el formulario
                if (count($facturas) !== count(array_unique($facturas))) {
                    echo json_encode(["status" => false, "message" => "Hay facturas duplicadas en el formulario."]);
                    exit;
                }

                // Validar facturas duplicadas con el mismo proveedor en BD
                foreach ($facturas as $facturaItem) {
                    $existe = $this->model->existeFacturaConProveedor($facturaItem, $proveedor_recibimiento, $contraseña);
                    if ($existe) {
                        $errores[] = "La factura {$facturaItem} ya está registrada con este proveedor.";
                    }
                }

                if (!empty($errores)) {
                    echo json_encode(["status" => false, "message" => $errores]);
                    exit;
                }
            }

            $updateContraseña = $this->model->correccionContraseña(
                $contraseña,
                $fecha_pago,
                $proveedor_recibimiento
            );

            if (!$updateContraseña) {
                echo json_encode(["status" => false, "message" => "Error al actualizar los datos principales."]);
                exit;
            }

            if (!empty($facturas)) {
                $detallesExistentes = $this->model->getDetallesPorContraseña($contraseña);
                $facturasExistentes = array_column($detallesExistentes, 'no_factura');

                foreach ($facturas as $index => $facturaItem) {
                    $bienItem = $bienes[$index] ?? null;
                    $valorItem = $valores[$index] ?? null;
                    $estado = 'Pendiente';

                    if (!$facturaItem || !$bienItem || !$valorItem) {
                        $errores[] = "Datos incompletos en la factura {$facturaItem}.";
                        continue;
                    }

                    if (in_array($facturaItem, $facturasExistentes)) {
                        // Actualizar factura existente
                        if (!$this->model->correccionDetallesFactura($contraseña, $facturaItem, $bienItem, $valorItem)) {
                            $errores[] = "Error al actualizar la factura {$facturaItem}.";
                        }
                    } else {
                        // Insertar nueva factura
                        if (!$this->model->insertDetalleSolicitud($facturaItem, $contraseña, $bienItem, $valorItem, $estado)) {
                            $errores[] = "Error al insertar la factura {$facturaItem}.";
                        }
                    }
                }
            }

            if (!empty($errores)) {
                echo json_encode(["status" => false, "message" => $errores]);
                exit;
            }

            log_Actividad(
                $_SESSION['PersonalData']['no_empleado'],
                $_SESSION['PersonalData']['nombre_completo'],
                "Contraseñas",
                "Se corrigio la contraseña: " . $contraseña
            );

            $datos = $this->model->getContraseña($contraseña);
            $id_area = $datos['id_area'];
            $categoria = "Contraseña";
            $respuesta = "Corregido";
            $arrData = [
                'contraseña' => $this->model->getContraseña($contraseña),
                'correos' => $this->model->getCorreosArea($id_area, $respuesta, $categoria)
            ];

            $sendcorreoEmpleado = 'Views/Template/Email/sendContraseña.php';
            try {
                ob_start();
                require $sendcorreoEmpleado;
                ob_end_clean();
            } catch (Exception $e) {
                echo json_encode([
                    "status" => false,
                    "message" => "Error al enviar el correo: " . $e->getMessage()
                ]);
                exit;
            }

            echo json_encode([
                "status" => true,
                "message" => "Contraseña Corregida y correo enviado correctamente."
            ]);
        }
    }

    public function validacionArea()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $contraseña = $_POST['contraseña'];
            $area = $_POST['area'];
            $area_user = $_POST['area_user'];
            $estado = $_POST['respuesta'];
            $anticipo = $_POST['anticipo'] ?? null;
            $errores = [];

            if (empty($contraseña) || empty($estado)) {
                echo json_encode(["status" => false, "msg" => "Problemas al obtener datos."]);
                exit;
            }
            $facturas = $this->model->getFacturasbyContra($contraseña);

            foreach ($facturas as $factura) {
                if ($factura['estado'] == 'Pendiente') {
                    echo json_encode(["status" => false, "msg" => "Tienes facturas pendientes, favor de revisarlas."]);
                    exit;
                }
            }

            // Crear la contraseña
            $this->model->validacionArea(
                $contraseña,
                $anticipo,
                $area_user,
                $estado
            );

            if (!empty($errores)) {
                echo json_encode([
                    "status" => false,
                    "message" => $errores,
                    "errors" => $errores
                ]);
                exit;
            }

            log_Actividad(
                $_SESSION['PersonalData']['no_empleado'],
                $_SESSION['PersonalData']['nombre_completo'],
                "Contraseñas",
                "Area valido la contraseña: " . $contraseña
            );

            $categoria = "Contraseña";
            $arrData = [
                'contraseña' => $this->model->getContraseña($contraseña),
                'correos' => $this->model->getCorreosArea($area, $estado, $categoria)
            ];

            $sendcorreoEmpleado = 'Views/Template/Email/sendContraseña.php';
            try {
                ob_start();
                require $sendcorreoEmpleado;
                ob_end_clean();
            } catch (Exception $e) {
                echo json_encode([
                    "status" => false,
                    "message" => "Error al enviar el correo: " . $e->getMessage()
                ]);
                exit;
            }


            echo json_encode([
                "status" => true,
                "message" => "Contraseña Guardada."
            ]);

        }
    }

    public function validacionConta()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $contraseña = $_POST['contraseña'];
            $area = $_POST['area'];
            $conta_user = $_POST['conta_user'];
            $estado = $_POST['respuesta'];
            $errores = [];
            if (empty($contraseña) || empty($estado)) {
                echo json_encode(["status" => false, "msg" => "Problemas al obtener datos."]);
                exit;
            }

            $this->model->validacionConta(
                $contraseña,
                $conta_user,
                $estado
            );

            if (!empty($errores)) {
                echo json_encode([
                    "status" => false,
                    "message" => $errores,
                    "errors" => $errores
                ]);
                exit;
            }

            log_Actividad(
                $_SESSION['PersonalData']['no_empleado'],
                $_SESSION['PersonalData']['nombre_completo'],
                "Contraseñas",
                "Contabilidad valido la contraseña: " . $contraseña
            );

            $categoria = "Contraseña";
            $arrData = [
                'contraseña' => $this->model->getContraseña($contraseña),
                'correos' => $this->model->getCorreosArea($area, $estado, $categoria)
            ];

            $sendcorreoEmpleado = 'Views/Template/Email/sendContraseña.php';
            try {
                ob_start();
                require $sendcorreoEmpleado;
                ob_end_clean();
            } catch (Exception $e) {
                echo json_encode([
                    "status" => false,
                    "message" => "Error al enviar el correo: " . $e->getMessage()
                ]);
                exit;
            }

            if ($estado == 'Descartado') {
                echo json_encode([
                    "status" => true,
                    "message" => "Contraseña descartada y correo enviado correctamente."
                ]);
            } else {
                echo json_encode([
                    "status" => true,
                    "message" => "Contraseña validado y correo enviado correctamente."
                ]);

            }

        }
    }
    public function solicitudFondos()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $contraseña = $_POST['contraseña'];
            $area = $_POST['area'];
            $categoria = 'N/A';
            $solicitante = $_POST['solicitante'];
            $estado = "Validado";
            $errores = [];

            if (empty($contraseña) || empty($area)) {
                echo json_encode(["status" => false, "msg" => "Problemas al obtener datos."]);
                exit;
            }

            // Crear la contraseña
            $this->model->solicitudFondoVehiculos(
                $contraseña,
                $area,
                $categoria
            );

            $this->model->descartarFacturas($contraseña);

            $this->model->validacionContraseñaSoli(
                $contraseña,
                $solicitante,
                $estado
            );

            if (!empty($errores)) {
                echo json_encode([
                    "status" => false,
                    "message" => $errores,
                    "errors" => $errores
                ]);
                exit;
            }

            log_Actividad(
                $_SESSION['PersonalData']['no_empleado'],
                $_SESSION['PersonalData']['nombre_completo'],
                "Solicitud Fondos",
                "Incio una solicitud de fondos de la contraseña: " . $contraseña
            );

            $categoria = "Contraseña";
            $arrData = [
                'contraseña' => $this->model->getContraseña($contraseña),
                'correos' => $this->model->getCorreosArea($area, $estado, $categoria)
            ];

            $sendcorreoEmpleado = 'Views/Template/Email/sendContraseña.php';
            try {
                ob_start();
                require $sendcorreoEmpleado;
                ob_end_clean();
            } catch (Exception $e) {
                echo json_encode([
                    "status" => false,
                    "message" => "Error al enviar el correo: " . $e->getMessage()
                ]);
                exit;
            }
            echo json_encode([
                "status" => true,
                "message" => "Solicitud de Fondos Realizada y correo enviado correctamente."
            ]);
        }
    }
    public function descartarContraseña()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $contraseña = trim($_POST['contraseña'] ?? '');
            $area = $_POST['area'];
            $correciones = null;
            $estado = "Descartado";

            if (empty($contraseña)) {
                echo json_encode([
                    "status" => false,
                    "msg" => "Problemas al obtener datos."
                ]);
                exit;
            }

            try {
                $ok1 = $this->model->estadoContraseña($contraseña, $correciones, $estado);
                $ok2 = $this->model->descartarDetalles($contraseña, $estado);

                if ($ok1 && $ok2) {

                    $categoria = "Contraseña";
                    $arrData = [
                        'contraseña' => $this->model->getContraseña($contraseña),
                        'correos' => $this->model->getCorreosArea($area, $estado, $categoria)
                    ];

                    $sendcorreoEmpleado = 'Views/Template/Email/sendContraseña.php';
                    try {
                        ob_start();
                        require $sendcorreoEmpleado;
                        ob_end_clean();
                    } catch (Exception $e) {
                        echo json_encode([
                            "status" => false,
                            "message" => "Error al enviar el correo: " . $e->getMessage()
                        ]);
                        exit;
                    }

                    log_Actividad(
                        $_SESSION['PersonalData']['no_empleado'],
                        $_SESSION['PersonalData']['nombre_completo'],
                        "Contraseñas",
                        "Contraseña Descartada: " . $contraseña
                    );

                    echo json_encode([
                        "status" => true,
                        "message" => "Contraseña descartada correctamente."
                    ]);
                } else {
                    echo json_encode([
                        "status" => false,
                        "msg" => "Error al descartar la contraseña."
                    ]);
                }
            } catch (Exception $e) {
                echo json_encode([
                    "status" => false,
                    "msg" => "Ocurrió un error inesperado.",
                    "error" => $e->getMessage()
                ]);
            }

        }
    }

}
