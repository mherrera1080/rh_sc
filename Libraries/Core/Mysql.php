<?php

class Mysql extends Conexion
{
	private $conexion;
	private $strquery;
	private $arrValues;

	private $strQuery;
	private $arrVAlues;

	function __construct()
	{
		$this->conexion = new Conexion();
		$this->conexion = $this->conexion->conect();
	}


	// Método insert modificado para registrar inserciones en el log
	public function insert(string $query, array $arrValues)
	{
		$this->strquery = $query;
		$this->arrValues = $arrValues;

		$insert = $this->conexion->prepare($this->strquery);
		$resInsert = $insert->execute($this->arrValues);

		if ($resInsert) {
			$lastInsert = $this->conexion->lastInsertId();
		} else {
			$lastInsert = 0;
		}

		return $lastInsert;
	}

	public function callProcedure(string $procedure, array $params)
	{
		// Asegúrate de que la consulta tiene solo un CALL
		$this->strquery = "CALL $procedure(" . implode(', ', array_fill(0, count($params), '?')) . ")";
		$stmt = $this->conexion->prepare($this->strquery);
		$resExecute = $stmt->execute($params);
		return $resExecute;
	}
	

	public function insertLog(string $query, array $arrValues, string $tabla, array $columnas)
	{
		$this->strquery = $query;
		$this->arrValues = $arrValues;

		$insert = $this->conexion->prepare($this->strquery);
		$resInsert = $insert->execute($this->arrValues);

		if ($resInsert) {
			$lastInsert = $this->conexion->lastInsertId();
			$columnas['id_usuario'] = 'id_usuario';
			$arrValues['id_usuario'] = $lastInsert;
			// Crear un nuevo arreglo con nombres de campos y valores
			$namedFields = array_combine($columnas, $arrValues);

			if (isset($_SESSION)) {
				// Aquí puedes acceder a las variables de sesión de forma segura
				$usuario = $_SESSION['userData']['nombres'] . " " . $_SESSION['userData']['apellidos'];
				// Realiza otras operaciones relacionadas con la sesión
			} else {
				// No hay sesión activa, puedes tomar medidas apropiadas aquí, como redirigir a la página de inicio de sesión
				$usuario = "Api";
			}

			$this->insertLogEntry($usuario, $_SERVER['REMOTE_ADDR'], $tabla, 'insert', $namedFields);

		} else {
			$lastInsert = 0;
		}

		return $lastInsert;
	}

	public function insertLogUsuario(string $query, array $arrValues, string $tabla, array $columnas)
	{
		$this->strquery = $query;
		$this->arrValues = $arrValues;

		$insert = $this->conexion->prepare($this->strquery);
		$resInsert = $insert->execute($this->arrValues);

		if ($resInsert) {
			$lastInsert = $this->conexion->lastInsertId();


			// Crear un nuevo arreglo con nombres de campos y valores
			$namedFields = array_combine($columnas, $arrValues);

			$usuario = "Externo";

			$this->insertLogEntry($usuario, $_SERVER['REMOTE_ADDR'], $tabla, 'insert', $namedFields);
		} else {
			$lastInsert = 0;
		}

		return $lastInsert;
	}

	//Busca un registro
	public function select($sql, $params = [])
	{
		$this->strQuery = $sql;
		$this->arrValues = $params;
		$query = $this->conexion->prepare($this->strQuery);
		$query->execute($this->arrValues);
		$data = $query->fetch(PDO::FETCH_ASSOC);
		return $data;
	}

	public function select_parameters(string $sql, array $params = [])
	{
		$this->strQuery = $sql;
		$this->arrValues = $params;
		$query = $this->conexion->prepare($this->strQuery);
		$query->execute($this->arrValues);

		// Obtener todos los registros
		$data = $query->fetch(PDO::FETCH_ASSOC);

		return $data;
	}

	//Devuelve todos los registros
	public function select_all(string $query)
	{
		$this->strquery = $query;
		$result = $this->conexion->prepare($this->strquery);
		$result->execute();
		$data = $result->fetchall(PDO::FETCH_ASSOC);
		return $data;
	}

	public function select_multi(string $sql, array $params = [])
	{
		$this->strQuery = $sql;
		$this->arrValues = $params;
		$query = $this->conexion->prepare($this->strQuery);
		$query->execute($this->arrValues);

		// Obtener todos los registros
		$data = $query->fetchAll(PDO::FETCH_ASSOC);

		return $data;
	}


	public function select_multi_parameters(string $sql, array $params = [])
	{
		$this->strQuery = $sql;
		$this->arrValues = $params;
		$query = $this->conexion->prepare($this->strQuery);
		$query->execute($this->arrValues);

		// Obtener todos los registros
		$data = $query->fetchAll(PDO::FETCH_ASSOC);

		return $data;
	}



