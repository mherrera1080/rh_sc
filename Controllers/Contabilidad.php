<?php
session_start();

class Contabilidad extends Controllers
{
    public function __construct()
    {
        parent::__construct();
        if (empty($_SESSION['login'])) {
            header('Location: ' . base_url() . '/login');
            die();
        }
    }

    public function Contabilidad()
    {
        $data['page_id'] = DASHBOARD;
        $data['page_tag'] = "Contabilidad";
        $data['page_title'] = "Contabilidad";
        $data['page_name'] = "Contabilidad";
        $data['page_functions_js'] = "functions_contabilidad.js";

        $this->views->getView($this, "Contabilidad", $data);
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


    public function Contraseñas()
    {
        $data['page_id'] = CONTABILIDAD;
        $data['page_tag'] = "Contraseñas";
        $data['page_title'] = "Contraseñas";
        $data['page_name'] = "Contraseñas";
        $data['page_functions_js'] = "functions_conta_contraseñas.js";

        $this->views->getView($this, "Contraseñas", $data);
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

    public function Detalles($contraseña)
    {
        $facturas = $this->model->getContraseñaContra($contraseña);
        $id_solicitud = $facturas["anticipo"];
        $anticipoinfo = $this->model->getAnticipoInfo($id_solicitud);

        $data['facturas'] = $facturas;
        $data['anticipoinfo'] = $anticipoinfo; // booleano
        $data['page_id'] = 'Detalles';
        $data['page_tag'] = "Detalles";
        $data['page_title'] = "Detalles";
        $data['page_name'] = "Detalles";
        $data['page_functions_js'] = "functions_contabilidad_detalles.js";

        $this->views->getView($this, "Detalles", $data);
    }

    public function Facturas()
    {
        $data['page_id'] = CONTABILIDAD;
        $data['page_tag'] = "Facturas";
        $data['page_title'] = "Facturas";
        $data['page_name'] = "Facturas";
        $data['page_functions_js'] = "functions_conta_facturas.js";

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


}
