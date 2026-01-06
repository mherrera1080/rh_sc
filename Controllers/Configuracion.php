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
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron registros previos.');
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
                    log_Actividad(
                        $_SESSION['PersonalData']['no_empleado'],
                        $_SESSION['PersonalData']['nombre_completo'],
                        "Configuracion",
                        "Area Creada: " . $nombre_area
                    );
                } else {
                    $option = 2;
                    $request_user = $this->model->updateAreas(
                        $id_area,
                        $nombre_area,
                        $estado
                    );

                    log_Actividad(
                        $_SESSION['PersonalData']['no_empleado'],
                        $_SESSION['PersonalData']['nombre_completo'],
                        "Configuracion",
                        "Area actualizada: " . $nombre_area
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
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron registros previos.');
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

                // ✔ Checkboxes (solo se evalúan en update)
                $iva = isset($_POST['iva']) ? 1 : 0;
                $isr = isset($_POST['isr']) ? 1 : 0;
                if ($id_proveedor == 0) {

                    // INSERT (sin IVA / ISR)
                    $request_user = $this->model->insertProveedor(
                        $nombre_proveedor,
                        $nombre_social,
                        $nit_proveedor,
                        $dias_credito,
                        $estado,
                        $regimen
                    );

                    log_Actividad(
                        $_SESSION['PersonalData']['no_empleado'],
                        $_SESSION['PersonalData']['nombre_completo'],
                        "Configuracion",
                        "Proveedor Creado: " . $nombre_proveedor
                    );

                } else {

                    // UPDATE (con IVA / ISR)
                    $request_user = $this->model->updateProveedor(
                        $id_proveedor,
                        $nombre_proveedor,
                        $nombre_social,
                        $nit_proveedor,
                        $dias_credito,
                        $estado,
                        $regimen,
                        $iva,
                        $isr
                    );

                    log_Actividad(
                        $_SESSION['PersonalData']['no_empleado'],
                        $_SESSION['PersonalData']['nombre_completo'],
                        "Configuracion",
                        "Proveedor actualizado: " . $nombre_proveedor
                    );
                }

                if ($request_user > 0) {
                    $arrResponse = array(
                        'status' => true,
                        'msg' => $id_proveedor == 0
                            ? 'Datos guardados correctamente.'
                            : 'Datos actualizados correctamente.'
                    );
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

    public function getGrupoFirmas()
    {
        $arrData = $this->model->firmasGrupo();

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron registros previos.');
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
            $categoria = $_POST['categoria'] ?? [];
            $usuarios = $_POST['usuarios'] ?? [];
            $nombres = $_POST['nombres'] ?? [];
            $roles = $_POST['roles'] ?? [];
            $orden = $_POST['orden'] ?? [];

            if (empty($nombre_grupo) || empty($usuarios) || empty($nombres) || empty($roles) || empty($orden)) {
                echo json_encode(["status" => false, "message" => "Faltan datos del formulario."]);
                exit;
            }

            if (count($usuarios) === 0) {
                echo json_encode(["status" => false, "message" => "Debe agregar al menos un firmante."]);
                exit;
            }

            $existe = $this->model->verificarGrupoPorAreaYCategoria($areas, $categoria);

            if ($existe) {
                echo json_encode([
                    "status" => false,
                    "message" => "Ya existe un grupo de tipo '{$categoria}' en esta área. No se pueden duplicar."
                ]);
                exit;
            }

            $fecha_creacion = date("Y-m-d");
            $estado = "Activo";

            $idGrupo = $this->model->insertGrupoFirmas($nombre_grupo, $areas, $categoria, $fecha_creacion, $estado);

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

            log_Actividad(
                $_SESSION['PersonalData']['no_empleado'],
                $_SESSION['PersonalData']['nombre_completo'],
                "Configuracion",
                "Grupo de Firmas creado: " . $nombre_grupo
            );
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
        $idGrupo = $_POST['id_grupo_edit'];
        $nombre = trim($_POST['nombre_grupo_edit'] ?? '');
        $areas = $_POST['areas'] ?? '[]';
        $categoria = $_POST['categoria'] ?? '[]';
        $firmasEliminadas = json_decode($_POST['firmas_eliminadas'] ?? '[]', true);
        $firmantes = json_decode($_POST['firmantes'] ?? '[]', true);

        if ($idGrupo <= 0) {
            echo json_encode(["status" => false, "message" => "ID de grupo invalido."]);
            exit;
        }

        $existe = $this->model->verificarGrupoPorAreaYCategoriabyID($areas, $categoria, $idGrupo);

        if ($existe) {
            echo json_encode([
                "status" => false,
                "message" => "Ya existe un grupo de tipo '{$categoria}' en esta área. No se pueden duplicar."
            ]);
            exit;
        }


        // 🧹 Eliminar firmas marcadas
        if (!empty($firmasEliminadas)) {
            foreach ($firmasEliminadas as $idFirma) {
                $this->model->eliminarFirma($idFirma);
            }
        }


        // 🧩 Actualizar grupo
        $this->model->actualizarGrupoFirmas($idGrupo, $nombre, $areas, $categoria);

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

        log_Actividad(
            $_SESSION['PersonalData']['no_empleado'],
            $_SESSION['PersonalData']['nombre_completo'],
            "Configuracion",
            "Grupo de Firmas Actualizada: "
        );

        echo json_encode(["status" => true, "message" => "Grupo actualizado correctamente."]);
        exit;
    }


    public function getUsers()
    {
        $htmlOptions = "<option selected disabled>Seleccione un Usuario ...</option>";
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
        $htmlOptions = '<option value="" selected disabled>Seleccione una Area</option>';
        $arrData = $this->model->Areas();
        if (count($arrData) > 0) {
            for ($i = 0; $i < count($arrData); $i++) {
                $htmlOptions .= '<option value="' . $arrData[$i]['id_area'] . '">' . $arrData[$i]['nombre_area'] . '</option>';
            }
        }
        echo $htmlOptions;
        die();
    }

    public function getSelectCategoria()
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

    public function getSelectRol()
    {
        $htmlOptions = '<option value="" selected disabled>Seleccione un rol</option>';
        $arrData = $this->model->Roles();
        if (count($arrData) > 0) {
            for ($i = 0; $i < count($arrData); $i++) {
                $htmlOptions .= '<option value="' . $arrData[$i]['id'] . '">' . $arrData[$i]['role_name'] . '</option>';
            }
        }
        echo $htmlOptions;
        die();
    }

    public function getReferenciaID($id_referencia)
    {
        $data = $this->model->referenciaID($id_referencia);
        if ($data) {
            $arrResponse = array('status' => true, 'data' => $data);
        } else {
            $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }


    public function Roles()
    {
        $data['page_id'] = 'Roles';
        $data['page_tag'] = "Roles";
        $data['page_title'] = "Roles";
        $data['page_name'] = "Roles";
        $data['page_functions_js'] = "functions_roles.js";
        $this->views->getView($this, "Roles", $data);
    }

    public function getRoles()
    {
        $arrData = $this->model->Roles();

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron registros previos.');
        } else {
            $arrResponse = array('status' => true, 'data' => $arrData);
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }


    public function insertRol()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $role_name = $_POST['nombre_rol'];

            if (empty($role_name)) {
                echo json_encode(["status" => false, "msg" => "Problemas al obtener datos."]);
                exit;
            }

            // 1️⃣ Insertar el rol y obtener su ID
            $role_id = $this->model->insertRol($role_name);

            if (!$role_id) {
                echo json_encode(["status" => false, "msg" => "Error al registrar el rol."]);
                exit;
            }

            // 2️⃣ Obtener todos los módulos
            $modulos = $this->model->getAllModulos();

            // 3️⃣ Insertar las relaciones por cada módulo
            foreach ($modulos as $modulo) {
                $this->model->insertRolModulo($role_id, $modulo['id_modulo']);
            }

            log_Actividad(
                $_SESSION['PersonalData']['no_empleado'],
                $_SESSION['PersonalData']['nombre_completo'],
                "Configuracion",
                "Rol ingresado: " . $role_name
            );

            echo json_encode(["status" => true, "message" => "Rol y permisos creados correctamente."]);
        }
    }

    public function getRolbyID($id)
    {
        $data = $this->model->getRolbyID($id);

        if (empty($data)) {
            echo json_encode(["status" => false, "msg" => "No se encontró el rol."]);
            return;
        }

        echo json_encode(["status" => true, "data" => $data]);
    }

    public function Modulos()
    {
        $data['page_id'] = 'modulos';
        $data['page_tag'] = "modulos";
        $data['page_title'] = "modulos";
        $data['page_name'] = "modulos";
        $data['page_functions_js'] = "functions_modulos.js";
        $this->views->getView($this, "Modulos", $data);
    }

    public function getModulos()
    {
        $arrData = $this->model->Modulos();

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron registros previos.');
        } else {
            $arrResponse = array('status' => true, 'data' => $arrData);
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }


    public function Notificaciones()
    {
        $data['page_id'] = 'Notificaciones';
        $data['page_tag'] = "Notificaciones";
        $data['page_title'] = "Notificaciones";
        $data['page_name'] = "Notificaciones";
        $data['page_functions_js'] = "functions_notificaciones.js";
        $this->views->getView($this, "Notificaciones", $data);
    }

    public function getGrupoCorreos()
    {
        $arrData = $this->model->grupoCorreos();

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron registros previos.');
        } else {
            $arrResponse = array('status' => true, 'data' => $arrData);
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getGruposbyArea($area)
    {
        $arrData = $this->model->getGruposAreas($area);

        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron registros previos.');
        } else {
            $arrResponse = array('status' => true, 'data' => $arrData);
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getFases(string $categoria)
    {
        $fases = $this->model->selectFases($categoria);
        echo json_encode($fases, JSON_UNESCAPED_UNICODE);
    }

    public function getUsuarios()
    {
        $usuarios = $this->model->selectUsuarios();
        echo json_encode($usuarios, JSON_UNESCAPED_UNICODE);
    }

    public function getCorreos()
    {
        $arrData = $this->model->selectUsers();
        echo json_encode($arrData);
        exit;
    }

    public function setFaseCorreo()
    {
        if ($_POST) {
            $usuario = trim($_POST['usuario'] ?? "");
            $fase = trim($_POST['fase'] ?? "");
            $grupo = trim($_POST['grupo'] ?? "");
            $requestInsert = $this->model->insertarFaseCorreo($usuario, $fase, $grupo);
            if ($requestInsert > 0) {
                $arrResponse = [
                    'status' => true,
                    'msg' => 'Registro insertado correctamente.'
                ];
            } else {
                $arrResponse = [
                    'status' => false,
                    'msg' => 'Error al insertar el registro.'
                ];
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
    }

    public function getUsuariosByGrupoYFase($param)
    {
        // $param viene como "7,1"
        list($grupo, $fase) = explode(",", $param);

        $data = $this->model->getUsuariosByGrupoYFase(
            intval($grupo),
            intval($fase)
        );

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function addUsuarioFase($param)
    {
        // $param = "7,1,23"
        $parts = array_map('trim', explode(',', $param));
        if (count($parts) !== 3) {
            echo json_encode(['status' => false, 'msg' => 'Parámetros incorrectos'], JSON_UNESCAPED_UNICODE);
            return;
        }
        [$grupo, $fase, $usuario] = $parts;

        $grupo = intval($grupo);
        $fase = intval($fase);
        $usuario = intval($usuario);

        $request = $this->model->insertUsuarioFase($grupo, $fase, $usuario);
        if ($request > 0) {
            echo json_encode(['status' => true, 'msg' => 'Usuario asignado correctamente'], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(['status' => false, 'msg' => 'No se pudo asignar el usuario'], JSON_UNESCAPED_UNICODE);
        }
    }



    public function removeUsuarioFase($param)
    {
        // $param = "7,1,23"
        $parts = array_map('trim', explode(',', $param));
        if (count($parts) !== 3) {
            echo json_encode(['status' => false, 'msg' => 'Parámetros incorrectos'], JSON_UNESCAPED_UNICODE);
            return;
        }
        [$grupo, $fase, $usuario] = $parts;

        $grupo = intval($grupo);
        $fase = intval($fase);
        $usuario = intval($usuario);

        $request = $this->model->deleteUsuarioFase($grupo, $fase, $usuario);

        if ($request > 0) {
            echo json_encode(['status' => true, 'msg' => 'Usuario asignado correctamente'], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(['status' => false, 'msg' => 'No se pudo asignar el usuario'], JSON_UNESCAPED_UNICODE);
        }
    }


    public function updatePermissions()
    {
        $data = json_decode(file_get_contents("php://input"), true); // Obtener los datos JSON

        $idRol = $data['idRol'];
        $permisos = $data['permisos'];

        foreach ($permisos as $permiso) {
            // Aquí deberías tener lógica para actualizar los permisos en la base de datos
            $moduloNombre = $permiso['moduloNombre'];
            $crear = $permiso['crear'];
            $acceder = $permiso['acceder'];
            $editar = $permiso['editar'];
            $eliminar = $permiso['eliminar'];

            // Actualiza los permisos en la base de datos (asegúrate de tener el método adecuado)
            // Por ejemplo:
            $this->model->updatePermiso($idRol, $moduloNombre, $crear, $acceder, $editar, $eliminar);
        }

        log_Actividad(
            $_SESSION['PersonalData']['no_empleado'],
            $_SESSION['PersonalData']['nombre_completo'],
            "Configuracion",
            "Se actualizaron permisos del rol : " . $idRol
        );
        // Respuesta
        echo json_encode(['status' => true, 'msg' => 'Permisos actualizados con éxito.']);
    }


}