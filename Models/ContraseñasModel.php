<?php
class ContraseñasModel extends Mysql
{
    public function __construct()
    {
        parent::__construct();
    }

    public function selectRecepcion()
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


    public function selectContras()
    {
        $area = intval($_SESSION['PersonalData']['area']);
        // $where = ($area == 4) ? "" : "";

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
        WHERE tc.area = $area
        GROUP BY tc.id_contraseña";

        return $this->select_all($sql);
    }

    public function selectDetalles()
    {
        $area = intval($_SESSION['PersonalData']['area']);
        $where = "WHERE td.area = $area";

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
        $where";

        return $this->select_all($sql);
    }

    public function contrasContabilidad()
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
        WHERE tc.estado = 'Validado' 
        OR tc.area = 4
        GROUP BY tc.contraseña;";
        $request = $this->select_all($sql);
        return $request;
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


    public function selectProveedores()
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

    public function selectAreas(): array
    {
        $sql = "SELECT 
            id_area,
            nombre_area,
            estado
        FROM tb_areas";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectLastContraseña()
    {
        $sql = "SELECT
            id_contraseña,
            contraseña,
            contraseña + 1 AS nueva_contraseña,
            CURDATE() AS fecha_registro
        FROM tb_contraseña
        ORDER BY fecha_registro, id_contraseña DESC
        LIMIT 1";
        $request = $this->select($sql);
        return $request;
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

    public function getAllContraseña($contraseña)
    {

        $sql = "SELECT
            tc.id_contraseña,
            tc.contraseña,
            tc.realizador,
            tc.fecha_registro,
            tc.id_proveedor,
            tc.area,
            tc.fecha_pago,
            tc.correcciones,
            GROUP_CONCAT(td.no_factura ORDER BY td.no_factura ASC) AS no_factura,
            GROUP_CONCAT(td.bien_servicio ORDER BY td.no_factura ASC) AS bien_servicio,
            GROUP_CONCAT(td.valor_documento ORDER BY td.no_factura ASC) AS valor_documento,
            GROUP_CONCAT(td.observacion ORDER BY td.no_factura ASC) AS observacion,
            GROUP_CONCAT(td.estado ORDER BY td.no_factura ASC) AS estado
        FROM tb_contraseña tc
        INNER JOIN tb_detalles td ON tc.contraseña = td.contraseña
        WHERE tc.contraseña = ? ";
        $request = $this->select($sql, array($contraseña));
        return $request;
    }

    public function getFacturasbyContra(int $contraseña)
    {
        $sql = "SELECT
            td.id_detalle,
            td.no_factura,
            td.bien_servicio,
            td.valor_documento,
            td.fecha_registro,
            td.estado,
            tc.area,
            tc.solicitante,
            tc.estado as estado_contra
        FROM tb_detalles td
        INNER JOIN tb_contraseña tc ON td.contraseña = tc.contraseña 
        WHERE td.contraseña = ?";
        $request = $this->select_multi($sql, array($contraseña));
        return $request;
    }

    public function updateFactura($id_factura, $bien_servicio, $valor_documento, $estado)
    {
        $sql = "UPDATE tb_detalles SET
            bien_servicio = ?,
            valor_documento = ?,
            estado = ?
            WHERE id_detalle = ?";
        $arrData = [$bien_servicio, $valor_documento, $estado, $id_factura];
        $request = $this->update($sql, $arrData); // <---- cambio aquí
        return $request;
    }

    public function deleteDetallesFactura($contraseña, $factura)
    {
        $sql = "DELETE FROM tb_detalles WHERE contraseña = ? AND no_factura = ?";
        $arrData = [$contraseña, $factura];
        return $this->deletebyid($sql, $arrData);
    }

    public function updateContraseña($contraseña, $realizador, $fecha_registro, $fecha_pago, $id_proveedor, $area)
    {
        $sql = "UPDATE tb_contraseña 
                SET realizador = ?, 
                    fecha_registro = ?, 
                    fecha_pago = ?, 
                    id_proveedor = ?, 
                    area = ?
                WHERE contraseña = ?";
        $arrData = [$realizador, $fecha_registro, $fecha_pago, $id_proveedor, $area, $contraseña];
        return $this->update($sql, $arrData);
    }

    public function getDetallesPorContraseña($contraseña)
    {
        $sql = "SELECT no_factura, bien_servicio, valor_documento, estado 
                FROM tb_detalles 
                WHERE contraseña = ?";
        return $this->select_multi($sql, [$contraseña]);
    }

    public function updateDetalleFactura($contraseña, $factura, $bien_servicio, $valor_documento)
    {
        $sql = "UPDATE tb_detalles
                SET bien_servicio = ?, 
                    valor_documento = ?
                WHERE contraseña = ? AND no_factura = ?";
        $arrData = [$bien_servicio, $valor_documento, $contraseña, $factura];
        return $this->update($sql, $arrData);
    }

    public function estadoContraseña($contraseña, $correciones, $estado)
    {
        $sql = "UPDATE tb_contraseña 
                SET 
                estado = ?,
                correcciones = ?
                WHERE contraseña = ?";
        $arrData = [$estado, $correciones, $contraseña];
        return $this->update($sql, $arrData);
    }

    public function correccionContraseña($contraseña, $fecha_pago, $id_proveedor)
    {
        $sql = "UPDATE tb_contraseña 
                SET
                    estado = 'Corregido',
                    fecha_pago = ?, 
                    id_proveedor = ?
                WHERE contraseña = ?";
        $arrData = [$fecha_pago, $id_proveedor, $contraseña];
        return $this->update($sql, $arrData);
    }

    public function correccionDetallesFactura($contraseña, $factura, $bien_servicio, $valor_documento)
    {
        $sql = "UPDATE tb_detalles
                SET 
                    estado = 'Pendiente',
                    bien_servicio = ?, 
                    valor_documento = ?
                WHERE contraseña = ? AND no_factura = ?";
        $arrData = [$bien_servicio, $valor_documento, $contraseña, $factura];
        return $this->update($sql, $arrData);
    }

    public function validacionArea($contraseña, $anticipo, $area_user, $estado)
    {
        $sql = "UPDATE tb_contraseña 
                SET 
                anticipo = ?,
                area_user = ?,
                estado = ?
                WHERE contraseña = ?";
        $arrData = [$anticipo, $area_user, $estado, $contraseña];
        return $this->update($sql, $arrData);
    }

    public function validacionConta($contraseña, $conta_user, $estado)
    {
        $sql = "UPDATE tb_contraseña 
                SET 
                estado = ?,
                conta_user = ?
                WHERE contraseña = ?";
        $arrData = [$estado, $conta_user, $contraseña];
        return $this->update($sql, $arrData);
    }

    public function validacionContraseñaSoli($contraseña, $conta_user, $estado)
    {
        $sql = "UPDATE tb_contraseña 
                SET 
                estado = ?,
                conta_user = ?
                WHERE contraseña = ?";
        $arrData = [$estado, $conta_user, $contraseña];
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

    public function descartarDetalles($contraseña, $estado)
    {
        $sql = "UPDATE tb_detalles 
            SET estado = ?
        WHERE contraseña = ? ";
        $arrData = [$estado, $contraseña];
        return $this->deletebyid($sql, $arrData);
    }

    public function descartarFacturas($contraseña)
    {
        $sql = "DELETE FROM tb_detalles WHERE contraseña = ? AND estado != 'Validado'";
        $arrData = [$contraseña];
        return $this->deletebyid($sql, $arrData);
    }

    public function getUsuariosPorArea($id_area)
    {
        $sql = "SELECT id_usuario, 
        CONCAT(nombres, ' ', primer_apellido, ' ', segundo_apellido) AS nombre_completo, 
        correo
        FROM tb_usuarios
        WHERE area = ?";
        $request = $this->select_parameters($sql, [$id_area]);

        echo json_encode($request, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getCorreobyName($realizador)
    {
        $sql = "SELECT 
        correo 
        FROM tb_usuarios
        WHERE CONCAT(nombres, ' ', primer_apellido, ' ', segundo_apellido) = ?";
        $request = $this->select($sql, array($realizador));
        return $request;
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

}