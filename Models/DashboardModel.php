<?php
class DashboardModel extends Mysql
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getUserByIdentificacion($correo_empresarial)
    {
        $sql = "SELECT 
        u.correo,
        u.estado,
        u.contraseña
        FROM tb_usuarios u
        WHERE u.correo = ? AND u.estado = 'Activo'";
        $request = $this->select($sql, [$correo_empresarial]);
        return $request;
    }

    public function verificarCorreo($correo_empresarial)
    {
        $sql = "SELECT 
        id_usuario, 
        nombres,
        CONCAT(nombres,' ', primer_apellido,' ', segundo_apellido) AS nombre_completo
        FROM tb_usuarios 
        WHERE correo = ? AND estado = 'Activo'";
        $arrData = array($correo_empresarial);
        return $this->select($sql, $arrData); // Devuelve los datos si existe
    }

    public function guardarToken($correo, $token, $expires_at)
    {
        $sql = "INSERT INTO auth_tokens (correo_empresarial, token, created_at, expires_at) VALUES (?, ?, NOW(), ?)";
        $arrData = [$correo, $token, $expires_at];
        return $this->insert($sql, $arrData);
    }

    public function updateContra($correo_empresarial, string $password)
    {
        $updatePassword = "contraseña = ?";
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);

        $sql = "UPDATE tb_usuarios 
                SET $updatePassword
                WHERE correo = ?";
        $arrData = array($password_hashed, $correo_empresarial);
        return $this->update($sql, $arrData);
    }

    public function validarToken($correo_empresarial, $token)
    {
        // Definir la zona horaria en PHP (por seguridad)
        date_default_timezone_set('America/Guatemala'); // Ajusta según tu zona horaria

        // Obtener la fecha y hora actual en la zona correcta
        $query = date("Y-m-d H:i:s");

        $sql = "SELECT 
            e.id_usuario,
            e.nombres,
            e.no_empleado AS no_empleado,
            CONCAT(e.primer_apellido, ' ', e.segundo_apellido) as apellidos,
            CONCAT(e.nombres, ' ',e.primer_apellido, ' ', e.segundo_apellido) as nombre_completo,
            e.correo 
                FROM auth_tokens t
                INNER JOIN tb_usuarios e ON e.correo = t.correo_empresarial
                WHERE t.correo_empresarial = ? 
                AND t.token = ? 
                AND t.expires_at > ?";

        $arrData = [$correo_empresarial, $token, $query];

        return $this->select($sql, $arrData); // Retorna los datos del usuario si el token es válido
    }


    public function getAreas()
    {
        $sql = "SELECT 
            id_area, 
            nombre_area, 
            estado 
            FROM tb_areas";
        $request = $this->select_all($sql);
        return $request;
    }

    public function getProveedores()
    {
        $sql = "SELECT 
            id_proveedor, 
            nombre_proveedor
            FROM tb_proveedor";
        $request = $this->select_all($sql);
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
            tc.correcciones,
            tc.estado
        FROM tb_contraseña tc
        INNER JOIN tb_proveedor tp ON tc.id_proveedor = tp.id_proveedor
        INNER JOIN tb_areas ta ON tc.area = ta.id_area
        INNER JOIN tb_detalles td ON tc.contraseña = td.contraseña 
        GROUP BY tc.id_contraseña";

        return $this->select_all($sql);
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
            tc.observacion,
            tc.estado
        FROM tb_solicitud_fondos tc
        INNER JOIN tb_areas ta ON tc.area = ta.id_area
        LEFT JOIN tb_contraseña ts ON tc.contraseña = ts.contraseña
        WHERE tc.categoria != 'Anticipo'
        ";
        $request = $this->select_all($sql);
        return $request;
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

    public function selectAnticipos()
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
            tc.observacion,
            tc.estado
        FROM tb_solicitud_fondos tc
        INNER JOIN tb_areas ta ON tc.area = ta.id_area
        LEFT JOIN tb_contraseña ts ON tc.contraseña = ts.contraseña
        WHERE tc.categoria = 'Anticipo' ";
        $request = $this->select_all($sql);
        return $request;
    }

}