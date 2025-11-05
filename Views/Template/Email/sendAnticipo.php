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
    $mail->Subject = '💰 Notificación de Solicitud de Anticipo';

    // Datos recibidos (simulados por ahora)
    $solicitud = $arrData['solicitud'];
    $contraseña = $solicitud['contraseña'];
    $realizador = $solicitud['usuario'];
    $area = $solicitud['area'];
    $proveedor = $solicitud['proveedor'];
    $monto = $solicitud['monto_total'];
    $valor_letras = "Doce mil cuatrocientos cincuenta quetzales exactos";
    $fecha_registro = $solicitud['fecha_creacion'];
    $estado = $solicitud['estado'];

    switch ($estado) {
        case 'Corregir':
            $mensaje_estado = "El anticipo ha sido mandada a corregir.";
            break;
        case 'Pagado':
            $mensaje_estado = "El anticipo ha sido pagado.";
            break;
        default:
            $mensaje_estado = "se encuentra en estado desconocido.";
            break;
    }

    // Cuerpo del correo (diseño limpio compatible con Outlook)
    $body = "
    <!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
    </head>
    <body style='margin:0;padding:0;background-color:#fffbea;font-family:Segoe UI,Tahoma,Geneva,Verdana,sans-serif;color:#333;'>
        <table width='100%' cellspacing='0' cellpadding='0' border='0'>
            <tr>
                <td align='center'>
                    <table width='600' cellspacing='0' cellpadding='0' border='0' style='background-color:#ffffff;border-radius:6px;overflow:hidden;'>
                        <tr>
                            <td align='center' style='background-color:#FFD700;color:#000;padding:25px 20px;'>
                                <h1 style='margin:0;font-size:24px;font-weight:700;'>Nacel de CentroAmerica S.A.</h1>
                                <p style='margin:5px 0 0;font-size:14px;'>Sistema de Solicitudes de Fondos</p>
                            </td>
                        </tr>
                        <tr>
                            <td style='padding:30px 35px;'>
                                <h2 style='color:#DAA520;font-size:20px;margin-bottom:8px;'>Solicitud de Anticipo N° $contraseña</h2>
                                <p style='font-size:15px;line-height:1.6;'>$mensaje_estado</p>

                                <table width='100%' cellspacing='0' cellpadding='0' border='0'
                                style='background-color:#fff8dc;border-left:5px solid #FFD700;border-radius:4px;margin:15px 0;'>
                                    <tr>
                                        <td style='padding:4px 10px;font-size:14px;line-height:1.2;'>
                                            <b>📅 Fecha de Registro:</b> $fecha_registro
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style='padding:4px 10px;font-size:14px;line-height:1.2;'>
                                            <b>👤 Solicitante:</b> $realizador
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
                                            <b>💰 Monto Total:</b> Q$monto
                                        </td>
                                    </tr>
                                </table>
                                <p style='margin-top:18px;'>Para consultar más detalles o dar seguimiento, puede acceder al sistema haciendo clic en el siguiente botón:</p>
                                <p align='center' style='margin:20px 0;'>
                                    <a href='#' style='display:inline-block;background-color:#FFD700;color:#000;text-decoration:none;font-weight:bold;padding:12px 24px;border-radius:6px;'>Ver Solicitud en el Sistema</a>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td align='center' style='background-color:#fff8dc;padding:15px;font-size:12px;color:#666;border-top:1px solid #eee;'>
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

    echo '✅ Correo de anticipo enviado correctamente.';
} catch (Exception $e) {
    echo "❌ Error al enviar el correo: {$mail->ErrorInfo}";
}
