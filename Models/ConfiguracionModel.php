<?php
class ConfiguracionModel extends Mysql
{
    public function __construct()
    {
        parent::__construct();
    }

    public $role_id;


    public function Areas()
    {
        $sql = "SELECT 
            id_area, 
            nombre_area, 
            estado 
            FROM tb_areas";
        $request = $this->select_all($sql);
        return $request;
    }


    public function areasbyID($id_area)
    {
        $sql =
            "SELECT 
            id_area,
            nombre_area,
            estado
        FROM tb_areas
        where id_area = ?";
        $request = $this->select($sql, array($id_area));
        return $request;
    }

    public function insertAreas($nombre_area, $estado)
    {
        $sql = "INSERT INTO tb_areas (nombre_area, estado) 
                VALUES (?, ?)";
        $arrData = array($nombre_area, $estado);
        return $this->insert($sql, $arrData); // Devuelve el ID generado o false si falla
    }

    public function updateAreas(int $id_area, string $nombre_area, string $estado)
    {
        $sql = " UPDATE tb_areas SET
        nombre_area = ?,
        estado = ?
        WHERE id_area = ?";

        $arrData = array(
            $nombre_area,
            $estado,
            $id_area
        );

        $request = $this->update($sql, $arrData);
        return $request;
    }

    public function Proveedores()
    {
        $sql = "SELECT 
            id_proveedor, 
            nombre_proveedor,
            nombre_regimen,
            nombre_social,
            nit_proveedor,
            estado,
            fecha_creacion,
            dias_credito 
            FROM tb_proveedor tp
            INNER JOIN tb_regimen tg ON tp.regimen = tg.id_regimen
            ";
        $request = $this->select_all($sql);
        return $request;
    }

    public function proveedorbyID($id_proveedor)
    {
        $sql =
            "SELECT 
            id_proveedor, 
            nombre_proveedor, 
            nombre_social,
            nit_proveedor,
            estado,
            regimen,
            fecha_creacion,
            dias_credito 
        FROM tb_proveedor
        where id_proveedor = ?";
        $request = $this->select($sql, array($id_proveedor));
        return $request;
    }

    public function insertProveedor($nombre_proveedor, $nombre_social, $nit_proveedor, $dias_credito, $estado, $regimen)
    {
        $sql = "INSERT INTO tb_proveedor (nombre_proveedor, nombre_social, nit_proveedor, dias_credito, estado, regimen, fecha_creacion) 
                VALUES (?, ?, ?, ?, ?, ?, NOW())";
        $arrData = array($nombre_proveedor, $nombre_social, $nit_proveedor, $dias_credito, $estado, $regimen);
        return $this->insert($sql, $arrData); // Devuelve el ID generado o false si falla
    }

    public function updateProveedor($id_proveedor, $nombre_proveedor, $nombre_social, $nit_proveedor, $dias_credito, $estado, $regimen)
    {
        $sql = "UPDATE tb_proveedor SET
        nombre_proveedor = ?, 
        nombre_social = ?,
        nit_proveedor = ?,
        dias_credito = ?,
        estado = ?,
        regimen = ?
        WHERE id_proveedor = ?";

        $arrData = array(
            $nombre_proveedor,
            $nombre_social,
            $nit_proveedor,
            $dias_credito,
            $estado,
            $regimen,
            $id_proveedor
        );

        return $this->update($sql, $arrData);
    }

    public function firmasGrupo()
    {
        $sql = "SELECT 
            g.id_grupo,
            g.nombre_grupo,
            g.area_grupo as areas,
            g.categoria,
            ta.nombre_area,
            g.fecha_creacion,
            g.estado AS estado_grupo,
            COUNT(f.id_firma) AS total_firmas
        FROM tb_grupo_firmas g
        INNER JOIN tb_firmas f ON f.id_grupo = g.id_grupo
        INNER JOIN tb_areas ta ON g.area_grupo = ta.id_area
        GROUP BY g.id_grupo";
        $request = $this->select_all($sql);
        return $request;
    }

    public function insertGrupoFirmas($nombre, $area, $categoria, $fecha, $estado)
    {
        $sql = "INSERT INTO tb_grupo_firmas (nombre_grupo, area_grupo, categoria, fecha_creacion, estado)
            VALUES (?, ?, ?, ?, ?)";
        $arrData = [$nombre, $area, $categoria, $fecha, $estado];
        $request = $this->insert($sql, $arrData);

        return $request;
    }

