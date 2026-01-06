<?php
session_start();

class SolicitudFondos extends Controllers
{
    public function __construct()
    {
        parent::__construct();
        if (empty($_SESSION['login'])) {
            header('Location: ' . base_url() . '/login');
            die();
        }
    }

    public function SolicitudFondos()
    {
        $data['page_id'] = DASHBOARD;
        $data['page_tag'] = "Solicitud_Fondos";
        $data['page_title'] = "Solicitud_Fondos";
        $data['page_name'] = "Solicitud_Fondos";
        $data['page_functions_js'] = "functions_solicitud_fondos.js";
        $this->views->getView($this, "Solicitud_Fondos", $data);
    }

    public function getSolucitudesFondos()
    {
        $arrData = $this->model->selectSolicitudFondos();

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
        $data['page_tag'] = "Solicitud_Fondos";
        $data['page_title'] = "Solicitud_Fondos";
        $data['page_name'] = "Solicitud_Fondos";
        $data['page_functions_js'] = "functions_solicitud_fondos_conta.js";
        $this->views->getView($this, "Contabilidad", $data);
    }


    public function getSolucitudesFondosConta()
    {
        $arrData = $this->model->selectSolicitudFondosConta();

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron registros previos.');
        } else {
            $arrResponse = array('status' => true, 'data' => $arrData);
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }


    public function Vehiculos()
    {
        $data['page_id'] = DASHBOARD;
        $data['page_tag'] = "Solicitud_Fondos";
        $data['page_title'] = "Solicitud_Fondos";
        $data['page_name'] = "Solicitud_Fondos";
        $data['page_functions_js'] = "functions_solicitud_fondos_vehiculos.js";
        $this->views->getView($this, "Vehiculos", $data);
    }


