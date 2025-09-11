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

}
