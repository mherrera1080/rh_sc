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
            nombre_social,
            nit_proveedor,
            estado,
            fecha_creacion,
            dias_credito 
            FROM tb_proveedor";
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
            fecha_creacion,
            dias_credito 
        FROM tb_proveedor
        where id_proveedor = ?";
        $request = $this->select($sql, array($id_proveedor));
        return $request;
    }

    public function insertProveedor($nombre_proveedor, $nombre_social, $nit_proveedor, $dias_credito, $estado)
    {
        $sql = "INSERT INTO tb_proveedor (nombre_proveedor, nombre_social, nit_proveedor, dias_credito, estado, fecha_creacion) 
                VALUES (?, ?, ?, ?, ?, NOW())";
        $arrData = array($nombre_proveedor, $nombre_social, $nit_proveedor, $dias_credito, $estado);
        return $this->insert($sql, $arrData); // Devuelve el ID generado o false si falla
    }

    public function updateProveedor($id_proveedor, $nombre_proveedor, $nombre_social, $nit_proveedor, $dias_credito, $estado)
    {
        $sql = "UPDATE tb_proveedor SET
        nombre_proveedor = ?, 
        nombre_social = ?,
        nit_proveedor = ?,
        dias_credito = ?,
        estado = ?
        WHERE id_proveedor = ?";

        $arrData = array(
            $nombre_proveedor,
            $nombre_social,
            $nit_proveedor,
            $dias_credito,
            $estado,
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

}