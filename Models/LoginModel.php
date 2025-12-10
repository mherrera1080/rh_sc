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
            us.contraseña,
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
        u.contraseña
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
        CONCAT(nombres, ' ', primer_apellido, ' ', segundo_apellido) AS nombre_completo
        FROM tb_usuarios 
        WHERE correo = ? AND estado = 'Activo'";
        $arrData = [$correo_empresarial];
        return $this->select($sql, $arrData);
    }

    public function getUserByCorreo($correo_empresarial)
    {
        $sql = "SELECT 
        id_usuario, 
        correo,
        contraseña,
        estado
        FROM tb_usuarios 
        WHERE correo = ? AND estado = 'Activo'";
        $arrData = [$correo_empresarial];
        return $this->select($sql, $arrData);
    }

    public function guardarToken($correo, $token, $expires_at)
    {
        $sql = "INSERT INTO auth_tokens (correo_empresarial, token, expires_at) VALUES (?, ?, ?)";
        $arrData = [$correo, $token, $expires_at];
        return $this->insert($sql, $arrData);
    }


    public function getTokenValido($correo_empresarial, $token)
    {
        $sql = "SELECT * FROM auth_tokens 
            WHERE correo_empresarial = ? 
            AND token = ? 
            AND expires_at >= NOW()
            ORDER BY id DESC LIMIT 1";
        $arrData = [$correo_empresarial, $token];
        return $this->select($sql, $arrData);
    }



}

