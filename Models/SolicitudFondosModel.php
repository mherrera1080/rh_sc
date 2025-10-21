<?php
class SolicitudFondosModel extends Mysql
{
    public function __construct()
    {
        parent::__construct();
    }

    public function selectSolicitudFondos()
    {
        $area = intval($_SESSION['PersonalData']['area']);
        $where = ($area == 4) ? "" : "WHERE area = $area";

        $sql = "SELECT
            tc.id_solicitud,
            tc.contraseña,
            ta.nombre_area AS area,
            tc.categoria,
            tc.fecha_creacion,
            ts.fecha_pago as fecha_pago,
            tc.fecha_pago as fecha_pago_sf,
            tc.estado
        FROM tb_solicitud_fondos tc
        INNER JOIN tb_areas ta ON tc.area = ta.id_area
        LEFT JOIN tb_contraseña ts ON tc.contraseña = ts.contraseña
        $where";
        $request = $this->select_all($sql);
        return $request;
    }

    public function getContraseña($contraseña)
    {
        $sql = "SELECT
            tc.id_contraseña,
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
            ROUND((SUM(td.valor_documento) * 0.12) , 2) AS iva_calc,
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
            tc.anticipo
        FROM tb_contraseña tc
        INNER JOIN tb_proveedor tp ON tc.id_proveedor = tp.id_proveedor
        INNER JOIN tb_areas ta ON tc.area = ta.id_area
        INNER JOIN tb_detalles td ON tc.contraseña = td.contraseña
        INNER JOIN tb_solicitud_fondos tsf ON tc.contraseña = tsf.contraseña
        WHERE tc.contraseña = ? ";

        $request = $this->select($sql, array($contraseña));
        return $request;
    }


    public function getSolicitud($id_solicitud)
    {
        $sql = "SELECT
            tp.nombre_proveedor AS proveedor,
            ta.nombre_area AS area,
            ROUND(SUM(td.valor_documento), 2) AS total_calc,
            DAY(tc.fecha_creacion)   AS dia_registro,
            MONTH(tc.fecha_creacion) AS mes_registro,
            YEAR(tc.fecha_creacion)  AS año_registro,
            tc.id_solicitud,
            tc.fecha_creacion as fecha_registro,
            tc.fecha_pago,
            tc.estado as solicitud_estado
        FROM tb_solicitud_fondos tc
        INNER JOIN tb_proveedor tp ON tc.proveedor = tp.id_proveedor
        INNER JOIN tb_areas ta ON tc.area = ta.id_area
        INNER JOIN tb_detalles td ON tc.id_solicitud = td.no_factura
        WHERE tc.id_solicitud = ? ";

        $request = $this->select($sql, array($id_solicitud));
        return $request;
    }

    public function getFacturasbyContra($contraseña)
    {
        $sql = "SELECT 
            id_detalle,
            contraseña,
            no_factura,
            no_comparativa,
            no_oc,
            registro_ax,
            bien_servicio,
            valor_documento,
            isr_valor,
            iva_valor,
            iva,
            isr,
            reten_iva,
            reten_isr,
            base,
            total,
            fecha_registro,
            observacion,
            estado
        FROM tb_detalles
        WHERE contraseña = ?";
        $request = $this->select_multi($sql, array($contraseña));
        return $request;
    }

    public function getFacturasbySoli(int $id_solicitud)
    {
        $sql = "SELECT 
            id_detalle,
            contraseña,
            no_factura,
            no_comparativa,
            no_oc,
            registro_ax,
            bien_servicio,
            valor_documento,
            isr_valor,
            iva_valor,
            iva,
            isr,
            reten_iva,
            reten_isr,
            base,
            total,
            fecha_registro,
            observacion,
            estado
        FROM tb_detalles
        WHERE no_factura = ?";
        $request = $this->select_multi($sql, array($id_solicitud));
        return $request;
    }

    public function getDetallesbyContra(int $contraseña)
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
    public function UpdateDetalle($id_detalle, $cod_ax, $base, $iva_base, $iva, $isr, $reten_iva, $reten_isr, $total_iquido, $observacion)
    {
        $sql = "UPDATE tb_detalles 
                SET registro_ax = ?, 
                    base = ?,
                    iva = ?, 
                    iva_valor = ?, 
                    isr_valor = ?, 
                    reten_iva = ?,
                    reten_isr = ?,
                    total = ?,
                    observacion = ?
                WHERE id_detalle = ?";
        $arrData = [$cod_ax, $base, $iva_base, $iva, $isr, $reten_iva, $reten_isr, $total_iquido, $observacion, $id_detalle];
        return $this->update($sql, $arrData);
    }