    public function insertFirmaEnGrupo($data)
    {
        $sql = "INSERT INTO tb_firmas (id_usuario, nombre_usuario, cargo_usuario, orden, estado, img_firma, id_grupo)
            VALUES (?, ?, ?, ?, ?, NULL, ?)";

        $arrData = [
            $data['id_usuario'] ?? null, // 👈 si es null, se inserta NULL real
            $data['nombre_usuario'],
            $data['cargo_usuario'],
            $data['orden'],
            $data['estado'],
            $data['id_grupo']
        ];

        return $this->insert($sql, $arrData);
    }

    public function selectUsers()
    {
        $sql = "SELECT 
        e.id_usuario AS id,
        CONCAT(e.nombres, ' ', e.primer_apellido, ' ', e.segundo_apellido) AS nombre,
        CONCAT(e.nombres, ' ', e.primer_apellido, ' ', e.segundo_apellido) AS usuario,
        e.correo,
        e.estado as estado
    FROM tb_usuarios e
    WHERE e.estado = 'Activo'
    ORDER BY e.primer_apellido, e.segundo_apellido ASC";
        return $this->select_all($sql);
    }

    public function selectGrupoFirmasByID($id_grupo)
    {
        $sql = "SELECT 
                gf.id_grupo,
                gf.nombre_grupo,
                gf.area_grupo,
                gf.categoria,
                gf.fecha_creacion,
                gf.estado
            FROM tb_grupo_firmas gf
            WHERE gf.id_grupo = ?";
        return $this->select($sql, [$id_grupo]);
    }

    public function selectFirmasByGrupo($id_grupo)
    {
        $sql = "SELECT 
                f.id_firma,
                f.id_usuario,
                f.nombre_usuario,
                f.cargo_usuario,
                f.orden,
                f.estado
            FROM tb_firmas f
            WHERE f.id_grupo = ? and f.id_usuario is not null
            ORDER BY f.orden ASC";
        return $this->select_multi_parameters($sql, [$id_grupo]);
    }

    public function eliminarFirma($idFirma)
    {
        $sql = "DELETE FROM tb_firmas WHERE id_firma = ?";
        return $this->deletebyid($sql, [$idFirma]);
    }

    public function actualizarGrupoFirmas($idGrupo, $nombre, $area, $categoria)
    {
        $sql = "UPDATE tb_grupo_firmas SET nombre_grupo = ?, area_grupo = ?, categoria = ? WHERE id_grupo = ?";
        return $this->update($sql, [$nombre, $area, $categoria, $idGrupo]);
    }

    public function existeFirmaEnGrupo($idGrupo, $orden)
    {
        $sql = "SELECT id_firma FROM tb_firmas WHERE id_grupo = ? AND orden = ?";
        $request = $this->select($sql, [$idGrupo, $orden]);
        return $request ? true : false;
    }

    public function actualizarFirmaEnGrupo($data)
    {
        $sql = "UPDATE tb_firmas
            SET id_usuario = ?, nombre_usuario = ?, cargo_usuario = ?
            WHERE id_grupo = ? AND orden = ?";
        $arrData = [
            $data['id_usuario'],
            $data['nombre_usuario'],
            $data['cargo_usuario'],
            $data['id_grupo'],
            $data['orden']
        ];
        return $this->update($sql, $arrData);
    }

    public function verificarGrupoPorAreaYCategoria($area, $categoria)
    {
        $sql = "SELECT COUNT(*) as total 
            FROM tb_grupo_firmas 
            WHERE area_grupo = ? 
              AND categoria = ?";
        $request = $this->select($sql, [$area, $categoria]);
        return $request['total'] > 0;
    }

        public function verificarGrupoPorAreaYCategoriabyID($area, $categoria, $idGrupo)
    {
        $sql = "SELECT COUNT(*) as total 
            FROM tb_grupo_firmas 
            WHERE area_grupo = ? 
              AND categoria = ?
              AND id_grupo != ?";
        $request = $this->select($sql, [$area, $categoria, $idGrupo]);
        return $request['total'] > 0;
    }


    public function Roles()
    {
        $sql = "SELECT
            id,
            role_name,
            estado
            FROM tb_roles";
        $request = $this->select_all($sql);
        return $request;
    }

