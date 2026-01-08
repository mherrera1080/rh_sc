<?php
session_start();

class Vehiculos extends Controllers
{
    public function __construct()
    {
        parent::__construct();
        if (empty($_SESSION['login'])) {
            header('Location: ' . base_url() . '/login');
            die();
        }
    }

    public function Vehiculos()
    {
        $data['page_id'] = "Vehiculos";
        $data['page_tag'] = "Vehiculos";
        $data['page_title'] = "Vehiculos";
        $data['page_name'] = "Vehiculos";
        $data['page_functions_js'] = "functions_vehiculos.js";

        $this->views->getView($this, "Vehiculos", $data);
    }

    public function Facturas()
    {
        $data['page_id'] = "Facturas";
        $data['page_tag'] = "Facturas";
        $data['page_title'] = "Facturas";
        $data['page_name'] = "Facturas";
        $data['page_functions_js'] = "functions_vehiculos_facturas.js";

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

    public function Detalles($contraseña)
    {
        $facturas = $this->model->getContraseña($contraseña);

        $data['facturas'] = $facturas;
        $data['page_id'] = 'INFO';
        $data['page_tag'] = "Detalles";
        $data['page_title'] = "Detalles";
        $data['page_name'] = "Detalles";
        $data['page_functions_js'] = "functions_contraseña_vehiculos_detalles.js";

        $this->views->getView($this, "Detalles", $data);
    }

    public function registrosVehiculos()
    {
        $arrData = $this->model->selectVehiculos();

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron registros previos.');
        } else {
            $arrResponse = array('status' => true, 'data' => $arrData);
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function validarContraseña()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $contraseña = $_POST['contraseña'];
            $estado = $_POST['respuesta'];
            $anticipo = $_POST['anticipo'] ?? null;
            $errores = [];
            if (empty($contraseña) || empty($estado)) {
                echo json_encode(["status" => false, "msg" => "Problemas al obtener datos."]);
                exit;
            }

            $this->model->setEstadoDetalles(
                $contraseña,
                $estado
            );

            // Crear la contraseña
            $this->model->validacionContraseña(
                $contraseña,
                $anticipo,
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
                "Vehiculos",
                "Se valido la contraseña: " . $contraseña
            );

            $datos = $this->model->getContraseña($contraseña);
            $area = $datos['id_area'];

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

            echo json_encode(["status" => true, "message" => "Contraseña registrada correctamente."]);
        }
    }

    public function solicitudFondos()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode([
                "status" => false,
                "message" => "Método no permitido."
            ]);
            return;
        }

        $contraseña = $_POST['contraseña'] ?? null;
        $area = $_POST['area'] ?? null;
        $proveedor = $_POST['proveedor'] ?? null;
        $categoria = $_POST['categoria'] ?? null;
        $estado = $_POST['respuesta'] ?? null;

        if (empty($contraseña) || empty($estado)) {
            echo json_encode([
                "status" => false,
                "message" => "Datos incompletos."
            ]);
            return;
        }

        if ($estado === "Validado" && empty($categoria)) {
            echo json_encode([
                "status" => false,
                "message" => "Debe seleccionar una categoría."
            ]);
            return;
        }

        $proveedores = $this->model->selectProveedor($proveedor);
        if (empty($proveedores)) {
            echo json_encode([
                "status" => false,
                "message" => "Proveedor no válido."
            ]);
            return;
        }

        $regimen = $proveedores["regimen"];
        $iva = $proveedores["iva"];
        $isr = $proveedores["isr"];

        $this->model->beginTransaction();

        try {

            $r1 = $this->model->setContraseña($contraseña, $estado);
            $r2 = $this->model->setEstadoDetalles($contraseña, $estado);

            if (
                (isset($r1['status']) && $r1['status'] === false) ||
                (isset($r2['status']) && $r2['status'] === false)
            ) {
                throw new Exception("Error al actualizar el estado.");
            }

            if ($estado === "Validado") {

                $r3 = $this->model->solicitudFondoVehiculos(
                    $contraseña,
                    $area,
                    $categoria,
                    $regimen,
                    $iva,
                    $isr
                );

                if (!$r3) {
                    throw new Exception("No fue posible crear la solicitud de fondos.");
                }

                $facturas = $this->model->getFacturasbyContra($contraseña);
                if (empty($facturas)) {
                    throw new Exception("La contraseña no tiene facturas asociadas.");
                }

                foreach ($facturas as $factura) {

                    $dataFactura = $this->model->FacturasbyID($factura['no_factura']);
                    if (empty($dataFactura)) {
                        throw new Exception(
                            "No se pudieron calcular valores de la factura {$factura['no_factura']}."
                        );
                    }

                    $update = $this->model->actualizarValoresFactura(
                        $factura['no_factura'],
                        $dataFactura['base'],
                        $dataFactura['iva'],
                        $dataFactura['reten_iva'],
                        $dataFactura['reten_isr'],
                        $dataFactura['total_liquido']
                    );

                    if (!$update) {
                        throw new Exception(
                            "Error al actualizar la factura {$factura['no_factura']}."
                        );
                    }
                }

                log_Actividad(
                    $_SESSION['PersonalData']['no_empleado'],
                    $_SESSION['PersonalData']['nombre_completo'],
                    "Vehiculos",
                    "Solicitud de fondos creada: {$contraseña}"
                );

                $mensaje = "Solicitud iniciada correctamente.";

            } else {

                log_Actividad(
                    $_SESSION['PersonalData']['no_empleado'],
                    $_SESSION['PersonalData']['nombre_completo'],
                    "Vehiculos",
                    "Contraseña descartada: {$contraseña}"
                );

                $mensaje = "Contraseña descartada correctamente.";
            }

            $this->model->commit();

            try {
                $categoria = "Contraseña";
                $base = "Solicitar Fondos";

                $arrData = [
                    'contraseña' => $this->model->getContraseña($contraseña),
                    'correos' => $this->model->getCorreosArea($area, $base, $categoria)
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

            } catch (Exception $e) {
                // Se ignora error de correo
            }

            echo json_encode([
                "status" => true,
                "message" => $mensaje
            ]);
            return;

        } catch (Exception $e) {

            $this->model->rollback();

            echo json_encode([
                "status" => false,
                "message" => $e->getMessage()
            ]);
            return;
        }
    }


}
