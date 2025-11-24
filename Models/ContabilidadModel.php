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
            COALESCE(ts.id_solicitud, 0) AS solicitud,
            tc.contraseña,
            tc.realizador,
            tc.fecha_registro,
            DAY(tc.fecha_registro)   AS dia_registro,
            MONTH(tc.fecha_registro) AS mes_registro,
            YEAR(tc.fecha_registro)  AS año_registro,
            tp.nombre_proveedor AS proveedor,
            tc.valor_letras,
            tc.monto_total AS monto_registrado,
            ta.id_area,
            tc.id_proveedor,
            ta.nombre_area AS area,
            FORMAT(SUM(td.valor_documento), 2) AS monto_formato,
            SUM(td.valor_documento) AS monto_total,
            tc.fecha_pago,
            tc.estado,
            tc.anticipo,
            tc.correcciones
        FROM tb_contraseña tc
        INNER JOIN tb_proveedor tp ON tc.id_proveedor = tp.id_proveedor
        INNER JOIN tb_areas ta ON tc.area = ta.id_area
        INNER JOIN tb_detalles td ON tc.contraseña = td.contraseña
        LEFT JOIN tb_solicitud_fondos ts ON tc.contraseña = ts.contraseña
        WHERE tc.contraseña = ? 
        GROUP BY tc.id_contraseña;";
        $request = $this->select($sql, array($contraseña));
        return $request;
    }

    public function selectContras()
    {

        $sql = "SELECT
            tc.id_contraseña,
            tc.contraseña,
            tc.fecha_registro,
            tp.nombre_proveedor AS proveedor,
            tc.area as id_area,
            ta.nombre_area AS area,
            tc.valor_letras,
            ROUND(SUM(td.valor_documento), 2) AS monto_total,
            tc.fecha_pago,
            tc.estado
        FROM tb_contraseña tc
        INNER JOIN tb_proveedor tp ON tc.id_proveedor = tp.id_proveedor
        INNER JOIN tb_areas ta ON tc.area = ta.id_area
        INNER JOIN tb_detalles td ON tc.contraseña = td.contraseña
        GROUP BY tc.id_contraseña";

        return $this->select_all($sql);
    }

        public function getAnticipoInfo($contraseña)
    {
        $sql = "SELECT
            tf.id_solicitud,
            ta.nombre_area AS area,
            tp.nombre_proveedor AS proveedor,
            CONCAT(tu.nombres, ' ', tu.primer_apellido, ' ', tu.segundo_apellido) as usuario,
            tf.categoria,
            tf.fecha_creacion,
            tf.estado,
            tf.no_transferencia,
            tf.fecha_transaccion,
            tf.fecha_pago,
            tf.observacion,
            ROUND(SUM(td.valor_documento), 2) AS monto_total
        FROM tb_solicitud_fondos tf
        INNER JOIN tb_areas ta ON tf.area = ta.id_area
        INNER JOIN tb_proveedor tp ON tf.proveedor = tp.id_proveedor
        INNER JOIN tb_usuarios tu ON tf.usuario = tu.id_usuario
        INNER JOIN tb_detalles td ON tf.id_solicitud = td.no_factura
        WHERE tf.id_solicitud = ?";
        $request = $this->select($sql, array($contraseña));
        return $request;
    }

    

}