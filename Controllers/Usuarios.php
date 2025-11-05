<?php
session_start();
class Usuarios extends Controllers
{
    public function __construct()
    {
        parent::__construct();
        if (empty($_SESSION['login'])) {
            header('Location: ' . base_url() . '/login');
            die();
        }
    }

    public function Usuarios()
    {
        $data['page_id'] = DASHBOARD;
        $data['page_tag'] = "Usuarios";
        $data['page_title'] = "Usuarios";
        $data['page_name'] = "Usuarios";
        $data['page_functions_js'] = "functions_usuarios.js";

        $this->views->getView($this, "Usuarios", $data);
    }

    public function selectUsuarios()
    {
        $arrData = $this->model->selectUsuarios();
        echo json_encode(['data' => $arrData], JSON_UNESCAPED_UNICODE);
        die();
    }

        public function selectUserByid($id)
    {
        $arrData = $this->model->selectUserByid($id);

        // Verificar y depurar datos
        error_log(print_r($arrData, true));

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos.');
        } else {
            $arrResponse = array('status' => true, 'data' => $arrData);
        }

        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }


    public function setUsuario()
    {
        if ($_POST) {
            if (empty($_POST['set_nombres'])) {
                $arrResponse = array("status" => false, "msg" => 'Datos Incorrectos');
            } else {
                $id_usuario = intval($_POST['id_usuario']);
                $nombres = trim($_POST['set_nombres']);
                $primer_apellido = trim($_POST['set_primer_apellido']);
                $segundo_apellido = trim($_POST['set_segundo_apellido']);
                $identificacion = trim($_POST['set_identificacion']);
                $codigo_empleado = trim($_POST['set_codigo']);
                $correo = trim($_POST['set_correo']);
                $rol = trim($_POST['set_area']);
                $password = trim($_POST['set_password']);

                if ($id_usuario == 0) {
                    // Insertar nuevo usuario
                    $request_user = $this->model->insertUsuario(
                        $nombres,
                        $primer_apellido,
                        $segundo_apellido,
                        $identificacion,
                        $codigo_empleado,
                        $correo,
                        $rol,
                        $password
                    );
                } else {
                    // Actualizar usuario existente
                    $request_user = $this->model->updateUsuario(
                        $id_usuario,
                        $nombres,
                        $primer_apellido,
                        $segundo_apellido,
                        $identificacion,
                        $codigo_empleado,
                        $correo,
                        $rol,
                        $password
                    );
                }

                // Manejar la respuesta de la operación
                if ($request_user > 0) {
                    $arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente.');
                } elseif (is_string($request_user) && str_starts_with($request_user, "ERROR:")) {
                    $arrResponse = array('status' => false, 'msg' => $request_user);
                } else {
                    $arrResponse = array('status' => false, 'msg' => 'No es posible almacenar los datos.');
                }

            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }



}
