<?php
class ConfiguracionModel extends Mysql
{
    public function __construct()
    {
        parent::__construct();
    }

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



    public function Modulos()
    {
        $sql = "SELECT 
            tm.id_modulo,
            tm.nombre_modulo,  
            ta.nombre_area,
            tm.estado
            FROM tb_modulos tm
            INNER JOIN tb_areas ta ON tm.id_area = ta.id_area";
        $request = $this->select_all($sql);
        return $request;
    }

    public function firmasGrupo()
    {
        $sql = "SELECT 
            g.id_grupo,
            g.nombre_grupo,
            g.area_grupo as areas,
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

    public function insertGrupoFirmas($nombre, $area, $fecha, $estado)
    {
        $sql = "INSERT INTO tb_grupo_firmas (nombre_grupo, area_grupo, fecha_creacion, estado)
            VALUES (?, ?, ?, ?)";
        $arrData = [$nombre, $area, $fecha, $estado];
        $request = $this->insert($sql, $arrData);

        return $request; // Devuelve el ID del grupo insertado
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
            concat( e.nombres,' ', e.primer_apellido,' ',e.segundo_apellido ) AS usuario,
            concat( e.primer_apellido,' ',e.segundo_apellido ) AS apellidos,
            e.estado as estado
        FROM tb_usuarios e
        ORDER BY apellidos ASC
        ";
        $request = $this->select_all($sql);
        return $request;
    }

    // Traer grupo por ID
    public function selectGrupoFirmasByID($id_grupo)
    {
        $sql = "SELECT 
                gf.id_grupo,
                gf.nombre_grupo,
                gf.area_grupo,
                gf.fecha_creacion,
                gf.estado
            FROM tb_grupo_firmas gf
            WHERE gf.id_grupo = ?";
        return $this->select($sql, [$id_grupo]);
    }

    // Traer todas las firmas de un grupo
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

    public function actualizarGrupoFirmas($idGrupo, $nombre, $area)
    {
        $sql = "UPDATE tb_grupo_firmas SET nombre_grupo = ?, area_grupo = ? WHERE id_grupo = ?";
        return $this->update($sql, [$nombre, $area, $idGrupo]);
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


}