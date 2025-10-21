<?php
class ContraseñasModel extends Mysql
{
    public function __construct()
    {
        parent::__construct();
    }

    public function selectContras()
    {
        $area = intval($_SESSION['PersonalData']['area']);
        $where = ($area == 4) ? "" : "WHERE tc.area = $area";

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
        $where
        GROUP BY tc.id_contraseña";

        return $this->select_all($sql);
    }

    public function selectDetalles()
    {
        $area = intval($_SESSION['PersonalData']['area']);
        $where = ($area == 4) ? "" : "WHERE td.area = $area";

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
            ROUND(SUM(td.valor_documento), 2) AS monto_total,
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

    public function getAnticipo($proveedor, $area)
    {
        $sql = "SELECT 1
            FROM tb_solicitud_fondos
            WHERE proveedor = ? 
              AND area = ? 
              AND categoria = 'Anticipo' 
              AND estado = 'Finalizado'
            LIMIT 1";
        $request = $this->select_multi_parameters($sql, [$proveedor, $area]);

        return !empty($request);
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

    public function validacionContraseñaSoli($contraseña, $estado)
    {
        $sql = "UPDATE tb_contraseña 
                SET 
                estado = ?
                WHERE contraseña = ?";
        $arrData = [$estado, $contraseña];
        return $this->update($sql, $arrData);
    }

    public function solicitudFondoVehiculos($contraseña, $area, $categoria)
    {
        $sql = "INSERT INTO tb_solicitud_fondos (
            contraseña,
            area,
            categoria,
            fecha_creacion,
            estado
        )
        VALUES (?, ?, ?, CURDATE(), ?)";

        $arrData = [
            $contraseña,
            $area,
            $categoria,
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

}