    public function insertRol($role_name)
    {
        $sql = "INSERT INTO tb_roles (role_name, estado) 
                VALUES (?, 'Activo')";
        $arrData = array($role_name);
        return $this->insert($sql, $arrData); // Devuelve el ID generado o false si falla
    }

    public function getAllModulos()
    {
        $sql = "SELECT id_modulo FROM tb_modulos WHERE estado = 'Activo'"; // puedes quitar el WHERE si quieres todos
        return $this->select_all($sql);
    }

    public function insertRolModulo($role_id, $modulo_id)
    {
        $sql = "INSERT INTO tb_permisos (role_id, modulo_id, acceder, crear, editar, eliminar)
            VALUES (?, ?, 0, 0, 0, 0)";
        return $this->insert($sql, [$role_id, $modulo_id]);
    }

    public function getRolbyID($id)
    {
        $sql = "SELECT
                    rs.id,
                    rs.role_name,
                    rm.modulo_id,
                    m.nombre_modulo,
                    rm.crear,
                    rm.acceder,
                    rm.editar,
                    rm.eliminar
                FROM tb_permisos rm
                INNER JOIN tb_roles rs ON rm.role_id = rs.id
                INNER JOIN tb_modulos m ON rm.modulo_id = m.id_modulo
                WHERE rs.id = ?";
        $data = $this->select_multi($sql, [$id]);

        // 🔒 Aplicar lógica de restricciones desde backend
        foreach ($data as &$item) {
            $item['disabled_crear'] = false;
            $item['disabled_editar'] = false;
            $item['disabled_eliminar'] = false;

            switch ($item['modulo_id']) {
                case 1:
                    $item['disabled_crear'] = true;
                    $item['disabled_editar'] = true;
                    $item['disabled_eliminar'] = true;
                    break;
                case 2:
                    $item['disabled_eliminar'] = true;
                    break;
                case 3:
                    $item['disabled_crear'] = true;
                    $item['disabled_editar'] = true;
                    $item['disabled_eliminar'] = true;
                    break;
            }
        }

        return $data;
    }

    public function Modulos()
    {
        $sql = "SELECT
            id_modulo,
            nombre_modulo,
            estado
            FROM tb_modulos
            WHERE tipo = 'correo'";
        $request = $this->select_all($sql);
        return $request;
    }

    public function insertarModulo($nombre, $tipo, $estadoSistema)
    {
        $sql = "INSERT INTO tb_modulos (nombre_modulo, tipo, estado) VALUES (?, ?, ?)";
        $arrData = array($nombre, $tipo, $estadoSistema);
        $request = $this->insert($sql, $arrData);
        return $request; // devuelve el id del nuevo módulo
    }

    public function grupoCorreos()
    {
        $sql = "SELECT
            tr.id_grupo_correo,
            tr.nombre_grupo,
            ta.nombre_area as area,
            tc.nombre_categoria as categoria,
            tr.estado
            FROM tb_grupo_correos tr
            INNER JOIN tb_areas ta ON tr.area = ta.id_area
            INNER JOIN tb_categoria tc ON tr.categoria = tc.id_categoria
            ";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectCategoria()
    {
        $sql = "SELECT 
        id_categoria, 
        nombre_categoria, 
        estado
        FROM tb_categoria";
        $request = $this->select_all($sql);
        return $request;
    }

    public function insertGrupoCorreo($nombre, $area, $categoria, $estado)
    {
        $sql = "INSERT INTO tb_grupo_correos (
                nombre_grupo,
                area,
                categoria,
                estado
            )
            VALUES (?, ?, ?, ?)";
        $arrData = [
            $nombre,
            $area,
            $categoria,
            $estado
        ];
        $request = $this->insert($sql, $arrData);
        return $request;
    }

    public function verificarGrupoCorreoPorAreaYCategoria($area, $categoria)
    {
        $sql = "SELECT COUNT(*) AS total 
            FROM tb_grupo_correos 
            WHERE area = ? AND categoria = ? AND estado = 'Activo'";
        $arrData = [$area, $categoria];
        $request = $this->select($sql, $arrData);

        return $request && $request['total'] > 0;
    }



