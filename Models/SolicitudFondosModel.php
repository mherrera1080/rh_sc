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
        $where = "WHERE tc.area = $area";

        $sql = "SELECT
            tc.id_solicitud,
            tc.contraseña,
            ta.nombre_area AS area,
            tc.categoria,
            tc.fecha_creacion,
            ts.fecha_pago as fecha_pago,
            tc.fecha_pago as fecha_pago_sf,
            tc.no_transferencia,
            tc.fecha_transaccion,
            tc.estado
        FROM tb_solicitud_fondos tc
        INNER JOIN tb_areas ta ON tc.area = ta.id_area
        LEFT JOIN tb_contraseña ts ON tc.contraseña = ts.contraseña
        $where";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectSolicitudFondosConta()
    {
        $sql = "SELECT
            tc.id_solicitud,
            tc.contraseña,
            ta.nombre_area AS area,
            tc.categoria,
            tc.fecha_creacion,
            ts.fecha_pago as fecha_pago,
            tc.fecha_pago as fecha_pago_sf,
            tc.no_transferencia,
            tc.fecha_transaccion,
            tc.estado
        FROM tb_solicitud_fondos tc
        INNER JOIN tb_areas ta ON tc.area = ta.id_area
        LEFT JOIN tb_contraseña ts ON tc.contraseña = ts.contraseña
        ";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectSolicitudFondosVehiculos()
    {
        $sql = "SELECT
            tc.id_solicitud,
            tc.contraseña,
            ta.nombre_area AS area,
            tc.categoria,
            tc.fecha_creacion,
            ts.fecha_pago as fecha_pago,
            tc.fecha_pago as fecha_pago_sf,
            tc.no_transferencia,
            tc.fecha_transaccion,
            tc.estado
        FROM tb_solicitud_fondos tc
        INNER JOIN tb_areas ta ON tc.area = ta.id_area
        LEFT JOIN tb_contraseña ts ON tc.contraseña = ts.contraseña
        WHERE tc.area = 2
        ";
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
            td.no_factura AS no_factura,
            tsf.mes_renta as renta,
            MONTH(tsf.mes_renta) AS mes_renta,
            YEAR(tsf.mes_renta)  AS año_renta,
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

    public function getCombustible($contraseña)
    {
        $sql = "SELECT
            tc.id_solicitud,
            tc.area as area_id,
            tc.contraseña as contraseña,
            ta.nombre_area as area,
            tp.nombre_proveedor as proveedor,
            ta.id_area,
            tn.transferencia as total,
            tn.saldo_disponible as saldo,
            tn.fecha_inicio,
            tn.fecha_final,
            CONCAT(tu.nombres, ' ', tu.primer_apellido, ' ', tu.segundo_apellido) as realizador,
            DAY(tc.fecha_creacion)   AS dia_registro,
            MONTH(tc.fecha_creacion) AS mes_registro,
            YEAR(tc.fecha_creacion)  AS año_registro,
            tc.fecha_creacion AS fecha_registro,
            tc.fecha_pago AS fecha_pago,
            tc.estado as solicitud_estado,
            tc.categoria as tipo
        FROM tb_solicitud_fondos tc
        INNER JOIN tb_areas ta ON tc.area = ta.id_area
        INNER JOIN tb_proveedor tp ON tc.proveedor = tp.id_proveedor
        INNER JOIN tb_usuarios tu ON tc.usuario = tu.id_usuario
        INNER JOIN tb_combustible tn ON tc.contraseña = tn.contraseña
        WHERE tc.contraseña = ?
        GROUP BY tc.contraseña;
        ";

        $request = $this->select($sql, array($contraseña));
        return $request;
    }

    public function getCorreosArea($area, $base, $categoria)
    {
        $sql = "SELECT 
        tu.correo as correos
        FROM tb_fase_correos tfc
        INNER JOIN tb_grupo_correos tg ON tfc.grupo = tg.id_grupo_correo
        INNER JOIN tb_usuarios tu ON tfc.usuario = tu.id_usuario
        INNER JOIN tb_fases tf ON tfc.fase = tf.id_fase
        INNER JOIN tb_categoria tc ON tf.categoria = tc.id_categoria
        WHERE tg.area = ? AND tf.nombre_base = ? AND tc.nombre_categoria = ?";
        $request = $this->select_multi($sql, array($area, $base, $categoria));
        return $request;
    }

    public function getSolisinContra($contraseña)
    {
        $sql = "SELECT
            tc.id_solicitud,
            tc.contraseña,
            tc.area,
            tc.proveedor as proveedor_id,
            tc.categoria,
            tc.fecha_creacion,
            tc.estado,
            tc.fecha_pago,
            tc.observacion,
            tp.nombre_proveedor AS proveedor,
            ta.nombre_area AS area,
            tc.fecha_creacion,
            CONCAT(tu.nombres, ' ', tu.primer_apellido, ' ', tu.segundo_apellido) as usuario,
            FORMAT(SUM(td.valor_documento), 2) AS monto_total,
            GROUP_CONCAT(td.id_detalle ORDER BY td.id_detalle ASC) AS no_factura,
            GROUP_CONCAT(td.tipo ORDER BY td.tipo ASC) AS tipo,
            GROUP_CONCAT(td.bien_servicio ORDER BY td.no_factura ASC) AS bien_servicio,
            GROUP_CONCAT(td.valor_documento ORDER BY td.no_factura ASC) AS valor_documento
        FROM tb_solicitud_fondos tc
        INNER JOIN tb_detalles td ON tc.id_solicitud = td.no_factura
        INNER JOIN tb_usuarios tu ON tc.usuario = tu.id_usuario
        LEFT JOIN tb_proveedor tp ON tc.proveedor = tp.id_proveedor
        LEFT JOIN tb_areas ta ON tc.area = ta.id_area
        WHERE tc.id_solicitud = ? ";
        $request = $this->select($sql, array($contraseña));
        return $request;
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


    // public function dfdfdf($id_solicitud)
    // {
    //     $sql = "SELECT
    //         tc.id_solicitud,
    //         tc.contraseña,
    //         CONCAT(tu.nombres, ' ', tu.primer_apellido, ' ', tu.segundo_apellido) as realizador,
    //         tp.nombre_proveedor AS proveedor,
    //         ta.id_area,
    //         ta.nombre_area AS area,
    //         ROUND(SUM(td.valor_documento), 2) AS total,
    //         DAY(tc.fecha_creacion)   AS dia_registro,
    //         MONTH(tc.fecha_creacion) AS mes_registro,
    //         YEAR(tc.fecha_creacion)  AS año_registro,
    //         tc.fecha_creacion AS fecha_registro,
    //         tc.fecha_pago,
    //         tc.estado AS solicitud_estado
    //     FROM tb_solicitud_fondos tc
    //     INNER JOIN tb_proveedor tp ON tc.proveedor = tp.id_proveedor
    //     INNER JOIN tb_areas ta ON tc.area = ta.id_area
    //     INNER JOIN tb_detalles td ON tc.id_solicitud = td.no_factura
    //     INNER JOIN tb_usuarios tu ON tc.usuario = tu.id_usuario
    //     WHERE tc.id_solicitud = ?
    //     GROUP BY tc.id_solicitud;
    //     ";

    //     $request = $this->select($sql, array($id_solicitud));
    //     return $request;
    // }

    public function getContraSoli($id_solicitud)
    {
        $sql = "SELECT
            tc.contraseña,
            tc.area
        FROM tb_solicitud_fondos tc
        WHERE tc.id_solicitud = ?";
        $request = $this->select($sql, array($id_solicitud));
        return $request;
    }

    public function getFacturasbyContra($contraseña)
    {
        $sql = "SELECT 
            td.id_detalle,
            td.contraseña,
            td.no_factura,
            td.no_comparativa,
            td.no_oc,
            td.registro_ax,
            td.bien_servicio,
            td.valor_documento,
            td.iva,
            td.isr,
            td.reten_iva,
            td.reten_isr,
            td.base,
            td.total,
            td.fecha_registro,
            td.observacion,
            td.estado,
            tc.estado as estado_contra
        FROM tb_detalles td
        INNER JOIN tb_contraseña tc ON td.contraseña = tc.contraseña
        WHERE td.contraseña = ?";
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

    public function getDetallesAnticipo($solicitud)
    {
        $sql = "SELECT
            id_detalle,
            no_factura,
            bien_servicio,
            valor_documento,
            fecha_registro,
            estado
        FROM tb_detalles 
        WHERE no_factura = ?";
        $request = $this->select_multi($sql, array($solicitud));
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

    public function UpdateDetalleDos($id_detalle, $cod_ax, $observacion)
    {
        $sql = "UPDATE tb_detalles 
                SET 
                registro_ax = ?,
                observacion = ?
                WHERE id_detalle = ?";
        $arrData = [$cod_ax, $observacion, $id_detalle];
        return $this->update($sql, $arrData);
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

    public function udapteSolicitud($id_solicitud, $respuesta, $observacion)
    {
        $sql = "UPDATE tb_solicitud_fondos 
                SET 
                estado = ?,
                observacion = ?
                WHERE id_solicitud = ?";
        $arrData = [$respuesta, $observacion, $id_solicitud];
        return $this->update($sql, $arrData);
    }

    public function descartarContra($contraseña, $respuesta, $observacion)
    {
        $sql = "UPDATE tb_contraseña 
                SET 
                estado = ?,
                correcciones = ?
                WHERE contraseña = ?";
        $arrData = [$respuesta, $observacion, $contraseña];
        return $this->update($sql, $arrData);
    }
    public function descartarDetalles($contraseña, $respuesta)
    {
        $sql = "UPDATE tb_detalles 
                SET 
                estado = ?
                WHERE contraseña = ?";
        $arrData = [$respuesta, $contraseña];
        return $this->update($sql, $arrData);
    }

    public function endSolicitud($id_solicitud, $respuesta, $no_transferencia, $fecha_pago, $observacion)
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

    public function getGrupo($idArea, $categoria)
    {
        $sql = "SELECT 
                    id_grupo
                FROM tb_grupo_firmas
                WHERE area_grupo = ? AND categoria = ?
                ORDER BY id_grupo DESC 
                LIMIT 1";

        $request = $this->select($sql, array($idArea, $categoria));
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
    public function solicitudFondoVehiculosNueva($realizador, $area, $proveedor, $categoria, $fecha_pago, $estado, $contraseña)
    {
        $sql = "INSERT INTO tb_solicitud_fondos (usuario, area, proveedor, categoria, fecha_creacion, fecha_pago, estado, contraseña)
                VALUES (?, ?, ?, ?, NOW(), ?, ?, ?)";
        $arrData = [$realizador, $area, $proveedor, $categoria, $fecha_pago, $estado, $contraseña];
        return $this->insert($sql, $arrData);
    }
    public function lastInsertId()
    {
        $sql = "SELECT LAST_INSERT_ID() AS id";
        $result = $this->select($sql);
        return $result['id'] ?? null;
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
    public function selectAnticipos($id_proveedor, $id_area)
    {
        $sql = "SELECT 
            tsf.id_solicitud,
            tsf.categoria,
            tsf.contraseña,
            tsf.no_transferencia,
            tsf.fecha_transaccion,
            tsf.fecha_pago
        FROM tb_solicitud_fondos tsf
        WHERE tsf.proveedor = ? 
        AND tsf.area = ?
        AND tsf.categoria = 'Anticipo'
        AND tsf.estado = 'Pagado'
        AND tsf.id_solicitud NOT IN (
            SELECT DISTINCT anticipo 
            FROM tb_contraseña 
            WHERE anticipo IS NOT NULL );
        ";

        return $this->select_multi($sql, array($id_proveedor, $id_area));
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

    public function getUltimoCorrelativo()
    {
        $sql = "SELECT
        contraseña 
        FROM tb_solicitud_fondos 
        WHERE contraseña LIKE 'ANT-%' 
        ORDER BY id_solicitud DESC LIMIT 1";
        $request = $this->select($sql);
        return $request['contraseña'] ?? null;
    }

    public function getUltimoCombustible()
    {
        $sql = "SELECT
        contraseña 
        FROM tb_solicitud_fondos 
        WHERE contraseña LIKE 'COMBUSTIBLE-%' 
        ORDER BY id_solicitud DESC LIMIT 1";
        $request = $this->select($sql);
        return $request['contraseña'] ?? null;
    }


    public function selectUsuario($usuario)
    {
        $sql = "SELECT
        id_usuario,
        identificacion,
        no_empleado,
        CONCAT(nombres, ' ', primer_apellido, ' ', segundo_apellido) AS nombre_completo,
        nombres,
        fecha_ingreso,
        correo,
        rol_usuario,
        contraseña,
        estado
        FROM tb_usuarios
        WHERE CONCAT(nombres, ' ', primer_apellido, ' ', segundo_apellido) LIKE ?";

        $request = $this->select($sql, array('%' . $usuario . '%'));
        return $request;
    }

    public function insertServicio($no_factura, $placa, $repuestos, $kilometraje, $estado, $tipo_servicio, $ln, $usuario, $tipo, $codigo_ax, $tipo_mantenimiento)
    {
        $sql = "INSERT INTO tb_servicios (
                no_factura, 
                placa, 
                repuestos,
                kilometraje, 
                estado, 
                tipo_servicio, 
                ln, 
                usuario,
                tipo_persona,
                codigo_ax, 
                tipo_mantenimiento
            ) VALUES (?,?,?,?,?,?,?,?,?,?,?)";

        $arrData = [
            $no_factura,
            $placa,
            $repuestos,
            $kilometraje,
            $estado,
            $tipo_servicio,
            $ln,
            $usuario,
            $tipo,
            $codigo_ax,
            $tipo_mantenimiento
        ];

        return $this->insert($sql, $arrData);
    }

    public function updateServicio($servicio, $no_factura, $placa, $kilometraje, $estado, $tipo_servicio, $ln, $usuario, $tipo, $codigo_ax, $tipo_mantenimiento)
    {
        $sql = "UPDATE tb_servicios 
            SET 
                no_factura=?,
                placa=?,
                kilometraje=?,
                estado=?,
                tipo_servicio=?,
                ln=?,
                usuario=?,
                tipo_persona=?,
                codigo_ax=?,
                tipo_mantenimiento=?
            WHERE id_servicio = ?";

        $arrData = [
            $no_factura,
            $placa,
            $kilometraje,
            $estado,
            $tipo_servicio,
            $ln,
            $usuario,
            $tipo,
            $codigo_ax,
            $tipo_mantenimiento,
            $servicio
        ];

        return $this->update($sql, $arrData);
    }

    public function FacturasServiciobyID(int $id)
    {
        // Consulta principal + subconsulta que concatena materiales con separador '|||'
        $sql = "SELECT
                tr.id_regimen,
                tr.nombre_regimen,
                td.id_detalle,
                td.no_factura,
                td.registro_ax,
                td.bien_servicio,
                td.valor_documento,
                td.iva,
                td.base,
                td.observacion,
                ts.id_servicio,
                ts.placa,
                ts.kilometraje,
                ts.estado,
                ts.tipo_servicio,
                ts.ln,
                ts.usuario,
                ts.codigo_ax,
                ts.tipo_mantenimiento,
                (SELECT GROUP_CONCAT(material SEPARATOR ';') 
                 FROM tb_materiales m 
                 WHERE m.servicio_id = ts.id_servicio
                ) AS materiales
            FROM tb_detalles td
            LEFT JOIN tb_servicios ts ON td.id_detalle = ts.no_factura
            INNER JOIN tb_contraseña tc ON td.contraseña = tc.contraseña
            INNER JOIN tb_proveedor tp ON tc.id_proveedor = tp.id_proveedor
            INNER JOIN tb_regimen tr ON tp.regimen = tr.id_regimen
            WHERE td.id_detalle = ?";
        // Devuelve un único registro (o null)
        $request = $this->select($sql, array($id));
        return $request;
    }

    public function FacturasRentabyID(int $id)
    {
        $sql = "SELECT
                tr.id_regimen,
                tr.nombre_regimen,
                td.id_detalle,
                td.no_factura,
                td.registro_ax,
                td.bien_servicio,
                td.valor_documento,
                td.iva,
                td.base,
                td.observacion,
                (SELECT GROUP_CONCAT(placa SEPARATOR ';') 
                 FROM tb_rentas m 
                 WHERE m.no_factura = td.id_detalle
                ) AS arrendamientos
            FROM tb_detalles td
            INNER JOIN tb_contraseña tc ON td.contraseña = tc.contraseña
            INNER JOIN tb_proveedor tp ON tc.id_proveedor = tp.id_proveedor
            INNER JOIN tb_regimen tr ON tp.regimen = tr.id_regimen
            WHERE td.id_detalle = ?";
        $request = $this->select($sql, array($id));
        return $request;
    }

public function getServiciosbyContra(int $contraseña)
{
    $sql = "SELECT
            td.no_factura AS no_factura,
            ts.repuestos,
            ts.placa,
            ts.estado,
            ts.usuario,
            ts.tipo_persona,
            REPLACE(ts.ln, 'LINEA DE NEGOCIO', 'LN') AS ln,
            td.valor_documento
        FROM tb_servicios ts
        INNER JOIN tb_detalles td ON ts.no_factura = td.id_detalle
        WHERE td.contraseña = ?";

    $request = $this->select_multi($sql, array($contraseña));
    return $request;
}


    public function getRentasbyContra($contraseña)
    {
        $sql = "SELECT
                td.no_factura AS no_factura,
                tr.placa,
                td.valor_documento
            FROM tb_rentas tr
            INNER JOIN tb_detalles td ON tr.no_factura = td.id_detalle
            WHERE td.contraseña = ?";
        $request = $this->select_multi($sql, array($contraseña));
        return $request;
    }

    public function getRetenciones($contraseña)
    {
        $sql = "SELECT
                no_factura AS no_factura,
                valor_documento,
                reten_iva,
                reten_isr
            FROM tb_detalles
            WHERE contraseña = ?";
        $request = $this->select_multi($sql, array($contraseña));
        return $request;
    }

    public function getMaterialesByServicio($servicio_id)
    {
        $sql = "SELECT * FROM tb_materiales WHERE servicio_id = ?";
        $arrData = [$servicio_id];
        return $this->select_multi($sql, $arrData);
    }


    public function insertMaterial($servicio_id, $material)
    {
        $sql = "INSERT INTO tb_materiales (servicio_id, material) VALUES (?, ?)";
        $arrData = [$servicio_id, $material];
        return $this->insert($sql, $arrData);
    }

    public function deleteMaterial($servicio_id, $material)
    {
        $sql = "DELETE FROM tb_materiales WHERE servicio_id = ? AND material = ?";
        $arrData = [$servicio_id, $material];
        return $this->deletebyid($sql, $arrData);
    }

    public function getPlacasByServicio($servicio_id)
    {
        $sql = "SELECT * FROM tb_rentas WHERE no_factura = ?";
        $arrData = [$servicio_id];
        return $this->select_multi($sql, $arrData);
    }

    public function insertRenta($no_factura, $placa)
    {
        $sql = "INSERT INTO tb_rentas (no_factura, placa) VALUES (?, ?)";
        $arrData = [$no_factura, $placa];
        return $this->insert($sql, $arrData);
    }

    public function deleteRenta($no_factura, $placa)
    {
        $sql = "DELETE FROM tb_rentas WHERE no_factura = ? AND placa = ?";
        $arrData = [$no_factura, $placa];
        return $this->deletebyid($sql, $arrData);
    }

    public function insertCombustible($contraseña, $transferencia, $saldo_disponible, $fecha_inicio, $fecha_final)
    {
        $sql = "INSERT INTO tb_combustible (contraseña, transferencia, saldo_disponible, fecha_inicio, fecha_final)
            VALUES (?,?,?,?,?)";
        $arrData = [$contraseña, $transferencia, $saldo_disponible, $fecha_inicio, $fecha_final];
        return $this->insert($sql, $arrData);
    }

    public function getCombustiblebyContra($contraseña)
    {
        $sql = "SELECT 
            tc.contraseña,
            tc.transferencia,
            tc.saldo_disponible,
            tc.fecha_inicio,
            tc.fecha_final
        FROM tb_combustible tc
        INNER JOIN tb_solicitud_fondos ts ON tc.contraseña = ts.contraseña
        WHERE ts.contraseña = ?";
        $request = $this->select_multi($sql, array($contraseña));
        return $request;
    }

    public function getImpuestosByContraseña($contraseña)
    {
        $sql = "SELECT
        DATE_FORMAT(tc.fecha_pago, '%d/%m/%Y') AS fecha_pago,

        /* MONTO TOTAL */
        ROUND(SUM(d.valor_documento), 2) AS monto_total,

        /* SUBTOTAL */
        ROUND(SUM(
            CASE
                WHEN sf.tipo_regimen = 1
                THEN d.valor_documento / 1.12
                ELSE d.valor_documento
            END
        ), 2) AS subtotal,

        /* IVA */
        ROUND(SUM(
            CASE
                WHEN sf.tipo_regimen = 1
                THEN d.valor_documento - (d.valor_documento / 1.12)
                ELSE 0
            END
        ), 2) AS iva,

        /* RETENCIÓN IVA */
        ROUND(SUM(
            CASE
                WHEN sf.iva = 1
                    AND (
                        (sf.tipo_regimen = 1 AND d.valor_documento >= 2232.14)
                        OR (sf.tipo_regimen = 2 AND d.valor_documento >= 2500.01)
                    )
                THEN
                    CASE
                        WHEN sf.tipo_regimen = 1
                        THEN (d.valor_documento - (d.valor_documento / 1.12)) * 0.15
                        WHEN sf.tipo_regimen = 2
                        THEN d.valor_documento * 0.05
                        ELSE 0
                    END
                ELSE 0
            END
        ), 2) AS reten_iva,

        /* RETENCIÓN ISR */
        ROUND(SUM(
            CASE
                WHEN sf.isr = 1
                    AND (
                        (sf.tipo_regimen = 1 AND d.valor_documento >= 2232.14)
                        OR (sf.tipo_regimen = 2 AND d.valor_documento >= 2500.01)
                    )
                THEN
                    CASE
                        WHEN sf.tipo_regimen = 1
                        THEN (d.valor_documento / 1.12) * 0.05
                        WHEN sf.tipo_regimen = 2
                        THEN d.valor_documento * 0.07
                        ELSE 0
                    END
                ELSE 0
            END
        ), 2) AS reten_isr,

        /* ANTICIPO */
        ROUND(IFNULL(SUM(df.valor_documento), 0), 2) AS anticipo,

        /* TOTAL FINAL */
        ROUND(
            SUM(d.valor_documento)
            - SUM(
                CASE
                    WHEN sf.iva = 1
                        AND (
                            (sf.tipo_regimen = 1 AND d.valor_documento >= 2232.14)
                            OR (sf.tipo_regimen = 2 AND d.valor_documento >= 2500.01)
                        )
                    THEN
                        CASE
                            WHEN sf.tipo_regimen = 1
                            THEN (d.valor_documento - (d.valor_documento / 1.12)) * 0.15
                            WHEN sf.tipo_regimen = 2
                            THEN d.valor_documento * 0.05
                        END
                    ELSE 0
                END
            )
            - SUM(
                CASE
                    WHEN sf.isr = 1
                        AND (
                            (sf.tipo_regimen = 1 AND d.valor_documento >= 2232.14)
                            OR (sf.tipo_regimen = 2 AND d.valor_documento >= 2500.01)
                        )
                    THEN
                        CASE
                            WHEN sf.tipo_regimen = 1
                            THEN (d.valor_documento / 1.12) * 0.05
                            WHEN sf.tipo_regimen = 2
                            THEN d.valor_documento * 0.07
                        END
                    ELSE 0
                END
            )
            - IFNULL(SUM(df.valor_documento), 0)
        , 2) AS total

    FROM tb_contraseña tc
    INNER JOIN tb_detalles d 
        ON tc.contraseña = d.contraseña
    LEFT JOIN tb_solicitud_fondos sf 
        ON sf.contraseña = tc.contraseña
    LEFT JOIN tb_detalles df 
        ON df.no_factura = sf.id_solicitud
    WHERE tc.contraseña = ?";

        return $this->select($sql, [$contraseña]);
    }


    public function getImpuestosRenta($contraseña)
    {
        $sql = "SELECT
        DATE_FORMAT(tc.fecha_pago, '%d/%m/%Y') AS fecha_pago,

        /* MONTO TOTAL */
        ROUND(SUM(d.valor_documento), 2) AS subtotal,

        /* SUBTOTAL (solo para régimen general) */
        ROUND(SUM(
            CASE
                WHEN sf.tipo_regimen = 1
                THEN d.valor_documento / 1.12
                ELSE d.valor_documento
            END
        ), 2) AS total_iva,

        /* RETENCIÓN IVA */
        ROUND(SUM(
            CASE
                WHEN sf.iva = 1
                    AND (
                        (sf.tipo_regimen = 1 AND d.valor_documento >= 2232.14)
                        OR (sf.tipo_regimen = 2 AND d.valor_documento >= 2500.01)
                    )
                THEN
                    CASE
                        WHEN sf.tipo_regimen = 1
                        THEN (d.valor_documento - (d.valor_documento / 1.12)) * 0.15
                        WHEN sf.tipo_regimen = 2
                        THEN d.valor_documento * 0.05
                        ELSE 0
                    END
                ELSE 0
            END
        ), 2) AS reten_iva,

        /* ANTICIPO */
        ROUND(IFNULL(SUM(df.valor_documento), 0), 2) AS anticipo,

        /* CREDITO */
        
        tc2.no_factura AS factura_credito,
        ROUND(IFNULL(tc2.monto_credito, 0), 2) AS monto_credito,

        /* INDICA SI HAY CREDITO */
        CASE
            WHEN tc2.monto_credito IS NOT NULL
                 AND tc2.monto_credito > 0
            THEN 1
            ELSE 0
        END AS tiene_credito,

        /* TOTAL FINAL */
        ROUND(
            SUM(d.valor_documento)

            /* RESTA RETENCIÓN IVA */
            - SUM(
                CASE
                    WHEN sf.iva = 1
                        AND (
                            (sf.tipo_regimen = 1 AND d.valor_documento >= 2232.14)
                            OR (sf.tipo_regimen = 2 AND d.valor_documento >= 2500.01)
                        )
                    THEN
                        CASE
                            WHEN sf.tipo_regimen = 1
                            THEN (d.valor_documento - (d.valor_documento / 1.12)) * 0.15
                            WHEN sf.tipo_regimen = 2
                            THEN d.valor_documento * 0.05
                            ELSE 0
                        END
                    ELSE 0
                END
            )

            /* RESTA RETENCIÓN ISR */
            - SUM(
                CASE
                    WHEN sf.isr = 1
                        AND (
                            (sf.tipo_regimen = 1 AND d.valor_documento >= 2232.14)
                            OR (sf.tipo_regimen = 2 AND d.valor_documento >= 2500.01)
                        )
                    THEN
                        CASE
                            WHEN sf.tipo_regimen = 1
                            THEN (d.valor_documento / 1.12) * 0.05
                            WHEN sf.tipo_regimen = 2
                            THEN d.valor_documento * 0.07
                            ELSE 0
                        END
                    ELSE 0
                END
            )

            /* RESTA ANTICIPOS */
            - IFNULL(SUM(df.valor_documento), 0)

            /* RESTA CREDITO */
            - IFNULL(tc2.monto_credito, 0)

        , 2) AS total,

        MAX(sf.iva) AS aplica_iva,
        MAX(sf.isr) AS aplica_isr

    FROM tb_contraseña tc
    INNER JOIN tb_detalles d 
        ON tc.contraseña = d.contraseña
    LEFT JOIN tb_solicitud_fondos sf 
        ON sf.contraseña = tc.contraseña
    LEFT JOIN tb_detalles df 
        ON df.no_factura = sf.id_solicitud
    LEFT JOIN tb_creditos tc2
        ON tc2.contraseña = tc.contraseña
    WHERE tc.contraseña = ?";

        return $this->select($sql, [$contraseña]);
    }

    public function updateMesRenta($contraseña, $renta_mes)
    {
        $sql = "UPDATE tb_solicitud_fondos 
                SET mes_renta = ?
                WHERE contraseña = ?";
        $arrData = [$renta_mes, $contraseña];
        return $this->update($sql, $arrData);
    }

    public function PDFdatos($contraseña)
    {
        $sql = "SELECT
            ts.contraseña,
            ts.mes_renta,
            tc.no_factura,
            tc.monto_credito,
            CASE
                WHEN tc.monto_credito IS NOT NULL
                    AND tc.monto_credito > 0
                THEN 1
                ELSE 0
            END AS credito
        FROM tb_solicitud_fondos ts
        LEFT JOIN tb_creditos tc ON ts.contraseña = tc.contraseña
        WHERE ts.contraseña = ?";

        return $this->select($sql, [$contraseña]);
    }


    public function existeCredito($contraseña)
    {
        $sql = "SELECT id_credito 
            FROM tb_creditos 
            WHERE contraseña = ?";
        return $this->select($sql, [$contraseña]);
    }

    public function insertCredito($contraseña, $no_factura, $monto_credito)
    {
        $sql = "INSERT INTO tb_creditos
            (contraseña, no_factura, monto_credito, estado)
            VALUES (?, ?, ?, 'Activo')";
        return $this->insert($sql, [$contraseña, $no_factura, $monto_credito]);
    }

    public function updateCredito($contraseña, $no_factura, $monto_credito)
    {
        $sql = "UPDATE tb_creditos 
                SET no_factura = ?,
                monto_credito = ?,
                estado = 'Activo'
                WHERE contraseña = ?";
        $arrData = [$no_factura, $monto_credito, $contraseña];
        return $this->update($sql, $arrData);
    }

    public function deleteCredito($contraseña)
    {
        $sql = "DELETE FROM tb_creditos
                WHERE contraseña = ?";
        $arrData = [$contraseña];
        return $this->update($sql, $arrData);
    }

    public function getTotalRentaSinCredito($contraseña)
    {
        $sql = "SELECT
        ROUND(
            SUM(d.valor_documento)

            - SUM(
                CASE
                    WHEN sf.iva = 1
                        AND (
                            (sf.tipo_regimen = 1 AND d.valor_documento >= 2232.14)
                            OR (sf.tipo_regimen = 2 AND d.valor_documento >= 2500.01)
                        )
                    THEN
                        CASE
                            WHEN sf.tipo_regimen = 1
                            THEN (d.valor_documento - (d.valor_documento / 1.12)) * 0.15
                            WHEN sf.tipo_regimen = 2
                            THEN d.valor_documento * 0.05
                            ELSE 0
                        END
                    ELSE 0
                END
            )

            - SUM(
                CASE
                    WHEN sf.isr = 1
                        AND (
                            (sf.tipo_regimen = 1 AND d.valor_documento >= 2232.14)
                            OR (sf.tipo_regimen = 2 AND d.valor_documento >= 2500.01)
                        )
                    THEN
                        CASE
                            WHEN sf.tipo_regimen = 1
                            THEN (d.valor_documento / 1.12) * 0.05
                            WHEN sf.tipo_regimen = 2
                            THEN d.valor_documento * 0.07
                            ELSE 0
                        END
                    ELSE 0
                END
            )

            - IFNULL(SUM(df.valor_documento), 0)
        , 2) AS total_sin_credito
    FROM tb_contraseña tc
    INNER JOIN tb_detalles d 
        ON tc.contraseña = d.contraseña
    LEFT JOIN tb_solicitud_fondos sf 
        ON sf.contraseña = tc.contraseña
    LEFT JOIN tb_detalles df 
        ON df.no_factura = sf.id_solicitud
    WHERE tc.contraseña = ?";

        return $this->select($sql, [$contraseña]);
    }

}