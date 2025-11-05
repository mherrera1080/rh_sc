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
    foreach ($arrData['correos'] as $correo) {
        $mail->addAddress($correo);
    }


    // Contenido del correo
    $mail->isHTML(true);
    $mail->Subject = '💰 Notificación de Solicitud de Fondos';

    // Datos recibidos
    $solicitud = $arrData['solicitud'];
    $numero_solicitud = $solicitud['id_solicitud'];
    $realizador = $solicitud['realizador'];
    $area = $solicitud['area'];
    $proveedor = $solicitud['proveedor'];
    $monto = $solicitud['total_calc'];
    $fecha_registro = $solicitud['fecha_registro'];
    $estado = $arrData['estado'];

    // Mensaje según el estado
    switch ($estado) {
        case 'Validado':
            $mensaje_estado = "ha sido validada.";
            break;
        case 'Pagado':
            $mensaje_estado = "ha sido pagada.";
            break;
        case 'Corregir':
            $mensaje_estado = "requiere corrección por parte del solicitante";
            break;
        case 'Pendiente Contabilidad':
            $mensaje_estado = "está pendiente de revisión por Contabilidad";
            break;
        case 'Pendiente':
            $mensaje_estado = "está pendiente de autorización";
            break;
        case 'Aprobado':
            $mensaje_estado = "ha sido aprobada correctamente";
            break;
        case 'Descartado':
            $mensaje_estado = "ha sido descartada";
            break;
        default:
            break;
    }

    // Cuerpo del correo
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
                        <td align='center' style='background-color:#2E7D32;color:#fff;padding:25px 20px;'>
                            <h1 style='margin:0;font-size:24px;font-weight:700;'>Nacel de CentroAmerica S.A.</h1>
                            <p style='margin:5px 0 0;font-size:14px;'>Sistema de Contraseñas</p>
                        </td>
                    </tr>
                    <tr>
                        <td style='padding:30px 35px;'>
                            <h2 style='color:#2E7D32;font-size:20px;margin-bottom:8px;'>Contraseña N° $contraseña</h2>
                            <p style='font-size:15px;line-height:1.6;'>Estimado usuario, la siguiente solicitud de fondos $mensaje_estado</p>

                            <table width='100%' cellspacing='0' cellpadding='0' border='0'
                            style='background-color:#E8F5E9;border-left:5px solid #2E7D32;border-radius:4px;margin:15px 0;'>
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
                                <a href='#' style='display:inline-block;background-color:#2E7D32;color:#fff;text-decoration:none;font-weight:bold;padding:12px 24px;border-radius:6px;'>Ver Detalle en el Sistema</a>
                            </p>

                            <p style='margin-top:25px;'>Saludos cordiales</p>
                        </td>
                    </tr>
                    <tr>
                        <td align='center' style='background-color:#f0f0f0;padding:15px;font-size:12px;color:#666;border-top:1px solid #ddd;'>
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

    // Enviar correo
    $mail->send();

    echo '✅ Correo de solicitud de fondos enviado correctamente.';
} catch (Exception $e) {
    echo "❌ Error al enviar el correo: {$mail->ErrorInfo}";
}