    public function getImpuestosRegimen($contraseña)
    {
        $sql = "SELECT
                DATE_FORMAT(tc.fecha_pago, '%d/%m/%Y') AS fecha_pago,
                ROUND(SUM(td.valor_documento), 2) AS monto_total,
                ROUND(SUM(td.reten_iva), 2) AS reten_iva,
                ROUND(SUM(td.reten_isr), 2) AS reten_isr,
                ROUND(SUM(td.valor_documento), 2) AS subtotal,
                ROUND(SUM(td.valor_documento) * 0.12, 2) AS iva,
                ROUND(
                    (SUM(td.valor_documento) + (SUM(td.valor_documento) * 0.12))
                    - (SUM(td.reten_isr) + SUM(td.reten_iva))
                    - SUM(tdf.valor_documento),
                    2
                ) AS total
            FROM tb_contraseña tc
            INNER JOIN tb_proveedor tp ON tc.id_proveedor = tp.id_proveedor
            INNER JOIN tb_areas ta ON tc.area = ta.id_area
            INNER JOIN tb_detalles td ON tc.contraseña = td.contraseña
            LEFT JOIN tb_solicitud_fondos tf ON tc.anticipo = tf.id_solicitud
            LEFT JOIN tb_detalles tdf ON tf.id_solicitud = tdf.no_factura
            WHERE tc.contraseña = ?;
            ";

        $request = $this->select($sql, array($contraseña));
        return $request;
    }

    public function getImpuestosPeqContribuyente($contraseña)
    {
        $sql = "SELECT
                DATE_FORMAT(tc.fecha_pago, '%d/%m/%Y') AS fecha_pago,
                ROUND(SUM(td.valor_documento), 2) AS monto_total,
                ROUND(SUM(td.reten_iva), 2) AS reten_iva,
                ROUND(SUM(td.reten_isr), 2) AS reten_isr,
                ROUND(SUM(td.valor_documento), 2) AS subtotal,
                0 AS iva,
                ROUND(
                    SUM(td.valor_documento)
                    - SUM(td.reten_iva), 
                    2
                ) AS total
            FROM tb_contraseña tc
            INNER JOIN tb_proveedor tp ON tc.id_proveedor = tp.id_proveedor
            INNER JOIN tb_areas ta ON tc.area = ta.id_area
            INNER JOIN tb_detalles td ON tc.contraseña = td.contraseña
            WHERE tc.contraseña = ?;
            ";

        $request = $this->select($sql, array($contraseña));
        return $request;
    }

    public function FacturasbyID(int $id)
    {
        $sql = "SELECT
			tr.id_regimen,
            tr.nombre_regimen,
            td.id_detalle,
            td.no_factura,
            td.registro_ax,
            td.bien_servicio,
            td.valor_documento,
            ROUND(td.valor_documento - (td.valor_documento / 1.12), 2) AS valor_documento_iva,
            td.isr_valor,
            td.iva_valor,
            td.iva,
            td.isr,
            td.reten_iva,
            td.base,
            td.observacion,
            td.fecha_registro,
            td.estado
        FROM tb_detalles td
        INNER JOIN tb_contraseña tc ON td.contraseña = tc.contraseña
        INNER JOIN tb_proveedor tp ON tc.id_proveedor = tp.id_proveedor
        INNER JOIN tb_regimen tr ON tp.regimen = tr.id_regimen
        WHERE id_detalle = ?";
        $request = $this->select($sql, array($id));
        return $request;
    }
    public function udapteSolicitud($id_solicitud, $respuesta)
    {
        $sql = "UPDATE tb_solicitud_fondos 
                SET estado = ?
                WHERE id_solicitud = ?";
        $arrData = [$respuesta, $id_solicitud];
        return $this->update($sql, $arrData);
    }

    public function endSolicitud($id_solicitud, $respuesta, $no_transferencia, $fecha_pago, $observacion)
    {
        $sql = "UPDATE tb_solicitud_fondos 
                SET 
                estado = ?,
                no_transferencia = ?,
                fecha_pago = ?,
                observacion = ?
                WHERE id_solicitud = ?";
        $arrData = [$respuesta, $no_transferencia, $fecha_pago, $observacion, $id_solicitud];
        return $this->update($sql, $arrData);
    }


