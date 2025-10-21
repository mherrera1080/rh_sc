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

        $data['facturas'] = $facturas;
        $data['page_id'] = 'Revision';
        $data['page_tag'] = "Revision";
        $data['page_title'] = "Revision";
        $data['page_name'] = "Revision";
        $data['page_functions_js'] = "functions_revision.js";
        $this->views->getView($this, "Revision", $data);
    }



}
