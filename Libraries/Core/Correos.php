<?php
include 'conexion.php'; // Incluir la clase de conexión
require 'mailer.php'; // PHPMailer

// Crear instancia de la conexión
$conexion = new Conexion();
$db = $conexion->conect(); // Obtener la conexión PDO