    public function getSolucitudesFondosVehiculos()
    {
        $arrData = $this->model->selectSolicitudFondosVehiculos();

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron registros previos.');
        } else {
            $arrResponse = array('status' => true, 'data' => $arrData);
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function Revision($contraseña)
    {
        $facturas = $this->model->getContraseña($contraseña);
        $id_anticipo = $facturas["anticipo"];
        $anticipo = $this->model->getAnticipoInfo($id_anticipo);

        $data['facturas'] = $facturas;
        $data['anticipoinfo'] = $anticipo;
        $data['page_id'] = 'Revision';
        $data['page_tag'] = "Revision";
        $data['page_title'] = "Revision";
        $data['page_name'] = "Revision";
        $data['page_functions_js'] = "functions_revision.js";
        $this->views->getView($this, "Revision", $data);
    }

    public function Anticipo($id_solicitud)
    {
        $facturas = $this->model->getSolicitud($id_solicitud);

        $data['facturas'] = $facturas;
        $data['page_id'] = 'Anticipo';
        $data['page_tag'] = "Anticipo";
        $data['page_title'] = "Anticipo";
        $data['page_name'] = "Anticipo";
        $data['page_functions_js'] = "functions_revision_sin.js";
        $this->views->getView($this, "Anticipo", $data);
    }


    public function Servicios($contraseña)
    {
        $facturas = $this->model->getContraseña($contraseña);
        $id_anticipo = $facturas["anticipo"];
        $anticipo = $this->model->getAnticipoInfo($id_anticipo);

        $data['facturas'] = $facturas;
        $data['anticipoinfo'] = $anticipo;
        $data['page_id'] = 'Revision';
        $data['page_tag'] = "Revision";
        $data['page_title'] = "Revision";
        $data['page_name'] = "Revision";
        $data['page_functions_js'] = "functions_servicios.js";
        $this->views->getView($this, "Servicios", $data);
    }

    public function Rentas($contraseña)
    {
        $facturas = $this->model->getContraseña($contraseña);
        $id_anticipo = $facturas["anticipo"];
        $anticipo = $this->model->getAnticipoInfo($id_anticipo);

        $data['facturas'] = $facturas;
        $data['anticipoinfo'] = $anticipo;
        $data['page_id'] = 'Revision';
        $data['page_tag'] = "Revision";
        $data['page_title'] = "Revision";
        $data['page_name'] = "Revision";
        $data['page_functions_js'] = "functions_rentas.js";
        $this->views->getView($this, "Rentas", $data);
    }

    public function Combustible($contraseña)
    {
        $facturas = $this->model->getCombustible($contraseña);

        $data['facturas'] = $facturas;
        $data['page_id'] = 'Revision';
        $data['page_tag'] = "Revision";
        $data['page_title'] = "Revision";
        $data['page_name'] = "Revision";
        $data['page_functions_js'] = "functions_combustible.js";
        $this->views->getView($this, "Combustible", $data);
    }

    public function getFacturas($contraseña)
    {
        $arrData = $this->model->getFacturasbyContra($contraseña);
        error_log(print_r($arrData, true));

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron registros previos.');
        } else {
            $arrResponse = array('status' => true, 'data' => $arrData);
        }

        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getCombustible($contraseña)
    {
        $arrData = $this->model->getCombustiblebyContra($contraseña);

        if (empty($arrData)) {
            echo json_encode([
                'status' => false,
                'msg' => 'No se encontraron registros previos.'
            ], JSON_UNESCAPED_UNICODE);
            die();
        }

        // === Procesar datos ===
        foreach ($arrData as &$row) {

            // 🔹 Formato monetario (Quetzales)
            $row['transferencia'] = 'Q ' . number_format((float) $row['transferencia'], 2, '.', ',');
            $row['saldo_disponible'] = 'Q ' . number_format((float) $row['saldo_disponible'], 2, '.', ',');

            // 🔹 Formato de rango de fechas
            $row['rango_fechas'] = $this->formatearRangoFechas(
                $row['fecha_inicio'],
                $row['fecha_final']
            );

            // Opcional: eliminar fechas individuales si ya no las necesitas
            unset($row['fecha_inicio'], $row['fecha_final']);
        }

        echo json_encode([
            'status' => true,
            'data' => $arrData
        ], JSON_UNESCAPED_UNICODE);

        die();
    }


    public function getFacturasSolicitud($id_solicitud)
    {
        $arrData = $this->model->getFacturasbySoli($id_solicitud);
        error_log(print_r($arrData, true));

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron registros previos.');
        } else {
            $arrResponse = array('status' => true, 'data' => $arrData);
        }

        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function updateDetalle()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_detalle = $_POST['edit_id'];
            $cod_ax = $_POST['edit_codax'];
            $base = $_POST['edit_base'];
            $iva_base = $_POST['edit_base_iva'];
            $iva = $_POST['input_iva'] ?? null;
            $isr = $_POST['input_isr'] ?? null;
            $reten_iva = $_POST['edit_reten_iva'];
            $reten_isr = $_POST['edit_reten_isr'];
            $total_iquido = $_POST['edit_total'];
            $observacion = $_POST['edit_observacion'];

            if (empty($id_detalle)) {
                echo json_encode(["status" => false, "message" => "Datos incompletos."]);
                exit;
            }

            $result = $this->model->UpdateDetalle($id_detalle, $cod_ax, $base, $iva_base, $iva, $isr, $reten_iva, $reten_isr, $total_iquido, $observacion);
            if ($result) {
                echo json_encode(["status" => true, "message" => "Factura actualizada correctamente."]);
            } else {
                echo json_encode(["status" => false, "message" => "Error al eliminar la factura."]);
            }
        }
    }

    public function updateDetalleServicio()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(["status" => false, "message" => "Método no permitido."]);
            exit;
        }

        $id_detalle = $_POST['edit_id'] ?? null;
        $cod_ax = $_POST['edit_codax'] ?? null;
        $observacion = $_POST['edit_observacion'] ?? null;

        $servicio = $_POST['servicio'] ?? null;
        $placa = $_POST['placa'] ?? null;
        $kilometraje = $_POST['kilometraje'] ?? null;
        $estado = $_POST['estado'] ?? null;
        $tipo_servicio = $_POST['tipo_servicio'] ?? null;
        $usuario = $_POST['usuario'] ?? null;
        $ln = $_POST['ln'] ?? null;
        $tipo = $_POST['tipo'] ?? null;
        $codigo_ax = $_POST['codigo_ax'] ?? null;
        $tipo_mantenimiento = $_POST['tipo_mantenimiento'] ?? null;

        // MATERIALES (ARREGLO)
        $materiales = $_POST['materiales'] ?? [];
        $repuestos = "Repuestos";

        if (empty($id_detalle)) {
            echo json_encode(["status" => false, "msg" => "Faltan datos obligatorios (ID detalle)."]);
            exit;
        }

        // Actualiza detalle
        $resultDetalle = $this->model->UpdateDetalleDos($id_detalle, $cod_ax, $observacion);

        if (!$resultDetalle) {
            echo json_encode(["status" => false, "msg" => "Error al actualizar el detalle del servicio."]);
            exit;
        }

        // Insertar o actualizar servicio
        if ($servicio == 0) {
            $servicio = $this->model->insertServicio(
                $id_detalle,
                $placa,
                $repuestos,
                $kilometraje,
                $estado,
                $tipo_servicio,
                $ln,
                $usuario,
                $tipo,
                $codigo_ax,
                $tipo_mantenimiento
            );
        } else {
            $this->model->updateServicio(
                $servicio,
                $id_detalle,
                $placa,
                $kilometraje,
                $estado,
                $tipo_servicio,
                $ln,
                $usuario,
                $tipo,
                $codigo_ax,
                $tipo_mantenimiento
            );
        }

        if (!$servicio) {
            echo json_encode(["status" => false, "message" => "El servicio no pudo ser registrado o actualizado."]);
            exit;
        }

        $materialesBD = $this->model->getMaterialesByServicio($servicio);

        $materialesBD_text = array_column($materialesBD, 'material');

        $paraEliminar = array_diff($materialesBD_text, $materiales);

        $paraInsertar = array_diff($materiales, $materialesBD_text);

        foreach ($paraEliminar as $m) {
            $this->model->deleteMaterial($servicio, $m);
        }

        foreach ($paraInsertar as $m) {
            if (trim($m) !== "") {
                $this->model->insertMaterial($servicio, $m);
            }
        }

        echo json_encode(["status" => true, "message" => "Servicio actualizado correctamente."]);
        exit;
    }

    public function updateDetalleRenta()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(["status" => false, "message" => "Método no permitido."]);
            exit;
        }

        $id_detalle = $_POST['edit_id'] ?? null;
        $cod_ax = $_POST['edit_codax'] ?? null;
        $observacion = $_POST['edit_observacion'] ?? null;
        $arrendamientos = $_POST['arrendamientos'] ?? [];
        $contraseña = $_POST['edit_contraseña'] ?? null;

        if (empty($id_detalle)) {
            echo json_encode([
                "status" => false,
                "msg" => "ID del servicio no recibido."
            ]);
            exit;
        }


        if (empty($contraseña)) {
            echo json_encode([
                "status" => false,
                "msg" => "Contraseña no recibida."
            ]);
            exit;
        }


        /* VALIDAR CANTIDAD DE FACTURAS ÚNICAS */
        $facturas = $this->model->getFacturasbyContra($contraseña);

        /* obtener facturas únicas */
        $facturasUnicas = array_unique(array_column($facturas, 'no_factura'));

        if (count($facturasUnicas) > 1 && count($arrendamientos) > 1) {
            echo json_encode([
                "status" => false,
                "msg" => "Cuando la solicitud tiene más de una factura, solo se permite registrar un vehículo."
            ]);
            exit;
        }


        $placasNormalizadas = array_map(function ($p) {
            return strtoupper(trim($p));
        }, $arrendamientos);

        $placasNormalizadas = array_filter($placasNormalizadas);

        /* CONTAR OCURRENCIAS */
        $conteo = array_count_values($placasNormalizadas);

        /* OBTENER SOLO LAS DUPLICADAS */
        $duplicadas = array_keys(
            array_filter($conteo, function ($cantidad) {
                return $cantidad > 1;
            })
        );

        if (!empty($duplicadas)) {
            echo json_encode([
                "status" => false,
                "msg" => "Las siguientes placas están duplicadas: " . implode(", ", $duplicadas)
            ]);
            exit;
        }

        $resultDetalle = $this->model->UpdateDetalleDos(
            $id_detalle,
            $cod_ax,
            $observacion
        );

        if (!$resultDetalle) {
            echo json_encode([
                "status" => false,
                "message" => "Error al actualizar el detalle."
            ]);
            exit;
        }

        $rentasBD = $this->model->getPlacasByServicio($id_detalle);
        $placasBD = array_column($rentasBD, 'placa');

        // Normalizar datos
        $arrendamientos = array_map('trim', $arrendamientos);
        $arrendamientos = array_filter($arrendamientos);

        $paraEliminar = array_diff($placasBD, $arrendamientos);
        $paraInsertar = array_diff($arrendamientos, $placasBD);

        foreach ($paraEliminar as $placa) {
            $this->model->deleteRenta($id_detalle, $placa);
        }

        foreach ($paraInsertar as $placa) {
            $this->model->insertRenta($id_detalle, $placa);
        }

        echo json_encode([
            "status" => true,
            "message" => "Rentas actualizadas correctamente."
        ]);
        exit;
    }


    public function guardarSolicitudFondos()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // 🔹 Datos del formulario
            $realizador = trim($_POST['realizador'] ?? '');
            $fecha_registro = $_POST['fecha_registro'] ?? date('Y-m-d');
            $fecha_pago = $_POST['fecha_pago'] ?? null;
            $proveedor = intval($_POST['proveedor'] ?? 0);
            $area = intval($_POST['area'] ?? 0);
            $tipo = $_POST['tipo'] ?? [];
            $bien = $_POST['bien'] ?? [];
            $valor = $_POST['valor'] ?? [];
            $categoria = 'Anticipo';
            $estado_solicitud = 'Pendiente';
            $errores = [];

            // 🔹 Validaciones básicas
            if (empty($realizador) || empty($fecha_pago) || $area <= 0 || $proveedor <= 0) {
                echo json_encode(["status" => false, "message" => "Datos incompletos en el formulario."]);
                exit;
            }

            if (empty($bien) || empty($valor)) {
                echo json_encode(["status" => false, "message" => "Debe agregar al menos un detalle."]);
                exit;
            }

            $ultimoCorrelativo = $this->model->getUltimoCorrelativo(); // <-- función que crearás en el modelo

            if ($ultimoCorrelativo) {
                $numero = intval(substr($ultimoCorrelativo, 4));
                $nuevoNumero = str_pad($numero + 1, 5, "0", STR_PAD_LEFT);
            } else {
                // Si no hay registros previos
                $nuevoNumero = "00001";
            }

            $contraseña = "ANT-" . $nuevoNumero;

            $solicitudCreada = $this->model->solicitudFondoVehiculosNueva(
                $realizador,
                $area,
                $proveedor,
                $categoria,
                $fecha_pago,
                $estado_solicitud,
                $contraseña
            );

            if (!$solicitudCreada) {
                echo json_encode(["status" => false, "message" => "Error al crear la solicitud de fondos."]);
                exit;
            }

            $idSolicitud = $this->model->lastInsertId();

            foreach ($bien as $index => $valorBien) {
                $valorTipo = $tipo[$index] ?? 'Anticipo';
                $valorValor = floatval($valor[$index] ?? 0);
                $estadoDetalle = 'Anticipo';

                if (!$valorBien || !$valorValor) {
                    $errores[] = "Error: datos incompletos en el detalle #" . ($index + 1);
                    continue;
                }

                $insertDetalle = $this->model->insertDetalleSolicitudFondosNueva(
                    $idSolicitud,
                    $valorTipo,
                    $valorBien,
                    $valorValor,
                    $estadoDetalle,
                    $fecha_registro
                );

                if (!$insertDetalle) {
                    $errores[] = "Error al insertar el detalle #" . ($index + 1);
                }
            }

            if (!empty($errores)) {
                echo json_encode([
                    "status" => false,
                    "message" => "La solicitud se creó con algunos errores.",
                    "errors" => $errores
                ]);
                exit;
            }

            $base = "Creacion";
            $arrData = [
                'anticipo' => $this->model->getSolisinContra($idSolicitud),
                'correos' => $this->model->getCorreosArea($area, $base, $categoria),
                'respuesta' => $estado_solicitud
            ];

            $sendcorreoEmpleado = 'Views/Template/Email/sendAnticipo.php';
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
                "Solicitud Fondos",
                "Se genero el anticipo: " . $contraseña
            );
            echo json_encode([
                "status" => true,
                "message" => "Anticipo Creado"
            ]);
        }
    }

    public function guardarSolicitudCombustible()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode([
                "status" => false,
                "message" => "Método no permitido."
            ]);
            exit;
        }

        // =========================
        // DATOS DEL FORMULARIO
        // =========================
        $realizador = trim($_POST['realizador'] ?? '');
        $fecha_pago = $_POST['fecha_pago'] ?? null;
        $proveedor = intval($_POST['proveedor'] ?? 0);
        $area = intval($_POST['area'] ?? 0);

        $transferencias = $_POST['transferencia'] ?? [];
        $saldos = $_POST['saldo'] ?? [];
        $fechas_inicio = $_POST['inicio'] ?? [];
        $fechas_final = $_POST['final'] ?? [];

        $categoria = 'Combustible';
        $estado_solicitud = 'Pendiente';

        // =========================
        // VALIDACIONES BÁSICAS
        // =========================
        if (
            empty($realizador) ||
            empty($fecha_pago) ||
            $proveedor <= 0 ||
            $area <= 0
        ) {
            echo json_encode([
                "status" => false,
                "message" => "Datos incompletos en el formulario."
            ]);
            exit;
        }

        // =========================
        // VALIDAR ARRAYS DE DETALLE
        // =========================
        if (
            empty($transferencias) ||
            count($transferencias) !== count($saldos) ||
            count($saldos) !== count($fechas_inicio) ||
            count($fechas_inicio) !== count($fechas_final)
        ) {
            echo json_encode([
                "status" => false,
                "message" => "Los datos de combustible no son válidos o están incompletos."
            ]);
            exit;
        }

        // =========================
        // GENERAR CORRELATIVO
        // =========================
        $ultimoCorrelativo = $this->model->getUltimoCombustible();

        if ($ultimoCorrelativo) {
            $numero = intval(substr($ultimoCorrelativo, 12)); // COMBUSTIBLE-
            $nuevoNumero = str_pad($numero + 1, 5, "0", STR_PAD_LEFT);
        } else {
            $nuevoNumero = "00001";
        }

        $contraseña = "COMBUSTIBLE-" . $nuevoNumero;

        // =========================
        // CREAR SOLICITUD PRINCIPAL
        // =========================
        $solicitudCreada = $this->model->solicitudFondoVehiculosNueva(
            $realizador,
            $area,
            $proveedor,
            $categoria,
            $fecha_pago,
            $estado_solicitud,
            $contraseña
        );

        if (!$solicitudCreada) {
            echo json_encode([
                "status" => false,
                "message" => "Error al crear la solicitud de combustible."
            ]);
            exit;
        }

        // =========================
        // INSERTAR DETALLE COMBUSTIBLE
        // =========================
        foreach ($transferencias as $i => $monto) {

            $insertado = $this->model->insertCombustible(
                $contraseña,
                floatval($monto),
                floatval($saldos[$i]),
                $fechas_inicio[$i],
                $fechas_final[$i]
            );

            if (!$insertado) {
                echo json_encode([
                    "status" => false,
                    "message" => "Error al guardar el detalle de combustible."
                ]);
                exit;
            }
        }

        // =========================
        // LOG DE ACTIVIDAD
        // =========================
        log_Actividad(
            $_SESSION['PersonalData']['no_empleado'],
            $_SESSION['PersonalData']['nombre_completo'],
            "Solicitud Fondos",
            "Se generó la solicitud de combustible: " . $contraseña
        );

        // =========================
        // RESPUESTA FINAL
        // =========================
        echo json_encode([
            "status" => true,
            "message" => "Solicitud de combustible creada correctamente.",
            "contraseña" => $contraseña
        ]);
    }

    public function generarSolicitud(int $contraseña)
    {
        if ($contraseña) {
            $informe = $this->model->getContraseña($contraseña);
            $id_anticipo = $informe["anticipo"];
            $usuario = $informe["solicitante"];
            $anticipo = $this->model->getAnticipoInfo($id_anticipo);
            $facturas = $this->model->getDetallesbyContra($contraseña);
            $solicitante = $this->model->selectUsuario($usuario);
            if (empty($informe)) {
                $arrResponse = array('status' => false, 'msg' => 'Seleccione una solicitud válida.');
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
                return;
            }

            if (empty($solicitante)) {
                $arrResponse = ['status' => false, 'msg' => 'No se encontró el solicitante'];
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
                return;
            }

            $inpuestos = $this->model->getImpuestosByContraseña($contraseña);

            $monto_total = $inpuestos['total'];
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

            $idArea = $informe['area_id'];

            if ($monto_total > LIMITE_COMPRA) {
                $categoria = "Mayor";
            } else if ($monto_total < LIMITE_COMPRA) {
                $categoria = "Menor";
            }

            $grupo = $this->model->getGrupo($idArea, $categoria);

            if (empty($grupo)) {
                $arrResponse = ['status' => false, 'msg' => 'No se encontró grupo de firmas.'];
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
                return;
            }

            $id_grupo = $grupo['id_grupo']; // ✅ primer registro
            $firmas = $this->model->getFirmas((int) $id_grupo);
            $ruta_pdf = 'Views/Template/PDF/Solicitud_Fondos.php';
            $arrData['contraseña'] = $informe;
            $arrData['anticipo'] = $anticipo;
            $arrData['facturas'] = $facturas;
            $arrData['inpuestos'] = $inpuestos;
            $arrData['mes'] = $mes;
            $arrData['monto'] = $monto_total;
            $arrData['monto_letras'] = $monto_letras;
            $arrData['grupo'] = $grupo;
            $arrData['firmas'] = $firmas;
            $arrData['solicitante'] = $solicitante;
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
    public function generarSolicitudServicios(int $contraseña)
    {
        if ($contraseña) {
            $informe = $this->model->getContraseña($contraseña);
            $usuario = $informe["solicitante"];
            $retenciones = $this->model->getServiciosbyContra($contraseña);
            $servicios = $this->model->getServiciosbyContra($contraseña); // ESTOS SERVICIOS, HOLA CHATGPT
            $solicitante = $this->model->selectUsuario($usuario);
            if (empty($informe)) {
                $arrResponse = array('status' => false, 'msg' => 'Seleccione una solicitud válida.');
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
                return;
            }

            if (empty($solicitante)) {
                $arrResponse = ['status' => false, 'msg' => 'No se encontró el solicitante'];
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
                return;
            }

            $inpuestos = $this->model->getImpuestosByContraseña($contraseña);

            $monto_total = $inpuestos['total'];
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

            $idArea = $informe['area_id'];

            if ($monto_total > LIMITE_COMPRA) {
                $categoria = "Mayor";
            } else if ($monto_total < LIMITE_COMPRA) {
                $categoria = "Menor";
            }

            $grupo = $this->model->getGrupo($idArea, $categoria);

            if (empty($grupo)) {
                $arrResponse = ['status' => false, 'msg' => 'No se encontró grupo de firmas.'];
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
                return;
            }

            $id_grupo = $grupo['id_grupo']; // ✅ primer registro
            $firmas = $this->model->getFirmas((int) $id_grupo);
            $ruta_pdf = 'Views/Template/PDF/Servicios.php';
            $arrData['contraseña'] = $informe;
            $arrData['servicios'] = $servicios;
            $arrData['retenciones'] = $retenciones;
            $arrData['inpuestos'] = $inpuestos;
            $arrData['mes'] = $mes;
            $arrData['monto'] = $monto_total;
            $arrData['monto_letras'] = $monto_letras;
            $arrData['grupo'] = $grupo;
            $arrData['firmas'] = $firmas;
            $arrData['solicitante'] = $solicitante;
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

    public function generarSolicitudRentas(int $contraseña)
    {
        if ($contraseña) {
            $informe = $this->model->getContraseña($contraseña);
            $usuario = $informe["solicitante"];
            $rentas = $this->model->getRentasbyContra($contraseña);
            $retenciones = $this->model->getRetenciones($contraseña);
            $solicitante = $this->model->selectUsuario($usuario);
            if (empty($informe)) {
                $arrResponse = array('status' => false, 'msg' => 'Seleccione una solicitud válida.');
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
                return;
            }

            if (empty($solicitante)) {
                $arrResponse = ['status' => false, 'msg' => 'No se encontró el solicitante'];
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
                return;
            }

            $inpuestos = $this->model->getImpuestosRenta($contraseña);

            $monto_total = $inpuestos['total'];
            $monto_letras = $this->numeroALetras($monto_total, 'asNumber', 2);
            switch ($informe['mes_renta']) {
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

            $mes_renta = "RENTA MES DE " . $mes . " " . $informe['año_renta'];


            $idArea = $informe['area_id'];

            if ($monto_total > LIMITE_COMPRA) {
                $categoria = "Mayor";
            } else if ($monto_total < LIMITE_COMPRA) {
                $categoria = "Menor";
            }

            $grupo = $this->model->getGrupo($idArea, $categoria);

            if (empty($grupo)) {
                $arrResponse = ['status' => false, 'msg' => 'No se encontró grupo de firmas.'];
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
                return;
            }

            $id_grupo = $grupo['id_grupo'];
            $firmas = $this->model->getFirmas((int) $id_grupo);
            $ruta_pdf = 'Views/Template/PDF/Rentas.php';
            $arrData['contraseña'] = $informe;
            $arrData['rentas'] = $rentas;
            $arrData['retenciones'] = $retenciones;
            $arrData['inpuestos'] = $inpuestos;
            $arrData['mes_renta'] = $mes_renta;
            $arrData['monto'] = $monto_total;
            $arrData['monto_letras'] = $monto_letras;
            $arrData['grupo'] = $grupo;
            $arrData['firmas'] = $firmas;
            $arrData['solicitante'] = $solicitante;
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

    public function generarSolicitudCombustible($contraseña)
    {
        if ($contraseña) {
            $informe = $this->model->getCombustible($contraseña);
            $usuario = $informe["realizador"];

            $solicitante = $this->model->selectUsuario($usuario);

            if (empty($informe)) {
                $arrResponse = array('status' => false, 'msg' => 'Seleccione una solicitud válida.');
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
                return;
            }

            /* ===== RANGO DE FECHAS ===== */
            $informe['rango_fechas'] = $this->formatearRangoFechas(
                $informe['fecha_inicio'],
                $informe['fecha_final']
            );

            $monto_total = $informe['total'];
            $monto_letras = $this->numeroALetras($monto_total, 'asNumber', 2) . ' Quetzales';

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

            $idArea = $informe['id_area'];
            $categoria = "Combustible";
            $grupo = $this->model->getGrupo($idArea, $categoria);

            if (empty($grupo)) {
                $arrResponse = ['status' => false, 'msg' => 'No se encontró grupo de firmas.'];
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
                return;
            }

            $id_grupo = $grupo['id_grupo'];

            $firmas = $this->model->getFirmas($id_grupo);

            $ruta_pdf = 'Views/Template/PDF/Combustible.php';
            $arrData['combustible'] = $informe;
            $arrData['rango_fecha'] = $informe['rango_fechas'];
            $arrData['mes'] = $mes;
            $arrData['monto'] = $monto_total;
            $arrData['monto_letras'] = $monto_letras;
            $arrData['grupo'] = $grupo;
            $arrData['firmas'] = $firmas;
            $arrData['solicitante'] = $solicitante;
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

    public function generarAnticipo($solicitud)
    {
        if ($solicitud) {
            $informe = $this->model->getSolicitud($solicitud);
            $facturas = $this->model->getDetallesAnticipo($solicitud);
            $usuario = $informe["realizador"];

            $solicitante = $this->model->selectUsuario($usuario);

            if (empty($informe)) {
                $arrResponse = array('status' => false, 'msg' => 'Seleccione una solicitud válida.');
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
                return;
            }

            if (empty($solicitante)) {
                $arrResponse = ['status' => false, 'msg' => 'No se encontró el solicitante'];
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
                return;
            }

            $monto_total = $informe['total'];
            $monto_letras = $this->numeroALetras($monto_total, 'asNumber', 2) . ' Quetzales';

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

            $idArea = $informe['id_area'];
            $categoria = "Anticipo";
            $grupo = $this->model->getGrupo($idArea, $categoria);

            if (empty($grupo)) {
                $arrResponse = ['status' => false, 'msg' => 'No se encontró grupo de firmas.'];
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
                return;
            }

            $id_grupo = $grupo['id_grupo'];

            $firmas = $this->model->getFirmas($id_grupo);

            $ruta_pdf = 'Views/Template/PDF/Anticipo.php';
            $arrData['anticipo'] = $informe;
            $arrData['facturas'] = $facturas;
            $arrData['mes'] = $mes;
            $arrData['monto'] = $monto_total;
            $arrData['monto_letras'] = $monto_letras;
            $arrData['grupo'] = $grupo;
            $arrData['firmas'] = $firmas;
            $arrData['solicitante'] = $solicitante;
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
    public function getFacturaId($id)
    {
        $arrData = $this->model->FacturasbyID($id);

        // Verificar y depurar datos
        error_log(print_r($arrData, true));

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron registros previos.');
        } else {
            $arrResponse = array('status' => true, 'data' => $arrData);
        }

        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getFacturaServicio($id)
    {
        $arrData = $this->model->FacturasServiciobyID($id);

        if (empty($arrData)) {
            $arrResponse = array(
                'status' => false,
                'msg' => 'No se encontraron registros previos.'
            );
        } else {

            // Convertir materiales a array (si hay algo)
            if (!empty($arrData['materiales'])) {
                $arrData['materiales_array'] = explode(";", $arrData['materiales']);
            } else {
                $arrData['materiales_array'] = [];
            }

            $arrResponse = array(
                'status' => true,
                'data' => $arrData
            );
        }

        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getFacturaRenta($id)
    {
        $arrData = $this->model->FacturasRentabyID($id);

        if (empty($arrData)) {
            $arrResponse = array(
                'status' => false,
                'msg' => 'No se encontraron registros previos.'
            );
        } else {

            if (!empty($arrData['arrendamientos'])) {
                $arrData['arrendamientos_array'] = explode(";", $arrData['arrendamientos']);
            } else {
                $arrData['arrendamientos_array'] = [];
            }

            $arrResponse = array(
                'status' => true,
                'data' => $arrData
            );
        }

        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getPDF($contraseña)
    {
        $arrData = $this->model->PDFdatos($contraseña);

        if (empty($arrData)) {
            $arrResponse = array(
                'status' => false,
                'msg' => 'No se encontraron registros previos.'
            );
        } else {

            // Convertir materiales a array (si hay algo)
            if (!empty($arrData['materiales'])) {
                $arrData['materiales_array'] = explode(";", $arrData['materiales']);
            } else {
                $arrData['materiales_array'] = [];
            }

            $arrResponse = array(
                'status' => true,
                'data' => $arrData
            );
        }

        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function updateRentaMes()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $contraseña = $_POST['contraseña'] ?? null;
            $renta_mes = $_POST['mes_renta'] ?? null;

            $tiene_nota_credito = $_POST['tiene_nota_credito'] ?? '0';
            $no_factura = $_POST['no_factura'] ?? null;
            $monto_credito = $_POST['monto_credito'] ?? null;

            if (empty($contraseña) || empty($renta_mes)) {
                echo json_encode([
                    "status" => false,
                    "message" => "Datos incompletos."
                ]);
                exit;
            }

            if ($tiene_nota_credito === '1') {

                if (empty($no_factura) || empty($monto_credito)) {
                    echo json_encode([
                        "status" => false,
                        "message" => "Debe ingresar factura y monto de la nota de crédito."
                    ]);
                    exit;
                }

                $monto_credito = str_replace(',', '.', trim($monto_credito));

                if (!is_numeric($monto_credito)) {
                    echo json_encode([
                        "status" => false,
                        "message" => "Monto de crédito inválido."
                    ]);
                    exit;
                }

                $totalBase = $this->model->getTotalRentaSinCredito($contraseña);

                if (empty($totalBase) || !isset($totalBase['total_sin_credito'])) {
                    echo json_encode([
                        "status" => false,
                        "message" => "No se pudo calcular el total de la renta."
                    ]);
                    exit;
                }

                if ($monto_credito > $totalBase['total_sin_credito']) {
                    echo json_encode([
                        "status" => false,
                        "message" => "El monto del crédito no puede ser mayor al total de la renta."
                    ]);
                    exit;
                }

                $existe = $this->model->existeCredito($contraseña);

                if ($existe) {
                    $this->model->updateCredito($contraseña, $no_factura, $monto_credito);
                } else {
                    $this->model->insertCredito($contraseña, $no_factura, $monto_credito);
                }

            } else if ($tiene_nota_credito === '0') {

                $existe = $this->model->existeCredito($contraseña);

                if ($existe) {
                    $this->model->deleteCredito($contraseña);
                }
            }

            $result = $this->model->updateMesRenta($contraseña, $renta_mes);

            echo json_encode([
                "status" => $result,
                "message" => $result
                    ? "Renta actualizada correctamente."
                    : "Error al actualizar la renta."
            ]);
        }
    }

    public function validarSolicitud()
    {
        // Solo permitir método POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode([
                "status" => false,
                "message" => "Método no permitido."
            ]);
            exit;
        }

        $id_solicitud = isset($_POST['id_solicitud']) ? intval($_POST['id_solicitud']) : 0;
        $area = isset($_POST['area']) ? trim($_POST['area']) : '';
        $respuesta = isset($_POST['respuesta']) ? trim($_POST['respuesta']) : '';
        $observacion = $_POST['observacion'];

        if ($id_solicitud <= 0 || empty($respuesta)) {
            echo json_encode([
                "status" => false,
                "message" => "Faltan datos obligatorios (solicitud o respuesta)."
            ]);
            exit;
        }

        $solicitudExistente = $this->model->getContraSoli($id_solicitud);
        if (empty($solicitudExistente)) {
            echo json_encode([
                "status" => false,
                "message" => "La solicitud no existe o fue eliminada."
            ]);
            exit;
        }

        $result = $this->model->udapteSolicitud($id_solicitud, $respuesta, $observacion);
        if (!$result) {
            echo json_encode([
                "status" => false,
                "message" => "Error al actualizar la solicitud."
            ]);
            exit;
        }

        $contraseña = $solicitudExistente['contraseña'] ?? null;
        if (empty($contraseña)) {
            echo json_encode([
                "status" => false,
                "message" => "No se pudo obtener la contraseña de la solicitud."
            ]);
            exit;
        }

        $categoria = 'Solicitud Fondos';
        $arrData = [
            'solicitud' => $this->model->getContraseña($contraseña),
            'correos' => $this->model->getCorreosArea($area, $respuesta, $categoria),
            'respuesta' => $respuesta
        ];

        $contraseña = $arrData['solicitud']['contraseña'];

        if ($respuesta === 'Descartado') {
            $this->model->descartarContra($contraseña, $respuesta, $observacion);
            $this->model->descartarDetalles($contraseña, $respuesta);

        }

        if (empty($arrData['correos'])) {
            echo json_encode([
                "status" => true,
                "message" => "Validado sin enviar correo"
            ]);
            exit;
        }

        $sendCorreoEmpleado = 'Views/Template/Email/sentSolicitudFondos.php';
        try {
            ob_start();
            require $sendCorreoEmpleado;
            ob_end_clean();
        } catch (Exception $e) {
            echo json_encode([
                "status" => false,
                "message" => "Error al enviar el correo: " . $e->getMessage()
            ]);
            exit;
        }

        $mensaje = ($respuesta === 'Descartado')
            ? "Solicitud descartada y correo enviado correctamente."
            : "Solicitud validada y correo enviado correctamente.";

        echo json_encode([
            "status" => true,
            "message" => $mensaje
        ]);
    }

    public function finalizarSolicitud()
    {
        // 🔒 Verificar método HTTP
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode([
                "status" => false,
                "message" => "Método no permitido."
            ]);
            exit;
        }

        // 🔹 Sanitizar y validar datos de entrada
        $id_solicitud = isset($_POST['id_solicitud']) ? intval($_POST['id_solicitud']) : 0;
        $area = isset($_POST['area']) ? trim($_POST['area']) : '';
        $respuesta = isset($_POST['respuesta']) ? trim($_POST['respuesta']) : '';
        $no_transferencia = isset($_POST['no_transferencia']) ? trim($_POST['no_transferencia']) : null;
        $fecha_pago = isset($_POST['fecha_pago']) ? trim($_POST['fecha_pago']) : null;
        $observacion = isset($_POST['observacion']) ? trim($_POST['observacion']) : null;

        // 🔹 Validación de campos básicos
        if ($id_solicitud <= 0 || empty($respuesta)) {
            echo json_encode([
                "status" => false,
                "message" => "Faltan datos obligatorios (ID de solicitud o respuesta)."
            ]);
            exit;
        }

        // 🔹 Validaciones específicas para "Pagado"
        if ($respuesta === 'Pagado') {
            if (empty($no_transferencia) || empty($fecha_pago)) {
                echo json_encode([
                    "status" => false,
                    "message" => "Debe ingresar número de transferencia y fecha de pago."
                ]);
                exit;
            }

            // Validar formato de fecha (YYYY-MM-DD)
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha_pago)) {
                echo json_encode([
                    "status" => false,
                    "message" => "El formato de la fecha de pago no es válido. Use AAAA-MM-DD."
                ]);
                exit;
            }
        }

        if ($respuesta === 'Descartado') {
            if (empty($observacion)) {
                echo json_encode([
                    "status" => false,
                    "message" => "Debe ingresar un comentario de descarte."
                ]);
                exit;
            }
        }

        // 🔹 Verificar si la solicitud existe
        $solicitudExistente = $this->model->getContraSoli($id_solicitud);
        if (empty($solicitudExistente)) {
            echo json_encode([
                "status" => false,
                "message" => "La solicitud no existe o fue eliminada."
            ]);
            exit;
        }

        // 🔹 Actualizar solicitud
        $result = $this->model->endSolicitud($id_solicitud, $respuesta, $no_transferencia, $fecha_pago, $observacion);
        if (!$result) {
            echo json_encode([
                "status" => false,
                "message" => "Error al actualizar la solicitud."
            ]);
            exit;
        }

        // 🔹 Obtener contraseña de la solicitud
        $contraseña = $solicitudExistente['contraseña'] ?? null;
        if (empty($contraseña)) {
            echo json_encode([
                "status" => false,
                "message" => "No se pudo obtener la contraseña de la solicitud."
            ]);
            exit;
        }

        $categoria = 'Solicitud Fondos';
        if ($respuesta = "Pagado") {
            $base = 'Pagar';
        } else if ($Descartado = "Descartado") {
            $base = 'Descartar';
        }
        $arrData = [
            'solicitud' => $this->model->getContraseña($contraseña),
            'correos' => $this->model->getCorreosArea($area, $base, $categoria),
            'respuesta' => $respuesta
        ];

        $contraseña = $arrData['solicitud']['contraseña'];
        $this->model->descartarContra($contraseña, $respuesta, $observacion);
        $this->model->descartarDetalles($contraseña, $respuesta);


        // 🔹 Enviar correo con plantilla
        $sendCorreoEmpleado = 'Views/Template/Email/sentSolicitudFondos.php';
        try {
            ob_start();
            require $sendCorreoEmpleado;
            ob_end_clean();
        } catch (Exception $e) {
            echo json_encode([
                "status" => false,
                "message" => "Error al enviar el correo: " . $e->getMessage()
            ]);
            exit;
        }

        // 🔹 Respuesta final según el estado
        $mensaje = ($respuesta === 'Descartado')
            ? "Solicitud descartada correctamente."
            : "Solicitud pagada correctamente.";

        log_Actividad(
            $_SESSION['PersonalData']['no_empleado'],
            $_SESSION['PersonalData']['nombre_completo'],
            "Configuracion",
            $mensaje
        );

        echo json_encode([
            "status" => true,
            "message" => $mensaje
        ]);
    }

    public function finalizarSolicitudSinContra()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id_solicitud = $_POST['id_solicitud'] ?? null;
            $area = $_POST['area'] ?? null;
            $respuesta = $_POST['respuesta'] ?? null;
            $no_transferencia = $_POST['no_transferencia'] ?? null;
            $fecha_pago = $_POST['fecha_pago'] ?? null;
            $observacion = $_POST['observacion'] ?? null;

            if (empty($id_solicitud) || empty($respuesta) || empty($area)) {
                echo json_encode([
                    "status" => false,
                    "message" => "Datos incompletos."
                ]);
                exit;
            }

            if ($respuesta === 'Pagado') {
                if (empty($no_transferencia) || empty($fecha_pago)) {
                    echo json_encode([
                        "status" => false,
                        "message" => "Debe ingresar número de transferencia y fecha de pago."
                    ]);
                    exit;
                }
            }

            $result = $this->model->endSolicitudSinContra(
                $id_solicitud,
                $respuesta,
                $no_transferencia,
                $fecha_pago,
                $observacion
            );

            /* -------- CONFIGURACIÓN DE CORREO -------- */
            $categoria = 'Anticipo';
            $base = null;

            switch ($respuesta) {
                case 'Pagado':
                    $base = 'Paga';
                    break;

                case 'Descartado':
                    $base = 'Descartar';
                    break;
            }

            if ($base) {
                $arrData = [
                    'anticipo' => $this->model->getSolisinContra($id_solicitud),
                    'correos' => $this->model->getCorreosArea($area, $base, $categoria),
                    'respuesta' => $respuesta
                ];

                $sendcorreoEmpleado = 'Views/Template/Email/sendAnticipo.php';

                try {
                    ob_start();
                    require $sendcorreoEmpleado;
                    ob_end_clean();
                } catch (Exception $e) {
                    echo json_encode([
                        "status" => false,
                        "message" => "Error al enviar el correo."
                    ]);
                    exit;
                }
            }

            log_Actividad(
                $_SESSION['PersonalData']['no_empleado'],
                $_SESSION['PersonalData']['nombre_completo'],
                "Configuracion",
                "Se revisó el anticipo ID: " . $id_solicitud
            );

            echo json_encode([
                "status" => $result,
                "message" => $result
                    ? "Revisión de anticipo completada correctamente."
                    : "Error al procesar el anticipo."
            ]);
        }
    }


    public function getSolisinContra($contraseña)
    {
        $arrData = $this->model->getSolisinContra($contraseña);

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron registros previos ');
        } else {
            $arrData['no_factura'] = explode(",", $arrData['no_factura']);
            $arrData['tipo'] = explode(",", $arrData['tipo']);
            $arrData['bien_servicio'] = explode(",", $arrData['bien_servicio']);
            $arrData['valor_documento'] = explode(',', $arrData['valor_documento']);
            $arrResponse = array('status' => true, 'data' => $arrData);
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function actualizarSolicitud()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_solicitud = $_POST['id_solicitud']; // ID o código de la contraseña
            $fecha_pago = $_POST['fecha_pago'];
            $proveedor = intval($_POST['proveedor']);
            $area = intval($_POST['area']);
            $estado = $_POST['respuesta'];

            $facturas = $_POST['factura'];
            $bienes = $_POST['bien'];
            $valores = $_POST['valor'];
            $errores = [];

            if (empty($id_solicitud)) {
                echo json_encode(["status" => false, "message" => "Datos incompletos."]);
                exit;
            }

            if (!empty($errores)) {
                echo json_encode(["status" => false, "message" => $errores]);
                exit;
            }

            // Actualizar datos generales de la contraseña
            $updateContraseña = $this->model->updateSolicitud(
                $id_solicitud,
                $fecha_pago,
                $proveedor,
                $estado
            );

            if (!$updateContraseña) {
                echo json_encode(["status" => false, "message" => "Error al actualizar los datos principales."]);
                exit;
            }

            // Manejar facturas nuevas o actualizadas
            foreach ($facturas as $index => $facturaItem) {
                $bienItem = $bienes[$index] ?? null;
                $valorItem = $valores[$index] ?? null;

                if (!$facturaItem || !$bienItem || !$valorItem) {
                    $errores[] = "Datos incompletos en la factura {$facturaItem}.";
                    continue;
                }

                $this->model->updateDetalleFactura($id_solicitud, $bienItem, $valorItem);

            }

            if (!empty($errores)) {
                echo json_encode(["status" => false, "message" => $errores, "errors" => $errores]);
                exit;
            }


            $categoria = 'Anticipo';
            // Enviar correo
            $arrData = [
                'anticipo' => $this->model->getSolisinContra($id_solicitud),
                'correos' => $this->model->getCorreosArea($area, $estado, $categoria),
                'respuesta' => $estado
            ];

            $sendcorreoEmpleado = 'Views/Template/Email/sendAnticipo.php';
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
    public function getAnticipos($params)
    {
        $arrParams = explode(",", $params);
        $id_proveedor = $arrParams[0];
        $id_area = $arrParams[1];

        $htmlOptions = "<option selected disabled>Seleccione un Anticipo...</option>";
        $arrData = $this->model->selectAnticipos($id_proveedor, $id_area);

        if (count($arrData) > 0) {
            for ($i = 0; $i < count($arrData); $i++) {
                $htmlOptions .= '<option value="' . $arrData[$i]['id_solicitud'] . '">'
                    . "Correlativo: " . $arrData[$i]['contraseña']
                    . " | Fecha Transaccion: " . $arrData[$i]['fecha_transaccion']
                    . '</option>';
            }
        }

        echo $htmlOptions;
        die();
    }

    public function deleteMaterial($id)
    {
        if (!is_numeric($id)) {
            echo json_encode(['status' => false, 'msg' => 'ID inválido']);
            return;
        }

        $delete = $this->model->deleteMaterial($id);

        if ($delete) {
            echo json_encode(['status' => true, 'msg' => 'Material eliminado correctamente']);
        } else {
            echo json_encode(['status' => false, 'msg' => 'No se pudo eliminar el material']);
        }
    }

    private function formatearRangoFechas($inicio, $fin)
    {
        $meses = [
            'January' => 'ENERO',
            'February' => 'FEBRERO',
            'March' => 'MARZO',
            'April' => 'ABRIL',
            'May' => 'MAYO',
            'June' => 'JUNIO',
            'July' => 'JULIO',
            'August' => 'AGOSTO',
            'September' => 'SEPTIEMBRE',
            'October' => 'OCTUBRE',
            'November' => 'NOVIEMBRE',
            'December' => 'DICIEMBRE'
        ];

        $fechaInicio = new DateTime($inicio);
        $fechaFin = new DateTime($fin);

        $diaInicio = $fechaInicio->format('d');
        $mesInicio = $meses[$fechaInicio->format('F')];

        $diaFin = $fechaFin->format('d');
        $mesFin = $meses[$fechaFin->format('F')];
        $anio = $fechaFin->format('Y');

        return "DEL {$diaInicio} DE {$mesInicio} AL {$diaFin} DE {$mesFin} {$anio}";
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



}
