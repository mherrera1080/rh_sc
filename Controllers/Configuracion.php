<?php
session_start();

class Configuracion extends Controllers
{
    public function __construct()
    {
        parent::__construct();
        if (empty($_SESSION['login'])) {
            header('Location: ' . base_url() . '/login');
            die();
        }
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
            if (empty($_POST['nombre_proveedor'])) {
                $arrResponse = array("status" => false, "msg" => 'Datos Incorrectos');
            } else {
                $id_proveedor = intval($_POST['id_proveedor']);
                $nombre_proveedor = $_POST['nombre_proveedor'];
                $nombre_social = $_POST['nombre_social'];
                $nit_proveedor = $_POST['nit_proveedor'];
                $dias_credito = $_POST['dias_credito'];
                $estado = $_POST['estado'];
                $regimen = $_POST['regimen'];

                if ($id_proveedor == 0) {
                    $option = 1;
                    $request_user = $this->model->insertProveedor(
                        $nombre_proveedor,
                        $nombre_social,
                        $nit_proveedor,
                        $dias_credito,
                        $estado,
                        $regimen
                    );
                } else {
                    $option = 2;
                    $request_user = $this->model->updateProveedor(
                        $id_proveedor,
                        $nombre_proveedor,
                        $nombre_social,
                        $nit_proveedor,
                        $dias_credito,
                        $estado,
                        $regimen
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


    public function Firmas()
    {
        $data['page_id'] = 'Firmas';
        $data['page_tag'] = "Firmas";
        $data['page_title'] = "Firmas";
        $data['page_name'] = "Firmas";
        $data['page_functions_js'] = "functions_firmas.js";
        $this->views->getView($this, "Firmas", $data);
    }

    public function getFirmasGroup()
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

    public function getGrupoFirmas()
    {
        $arrData = $this->model->firmasGrupo();

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos.');
        } else {
            $arrResponse = array('status' => true, 'data' => $arrData);
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function guardarGrupoFirmas()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // 📦 Recibir datos del formulario
            $nombre_grupo = trim($_POST['nombre_grupo'] ?? '');
            $areas = $_POST['areas'] ?? [];
            $usuarios = $_POST['usuarios'] ?? [];
            $nombres = $_POST['nombres'] ?? [];
            $roles = $_POST['roles'] ?? [];
            $orden = $_POST['orden'] ?? [];

            // 🧩 Validación inicial
            if (empty($nombre_grupo) || empty($usuarios) || empty($nombres) || empty($roles) || empty($orden)) {
                echo json_encode(["status" => false, "message" => "Faltan datos del formulario."]);
                exit;
            }

            // 🔎 Validar que haya al menos un firmante
            if (count($usuarios) === 0) {
                echo json_encode(["status" => false, "message" => "Debe agregar al menos un firmante."]);
                exit;
            }

            // 📅 Insertar el grupo principal
            $fecha_creacion = date("Y-m-d");
            $estado = "Activo";

            $idGrupo = $this->model->insertGrupoFirmas($nombre_grupo, $areas, $fecha_creacion, $estado);

            if (!$idGrupo) {
                echo json_encode(["status" => false, "message" => "Error al guardar el grupo de firmas."]);
                exit;
            }

            // 🔁 Insertar las firmas relacionadas
            $errores = [];
            foreach ($usuarios as $i => $usuario) {
                $idUsuario = !empty($usuario) ? $usuario : null; // 👈 convierte vacío a null
                $nombreUsuario = $nombres[$i] ?? '';
                $cargo = $roles[$i] ?? '';
                $ordenFirma = $orden[$i] ?? 0;

                if (empty($cargo)) {
                    $errores[] = "Faltan datos en la firma número " . ($i + 1);
                    continue;
                }

                $insert = $this->model->insertFirmaEnGrupo([
                    'id_grupo' => $idGrupo,
                    'id_usuario' => $idUsuario, // 👈 aquí puede ir null
                    'nombre_usuario' => $nombreUsuario,
                    'cargo_usuario' => $cargo,
                    'estado' => 'Activo',
                    'orden' => $ordenFirma
                ]);

                if (!$insert) {
                    $errores[] = "Error al guardar la firma del usuario: {$nombreUsuario}";
                }
            }

            // ⚠️ Validar errores
            if (!empty($errores)) {
                echo json_encode([
                    "status" => false,
                    "message" => "Algunos firmantes no se pudieron guardar.",
                    "errors" => $errores
                ]);
                exit;
            }

            // ✅ Respuesta final
            echo json_encode([
                "status" => true,
                "message" => "Grupo de firmas creado correctamente."
            ]);
            exit;
        }
    }

    public function actualizarGrupoFirmas()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST')
            return;

        $idGrupo = intval($_POST['id_grupo_edit'] ?? 0);
        $nombre = trim($_POST['nombre_grupo_edit'] ?? '');
        $areas = $_POST['areas'] ?? '[]';
        $firmasEliminadas = json_decode($_POST['firmas_eliminadas'] ?? '[]', true);
        $firmantes = json_decode($_POST['firmantes'] ?? '[]', true);

        if ($idGrupo <= 0) {
            echo json_encode(["status" => false, "message" => "ID de grupo inválido."]);
            exit;
        }

        // 🧹 Eliminar firmas marcadas
        if (!empty($firmasEliminadas)) {
            foreach ($firmasEliminadas as $idFirma) {
                $this->model->eliminarFirma($idFirma);
            }
        }

        // 🧩 Actualizar grupo
        $this->model->actualizarGrupoFirmas($idGrupo, $nombre, $areas);

        // 🔁 Actualizar o insertar firmas restantes
        if (!empty($firmantes)) {
            foreach ($firmantes as $firma) {
                $idFirma = isset($firma['idFirma']) ? intval($firma['idFirma']) : null;
                $usuario = $firma['usuario'] ?? null;
                $nombreUsuario = $firma['nombres'] ?? '';
                $cargo = $firma['rol'] ?? '';
                $ordenFirma = $firma['orden'] ?? 0;

                if ($idFirma) {
                    // Actualizar firma existente
                    $this->model->actualizarFirmaEnGrupo([
                        'id_firma' => $idFirma,
                        'id_grupo' => $idGrupo,
                        'id_usuario' => $usuario,
                        'nombre_usuario' => $nombreUsuario,
                        'cargo_usuario' => $cargo,
                        'orden' => $ordenFirma
                    ]);
                } else {
                    // Insertar nueva firma
                    $this->model->insertFirmaEnGrupo([
                        'id_grupo' => $idGrupo,
                        'id_usuario' => $usuario,
                        'nombre_usuario' => $nombreUsuario,
                        'cargo_usuario' => $cargo,
                        'estado' => 'Activo',
                        'orden' => $ordenFirma
                    ]);
                }
            }
        }

        echo json_encode(["status" => true, "message" => "Grupo actualizado correctamente."]);
        exit;
    }

    public function getUsers()
    {
        $htmlOptions = "";
        $arrData = $this->model->selectUsers();
        if (count($arrData) > 0) {
            for ($i = 0; $i < count($arrData); $i++) {
                $htmlOptions .= '<option value="' . $arrData[$i]['id'] . '">' . $arrData[$i]['usuario'] . '</option>';
            }
        }
        echo $htmlOptions;
        die();
    }

    public function getGrupoFirmasByID($id_grupo)
    {
        $grupo = $this->model->selectGrupoFirmasByID($id_grupo);
        if (!$grupo) {
            echo json_encode(["status" => false, "message" => "No se encontró el grupo."]);
            exit;
        }

        $firmas = $this->model->selectFirmasByGrupo($id_grupo);

        echo json_encode([
            "status" => true,
            "data" => [
                "grupo" => $grupo,
                "firmas" => $firmas
            ]
        ]);
        exit;
    }

    public function getSelectArea()
    {
        $htmlOptions = "";
        $arrData = $this->model->Areas();
        if (count($arrData) > 0) {
            for ($i = 0; $i < count($arrData); $i++) {
                $htmlOptions .= '<option value="' . $arrData[$i]['id_area'] . '">' . $arrData[$i]['nombre_area'] . '</option>';
            }
        }
        echo $htmlOptions;
        die();
    }

}
