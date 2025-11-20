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

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $correo_empresarial = trim($_POST['correo_empresarial'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (empty($correo_empresarial) || empty($password)) {
                $arrResponse = array('status' => false, 'msg' => 'Debe ingresar correo y contraseña.');
            } else {
                // 1️⃣ Verificar si el usuario existe
                $userData = $this->model->verificarCorreo($correo_empresarial);

                if (empty($userData)) {
                    $arrResponse = array('status' => false, 'msg' => 'El usuario no existe o está dado de baja.');
                } else {
                    // 2️⃣ Obtener datos completos para validar contraseña
                    $userInfo = $this->model->getUserByCorreo($correo_empresarial);

                    if (empty($userInfo)) {
                        $arrResponse = array('status' => false, 'msg' => 'Error al obtener información del usuario.');
                    } else {
                        $hashedPassword = $userInfo['contraseña'];

                        // 3️⃣ Validar contraseña
                        if (password_verify($password, $hashedPassword)) {
                            // 4️⃣ Generar token temporal
                            $token = rand(100000, 999999);
                            $expires_at = date('Y-m-d H:i:s', strtotime('+5 minutes'));

                            // Guardar token
                            $result = $this->model->guardarToken($correo_empresarial, $token, $expires_at);

                            if (!$result) {
                                $arrResponse = array('status' => false, 'msg' => 'No se pudo generar el token de acceso.');
                            } else {
                                // 5️⃣ Enviar correo con token
                                $arrData = [
                                    'correo_empresarial' => $correo_empresarial,
                                    'token' => $token,
                                    'nombres' => $userData['nombres']
                                ];

                                $emailTemplatePath = 'Views/Template/Email/TokenEmail.php';
                                require $emailTemplatePath;

                                $arrResponse = array('status' => true, 'msg' => 'Se envió un correo con el código de verificación.');
                            }
                        } else {
                            $arrResponse = array('status' => false, 'msg' => 'Contraseña incorrecta.');
                        }
                    }
                }
            }
        } else {
            $arrResponse = array('status' => false, 'msg' => 'Método de solicitud incorrecto.');
        }

        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function verificarToken()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['status' => false, 'msg' => 'Método de solicitud incorrecto.'], JSON_UNESCAPED_UNICODE);
            die();
        }

        $correo_empresarial = trim($_POST['correo_empresarial'] ?? '');
        $token = trim($_POST['token'] ?? '');

        if (empty($correo_empresarial) || empty($token)) {
            $arrResponse = ['status' => false, 'msg' => 'Debe ingresar el correo y el token.'];
        } else {
            $tokenData = $this->model->getTokenValido($correo_empresarial, $token);

            if (empty($tokenData)) {
                $arrResponse = ['status' => false, 'msg' => 'Token inválido o expirado.'];
            } else {
                // ✅ Token válido → crear sesión
                session_start();

                $PersonalData = $this->model->loginUser($correo_empresarial);
                $_SESSION['login'] = true;
                $_SESSION['PersonalData'] = $PersonalData;

                $arrResponse = [
                    'status' => true,
                    'msg' => 'Inicio de sesión exitoso.',
                    'redirect' => base_url() . '/Dashboard'
                ];
            }
        }

        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }




    public function logout()
    {
        session_start();

        // Eliminar todas las variables de sesión
        $_SESSION = array();

        // Destruir la sesión
        session_destroy();

        // Eliminar la cookie de sesión (por seguridad adicional)
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        // Redirigir al login
        header('Location: ' . base_url() . '/login');
        exit;
    }

}
