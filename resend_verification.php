<?php
require_once 'config.php';
require_once 'email_config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Ajusta las rutas si es necesario según tu estructura de carpetas
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$message = '';
$success = false;

if (isset($_GET['email'])) {
    $email = trim($_GET['email']);
    
    // Verificar si el usuario existe
    $stmt = $conn->prepare("SELECT id, username, is_verified, verification_token FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        if ($user['is_verified'] == 1) {
            $message = 'Esta cuenta ya ha sido verificada anteriormente. <a href="login.php">Inicia sesión aquí</a>.';
        } else {
            // Generar nuevo token si no existe, o usar el existente
            $verification_token = $user['verification_token'];
            if (empty($verification_token)) {
                $verification_token = bin2hex(random_bytes(32));
                $stmt_update = $conn->prepare("UPDATE users SET verification_token = ? WHERE id = ?");
                $stmt_update->bind_param("si", $verification_token, $user['id']);
                $stmt_update->execute();
                $stmt_update->close();
            }
            
            // --- CORRECCIÓN DEL LINK ---
            // Usamos HTTPS por defecto y BASE_URL definida en config.php
            $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https://" : "http://";
            // Forzamos https si estás en producción para evitar problemas
            if (strpos($_SERVER['HTTP_HOST'], 'localhost') === false) {
                $protocol = "https://";
            }
            
            $verification_link = $protocol . $_SERVER['HTTP_HOST'] . BASE_URL . "/verify.php?token=" . $verification_token;
            $username = $user['username'];
            
            $mail = new PHPMailer(true);
            
            try {
                $mail->isSMTP();
                $mail->Host       = SMTP_HOST;
                $mail->SMTPAuth   = true;
                $mail->Username   = SMTP_USERNAME;
                $mail->Password   = SMTP_PASSWORD;
                $mail->SMTPSecure = SMTP_SECURE;
                $mail->Port       = SMTP_PORT;
                $mail->CharSet    = 'UTF-8';
                
                $mail->setFrom(MAIL_FROM_EMAIL, MAIL_FROM_NAME);
                $mail->addAddress($email, $username);
                
                $mail->isHTML(true);
                $mail->Subject = 'Reenvío de verificación - Ágorateca Escolar';
                
                // --- DISEÑO CORREGIDO (Estilos en línea) ---
                $mail->Body = "
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset='UTF-8'>
                </head>
                <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f4f4f4; margin: 0; padding: 20px;'>
                    <div style='max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.1);'>
                        
                        <!-- Encabezado -->
                        <div style='background-color: #601a1a; color: white; padding: 30px 20px; text-align: center;'>
                            <h1 style='margin: 0; font-size: 24px; font-family: \"Times New Roman\", serif;'>ÁGORATECA ESCOLAR</h1>
                        </div>
                        
                        <!-- Contenido -->
                        <div style='padding: 40px 30px; text-align: center;'>
                            <h2 style='color: #333; margin-top: 0;'>Verificación de cuenta</h2>
                            <p style='font-size: 16px; color: #555;'>Hola, <strong>$username</strong></p>
                            <p style='font-size: 16px; color: #555;'>Has solicitado un nuevo enlace de verificación.</p>
                            <p style='font-size: 16px; color: #555;'>Para activar tu cuenta y acceder a la plataforma, haz clic en el botón:</p>
                            
                            <div style='margin: 30px 0;'>
                                <a href='$verification_link' style='display: inline-block; padding: 15px 30px; background-color: #a92a2a; color: white; text-decoration: none; border-radius: 50px; font-weight: bold; font-size: 16px;'>Activar mi cuenta</a>
                            </div>
                            
                            <p style='font-size: 14px; color: #777; margin-top: 30px;'>O copia y pega este enlace en tu navegador:</p>
                            <p style='font-size: 12px; color: #999; word-break: break-all; background-color: #f9f9f9; padding: 10px; border-radius: 4px;'>$verification_link</p>
                            <p style='font-size: 14px; color: #a92a2a; font-weight: bold;'>Este enlace expirará en 24 horas.</p>
                        </div>
                        
                        <!-- Pie de página -->
                        <div style='background-color: #f0f0f0; padding: 20px; text-align: center; font-size: 12px; color: #888;'>
                            <p style='margin: 0;'>&copy; 2025 Ágorateca Escolar. Todos los derechos reservados.</p>
                        </div>
                    </div>
                </body>
                </html>
                ";
                
                $mail->AltBody = "Hola $username.\n\nUsa este enlace para verificar tu cuenta:\n$verification_link";
                
                $mail->send();
                $success = true;
                $message = '¡Correo reenviado con éxito! Por favor revisa tu bandeja de entrada.';
                
            } catch (Exception $e) {
                // En producción, puedes ocultar $mail->ErrorInfo
                $message = 'Error al enviar el correo: ' . $mail->ErrorInfo;
            }
        }
    } else {
        $message = 'No encontramos ninguna cuenta registrada con ese correo electrónico.';
    }
    
    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reenviar Verificación - Ágorateca Escolar</title>
    <!-- Usamos BASE_URL para el estilo -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/style.css?v=20251121">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Roboto:wght@400&display=swap" rel="stylesheet">
    <style>
        .resend-container {
            text-align: center;
            padding: 40px;
            max-width: 500px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-top: 50px;
        }
        .message-box {
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            font-weight: 500;
        }
        .success-box {
            background-color: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #c8e6c9;
        }
        .error-box {
            background-color: #ffebee;
            color: #c62828;
            border: 1px solid #ffcdd2;
        }
        .action-btn {
            display: inline-block;
            padding: 12px 30px;
            background-color: #a92a2a;
            color: white;
            text-decoration: none;
            border-radius: 25px;
            margin-top: 20px;
            font-weight: bold;
            transition: background 0.3s;
        }
        .action-btn:hover {
            background-color: #8c2323;
        }
    </style>
</head>
<body>
    <div class="resend-container">
        <h1 style="font-family: 'Playfair Display', serif; color: #601a1a; margin-bottom: 10px;">ÁGORATECA ESCOLAR</h1>
        <h2 style="font-size: 1.2rem; color: #555; margin-bottom: 30px;">Reenviar enlace de verificación</h2>
        
        <?php if (!empty($message)): ?>
            <div class="message-box <?php echo $success ? 'success-box' : 'error-box'; ?>">
                <?php echo $message; ?> <!-- Permitimos HTML para el link de login -->
            </div>
        <?php endif; ?>
        
        <?php if (!$success): ?>
            <form method="GET" action="">
                <div class="input-group" style="margin-bottom: 20px;">
                    <input type="email" name="email" placeholder="Ingresa tu correo electrónico" required 
                           style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px;">
                </div>
                <button type="submit" class="submit-btn">Reenviar correo</button>
            </form>
            <div style="margin-top: 20px;">
                <a href="login.php" style="color: #666; text-decoration: none; font-size: 0.9rem;">Volver al inicio de sesión</a>
            </div>
        <?php else: ?>
            <a href="login.php" class="action-btn">Ir al inicio de sesión</a>
        <?php endif; ?>
    </div>
</body>
</html>