    public function selectGrupoCorreoByID($id_grupo)
    {
        $sql = "SELECT 
        id_grupo_correo, 
        nombre_grupo, 
        area, 
        categoria,
        estado 
        FROM tb_grupo_correos
        WHERE id_grupo_correo = ?";
        return $this->select($sql, [$id_grupo]);
    }

    public function selectFasesConUsuarios($categoria, $id_grupo)
    {
        $sql = "SELECT 
                f.id_fase, 
                f.orden_fase, 
                f.nombre_base, 
                f.categoria,
                fc.usuario,
                u.nombres,
                u.correo
            FROM tb_fases f
            LEFT JOIN tb_fase_correos fc ON f.id_fase = fc.fase AND fc.grupo = ?
            LEFT JOIN tb_usuarios u ON fc.usuario = u.id_usuario
            WHERE f.categoria = ?
            ORDER BY f.orden_fase, u.nombres";

        $result = $this->select_multi_parameters($sql, [$id_grupo, $categoria]);

        // DEBUG: Verificar resultado de la consulta
        error_log("Resultado de selectFasesConUsuarios: " . print_r($result, true));

        return $result;
    }

    public function selectFases($categoria)
    {
        $sql = "SELECT 
        id_fase, 
        orden_fase, 
        nombre_base, 
        categoria
        FROM tb_fases 
        WHERE categoria = ?";
        return $this->select_multi_parameters($sql, [$categoria]);
    }

    public function actualizarGrupoCorreos($idGrupo, $nombre, $area)
    {
        $sql = "UPDATE tb_grupo_correos SET nombre_grupo = ?, area = ? WHERE id_grupo_correo = ?";
        return $this->update($sql, [$nombre, $area, $idGrupo]);
    }

    public function eliminarFaseCorreosPorGrupo($id_grupo)
    {
        $sql = "DELETE FROM tb_fase_correos WHERE grupo = ?";
        return $this->deletebyid($sql, [$id_grupo]);
    }

    public function insertarFaseCorreo($usuario, $fase, $grupo)
    {
        $sql = "INSERT INTO tb_fase_correos (usuario, fase, grupo) VALUES (?, ?, ?)";
        return $this->insert($sql, [$usuario, $fase, $grupo]);
    }

    public function getGrupoCorreoByID($id_grupo)
    {
        $sql = "SELECT * FROM tb_grupo_correos WHERE id_grupo_correo = ?";
        return $this->select($sql, [$id_grupo]);
    }

    public function getFasesPorCategoria($id_categoria)
    {
        $sql = "SELECT * FROM tb_fases WHERE categoria = ? ORDER BY orden_fase ASC";
        return $this->select_multi($sql, [$id_categoria]);
    }

    public function getFaseCorreosPorGrupo($id_grupo)
    {
        $sql = "SELECT fc.*, u.nombre, u.email, f.nombre_base 
            FROM tb_fase_correos fc
            INNER JOIN tb_usuarios u ON u.id_usuario = fc.usuario
            INNER JOIN tb_fases f ON f.id_fase = fc.fase
            WHERE fc.grupo = ?";
        return $this->select_multi($sql, [$id_grupo]);
    }

    public function permisosModulo(int $role_id)
    {
        $this->role_id = $role_id;
        $sql = "SELECT 
            r.role_id,
            r.modulo_id,
            m.nombre_modulo as modulo,
            r.crear,
            r.acceder,
            r.editar,
            r.eliminar 
        FROM tb_permisos r 
        INNER JOIN tb_modulos m ON r.modulo_id = m.id_modulo
        WHERE r.role_id = $this->role_id";

        $request = $this->select_all($sql);
        $arrPermisos = array();
        for ($i = 0; $i < count($request); $i++) {
            $arrPermisos[$request[$i]['modulo_id']] = $request[$i];
        }
        return $arrPermisos;
    }


    public function updatePermiso($idRol, $moduloNombre, $crear, $leer, $editar, $eliminar)
    {
        $sql = "UPDATE tb_permisos 
            SET crear = ?, acceder = ?, editar = ?, eliminar = ? 
            WHERE role_id = ? AND modulo_id = (SELECT id_modulo FROM tb_modulos WHERE nombre_modulo = ?)";

        $params = [$crear, $leer, $editar, $eliminar, $idRol, $moduloNombre];
        return $this->update($sql, $params);
    }



}