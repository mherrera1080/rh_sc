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
    $mail->Subject = '💰 Notificación de Solicitud de Anticipo';

    // Datos recibidos (simulados por ahora)
    $anticipo = $arrData['anticipo'];
    $contraseña = $anticipo['contraseña'];
    $realizador = $anticipo['realizador'];
    $area = $anticipo['area'];
    $proveedor = $anticipo['proveedor'];
    $monto = $anticipo['total'];
    $fecha_registro = $anticipo['fecha_registro'];
    $estado = $anticipo['solicitud_estado'];

    if ($area == "Vehiculos") {
        $caso = "Combustible";
        switch ($estado) {
            case 'Pendiente':
                $mensaje_estado = "Se ha creado un solicitud de combustible.";
                break;
            case 'Corregir':
                $mensaje_estado = "El ha mandado a corregir la siguiente solicitud de combustible: ";
                break;
            case 'Corregido':
                $mensaje_estado = "El ha mandado a corregido la siguiente solicitud de combustible: ";
                break;
            case 'Pagado':
                $mensaje_estado = "La solicitud de combustible ha sido liquidada";
                break;
            case 'Descartado':
                $mensaje_estado = "La solicitud de combustible ha sido descartado.";
                break;
            default:
                $mensaje_estado = "Se encuentra en un estado desconocido.";
                break;
        }
    } else {
        $caso = "Anticipo";
        switch ($estado) {

            case 'Pendiente':
                $mensaje_estado = "Se ha creado un anticipo del área " . $area;
                break;
            case 'Pagado':
                $mensaje_estado = "El anticipo ha sido liquidado.";
                break;
            case 'Descartado':
                $mensaje_estado = "El anticipo ha sido descartado.";
                break;
            default:
                $mensaje_estado = "Se encuentra en un estado desconocido.";
                break;
        }
    }


    // Cuerpo del correo (diseño limpio y compacto)
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
                        <table width='520' cellspacing='0' cellpadding='0' border='0' style='background-color:#ffffff;border-radius:6px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.1);'>
                            <tr>
                                <td align='center' style='background-color:#FFD700;color:#000;padding:20px;'>
                                    <h1 style='margin:0;font-size:22px;font-weight:700;'>Nacel de CentroAmerica S.A.</h1>
                                    <p style='margin:4px 0 0;font-size:13px;'>Sistema de Solicitudes de Fondos</p>
                                </td>
                            </tr>
                            <tr>
                                <td style='padding:25px 30px;'>
                                    <h2 style='color:#DAA520;font-size:18px;margin-bottom:10px;'>Solicitud de $caso N° $contraseña</h2>
                                    <p style='font-size:14px;line-height:1.5;margin-bottom:15px;'>$mensaje_estado</p>

                                    <table width='100%' cellspacing='0' cellpadding='0' border='0'
                                    style='background-color:#fff8dc;border-left:4px solid #FFD700;border-radius:4px;margin:12px 0;'>
                                        <tr><td style='padding:6px 10px;font-size:13px;'><b>📅 Fecha de Registro:</b> $fecha_registro</td></tr>
                                        <tr><td style='padding:6px 10px;font-size:13px;'><b>👤 Solicitante:</b> $realizador</td></tr>
                                        <tr><td style='padding:6px 10px;font-size:13px;'><b>🏢 Área:</b> $area</td></tr>
                                        <tr><td style='padding:6px 10px;font-size:13px;'><b>🏭 Proveedor:</b> $proveedor</td></tr>
                                        <tr><td style='padding:6px 10px;font-size:13px;'><b>💰 Monto Total:</b> Q$monto</td></tr>
                                    </table>

                                    <div style='background-color:#fff8dc;border-radius:6px;padding:10px 12px;margin-top:12px;'>
                                        <p style='margin:0;font-size:13px;line-height:1.4;'>
                                            🔔 <b>Estado actual:</b> <span style='color:#DAA520;'>$estado</span><br>
                                            ⏰ <b>Última actualización:</b> " . date('d/m/Y H:i') . "
                                        </p>
                                    </div>

                                    <p style='margin-top:18px;font-size:13px;'>Para consultar más detalles o dar seguimiento, puede acceder al sistema haciendo clic en el siguiente botón:</p>
                                    <p align='center' style='margin:18px 0;'>
                                        <a href=". BASE_URL ." style='display:inline-block;background-color:#FFD700;color:#000;text-decoration:none;font-weight:bold;padding:10px 20px;border-radius:5px;font-size:14px;'>Ver Solicitud en el Sistema</a>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td align='center' style='background-color:#fff8dc;padding:12px;font-size:11px;color:#666;border-top:1px solid #eee;'>
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
