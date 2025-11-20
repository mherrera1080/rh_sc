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

}