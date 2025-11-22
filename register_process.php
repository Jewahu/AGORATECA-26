<?php
// Reportar errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'config.php';
require_once 'email_config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Asegúrate de que estas rutas sean correctas en tu servidor Hostinger
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $verify_password = $_POST['verify_password'];
    $allowed_domain = 'cetis26.edu.mx';

    try {
        // 1. Validaciones básicas
        if (empty($email) || empty($username) || empty($password) || empty($verify_password)) {
            throw new Exception('empty'); // Enviamos código de error
        }
        
        // Validar dominio
        if (substr($email, -strlen($allowed_domain)) !== $allowed_domain) {
            throw new Exception('email_invalid_domain');
        }
        
        // Validar contraseñas iguales
        if ($password !== $verify_password) {
            throw new Exception('password_mismatch');
        }
        
        // Validar longitud contraseña
        if (strlen($password) < 6) {
            throw new Exception('password_short');
        }

        // 2. Verificar si usuario existe
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
        $stmt->bind_param("ss", $email, $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Averiguar cuál de los dos ya existe para dar un mensaje preciso (opcional)
            throw new Exception('email_exists'); 
        }
        $stmt->close();

        // 3. Insertar usuario
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $verification_token = bin2hex(random_bytes(32));

        $stmt = $conn->prepare("INSERT INTO users (email, username, password, is_verified, verification_token) VALUES (?, ?, ?, 0, ?)");
        $stmt->bind_param("ssss", $email, $username, $hashed_password, $verification_token);
        
        if ($stmt->execute()) {
            $stmt->close();

            // 4. Enviar correo
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
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = 'Verifica tu cuenta en Ágorateca Escolar';
                $mail->Body = "Haz clic <a href='".BASE_URL."/verify.php?token=$verification_token'>aquí</a> para verificar tu cuenta.";
                
                $mail->send();
                
                // ÉXITO: Redirigir a pendiente
                header("Location: registration_pending.php?email=" . urlencode($email));
                exit();

            } catch (Exception $e) {
                // Si falla el correo, borramos el usuario para que pueda intentar de nuevo
                $conn->query("DELETE FROM users WHERE email = '$email'");
                throw new Exception('email_send_failed');
            }

        } else {
            throw new Exception('database');
        }
    } catch (Exception $e) {
        // AQUI ESTABA EL ERROR: Ahora pasamos el error por URL
        $error_code = $e->getMessage();
        // Si el mensaje no es uno de nuestros códigos cortos, lo enviamos como mensaje custom
        if (!in_array($error_code, ['empty', 'password_mismatch', 'password_short', 'email_invalid_domain', 'email_exists', 'database', 'email_send_failed'])) {
             // Es un error técnico inesperado (ej. error de SQL detallado)
             $url_msg = urlencode($error_code);
             header("Location: register.php?error=custom&msg=$url_msg");
        } else {
             header("Location: register.php?error=$error_code");
        }
        exit();
    }
}
?>