    public function endSolicitudSinContra($id_solicitud, $respuesta, $no_transferencia, $fecha_pago, $observacion)
    {
        $sql = "UPDATE tb_solicitud_fondos 
                SET 
                estado = ?,
                no_transferencia = ?,
                fecha_transaccion = ?,
                observacion = ?
                WHERE id_solicitud = ?";
        $arrData = [$respuesta, $no_transferencia, $fecha_pago, $observacion, $id_solicitud];
        return $this->update($sql, $arrData);
    }

    public function getGrupo($idArea)
    {
        $sql = "SELECT 
        id_grupo
        FROM tb_grupo_firmas
        WHERE area_grupo = ? ";

        $request = $this->select($sql, array($idArea));
        return $request;
    }

    public function getFirmas(int $id_grupo)
    {
        $sql = "SELECT 
                f.id_firma,
                f.nombre_usuario,
                f.cargo_usuario,
                f.img_firma,
                f.orden,
                f.estado,
                g.nombre_grupo,
                g.area_grupo
            FROM tb_firmas f
            INNER JOIN tb_grupo_firmas g ON f.id_grupo = g.id_grupo
            WHERE g.id_grupo = ?
            ORDER BY f.orden ";
        $request = $this->select_multi($sql, array($id_grupo));
        return $request;
    }


    public function insertDetalleSolicitudFondosNueva($idSolicitud, $tipo, $bien, $valor, $estado, $fecha)
    {
        $sql = "INSERT INTO tb_detalles (no_factura, tipo, bien_servicio, valor_documento, estado, fecha_registro)
            VALUES (?, ?, ?, ?, ?, ?)";
        $arrData = [$idSolicitud, $tipo, $bien, $valor, $estado, $fecha];
        return $this->insert($sql, $arrData);
    }
    public function solicitudFondoVehiculosNueva($realizador, $area, $proveedor, $categoria, $fecha_pago, $estado)
    {
        $sql = "INSERT INTO tb_solicitud_fondos (usuario, area, proveedor, categoria, fecha_creacion, fecha_pago, estado, no_transferencia)
            VALUES (?, ?, ?, ?, CURDATE(), ?, ?, NULL)";
        $arrData = [$realizador, $area, $proveedor, $categoria, $fecha_pago, $estado];
        return $this->insert($sql, $arrData);
    }
    public function lastInsertId()
    {
        $sql = "SELECT LAST_INSERT_ID() AS id";
        $result = $this->select($sql);
        return $result['id'] ?? null;
    }

    public function getSolisinContra($contraseña)
    {
        $sql = "SELECT
            tc.id_solicitud,
            tc.area,
            tc.proveedor,
            tc.categoria,
            tc.fecha_creacion,
            tc.estado,
            tc.fecha_pago,
            tc.observacion,
            GROUP_CONCAT(td.id_detalle ORDER BY td.id_detalle ASC) AS no_factura,
            GROUP_CONCAT(td.tipo ORDER BY td.tipo ASC) AS tipo,
            GROUP_CONCAT(td.bien_servicio ORDER BY td.no_factura ASC) AS bien_servicio,
            GROUP_CONCAT(td.valor_documento ORDER BY td.no_factura ASC) AS valor_documento
        FROM tb_solicitud_fondos tc
        INNER JOIN tb_detalles td ON tc.id_solicitud = td.no_factura
        WHERE tc.id_solicitud = ? ";
        $request = $this->select($sql, array($contraseña));
        return $request;
    }

    public function updateSolicitud($id_solicitud, $fecha_pago, $proveedor, $estado)
    {
        $sql = "UPDATE tb_solicitud_fondos 
                SET fecha_pago = ?, 
                    proveedor = ?,
                    estado = ?
                WHERE id_solicitud  = ?";
        $arrData = [$fecha_pago, $proveedor, $estado, $id_solicitud];
        return $this->update($sql, $arrData);
    }

    public function updateDetalleFactura($id_solicitud, $bien_servicio, $valor_documento)
    {
        $sql = "UPDATE tb_detalles
                SET bien_servicio = ?, 
                    valor_documento = ?
                WHERE no_factura = ?";
        $arrData = [$bien_servicio, $valor_documento, $id_solicitud];
        return $this->update($sql, $arrData);
    }

    public function selectAnticipos($id_solicitud)
    {
        $sql = "SELECT 
            id_solicitud,
            categoria,
            no_transferencia,
            fecha_transaccion,
            fecha_pago
        FROM tb_solicitud_fondos
        WHERE categoria = 'Anticipo' AND estado = 'Finalizado' ";
        $request = $this->select_multi($sql, array($id_solicitud));
        return $request;
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