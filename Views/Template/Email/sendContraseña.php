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
    $mail->Subject = '🚀 noreply';

    $contraseña = $arrData['contraseña']['contraseña'];
    $realizador = $arrData['contraseña']['realizador'];
    $area = $arrData['contraseña']['area'];
    $proveedor = $arrData['contraseña']['proveedor'];
    $monto = $arrData['contraseña']['monto_total'];
    $fecha_registro = $arrData['contraseña']['fecha_registro'];
    $estado = $arrData['contraseña']['estado'];

    switch ($estado) {
        case 'Validado':
            $mensaje_estado = "ha sido validada para solicitud de fondos";
            break;
        case 'Corregir':
            $mensaje_estado = "ha sido mandanda a corregir";
            break;
        case 'Corregido':
            $mensaje_estado = "ha sido corregida";
            break;
        case 'Pendiente Contabilidad':
            $mensaje_estado = "ha sido solicitado para una solicitud de fondos";
            break;
        case 'Pendiente':
            $mensaje_estado = "se ha mandado a corregir";
            break;
        case 'Descartado':
            $mensaje_estado = "ha sido descartada";
            break;
        default:
            # code...
            break;
    }

    $body = "
    <!DOCTYPE html>
    <html lang='es'>
    <head>
    <meta charset='UTF-8'>
    </head>
    <body style='margin:0;padding:0;background-color:#f5f7fa;font-family:Segoe UI,Tahoma,Geneva,Verdana,sans-serif;color:#333;'>
        <table width='100%' cellspacing='0' cellpadding='0' border='0'>
            <tr>
                <td align='center'>
                    <table width='600' cellspacing='0' cellpadding='0' border='0' style='background-color:#ffffff;border-radius:6px;overflow:hidden;'>
                        <tr>
                            <td align='center' style='background-color:#004aad;color:#fff;padding:25px 20px;'>
                                <h1 style='margin:0;font-size:24px;font-weight:700;'>Nacel de CentroAmerica S.A.</h1>
                                <p style='margin:5px 0 0;font-size:14px;'>Sistema de Contraseñas</p>
                            </td>
                        </tr>
                        <tr>
                            <td style='padding:30px 35px;'>
                                <h2 style='color:#004aad;font-size:20px;margin-bottom:8px;'>Contraseña N° $contraseña</h2>
                                <p style='font-size:15px;line-height:1.6;'>Estimado usuario, la siguiente contraseña $mensaje_estado</p>
                                <table width='100%' cellspacing='0' cellpadding='0' border='0'
                                style='background-color:#f0f5ff;border-left:5px solid #004aad;border-radius:4px;margin:15px 0;'>
                                    <tr>
                                        <td style='padding:4px 10px;font-size:14px;line-height:1.2;'>
                                            <b>📅 Fecha de Registro:</b> $fecha_registro
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style='padding:4px 10px;font-size:14px;line-height:1.2;'>
                                            <b>🏢 Área:</b> $area
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style='padding:4px 10px;font-size:14px;line-height:1.2;'>
                                            <b>🏭 Proveedor:</b> $proveedor
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style='padding:4px 10px;font-size:14px;line-height:1.2;'>
                                            <b>💰 Monto Total:</b> Q. $monto
                                        </td>
                                    </tr>
                                </table>
                                <p>Para consultar más detalles o realizar seguimiento, puede acceder al sistema haciendo clic en el siguiente botón:</p>
                                <p align='center'>
                                    <a href='#' style='display:inline-block;background-color:#004aad;color:#fff;text-decoration:none;font-weight:bold;padding:12px 24px;border-radius:6px;'>Ver Detalle en el Sistema</a>
                                </p>
                                <p style='margin-top:25px;'>Saludos cordiales</p>
                            </td>
                        </tr>
                        <tr>
                            <td align='center' style='background-color:#fafafa;padding:15px;font-size:12px;color:#888;border-top:1px solid #eee;'>
                                © " . date('Y') . " Nacel de CentroAmerica S.A. | Este correo fue generado automáticamente.
                            </td>
                        </tr>
                    </table>
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
