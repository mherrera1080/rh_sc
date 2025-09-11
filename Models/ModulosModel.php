<?php
class ModulosModel extends Mysql
{
    public function __construct()
    {
        parent::__construct();
    }

    public function SelectModelos()
    {
        $sql = "SELECT 
                id_modulo,
                nombre_modulo, 
                estado
            FROM tb_modulos 
            ORDER BY id_modulo ASC";
        $request = $this->select_all($sql); // 👈 si tu framework tiene select_all en lugar de select()
        return $request;
    }

    public function getModulo($Modulo)
    {

        $sql = "SELECT 
        tm.nombre_modulo,
        tm.id_area,
        ta.nombre_area
        FROM tb_modulos tm
        INNER JOIN tb_areas ta ON tm.id_area = ta.id_area
        WHERE nombre_modulo = ?";
        $request = $this->select($sql, array($Modulo));
        return $request;
    }

    public function selectContrasAreas($id_area)
    {
        $sql = "SELECT
        tc.id_contraseña,
        tc.contraseña,
        tc.fecha_registro,
        tp.nombre_proveedor as proveedor,
        ta.nombre_area as area,
        tc.valor_letras,
        ROUND(SUM(td.valor_documento), 2) AS monto_total,
        tc.fecha_pago,
        tc.estado
        FROM tb_contraseña tc
        INNER JOIN tb_proveedor tp ON tc.id_proveedor = tp.id_proveedor
        INNER JOIN tb_areas ta ON tc.area = ta.id_area
        INNER JOIN tb_detalles td ON tc.contraseña = td.contraseña
        WHERE ta.id_area = ? 
        GROUP BY tc.contraseña";

        $request = $this->select_multi($sql, array($id_area));
        return $request;
    }

    public function existeFacturaConProveedor($factura, $id_proveedor, $contraseñaExcluir = null)
    {
        $sql = "SELECT d.no_factura
                FROM tb_detalles d
                INNER JOIN tb_contraseña c ON d.contraseña = c.contraseña
                WHERE d.no_factura = ? 
                  AND c.id_proveedor = ?";

        $arrData = [$factura, $id_proveedor];

        // Excluir la misma contraseña que estamos editando
        if ($contraseñaExcluir) {
            $sql .= " AND c.contraseña != ?";
            $arrData[] = $contraseñaExcluir;
        }

        $result = $this->select($sql, $arrData);
        return !empty($result);
    }

    public function crearContraseña($realizador, $contraseña, $fecha_registro, $fecha_pago, $id_proveedor, $area)
    {
        $sql = "INSERT INTO tb_contraseña (
                realizador,
                contraseña,
                fecha_registro,
                fecha_pago,
                id_proveedor,
                area,
                estado
            )
            VALUES (?, ?, ?, ?, ?, ?, 'Pendiente')";

        $arrData = [
            $realizador,
            $contraseña,
            $fecha_registro,
            $fecha_pago,
            $id_proveedor,
            $area
        ];

        $request = $this->insert($sql, $arrData); // Asumiendo que tienes un método insert que devuelve true o false
        return $request;
    }

    public function insertDetalleSolicitud($no_factura, $contraseña, $bien_servicio, $valor_documento, $estado)
    {
        $sql = "INSERT INTO tb_detalles (
                contraseña,
                no_factura,
                bien_servicio,
                valor_documento,
                estado,
                fecha_registro
            )
            VALUES (?, ?, ?, ?, ?, CURDATE())";
        $arrData = [
            $contraseña,
            $no_factura,
            $bien_servicio,
            $valor_documento,
            $estado
        ];
        $request = $this->insert($sql, $arrData);
        return $request;
    }

}