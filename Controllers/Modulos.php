<?php
class Modulos extends Controllers
{
    public function __construct()
    {
        parent::__construct();
    }

    public function Modulos()
    {
        $data['page_id'] = DASHBOARD;
        $data['page_tag'] = "Contraseñas";
        $data['page_title'] = "Contraseñas";
        $data['page_name'] = "Contraseñas";
        $data['page_functions_js'] = "functions_contraseñas.js";

        $this->views->getView($this, "Modulos", $data);
    }

    public function Plantilla($Modulo)
    {
        $modulo = $this->model->getModulo($Modulo);

        $data['modulo'] = $modulo;
        $data['page_id'] = "Modulos";
        $data['page_tag'] = "Modulos";
        $data['page_title'] = "Modulos";
        $data['page_name'] = "Modulos";
        $data['page_functions_js'] = "functions_plantilla.js";

        $this->views->getView($this, "Plantillas", $data);
    }

    public function contraseñasAreas($id_area)
    {
        $arrData = $this->model->selectContrasAreas($id_area);

        error_log(print_r($arrData, true));

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos.');
        } else {
            $arrResponse = array('status' => true, 'data' => $arrData);
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
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
            echo json_encode(["status" => true, "message" => "Contraseña registrada correctamente."]);
        }
    }



}
