<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'Assets/vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.hostinger.com';
    $mail->SMTPAuth = true;
    $mail->Username = CORREO;
    $mail->Password = PASSCORREO;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom(CORREO, 'Sistema de Autenticacion');
    $mail->addAddress($arrData['correo_empresarial']);

    $mail->isHTML(true);
    $mail->Subject = 'Codigo de Verificacion';
    $mail->Body = "
        <h3>Hola {$arrData['nombres']},</h3>
        <p>Tu codigo de verificacion es:</p>
        <h2>{$arrData['token']}</h2>
        <p>Este codigo expirara en 5 minutos.</p>
    ";

    $mail->send();
} catch (Exception $e) {
    error_log("Error al enviar correo: {$mail->ErrorInfo}");
}
