<?php
class ContabilidadModel extends Mysql
{
    public function __construct()
    {
        parent::__construct();
    }


        public function getSolicitud($id_solicitud)
    {
        $sql = "SELECT
            tc.id_solicitud,
            tc.contraseña,
            CONCAT(tu.nombres, ' ', tu.primer_apellido, ' ', tu.segundo_apellido) as realizador,
            tp.nombre_proveedor AS proveedor,
            ta.id_area,
            ta.nombre_area AS area,
            ROUND(SUM(td.valor_documento), 2) AS total,
            DAY(tc.fecha_creacion)   AS dia_registro,
            MONTH(tc.fecha_creacion) AS mes_registro,
            YEAR(tc.fecha_creacion)  AS año_registro,
            tc.fecha_creacion AS fecha_registro,
            tc.fecha_pago,
            tc.estado AS solicitud_estado
        FROM tb_solicitud_fondos tc
        INNER JOIN tb_proveedor tp ON tc.proveedor = tp.id_proveedor
        INNER JOIN tb_areas ta ON tc.area = ta.id_area
        INNER JOIN tb_detalles td ON tc.id_solicitud = td.no_factura
        INNER JOIN tb_usuarios tu ON tc.usuario = tu.id_usuario
        WHERE tc.id_solicitud = ?
        GROUP BY tc.id_solicitud;
        ";

        $request = $this->select($sql, array($id_solicitud));
        return $request;
    }

    public function getContraseña($contraseña)
    {
        $sql = "SELECT
            tc.id_contraseña,
            COALESCE(tsf.id_solicitud, 0) AS solicitud,
            tc.contraseña,
            tc.realizador,
            tc.fecha_registro,
            DATE_FORMAT(tc.fecha_registro, '%d/%m/%Y') AS fecha_registro,
            tp.nombre_proveedor AS proveedor,
            tc.valor_letras,
            tc.monto_total,
            ta.nombre_area AS area,
            ta.id_area as area_id,
            tp.regimen,
            ROUND(
                SUM(td.valor_documento) 
                + (SUM(td.valor_documento) * 0.12) 
                - (SUM(td.reten_isr) + SUM(td.reten_iva)), 
                2
            ) AS total,
            ROUND(
                SUM(td.valor_documento)
                - SUM(td.reten_iva), 
                2
            ) AS total_pequeño,
            ROUND(SUM(td.iva), 2) AS iva_calc,
            ROUND(SUM(td.valor_documento), 2) AS total_calc,
            ROUND(SUM(td.reten_iva), 2) AS reten_iva,
            ROUND(SUM(td.reten_isr), 2) AS reten_isr,
            DAY(tc.fecha_registro)   AS dia_registro,
            MONTH(tc.fecha_registro) AS mes_registro,
            YEAR(tc.fecha_registro)  AS año_registro,
            tc.fecha_pago,
            tc.estado,
            tsf.id_solicitud,
            tsf.estado as solicitud_estado,
            tsf.usuario as solicitante,
            tc.anticipo
        FROM tb_contraseña tc
        INNER JOIN tb_proveedor tp ON tc.id_proveedor = tp.id_proveedor
        INNER JOIN tb_areas ta ON tc.area = ta.id_area
        INNER JOIN tb_detalles td ON tc.contraseña = td.contraseña
        INNER JOIN tb_solicitud_fondos tsf ON tc.contraseña = tsf.contraseña
        WHERE tc.contraseña = ?";
        $request = $this->select($sql, array($contraseña));
        return $request;
    }

    public function getContraseñaContra($contraseña)
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
            tf.contraseña as correlativo,
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

    public function selectDetalles()
    {
        $sql = "SELECT 
        tc.id_detalle,
        tc.contraseña,
        tc.no_factura,
        tc.registro_ax,
        tc.bien_servicio,
        tc.valor_documento,
        tc.isr_valor,
        tc.iva_valor,
        tc.iva,
        tc.isr,
        tc.reten_iva,
        tc.base,
        tc.total,
        tc.fecha_registro,
        tc.observacion,
        tc.estado,
        ta.nombre_area as area
        FROM tb_detalles tc
        INNER JOIN tb_contraseña td ON tc.contraseña = td.contraseña
        INNER JOIN tb_areas ta ON td.area = ta.id_area";

        return $this->select_all($sql);
    }

    public function FacturasbyID($no_factura)
    {
        $sql = "SELECT
        d.id_detalle,
        d.no_factura,
        d.valor_documento AS valor_documento,
        d.bien_servicio,
        r.nombre_regimen,
        CASE
            WHEN r.id_regimen = 1
            THEN ROUND(d.valor_documento / 1.12, 2)
            ELSE d.valor_documento
        END AS base,
        CASE
            WHEN r.id_regimen = 1
            THEN ROUND(d.valor_documento - (d.valor_documento / 1.12), 2)
            ELSE 0
        END AS iva,
        CASE
            WHEN p.iva = 1
                AND (
                    (r.id_regimen = 1 AND d.valor_documento >= 2500.01)
                    OR (r.id_regimen = 2 AND d.valor_documento >= 2232.14)
                )
            THEN
                CASE
                    WHEN r.id_regimen = 1
                    THEN ROUND(
                        (d.valor_documento - (d.valor_documento / 1.12)) * 0.15
                    , 2)
                    WHEN r.id_regimen = 2
                    THEN ROUND(d.valor_documento * 0.05, 2)
                    ELSE 0
                END
            ELSE 0
        END AS reten_iva,
        CASE
            WHEN p.isr = 1
                AND (
                    (r.id_regimen = 1 AND d.valor_documento >= 2500.01)
                    OR (r.id_regimen = 2 AND d.valor_documento >= 2232.14)
                )
            THEN
                CASE
                    WHEN r.id_regimen = 1
                    THEN ROUND((d.valor_documento / 1.12) * 0.05, 2)
                    WHEN r.id_regimen = 2
                    THEN ROUND(d.valor_documento * 0.07, 2)
                    ELSE 0
                END
            ELSE 0
        END AS reten_isr,
        ROUND(
            d.valor_documento
            - (
                CASE
                    WHEN p.iva = 1
                        AND (
                            (r.id_regimen = 1 AND d.valor_documento >= 2500.01)
                            OR (r.id_regimen = 2 AND d.valor_documento >= 2232.14)
                        )
                    THEN
                        CASE
                            WHEN r.id_regimen = 1
                            THEN (d.valor_documento - (d.valor_documento / 1.12)) * 0.15
                            WHEN r.id_regimen = 2
                            THEN d.valor_documento * 0.05
                            ELSE 0
                        END
                    ELSE 0
                END
            )
            - (
                CASE
                    WHEN p.isr = 1
                        AND (
                            (r.id_regimen = 1 AND d.valor_documento >= 2500.01)
                            OR (r.id_regimen = 2 AND d.valor_documento >= 2232.14)
                        )
                    THEN
                        CASE
                            WHEN r.id_regimen = 1
                            THEN (d.valor_documento / 1.12) * 0.05
                            WHEN r.id_regimen = 2
                            THEN d.valor_documento * 0.07
                            ELSE 0
                        END
                    ELSE 0
                END
            )
        , 2) AS total_liquido
        FROM tb_detalles d
        INNER JOIN tb_contraseña c ON d.contraseña = c.contraseña
        INNER JOIN tb_proveedor p ON c.id_proveedor = p.id_proveedor
        INNER JOIN tb_regimen r ON p.regimen = r.id_regimen
        WHERE d.id_detalle =  ?";
        $request = $this->select($sql, array($no_factura));
        return $request;
    }

}