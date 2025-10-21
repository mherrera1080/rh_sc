<?php
class ContabilidadModel extends Mysql
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getContraseña($contraseña)
    {
        $sql = "SELECT
            tc.id_contraseña,
            tc.contraseña,
            tc.realizador,
            tc.fecha_registro,
            tp.nombre_proveedor AS proveedor,
            tc.valor_letras,
            tc.monto_total,
            ta.nombre_area AS area,
            ROUND(SUM(td.valor_documento), 2) AS monto_total,
            tc.fecha_pago,
            tc.estado
        FROM tb_contraseña tc
        INNER JOIN tb_proveedor tp ON tc.id_proveedor = tp.id_proveedor
        INNER JOIN tb_areas ta ON tc.area = ta.id_area
        INNER JOIN tb_detalles td ON tc.contraseña = td.contraseña
        WHERE tc.contraseña = ? ";

        $request = $this->select($sql, array($contraseña));
        return $request;
    }
    



}