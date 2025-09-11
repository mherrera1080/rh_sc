<?php

require "Assets/vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'Libraries/phpmailer/Exception.php';
require 'Libraries/phpmailer/PHPMailer.php';
require 'Libraries/phpmailer/SMTP.php';

//Retorla la url del proyecto
function base_url()
{
    return BASE_URL;
}
//Retorla la url de Assets
function media()
{
    return BASE_URL . "/Assets";
}
function headerAdmin($data = "")
{
    $view_header = "Views/Template/header_admin.php";
    require_once($view_header);
}

function footerAdmin($data = "")
{
    $view_footer = "Views/Template/footer_admin.php";
    require_once($view_footer);
}


function strClean($strCadena)
{
    $string = preg_replace(['/\s+/', '/^\s|\s$/'], [' ', ''], $strCadena);
    $string = trim($string); //Elimina espacios en blanco al inicio y al final
    $string = stripslashes($string); // Elimina las \ invertidas
    $string = str_ireplace("<script>", "", $string);
    $string = str_ireplace("</script>", "", $string);
    $string = str_ireplace("<script src>", "", $string);
    $string = str_ireplace("<script type=>", "", $string);
    $string = str_ireplace("SELECT * FROM", "", $string);
    $string = str_ireplace("DELETE FROM", "", $string);
    $string = str_ireplace("INSERT INTO", "", $string);
    $string = str_ireplace("SELECT COUNT(*) FROM", "", $string);
    $string = str_ireplace("DROP TABLE", "", $string);
    $string = str_ireplace("OR '1'='1", "", $string);
    $string = str_ireplace('OR "1"="1"', "", $string);
    $string = str_ireplace('OR ´1´=´1´', "", $string);
    $string = str_ireplace("is NULL; --", "", $string);
    $string = str_ireplace("is NULL; --", "", $string);
    $string = str_ireplace("LIKE '", "", $string);
    $string = str_ireplace('LIKE "', "", $string);
    $string = str_ireplace("LIKE ´", "", $string);
    $string = str_ireplace("OR 'a'='a", "", $string);
    $string = str_ireplace('OR "a"="a', "", $string);
    $string = str_ireplace("OR ´a´=´a", "", $string);
    $string = str_ireplace("OR ´a´=´a", "", $string);
    $string = str_ireplace("--", "", $string);
    $string = str_ireplace("^", "", $string);
    $string = str_ireplace("[", "", $string);
    $string = str_ireplace("]", "", $string);
    $string = str_ireplace("==", "", $string);
    return $string;
}


function strComillas($cadena)
{
    $string = str_replace(['"', '\\'], '', $cadena);
    return $string;
}

function sessionUser(int $id_user)
{
    require_once("Models/LoginModel.php");
    $objLogin = new LoginModel(); // Asegúrate de que este nombre de clase sea correcto
    $request = $objLogin->sessionLogin($id_user); 
    return $request;
}

function sessionStart()
{
    session_start(); // Inicia la sesión

    $inactive = 1800; // media hora

    // Si existe 'timeout' en la sesión, verifica la inactividad
    if (isset($_SESSION['timeout'])) {
        // Calcula el tiempo de inactividad
        $session_in = time() - $_SESSION['timeout'];

        // Si el tiempo de inactividad excede el límite permitido
        if ($session_in > $inactive) {
            session_unset();    // Elimina todas las variables de sesión
            session_destroy();  // Destruye la sesión actual
            header("Location: " . BASE_URL . "/login"); // Redirige al login o logout
            exit(); // Finaliza el script después de la redirección
        }
    }

    // Actualiza el tiempo de la última actividad
    $_SESSION['timeout'] = time(); 
}


function Meses()
{
    $meses = array(
        "Enero",
        "Febrero",
        "Marzo",
        "Abril",
        "Mayo",
        "Junio",
        "Julio",
        "Agosto",
        "Septiembre",
        "Octubre",
        "Noviembre",
        "Diciembre"
    );
    return $meses;
}

function getModulos()
{
    require_once("Models/ModulosModel.php");
    $model = new ModulosModel();

    $modulos = $model->SelectModelos(); 
    return $modulos;
}


