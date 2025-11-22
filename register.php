<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse - Ágorateca Escolar</title>
    <!-- Asegurar que BASE_URL esté correctamente configurado -->
    <?php 
    require_once 'config.php'; 
    ?>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/style.css?v=20251121">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Roboto:wght@400&display=swap" rel="stylesheet">
    <!-- Iconos de Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<!-- ... resto del head ... -->
<body>
    <div class="form-container">
        <h1>ÁGORATECA ESCOLAR</h1>
        <h2>Registrarse</h2>
        <?php
        // Mostrar errores enviados por register_process.php
        if (isset($_GET['error'])) {
            $error_message = '';
            switch ($_GET['error']) {
                case 'empty':
                    $error_message = 'Por favor, completa todos los campos.';
                    break;
                case 'password_mismatch':
                    $error_message = 'Las contraseñas no coinciden.';
                    break;
                case 'password_short':
                    $error_message = 'La contraseña debe tener al menos 6 caracteres.';
                    break;
                case 'email_invalid_domain':
                    $error_message = 'Solo se permiten correos con el dominio @cetis26.edu.mx';
                    break;
                case 'email_exists':
                    $error_message = 'Este correo o usuario ya está registrado.';
                    break;
                case 'database':
                    $error_message = 'Error de base de datos al intentar registrar.';
                    break;
                case 'email_send_failed':
                    $error_message = 'No se pudo enviar el correo. Verifica tu configuración SMTP.';
                    break;
                case 'custom':
                    // Para mostrar errores técnicos específicos si los hay
                    if (isset($_GET['msg'])) {
                        $error_message = 'Error: ' . htmlspecialchars($_GET['msg']);
                    }
                    break;
                default:
                    $error_message = 'Error desconocido.';
            }
            
            if ($error_message) {
                echo '<div class="error-message" style="background-color: #ffebee; color: #c62828; padding: 10px; border-radius: 5px; margin-bottom: 15px;">' . $error_message . '</div>';
            }
        }
        ?>
        <form action="register_process.php" method="POST">
            <div class="input-group">
                <input type="email" id="email" name="email" placeholder="Correo electrónico" required>
            </div>
            <div class="input-group">
                <input type="text" id="username" name="username" placeholder="Nombre de usuario" required>
            </div>
            <div class="input-group password-wrapper">
                <input type="password" id="password" name="password" placeholder="Contraseña" required>
                <i class="fas fa-eye toggle-password"></i>
            </div>
            <div class="input-group password-wrapper">
                <input type="password" id="verify_password" name="verify_password" placeholder="Verificar contraseña" required>
                <i class="fas fa-eye toggle-password"></i>
            </div>
            <button type="submit" class="submit-btn">Registrarse</button>
        </form>
        <div class="footer-link">
            <p>¿Ya tienes una cuenta? <a href="login.php">Iniciar sesión</a></p>
        </div>
    </div>

    <script>
    // Lógica del input para mostrar y ocultar contraseñas
    document.addEventListener('DOMContentLoaded', function() {
        const togglePasswordIcons = document.querySelectorAll('.toggle-password');
        togglePasswordIcons.forEach(icon => {
            icon.addEventListener('click', function() {
                const targetId = this.previousElementSibling.getAttribute('id');
                const passwordInput = document.querySelector(`#${targetId}`);
                if (passwordInput.type === "password") {
                    passwordInput.type = "text";
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                } else {
                    passwordInput.type = "password";
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                }
            });
        });
    });
    </script>
</body>
</html>