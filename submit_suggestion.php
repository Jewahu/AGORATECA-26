<?php
session_start();
require_once 'config.php';

// Verificar que el usuario esté logueado
if (!isset($_SESSION['user_id'])) {
    // Si no está logueado, redirigir al login
    header("Location: login.php");
    exit();
}

// Verificar que se haya enviado el formulario por POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger los datos del formulario
    $user_id = $_SESSION['user_id'];
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $type = $_POST['type']; // 'Queja' o 'Sugerencia'
    $building = $_POST['building'];
    $category = $_POST['category'];

    // Validación simple de que los campos no estén vacíos
    if (empty($title) || empty($description) || empty($type) || empty($building) || empty($category)) {
        // Idealmente, aquí se manejaría un mensaje de error más específico
        header("Location: index.php?error=empty_fields");
        exit();
    }

    // Preparar la consulta para insertar los datos de forma segura
    $stmt = $conn->prepare(
        "INSERT INTO suggestions (user_id, title, description, type, building, category) VALUES (?, ?, ?, ?, ?, ?)"
    );
    
    // Vincular los parámetros a la consulta
    $stmt->bind_param("isssss", $user_id, $title, $description, $type, $building, $category);
    
    // Ejecutar la consulta
    $success = $stmt->execute();
    
    // Cerrar el statement antes de redirigir
    $stmt->close();
    $conn->close();

    if ($success) {
        // Si fue exitoso, redirigir al index con un mensaje de éxito
        header("Location: index.php?success=suggestion_sent");
        exit();
    } else {
        // Si hubo un error, redirigir con un mensaje de error
        header("Location: index.php?error=database_error");
        exit();
    }
} else {
    // Si alguien intenta acceder a este archivo directamente, lo redirigimos
    $conn->close();
    header("Location: index.php");
    exit();
}
?>