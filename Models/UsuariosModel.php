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
        rol,
        contraseña,
        estado
        FROM tb_usuarios";
        $request = $this->select_all($sql);
        return $request;
    }

    public function insertUsuario(string $nombres, string $primer_apellido, string $segundo_apellido, int $identificacion, ?string $codigo_empleado, string $correo, int $rol)
    {
        try {

            $sql = "INSERT INTO tb_usuarios(nombres, primer_apellido, segundo_apellido, identificacion, no_empleado, correo, rol) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
            $arrData = array($nombres, $primer_apellido, $segundo_apellido, $identificacion, $codigo_empleado, $correo, $rol);
            $result = $this->insert($sql, $arrData);

            return $result;
        } catch (PDOException $e) {
            return "ERROR: " . $e->getMessage();
        }
    }

    public function updateUsuario(int $id_usuario, string $nombres, string $primer_apellido, string $segundo_apellido, int $identificacion, ?string $codigo_empleado, string $correo, int $rol)
    {
        try {
            $sql = "UPDATE tb_usuarios SET 
                    nombres = ?, 
                    primer_apellido = ?, 
                    segundo_apellido = ?, 
                    identificacion = ?, 
                    no_empleado = ?, 
                    correo = ?, 
                    rol = ?
                WHERE id_usuario = ?";

            $arrData = array(
                $nombres,
                $primer_apellido,
                $segundo_apellido,
                $identificacion,
                $codigo_empleado,
                $correo,
                $rol,
                $id_usuario
            );

            $result = $this->update($sql, $arrData);

            return $result;
        } catch (PDOException $e) {
            return "ERROR: " . $e->getMessage();
        }
    }

}