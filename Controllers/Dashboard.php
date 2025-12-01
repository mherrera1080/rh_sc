<?php
session_start();

class Dashboard extends Controllers
{
    public function __construct()
    {
        parent::__construct();
        if (empty($_SESSION['login'])) {
            header('Location: ' . base_url() . '/login');
            die();
        }
        getPermisos(CONFIGURACION);

    }

    public function Dashboard()
    {
        $data['page_id'] = DASHBOARD;
        $data['page_tag'] = "Dashboard";
        $data['page_title'] = "Dashboard";
        $data['page_name'] = "dashboard";
        $data['page_functions_js'] = "functions_dashboard.js";

        $this->views->getView($this, "Dashboard", $data);
    }

    public function Nada()
    {
        $data['page_id'] = DASHBOARD;
        $data['page_tag'] = "Dashboard";
        $data['page_title'] = "Dashboard";
        $data['page_name'] = "dashboard";
        $data['page_functions_js'] = "functions_dashboard.js";

        $this->views->getView($this, "Nada", $data);
    }

    public function Reportes()
    {

        $data['areas'] = $this->model->getAreas();
        $data['proveedores'] = $this->model->getProveedores();

        $data['page_id'] = DASHBOARD;
        $data['page_tag'] = "Reportes";
        $data['page_title'] = "Reportes";
        $data['page_name'] = "Reportes";
        $data['page_functions_js'] = "functions_reportes.js";

        $this->views->getView($this, "Reportes", $data);
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

        public function getAnticipos()
    {
        $arrData = $this->model->selectAnticipos();

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron registros previos.');
        } else {
            $arrResponse = array('status' => true, 'data' => $arrData);
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function putFiltros()
    {
        $area = strClean($_POST['area']);
        $proveedor = strClean($_POST['proveedor']);
        $estado = strClean($_POST['estado']);
        $categoria = strClean($_POST['categoria']);
        $desde = strClean($_POST['desde']);
        $hasta = strClean($_POST['hasta']);

        $arrData = $this->model->filtrarContraseñas($area, $proveedor, $estado, $categoria, $desde, $hasta);

        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        die();
    }


    public function actualizarPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['status' => false, 'msg' => 'Método de solicitud incorrecto'], JSON_UNESCAPED_UNICODE);
            die();
        }

        $correo_empresarial = $_POST['correo_empresarial'] ?? '';
        $password = $_POST['password'] ?? '';
        $password_new = $_POST['password_new'] ?? '';
        $password_confirmacion = $_POST['password_confirmacion'] ?? '';

        // Validación campos vacíos
        if (empty($correo_empresarial) || empty($password) || empty($password_new) || empty($password_confirmacion)) {
            echo json_encode(['status' => false, 'msg' => 'Formulario incompleto'], JSON_UNESCAPED_UNICODE);
            die();
        }

        // La nueva contraseña no puede ser igual a la anterior
        if ($password == $password_new) {
            echo json_encode(['status' => false, 'msg' => 'La nueva contraseña debe ser diferente a la anterior'], JSON_UNESCAPED_UNICODE);
            die();
        }

        // Confirmación coincide
        if ($password_new !== $password_confirmacion) {
            echo json_encode(['status' => false, 'msg' => 'La nueva contraseña y su confirmación no coinciden'], JSON_UNESCAPED_UNICODE);
            die();
        }

        // Validación de complejidad
        $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/';
        if (!preg_match($pattern, $password_new)) {
            echo json_encode(['status' => false, 'msg' => 'La nueva contraseña debe tener al menos 8 caracteres, incluyendo mayúscula, minúscula, número y signo.'], JSON_UNESCAPED_UNICODE);
            die();
        }

        // Verificar usuario
        $requestUser = $this->model->getUserByIdentificacion($correo_empresarial);
        if (!$requestUser) {
            echo json_encode(['status' => false, 'msg' => 'El correo no está registrado en el sistema.'], JSON_UNESCAPED_UNICODE);
            die();
        }
        $hashedPassword = $requestUser['contraseña'];
        // Verificar contraseña anterior
        if (!password_verify($password, $hashedPassword)) {
            echo json_encode(['status' => false, 'msg' => 'Contraseña incorrecta'], JSON_UNESCAPED_UNICODE);
            die();
        }

        // Generar token
        $token = rand(100000, 999999);
        $expires_at = date('Y-m-d H:i:s', strtotime('+5 minutes'));

        if (!$this->model->guardarToken($correo_empresarial, $token, $expires_at)) {
            echo json_encode(['status' => false, 'msg' => 'No se pudo guardar el token en la base de datos.'], JSON_UNESCAPED_UNICODE);
            die();
        }
        $userData = $this->model->verificarCorreo($correo_empresarial);
        // Enviar correo
        $arrData = [
            'correo_empresarial' => $correo_empresarial,
            'token' => $token,
            'nombres' => $userData['nombres'],
        ];
        require 'Views/Template/Email/PasswordEmail.php';

        echo json_encode(['status' => true, 'msg' => 'El correo con el token ha sido enviado.'], JSON_UNESCAPED_UNICODE);
        die();
    }


    public function validarToken()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['correo_empresarial']) || empty($_POST['token'])) {
                echo json_encode([
                    'status' => false,
                    'msg' => 'Correo y token son obligatorios.',
                ]);
                exit;
            }

            $correo_empresarial = trim($_POST['correo_empresarial']);
            $password = trim($_POST['password']);

            $token = trim($_POST['token']);

            // Validar el token en la base de datos
            $userData = $this->model->validarToken($correo_empresarial, $token);
            if ($userData) {

                $this->model->updateContra($correo_empresarial, $password);

                echo json_encode([
                    'status' => true,
                    'msg' => 'Actualizacion de Contraseña Exitosa.'
                ]);

            } else {
                echo json_encode([
                    'status' => false,
                    'msg' => 'El token ingresado no es válido o ha expirado.',
                ]);
            }
            exit;
        }
    }

}
