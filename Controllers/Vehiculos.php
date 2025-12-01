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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Obtener y validar datos
            $contraseña = $_POST['contraseña'] ?? null;
            $area = $_POST['area'] ?? null;
            $categoria = $_POST['categoria'] ?? null;
            $estado = $_POST['respuesta'] ?? null;

            // Validaciones básicas
            if (empty($contraseña) || empty($estado)) {
                echo json_encode(["status" => false, "message" => "Problemas al obtener datos."]);
                exit;
            }

            if ($categoria === null && $estado === "Finalizado") {
                echo json_encode(["status" => false, "message" => "Seleccione una categoria antes"]);
                exit;
            }

            // Registrar cambios en el modelo
            $r1 = $this->model->setContraseña($contraseña, $estado);
            $r2 = $this->model->setEstadoDetalles($contraseña, $estado);

            // Verificar errores en cualquiera de los dos procesos
            if (
                (isset($r1['status']) && $r1['status'] === false) ||
                (isset($r2['status']) && $r2['status'] === false)
            ) {
                echo json_encode([
                    "status" => false,
                    "message" => "Error al actualizar estado de la contraseña.",
                    "errors" => [$r1, $r2]
                ]);
                exit;
            }

            // Si el estado es Finalizado, crear el fondo
            if ($estado === "Validado") {

                $this->model->solicitudFondoVehiculos($contraseña, $area, $categoria);

                echo json_encode([
                    "status" => true,
                    "message" => "Solicitud iniciada correctamente."
                ]);

                log_Actividad(
                    $_SESSION['PersonalData']['no_empleado'],
                    $_SESSION['PersonalData']['nombre_completo'],
                    "Vehiculos",
                    "Solicitud de fondos creada: " . $contraseña
                );
            } else {
                echo json_encode([
                    "status" => true,
                    "message" => "Contraseña descartada."
                ]);

                log_Actividad(
                    $_SESSION['PersonalData']['no_empleado'],
                    $_SESSION['PersonalData']['nombre_completo'],
                    "Configuracion",
                    "Se descarto la contraseña: " . $contraseña
                );
            }


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


        } else {
            // Si no es POST
            echo json_encode([
                "status" => false,
                "message" => "Método no permitido."
            ]);
        }
    }


    // no
}