	//Actualiza registros
	public function update(string $query, array $arrValues)
	{
		$this->strquery = $query;
		$this->arrVAlues = $arrValues;
		$update = $this->conexion->prepare($this->strquery);
		$resExecute = $update->execute($this->arrVAlues);
		return $resExecute;
	}
	//Eliminar un registros
	public function delete(string $query)
	{
		$this->strquery = $query;
		$result = $this->conexion->prepare($this->strquery);
		$del = $result->execute();
		return $del;
	}

	public function deleteSolicitud(string $query, array $arrParams = [])
	{
		$this->strquery = $query;
		$result = $this->conexion->prepare($this->strquery);
		$del = $result->execute($arrParams);
		return $del;
	}


	public function deletebyid(string $query, array $params = [])
	{
		$this->strquery = $query;
		$result = $this->conexion->prepare($this->strquery);
		$del = $result->execute($params);

		if (!$del) {
			// Captura errores de PDO
			$errorInfo = $result->errorInfo();
			throw new Exception("Error executing query: " . $errorInfo[2]);
		}

		return $del;
	}



	// Método para insertar un registro en la tabla log
	public function insertLogEntry(string $usuario, string $ip, string $tabla, string $operacion, array $campos)
	{
		$query = "INSERT INTO tb_log (usuario, ip, tabla, operacion, campos, fecha) VALUES (?, ?, ?, ?, ?, DATE_SUB(NOW(), INTERVAL 2 HOUR))";

		$formattedFields = implode(
			"||",
			array_map(
				function ($campo, $valor) {
					return "$campo: $valor";
				},
				array_keys($campos),
				$campos
			)
		);

		$values = array($usuario, $ip, $tabla, $operacion, $formattedFields);

		$insert = $this->conexion->prepare($query);
		$resInsert = $insert->execute($values);

		return $resInsert;
	}
	//Actualiza registros
	public function updateWithLog(string $query, array $arrValues, string $tabla, array $oldValues, array $columnas)
	{

		$this->strquery = $query;
		$this->arrValues = $arrValues;
		$update = $this->conexion->prepare($this->strquery);
		$resUpdate = $update->execute($this->arrValues);
		if (isset($_SESSION)) {
			// Aquí puedes acceder a las variables de sesión de forma segura
			$usuario = $_SESSION['userData']['nombres'] . " " . $_SESSION['userData']['apellidos'];
			// Realiza otras operaciones relacionadas con la sesión
		} else {
			// No hay sesión activa, puedes tomar medidas apropiadas aquí, como redirigir a la página de inicio de sesión
			$usuario = "Api";
		}
		if ($resUpdate) {
			// Crear un arreglo con las columnas que han cambiado
			$changedFields = array();
			foreach ($columnas as $columna) {
				$newValue = array_shift($arrValues); // Obtenemos el valor actual de arrValues
				$oldValue = $oldValues[$columna]; // Obtenemos el valor anterior de oldValues		

				if (trim($newValue) !== trim($oldValue)) {
					$changedFields['ID Tabla'] = $newValue;
					$changedFields[$columna] = "Antiguo Registro= $oldValue - Nuevo Registro= $newValue";
				}
			}

			if (!empty($changedFields)) {
				$this->insertLogEntry(
					$usuario,
					$_SERVER['REMOTE_ADDR'],
					$tabla,
					'update',
					$changedFields
				);
			}
		}
		return $resUpdate;
	}

	public function deleteWithLog(string $query, string $tabla, array $whereValues)
	{


		$this->strquery = $query;
		$delete = $this->conexion->prepare($this->strquery);
		$resDelete = $delete->execute($whereValues);
		if (isset($_SESSION)) {
			// Aquí puedes acceder a las variables de sesión de forma segura
			$usuario = $_SESSION['userData']['nombres'] . " " . $_SESSION['userData']['apellidos'];
			// Realiza otras operaciones relacionadas con la sesión
		} else {
			// No hay sesión activa, puedes tomar medidas apropiadas aquí, como redirigir a la página de inicio de sesión
			$usuario = "Api";
		}
		if ($resDelete) {
			// Obtener información para el log
			$changedFields = array();
			foreach ($whereValues as $columna => $value) {
				$changedFields[$columna] = "Valor Eliminado= $value";
			}

			if (!empty($changedFields)) {
				$this->insertLogEntry(
					$usuario,
					$_SERVER['REMOTE_ADDR'],
					$tabla,
					'delete',
					$changedFields
				);
			}
		}

		return $resDelete;
	}

}
