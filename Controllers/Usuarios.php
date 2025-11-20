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
        getPermisos(USUARIOS);
    }

    public function Usuarios()
    {
        $data['page_id'] = USUARIOS;
        $data['page_tag'] = "Usuarios";
        $data['page_title'] = "Usuarios";
        $data['page_name'] = "Usuarios";
        $data['page_functions_js'] = "functions_usuarios.js";

        $this->views->getView($this, "Usuarios", $data);
    }

    public function selectUsuarios()
    {
        $arrData = $this->model->selectUsuarios();

        if (empty($arrData)) {
            $arrResponse = ['status' => false, 'msg' => 'No se encontraron registros previos.'];
        } else {
            $arrResponse = ['status' => true, 'data' => $arrData];
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    // 🔹 Aquí el cambio principal
    public function selectUserByid(int $id)
    {

        $arrData = $this->model->selectUserByid($id);

        if (empty($arrData)) {
            $arrResponse = ['status' => false, 'msg' => 'No se encontraron registros previos.'];
        } else {
            $arrResponse = ['status' => true, 'data' => $arrData];
        }

        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }


    public function setUsuario()
    {
        if ($_POST) {
            if (empty($_POST['set_nombres']) || empty($_POST['set_primer_apellido'])) {
                $arrResponse = array("status" => false, "msg" => 'Datos incompletos.');
            } else {
                $id_usuario = intval($_POST['id_usuario']);
                $nombres = trim($_POST['set_nombres']);
                $primer_apellido = trim($_POST['set_primer_apellido']);
                $segundo_apellido = trim($_POST['set_segundo_apellido']);
                $identificacion = trim($_POST['set_identificacion']);
                $codigo_empleado = trim($_POST['set_codigo']);
                $correo = trim($_POST['set_correo']);
                $area = intval($_POST['set_area']);
                $rol = intval($_POST['set_rol']);
                $password = trim($_POST['set_password']);

                if ($id_usuario == 0) {

                    $email = $this->model->existenciaCorreo($correo);

                    if ($email) {
                        echo json_encode([
                            "status" => false,
                            "msg" => "Correo existente"
                        ]);
                        exit;
                    }

                    $doc = $this->model->existenciaDoc($identificacion);
                    if ($doc) {
                        echo json_encode([
                            "status" => false,
                            "msg" => "Documento de Identificacion existente"
                        ]);
                        exit;
                    }

                    // Nuevo usuario
                    $request_user = $this->model->insertUsuario(
                        $nombres,
                        $primer_apellido,
                        $segundo_apellido,
                        $identificacion,
                        $codigo_empleado,
                        $correo,
                        $area,
                        $rol,
                        $password
                    );
                } else {

                    $email = $this->model->existenciaCorreoUpdate($correo, $id_usuario);

                    if ($email) {
                        echo json_encode([
                            "status" => false,
                            "msg" => "Correo existente"
                        ]);
                        exit;
                    }

                    $doc = $this->model->existenciaDocUpdate($identificacion, $id_usuario);

                    if ($doc) {
                        echo json_encode([
                            "status" => false,
                            "msg" => "Documento de Identificacion existente"
                        ]);
                        exit;
                    }


                    // Actualizar
                    $request_user = $this->model->updateUsuario(
                        $id_usuario,
                        $nombres,
                        $primer_apellido,
                        $segundo_apellido,
                        $identificacion,
                        $codigo_empleado,
                        $correo,
                        $area,
                        $rol,
                        $password
                    );
                }

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
