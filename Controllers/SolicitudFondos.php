<?php
class SolicitudFondos extends Controllers
{
    public function __construct()
    {
        parent::__construct();
    }

    public function SolicitudFondos()
    {
        $data['page_id'] = DASHBOARD;
        $data['page_tag'] = "Solicitud_Fondos";
        $data['page_title'] = "Solicitud_Fondos";
        $data['page_name'] = "Solicitud_Fondos";
        $data['page_functions_js'] = "functions_solicitud_fondos.js";

        $this->views->getView($this, "Solicitud_Fondos", $data);
    }
    public function getSolucitudesFondos()
    {
        $arrData = $this->model->selectSolicitudFondos();

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos.');
        } else {
            $arrResponse = array('status' => true, 'data' => $arrData);
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function Revision($contraseña)
    {
        $facturas = $this->model->getContraseña($contraseña);

        $data['facturas'] = $facturas;
        $data['page_id'] = 'Revision';
        $data['page_tag'] = "Revision";
        $data['page_title'] = "Revision";
        $data['page_name'] = "Revision";
        $data['page_functions_js'] = "functions_revision.js";
        $this->views->getView($this, "Revision", $data);
    }

}
