<?php
class VehiculosModel extends Mysql
{
    public function __construct()
    {
        parent::__construct();
    }

    public function selectVehiculos()
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
        WHERE tc.area = 2
        GROUP BY tc.contraseña";
        $request = $this->select_all($sql);
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
        INNER JOIN tb_areas ta ON td.area = ta.id_area
        WHERE td.area = 2";

        return $this->select_all($sql);
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
        GROUP BY tc.id_contraseña";
        $request = $this->select($sql, array($contraseña));
        return $request;
    }

    public function getCorreosArea($area, $estado, $categoria)
    {
        $sql = "SELECT 
        tu.correo as correos
        FROM tb_fase_correos tfc
        INNER JOIN tb_grupo_correos tg ON tfc.grupo = tg.id_grupo_correo
        INNER JOIN tb_usuarios tu ON tfc.usuario = tu.id_usuario
        INNER JOIN tb_fases tf ON tfc.fase = tf.id_fase
        INNER JOIN tb_categoria tc ON tf.categoria = tc.id_categoria
        WHERE tg.area = ? AND tf.nombre_base = ? AND tc.nombre_categoria = ?";
        $request = $this->select_multi($sql, array($area, $estado, $categoria));
        return $request;
    }

    public function getFacturasbyContra(int $contraseña)
    {
        $sql = "SELECT
            id_detalle,
            no_factura,
            bien_servicio,
            valor_documento,
            fecha_registro,
            estado
        FROM tb_detalles 
        WHERE contraseña = ?";
        $request = $this->select_multi($sql, array($contraseña));
        return $request;
    }

    public function validacionContraseña($contraseña, $anticipo, $estado)
    {
        $sql = "UPDATE tb_contraseña 
                SET 
                anticipo = ?,
                estado = ?
                WHERE contraseña = ?";
        $arrData = [$anticipo, $estado, $contraseña];
        return $this->update($sql, $arrData);
    }

    public function setEstadoDetalles($contraseña, $estado)
    {
        $sql = "UPDATE tb_detalles 
                SET 
                estado = ?
                WHERE contraseña = ?";
        $arrData = [$estado, $contraseña];
        return $this->update($sql, $arrData);
    }

    public function solicitudFondoVehiculos($contraseña, $area, $categoria, $regimen, $iva, $isr)
    {
        $sql = "INSERT INTO tb_solicitud_fondos (
            contraseña,
            area,
            categoria,
            tipo_regimen,
            iva,
            isr,
            fecha_creacion,
            estado
        )
        VALUES (?, ?, ?,?,?,?, CURDATE(), ?)";

        $arrData = [
            $contraseña,
            $area,
            $categoria,
            $regimen,
            $iva,
            $isr,
            'Pendiente'
        ];

        $request = $this->insert($sql, $arrData);
        return $request;
    }


    public function setContraseña($contraseña, $estado)
    {
        $sql = "UPDATE tb_contraseña 
                SET 
                estado = ?
                WHERE contraseña = ?";
        $arrData = [$estado, $contraseña];
        return $this->update($sql, $arrData);
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
        WHERE d.no_factura =  ?";
        $request = $this->select($sql, array($no_factura));
        return $request;
    }

    public function actualizarValoresFactura(
        $no_factura,
        $base,
        $iva,
        $reten_iva,
        $reten_isr,
        $total
    ) {
        $sql = "UPDATE tb_detalles SET
        base       = ?,
        iva        = ?,
        reten_iva  = ?,
        reten_isr  = ?,
        total      = ?
        WHERE no_factura = ?";

        $arrData = [
            $base,
            $iva,
            $reten_iva,
            $reten_isr,
            $total,
            $no_factura
        ];

        return $this->update($sql, $arrData);
    }

    public function selectProveedor($proveedor)
    {
        $sql = "SELECT 
            id_proveedor,
            regimen,
            iva,
            isr
        FROM tb_proveedor
        WHERE id_proveedor = ?";
        $request = $this->select($sql, array($proveedor));
        return $request;
    }

}