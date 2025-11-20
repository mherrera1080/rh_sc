<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'Assets/vendor/autoload.php';
$mail = new PHPMailer(true);

try {
    // Configuración del servidor SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.hostinger.com';
    $mail->SMTPAuth = true;
    $mail->Username = CORREO;
    $mail->Password = PASSCORREO;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->CharSet = 'UTF-8';

    // Remitente
    $mail->setFrom('notificacionescontrasenagt@carsoca.com', 'noreply@');

    // Destinatario (Cámbialo por tu correo para probar)
    foreach ($arrData['correos'] as $correoItem) {
        // Si es array (getCorreosArea)
        if (is_array($correoItem) && isset($correoItem['correos'])) {
            $mail->addAddress($correoItem['correos']);
        } 
        // Si es array (getCorreobyName)  
        else if (is_array($correoItem) && isset($correoItem['correo'])) {
            $mail->addAddress($correoItem['correo']);
        }
        // Si es string directo
        else if (is_string($correoItem)) {
            $mail->addAddress($correoItem);
        }
    }


    // Contenido del correo
    $mail->isHTML(true);
    $mail->Subject = '🚀 Notificacion de Contraseñas';

    $contraseña = $arrData['contraseña']['contraseña'];
    $realizador = $arrData['contraseña']['realizador'];
    $area = $arrData['contraseña']['area'];
    $proveedor = $arrData['contraseña']['proveedor'];
    $monto_formato = $arrData['contraseña']['monto_formato'];
    $fecha_registro = $arrData['contraseña']['fecha_registro'];

    $mensaje_estado = "Se ha creado la siguiente contraseña";



    $body = "
        <!DOCTYPE html>
        <html lang='es'>
        <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Nacel de Centroamérica S.A.</title>
        </head>
        <body style='margin:0;padding:0;background-color:#f4f6f8;font-family:Segoe UI,Tahoma,Geneva,Verdana,sans-serif;color:#333;'>

        <table width='100%' cellspacing='0' cellpadding='0' border='0'>
            <tr>
            <td align='center' style='padding:40px 15px;'>

                <!-- Contenedor principal -->
                <table width='600' cellspacing='0' cellpadding='0' border='0' style='background-color:#ffffff;border-radius:10px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.05);'>

                <!-- Encabezado -->
                <tr>
                    <td align='center' style='background-color:#004aad;padding:25px 20px;color:#ffffff;'>
                    <h1 style='margin:0;font-size:22px;'>Nacel de Centroamérica S.A.</h1>
                    <p style='margin:4px 0 0;font-size:14px;opacity:0.9;'>Sistema de Contraseñas</p>
                    </td>
                </tr>

                <!-- Contenido -->
                <tr>
                    <td style='padding:35px 40px;'>

                    <h2 style='color:#004aad;font-size:20px;margin-bottom:12px;'>Contraseña N° $contraseña </h2>
                    <p style='font-size:15px;line-height:1.6;' >$mensaje_estado</p>

                    <table width='100%' cellpadding='8' cellspacing='0' border='0' style='background-color:#f8fbff;border-left:5px solid #004aad;border-radius:6px;margin:25px 0;'>
                        <tr><td><strong>📅 Fecha de Registro:</strong> $fecha_registro </td></tr>
                        <tr><td><strong>👤 Realizador:</strong>  $realizador</td></tr>
                        <tr><td><strong>🏢 Área:</strong>  $area </td></tr>
                        <tr><td><strong>🏭 Proveedor:</strong> $proveedor</td></tr>
                        <tr><td><strong>💰 Monto Total:</strong> Q. $monto_formato</td></tr>
                    </table>

                    <p style='margin-top:15px;font-size:15px;'>Para consultar más detalles o realizar seguimiento, puede acceder al sistema haciendo clic en el siguiente botón:</p>

                    <p align='center' style='margin:30px 0;'>
                        <a href='#' style='background-color:#004aad;color:#ffffff;text-decoration:none;font-weight:600;padding:12px 28px;border-radius:6px;display:inline-block;transition:background-color 0.2s;'>
                        Ver Detalle en el Sistema
                        </a>
                    </p>
                    </td>
                </tr>

                <!-- Pie -->
                <tr>
                    <td align='center' style='background-color:#f9fafb;padding:18px;font-size:12px;color:#777;border-top:1px solid #eaeaea;'>
                    © <?= date('Y') ?> Nacel de Centroamérica S.A. — Correo generado automáticamente.<br>
                    <span style='font-size:11px;'>No responda a este mensaje.</span>
                    </td>
                </tr>

                </table>
                <!-- Fin contenedor principal -->

            </td>
            </tr>
        </table>

        </body>
        </html>
        ";

    $mail->Body = $body;
    $mail->send();
    echo '✅ Correo enviado correctamente.';
} catch (Exception $e) {
    echo "❌ Error al enviar el correo: {$mail->ErrorInfo}";
}
