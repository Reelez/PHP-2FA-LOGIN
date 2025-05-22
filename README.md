   # Sistema de Autenticación con Verificación en Dos Pasos (2FA) en PHP

   Este proyecto es una aplicación web de autenticación segura que implementa inicio de sesión con verificación en dos pasos (2FA) usando Google Authenticator, desarrollada con PHP y MySQL.

   ## Características

   - Registro de nuevos usuarios
   - Inicio de sesión con nombre de usuario y contraseña
   - Verificación en dos pasos (2FA) usando Google Authenticator
   - Redirección al panel de control solo después de autenticación exitosa
   - Diseño limpio y moderno con CSS personalizado

   ## Requisitos

   - PHP 8.x o superior
   - MySQL/MariaDB
   - Servidor Apache (WAMP, XAMPP, etc.)
   - Composer

   ## Instalación

   1. Clona el repositorio:
      ```bash
      git clone https://github.com/tuusuario/sistema-login-2fa.git
      ```

   2. Instala las dependencias con Composer:
      ```bash
      composer install
      ```

   3. Configura tu base de datos MySQL y ejecuta el script para crear la tabla de usuarios.

   4. Asegúrate de tener configurado correctamente el acceso a la base de datos en `clases/mysql.inc.php`.

   ## Uso

   1. Accede a `registro.php` para registrar un nuevo usuario.
   2. Luego de registrarte, escanea el código QR con Google Authenticator.
   3. Inicia sesión en `login.php`, luego valida el código 2FA para acceder al Panel de Control.

   ## Capturas de Pantalla

   

   ## Estructura del Proyecto

   ```
   ├── clases/
   │   └── mysql.inc.php
   ├── comunes/
   │   ├── cabecera4.php
   │   └── footer.php
   ├── css/
   │   ├── login.css
   │   └── formulario.css
   ├── formularios/
   │   └── PanelControl.php
   ├── jquery/
   ├── vendor/
   ├── login.php
   ├── registro.php
   ├── validar2fa.php
   ├── GenerarSecreto.php
   ├── salir.php
   └── README.md
   ```

   ## Licencia

   Este proyecto está licenciado bajo la Licencia AFL-3.0.
