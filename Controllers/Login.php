<?php
class Login extends Controllers
{
    public function __construct()
    {
        if (isset($_SESSION['login'])) {
            header('Location: ' . base_url() . '/dashboard');
            die();
        }
        parent::__construct();
    }

    public function login()
    {
        $data['page_tag'] = "Login";
        $data['page_title'] = "Login";
        $data['page_name'] = "Login";
        $data['page_functions_js'] = "functions_login.js";

        $this->views->getView($this, "Login", $data);
    }

    public function loginUser()
    {
        $arrResponse = array();

        if ($_POST) {
            $correo_empresarial = trim($_POST['correo_empresarial']);

            if (empty($correo_empresarial)) {
                $arrResponse = array('status' => false, 'msg' => 'Debe ingresar el correo.');
            } else {
                $userData = $this->model->verificarCorreo($correo_empresarial);
                $PersonalData = $this->model->loginUser($correo_empresarial);

                if (empty($userData)) {
                    $arrResponse = array('status' => false, 'msg' => 'El correo no existe o está de baja.');
                } else {
                    // Crear sesión
                    session_start();
                    $_SESSION['login'] = true;
                    $_SESSION['PersonalData'] = $PersonalData;
                    $_SESSION['userData'] = $userData;


                    $arrResponse = array('status' => true, 'msg' => 'Inicio de sesión exitoso.');
                }
            }
        } else {
            $arrResponse = array('status' => false, 'msg' => 'Método de solicitud incorrecto.');
        }

        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function logout()
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();
        }
        header('Location: ' . base_url() . '/login');
        exit;
    }
}
