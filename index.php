<?php
session_start();
require_once 'config.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    // Redirigir al login si no está autenticado
    header("Location: login.php");
    exit();
}

// Establecer zona horaria a México Centro
date_default_timezone_set('America/Mexico_City');

// Información de fecha y hora actual
$current_date = date('d / m / Y');
$current_time = date('h:i:s a');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ágorateca Escolar</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/style.css?v=20251121">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-wrapper">
        <header class="dashboard-header">
            <div class="header-logo">
                <h1>ÁGORATECA ESCOLAR</h1>
            </div>
            <div class="header-usermenu">
                <a href="logout.php" title="Cerrar sesión">
                    <i class="fa-solid fa-user-circle user-icon"></i>
                </a>
            </div>
        </header>

        <nav class="dashboard-nav">
            <div class="nav-tabs">
                <button class="tab-button active" data-tab="create-suggestion">Crear nueva sugerencia</button>
            </div>
            <div class="nav-datetime">
                <span>Fecha: <span id="current-date"><?php echo $current_date; ?></span></span>
                <span>Hora: <span id="current-time"><?php echo $current_time; ?></span></span>
            </div>
            <div class="nav-tabs">
                <button class="tab-button" data-tab="my-suggestions">Mis sugerencias</button>
            </div>
        </nav>

        <main class="dashboard-main">
            <!-- Pestaña 1: Crear Sugerencia -->
            <div id="create-suggestion" class="tab-content active">
                <form action="submit_suggestion.php" method="POST" class="suggestion-form">
                    <div class="form-left">
                        <div class="form-group">
                            <label for="title">Título:</label>
                            <input type="text" id="title" name="title" required>
                        </div>
                        <div class="form-group-full">
                            <label for="description">Descripción:</label>
                            <textarea id="description" name="description" rows="10" required></textarea>
                        </div>
                    </div>
                    <div class="form-right">
                        <div class="form-group radio-group">
                            <label>Selecciona una opción:</label>
                            <div class="radio-options">
                                <label><input type="radio" name="type" value="Queja" required> Queja</label>
                                <label><input type="radio" name="type" value="Sugerencia" checked> Sugerencia</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="building">Edificio involucrado:</label>
                            <div class="select-wrapper">
                                <select id="building" name="building" required>
                                    <option value="Administrativo">Edificio Administrativo</option>
                                    <option value="A">Edificio A</option>
                                    <option value="B">Edificio B</option>
                                    <option value="C">Edificio C</option>
                                    <option value="D">Edificio D</option>
                                    <option value="E">Edificio E</option>
                                    <option value="F">Edificio F</option>
                                    <option value="G">Edificio G</option>
                                    <option value="H">Edificio H</option>
                                    <option value="P">Edificio P</option>
                                    <option value="L">Edificio L</option>
                                    <option value="M">Edificio M</option>
                                    <option value="N" selected>Edificio N</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="category">Área o Categoría:</label>
                            <div class="select-wrapper">
                                <select id="category" name="category" required>
                                    <option value="Servicios Escolares" selected>Servicios Escolares</option>
                                    <option value="Instalaciones">Instalaciones</option>
                                    <option value="Profesores">Profesores</option>
                                    <option value="Administrativo">Administrativo</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>
                        </div>
                        <p class="form-disclaimer">Recuerda comentar con responsabilidad, los administradores tienen acceso a tu correo y usuario para un mejor control de la situación.</p>
                        <button type="submit" class="submit-button">Enviar</button>
                    </div>
                </form>
            </div>

            <!-- Pestaña 2: Mis Sugerencias -->
            <div id="my-suggestions" class="tab-content">
                <h2>Mis Quejas y Sugerencias</h2>
                <p>Aquí se mostrará una lista de las sugerencias que has enviado.</p>
                <!-- Aquí se cargará dinámicamente el contenido con PHP/JS en el futuro -->
            </div>
        </main>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Lógica para el reloj
        const timeElement = document.getElementById('current-time');
        setInterval(() => {
            const now = new Date();
            timeElement.textContent = now.toLocaleTimeString('en-US', { hour12: true });
        }, 1000);

        // Lógica para las pestañas
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabContents = document.querySelectorAll('.tab-content');

        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                const targetTab = button.getAttribute('data-tab');

                // Desactivar todos
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabContents.forEach(content => content.classList.remove('active'));

                // Activar el correspondiente
                button.classList.add('active');
                document.getElementById(targetTab).classList.add('active');
            });
        });
    });
    </script>
    </script>
</body>
</html>