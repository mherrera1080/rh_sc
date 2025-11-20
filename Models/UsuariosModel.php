<?php
class UsuariosModel extends Mysql
{
    public function __construct()
    {
        parent::__construct();
    }

    public function selectUsuarios()
    {
        $sql = "SELECT
        id_usuario,
        identificacion,
        no_empleado,
        concat(nombres, ' ', primer_apellido, ' ', segundo_apellido) as nombre_completo,
        nombres,
        fecha_ingreso,
        correo,
        rol_usuario,
        contraseña,
        estado
        FROM tb_usuarios";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectUserByid($id_usuario)
    {
        $sql = "SELECT
                id_usuario,
                identificacion,
                no_empleado,
                primer_apellido, 
                segundo_apellido,
                nombres,
                fecha_ingreso,
                correo,
                area,
                rol_usuario,
                contraseña,
                estado
            FROM tb_usuarios
            WHERE id_usuario = ?";

        $request = $this->select($sql, array($id_usuario));
        return $request;
    }


    public function insertUsuario(
        string $nombres,
        string $primer_apellido,
        string $segundo_apellido,
        string $identificacion,
        ?string $codigo_empleado,
        string $correo,
        int $area,
        int $rol_usuario,
        string $password
    ) {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO tb_usuarios 
            (nombres, primer_apellido, segundo_apellido, identificacion, no_empleado, correo, area, rol_usuario, contraseña, fecha_ingreso) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
            $arrData = array(
                $nombres,
                $primer_apellido,
                $segundo_apellido,
                $identificacion,
                $codigo_empleado,
                $correo,
                $area,
                $rol_usuario,
                $hashedPassword
            );

            return $this->insert($sql, $arrData);
        } catch (PDOException $e) {
            return "ERROR: " . $e->getMessage();
        }
    }

    public function updateUsuario(
        int $id_usuario,
        string $nombres,
        string $primer_apellido,
        string $segundo_apellido,
        int $identificacion,
        ?string $codigo_empleado,
        string $correo,
        int $area,
        int $rol_usuario,
        ?string $password
    ) {
        try {
            if (!empty($password)) {
                // Encriptar si viene una nueva contraseña
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $sql = "UPDATE tb_usuarios SET 
                        nombres = ?, 
                        primer_apellido = ?, 
                        segundo_apellido = ?, 
                        identificacion = ?, 
                        no_empleado = ?, 
                        correo = ?,
                        area = ?,
                        rol_usuario = ?, 
                        contraseña = ?
                    WHERE id_usuario = ?";
                $arrData = array(
                    $nombres,
                    $primer_apellido,
                    $segundo_apellido,
                    $identificacion,
                    $codigo_empleado,
                    $correo,
                    $area,
                    $rol_usuario,
                    $hashedPassword,
                    $id_usuario
                );
            } else {
                // Si no se envía password, no lo actualiza
                $sql = "UPDATE tb_usuarios SET 
                        nombres = ?, 
                        primer_apellido = ?, 
                        segundo_apellido = ?, 
                        identificacion = ?, 
                        no_empleado = ?,
                        area = ?,
                        correo = ?, 
                        rol_usuario = ?
                    WHERE id_usuario = ?";
                $arrData = array(
                    $nombres,
                    $primer_apellido,
                    $segundo_apellido,
                    $identificacion,
                    $codigo_empleado,
                    $area,
                    $correo,
                    $rol_usuario,
                    $id_usuario
                );
            }

            $result = $this->update($sql, $arrData);
            return $result;
        } catch (PDOException $e) {
            return "ERROR: " . $e->getMessage();
        }
    }

    public function existenciaCorreo($correo)
    {
        $sql = "SELECT COUNT(*) as total 
            FROM tb_usuarios 
            WHERE correo = ? ";
        $request = $this->select($sql, [$correo]);
        return $request['total'] > 0;
    }

    public function existenciaDoc($identificacion)
    {
        $sql = "SELECT COUNT(*) as total 
            FROM tb_usuarios 
            WHERE identificacion = ? ";
        $request = $this->select($sql, [$identificacion]);
        return $request['total'] > 0;
    }

    public function existenciaCorreoUpdate($correo, $id_usuario)
    {
        $sql = "SELECT COUNT(*) as total 
            FROM tb_usuarios 
            WHERE correo = ? 
                AND id_usuario != ?";
        $request = $this->select($sql, [$correo, $id_usuario]);
        return $request['total'] > 0;
    }

        public function existenciaDocUpdate($identificacion, $id_usuario)
    {
        $sql = "SELECT COUNT(*) as total 
            FROM tb_usuarios 
            WHERE identificacion = ? 
                AND id_usuario != ?";
        $request = $this->select($sql, [$identificacion, $id_usuario]);
        return $request['total'] > 0;
    }


}