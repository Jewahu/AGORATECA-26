<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']); // Checkbox de 'Recordar contraseña'

    if (empty($email) || empty($password)) {
        header("Location: login.php?error=empty");
        exit();
    }

    $stmt = $conn->prepare("SELECT id, username, email, password, is_verified FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            if ($user['is_verified'] == 1) {
                // Iniciar la sesión principal
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                
                // --- NUEVA LÓGICA PARA 'MANTENER SESIÓN' ---
                if ($remember) {
                    // Generar un token seguro y único
                    $token = bin2hex(random_bytes(32));
                    
                    // Guardar el token en la base de datos para este usuario
                    $update_stmt = $conn->prepare("UPDATE users SET remember_token = ? WHERE id = ?");
                    $update_stmt->bind_param("si", $token, $user['id']);
                    $update_stmt->execute();
                    $update_stmt->close();
                    
                    // Enviar la cookie al navegador con una duración larga (ej. 30 días)
                    setcookie('remember_me', $token, time() + (30 * 24 * 60 * 60), "/");
                }
                
                header("Location: index.php");
                exit();
            } else {
                header("Location: login.php?error=not_verified");
                exit();
            }
        } else {
            header("Location: login.php?error=invalid");
            exit();
        }
    } else {
        header("Location: login.php?error=notfound");
        exit();
    }
}
$conn->close();
?>