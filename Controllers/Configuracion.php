<?php
class Configuracion extends Controllers
{
    public function __construct()
    {
        parent::__construct();
    }

    public function Configuracion()
    {
        $data['page_id'] = DASHBOARD;
        $data['page_tag'] = "Configuracion";
        $data['page_title'] = "Configuracion";
        $data['page_name'] = "Configuracion";
        $data['page_functions_js'] = "functions_configuracion.js";

        $this->views->getView($this, "Configuracion", $data);
    }

    public function Areas()
    {
        $data['page_id'] = 'Areas';
        $data['page_tag'] = "Areas";
        $data['page_title'] = "Areas";
        $data['page_name'] = "Areas";
        $data['page_functions_js'] = "functions_areas.js";
        $this->views->getView($this, "Areas", $data);
    }

    public function getAreas()
    {
        $arrData = $this->model->Areas();

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos.');
        } else {
            $arrResponse = array('status' => true, 'data' => $arrData);
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getAreabyID($id_area)
    {
        $data = $this->model->areasbyID($id_area);
        if ($data) {
            $arrResponse = array('status' => true, 'data' => $data);
        } else {
            $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function setArea()
    {
        if ($_POST) {
            if (empty($_POST['nombre_area'])) {
                $arrResponse = array("status" => false, "msg" => 'Datos Incorrectos');
            } else {
                $id_area = intval($_POST['id_area']);
                $nombre_area = $_POST['nombre_area'];
                $estado = $_POST['estado'];

                if ($id_area == 0) {
                    $option = 1;
                    $request_user = $this->model->insertAreas(
                        $nombre_area,
                        $estado
                    );
                } else {
                    $option = 2;
                    $request_user = $this->model->updateAreas(
                        $id_area,
                        $nombre_area,
                        $estado
                    );
                }

                if ($request_user > 0) {
                    if ($option == 1) {
                        $arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente.');
                    } else {
                        $arrResponse = array('status' => true, 'msg' => 'Datos actualizados correctamente.');
                    }
                } elseif ($request_user == 'exist') {
                    $arrResponse = array('status' => false, 'msg' => '¡Atención! El usuario ya existe.');
                } else {
                    $arrResponse = array('status' => false, 'msg' => 'No es posible almacenar los datos.');
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function Proveedores()
    {
        $data['page_id'] = 'Proveedores';
        $data['page_tag'] = "Proveedores";
        $data['page_title'] = "Proveedores";
        $data['page_name'] = "Proveedores";
        $data['page_functions_js'] = "functions_proveedores.js";
        $this->views->getView($this, "Proveedores", $data);
    }

    public function getProveedores()
    {
        $arrData = $this->model->Proveedores();

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos.');
        } else {
            $arrResponse = array('status' => true, 'data' => $arrData);
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getProveedorID($id_proveedor)
    {
        $data = $this->model->proveedorbyID($id_proveedor);
        if ($data) {
            $arrResponse = array('status' => true, 'data' => $data);
        } else {
            $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function setProveedor()
    {
        if ($_POST) {
            if (empty($_POST['id_proveedor'])) {
                $arrResponse = array("status" => false, "msg" => 'Datos Incorrectos');
            } else {
                $id_proveedor = intval($_POST['id_proveedor']);
                $nombre_proveedor = $_POST['nombre_proveedor'];
                $nombre_social = $_POST['nombre_social'];
                $nit_proveedor = $_POST['nit_proveedor'];
                $dias_credito = $_POST['dias_credito'];
                $estado = $_POST['estado'];

                if ($id_proveedor == 0) {
                    $option = 1;
                    $request_user = $this->model->insertProveedor(
                        $nombre_proveedor,
                        $nombre_social,
                        $nit_proveedor,
                        $dias_credito,
                        $estado
                    );
                } else {
                    $option = 2;
                    $request_user = $this->model->updateProveedor(
                        $id_proveedor,
                        $nombre_proveedor,
                        $nombre_social,
                        $nit_proveedor,
                        $dias_credito,
                        $estado
                    );
                }

                if ($request_user > 0) {
                    if ($option == 1) {
                        $arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente.');
                    } else {
                        $arrResponse = array('status' => true, 'msg' => 'Datos actualizados correctamente.');
                    }
                } elseif ($request_user == 'exist') {
                    $arrResponse = array('status' => false, 'msg' => '¡Atención! El usuario ya existe.');
                } else {
                    $arrResponse = array('status' => false, 'msg' => 'No es posible almacenar los datos.');
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }



    public function Modulos()
    {
        $data['page_id'] = 'Modulos';
        $data['page_tag'] = "Modulos";
        $data['page_title'] = "Modulos";
        $data['page_name'] = "Modulos";
        $data['page_functions_js'] = "functions_modulos.js";
        $this->views->getView($this, "Modulos", $data);
    }

    public function getModulos()
    {
        $arrData = $this->model->Modulos();

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos.');
        } else {
            $arrResponse = array('status' => true, 'data' => $arrData);
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

}
