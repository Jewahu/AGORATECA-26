<?php
// Activar errores para ver si algo falla en lugar de pantalla blanca
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// IMPORTANTE: Cargar la configuración para que funcione BASE_URL
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifica tu Correo - Ágorateca Escolar</title>
    
    <!-- Ahora BASE_URL funcionará correctamente -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/style.css?v=20251121">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Roboto:wght@400&display=swap" rel="stylesheet">
</head>
<body>
    <div class="form-container">
        <h1>ÁGORATECA ESCOLAR</h1>
        <h2>Registro Exitoso</h2>
        
        <div class="message-box" style="margin: 20px 0;">
            <div style="color: #2e7d32; font-size: 3rem; margin-bottom: 15px;">
                <i class="fa-solid fa-envelope-circle-check"></i> <!-- Icono opcional si usas fontawesome -->
                ✉️
            </div>
            <p class="main-message" style="font-size: 1.1rem; font-weight: bold; color: #333; margin-bottom: 10px;">
                Hemos enviado un mensaje de confirmación a:
            </p>
            <p style="color: #601a1a; font-weight: bold; background: #f9f9f9; padding: 10px; border-radius: 5px; word-break: break-all;">
                <?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : 'tu correo electrónico'; ?>
            </p>
            <p class="sub-message" style="color: #666; margin-top: 15px; font-size: 0.9rem;">
                Por favor, verifica tu bandeja de entrada (o spam) y haz clic en el enlace para activar tu cuenta.
            </p>
        </div>

        <a href="login.php" class="submit-btn" style="text-decoration: none; display: block; margin-top: 20px; line-height: 20px;">
            Volver al inicio de sesión
        </a>
    </div>
</body>
</html>