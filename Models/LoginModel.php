<?php

class LoginModel extends Mysql
{
    private $id_user;
    private $usuario;
    private $password;


    public function __construct()
    {
        parent::__construct();
    }

    public function loginUser($correo_empresarial)
    {
        $sql = "SELECT 
        id_usuario, 
        nombres,
        CONCAT(nombres,' ', primer_apellido,' ', segundo_apellido) AS nombre_completo,
        identificacion,
        no_empleado,
        correo,
        tu.area,
        ta.nombre_area,
        rol_usuario,
        fecha_ingreso,
        tu.estado
        FROM tb_usuarios tu
        INNER JOIN tb_areas ta ON tu.area = ta.id_area
        WHERE correo = ? AND tu.estado = 'Activo'";
        $arrData = array($correo_empresarial);
        return $this->select($sql, $arrData); // Devuelve los datos si existe
    }

    public function sessionLogin(int $id_user)
    {
        $this->id_user = $id_user;

        $sql = "SELECT 
            us.id_user,
            us.usuario_id,
            us.correo_empresarial,
            us.password,
            CONCAT(et.nombres,' ', et.primer_apellido,' ', et.segundo_apellido) AS nombres,
            us.role_id,
            us.estado
        FROM users_sistema us
        INNER JOIN empleado_tb et ON us.usuario_id = et.id_empleado
		WHERE us.id_user = $this->id_user";
        $request = $this->select($sql);
        $_SESSION['userData'] = $request;
        return $request;
    }

    public function updateSessionToken($usuario_id, $sessionToken)
    {
        $sql = "UPDATE users_sistema SET session_token = ? WHERE usuario_id = ?";
        $arrData = array($sessionToken, $usuario_id);
        $this->select($sql, $arrData);
    }

    public function getUserByIdentificacion($correo_empresarial)
    {
        $sql = "SELECT 
        u.correo_empresarial,
        e.estado,
        u.password
        FROM users_sistema u
        INNER JOIN empleado_tb e ON u.usuario_id = e.id_empleado
        WHERE u.correo_empresarial = ? AND e.estado = 'Activo'";
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
        $sql = "INSERT INTO login_tokens (correo_empresarial, token, expires_at) VALUES (?, ?, ?)";
        $arrData = [$correo, $token, $expires_at];
        return $this->insert($sql, $arrData);
    }


    public function validarToken($correo_empresarial, $token)
    {
        // Definir la zona horaria en PHP (por seguridad)
        date_default_timezone_set('America/Guatemala'); // Ajusta según tu zona horaria

        // Obtener la fecha y hora actual en la zona correcta
        $query = date("Y-m-d H:i:s");

        $sql = "SELECT 
            e.id_empleado, 
            e.nombres,
            e.codigo_empleado AS no_empleado,
            CONCAT(e.primer_apellido, ' ', e.segundo_apellido) as apellidos,
            CONCAT(e.nombres, ' ',e.primer_apellido, ' ', e.segundo_apellido) as nombre_completo,
            e.correo_empresarial 
                FROM login_tokens t
                INNER JOIN empleado_tb e ON e.correo_empresarial = t.correo_empresarial
                WHERE t.correo_empresarial = ? 
                AND t.token = ? 
                AND t.expires_at > ?";

        $arrData = [$correo_empresarial, $token, $query];

        return $this->select($sql, $arrData); // Retorna los datos del usuario si el token es válido
    }


}

