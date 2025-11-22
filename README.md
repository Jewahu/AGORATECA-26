# ÁGORATECA ESCOLAR

Sistema web de quejas y sugerencias escolares desarrollado en PHP.

## 📋 Descripción

ÁGORATECA ESCOLAR es una plataforma web que permite a los usuarios del ámbito escolar registrar quejas y sugerencias sobre diferentes aspectos de la institución educativa. El sistema incluye funcionalidades de registro de usuarios, verificación por correo electrónico, y gestión de sugerencias organizadas por edificio y categoría.

## ✨ Características

- **Sistema de autenticación de usuarios**
  - Registro con verificación por correo electrónico
  - Inicio de sesión con "Recordarme"
  - Recuperación de contraseña
  
- **Gestión de quejas y sugerencias**
  - Creación de quejas o sugerencias
  - Clasificación por edificio (A-N, P, L, M, Administrativo)
  - Categorías: Servicios Escolares, Instalaciones, Profesores, Administrativo, Otro
  
- **Interfaz de usuario intuitiva**
  - Diseño responsive
  - Dashboard para gestión de sugerencias
  - Visualización de fecha y hora en tiempo real

## 🛠️ Requisitos del Sistema

- PHP 7.4 o superior
- MySQL 5.7 o superior
- Servidor web (Apache/Nginx)
- Extensiones PHP requeridas:
  - mysqli
  - mbstring
  - openssl (para PHPMailer)

## 📦 Instalación

1. **Clonar el repositorio**
   ```bash
   git clone https://github.com/Jewahu/AGORATECA-26.git
   cd AGORATECA-26
   ```

2. **Configurar la base de datos**
   - Crear una base de datos MySQL
   - Importar el archivo `database.sql`:
     ```bash
     mysql -u usuario -p nombre_base_datos < database.sql
     ```

3. **Configurar los archivos de configuración**
   
   Copiar y editar `config.php.example` (si existe) o crear `config.php`:
   ```php
   <?php
   // Configuración de base de datos
   define('DB_HOST', 'localhost');
   define('DB_USER', 'tu_usuario');
   define('DB_PASS', 'tu_contraseña');
   define('DB_NAME', 'u339208770_Agorateca');
   define('BASE_URL', 'http://tu-dominio.com');
   ?>
   ```
   
   Configurar `email_config.php` para el envío de correos electrónicos con tus credenciales SMTP.

4. **Configurar permisos**
   ```bash
   chmod 755 *.php
   chmod 755 PHPMailer
   ```

5. **Acceder a la aplicación**
   - Abrir en navegador: `http://tu-dominio.com/`
   - Crear una cuenta de usuario
   - Verificar el correo electrónico
   - Iniciar sesión

## 📁 Estructura del Proyecto

```
.
├── PHPMailer/              # Librería para envío de correos
├── config.php              # Configuración de base de datos
├── database.sql            # Esquema de base de datos
├── email_config.php        # Configuración de correo electrónico
├── index.php               # Dashboard principal
├── login.php               # Página de inicio de sesión
├── login_process.php       # Procesamiento de login
├── logout.php              # Cierre de sesión
├── register.php            # Página de registro
├── register_process.php    # Procesamiento de registro
├── registration_pending.php # Página de confirmación de registro
├── resend_verification.php # Reenvío de correo de verificación
├── style.css               # Estilos CSS
├── submit_suggestion.php   # Procesamiento de sugerencias
└── verify.php              # Verificación de correo electrónico
```

## 🗄️ Esquema de Base de Datos

### Tabla `users`
- Almacena información de usuarios registrados
- Campos: id, email, username, password, is_verified, verification_token, remember_token, timestamps

### Tabla `suggestions`
- Almacena quejas y sugerencias
- Campos: id, user_id, title, description, type, building, category, created_at
- Relación con tabla users mediante foreign key

## 🔒 Seguridad

- Contraseñas hasheadas con bcrypt
- Protección contra inyección SQL mediante prepared statements
- Validación de sesiones de usuario
- Tokens de verificación únicos para registro
- Configuración HTTPS recomendada en producción

## 🌐 Uso

1. **Registro de usuario**: Crear cuenta con email institucional
2. **Verificación**: Confirmar cuenta mediante enlace enviado por correo
3. **Inicio de sesión**: Acceder con credenciales
4. **Crear sugerencia**: Completar formulario con título, descripción, tipo, edificio y categoría
5. **Gestión**: Ver y administrar sugerencias enviadas

## 📝 Notas de Desarrollo

- Zona horaria configurada para México Centro (`America/Mexico_City`)
- Sistema desarrollado en español
- Base de datos configurada con charset utf8mb4 para soporte de caracteres especiales

## 🤝 Contribución

Este es un proyecto escolar. Para contribuir:
1. Fork el proyecto
2. Crear una rama para tu función (`git checkout -b feature/NuevaFuncion`)
3. Commit tus cambios (`git commit -m 'Agregar nueva función'`)
4. Push a la rama (`git push origin feature/NuevaFuncion`)
5. Abrir un Pull Request

## 📄 Licencia

Proyecto escolar - AGORATECA-26

## 👥 Autores

Proyecto desarrollado por estudiantes como parte del programa AGORATECA-26.

## 📞 Soporte

Para preguntas o soporte, por favor abrir un issue en el repositorio de GitHub.
