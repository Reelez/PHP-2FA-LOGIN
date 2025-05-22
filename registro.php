<?php
require 'vendor/autoload.php';
include("clases/mysql.inc.php");

session_start();
$db = new mod_db();

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre     = $_POST['nombre'] ?? '';
    $usuario    = $_POST['usuario'] ?? '';
    $apellido   = $_POST['apellido'] ?? '';
    $correo     = $_POST['correo'] ?? '';
    $sexo       = isset($_POST['sexo']) ? (int)$_POST['sexo'] : 0; // 0 = hombre, 1 = mujer
    $contrasena = $_POST['contrasena'] ?? '';

    // Validación mínima
    if ($nombre && $usuario && $correo && $contrasena) {
        $hash = password_hash($contrasena, PASSWORD_BCRYPT);

        // Preparar valores para insertar
        $sql = "INSERT INTO usuarios (Nombre, Usuario, Apellido, correo, Sexo, HashMagic) VALUES (
            " . $db->sql_quote($nombre) . ",
            " . $db->sql_quote($usuario) . ",
            " . $db->sql_quote($apellido) . ",
            " . $db->sql_quote($correo) . ",
            $sexo,
            " . $db->sql_quote($hash) . "
        )";

        if ($db->consultar($sql)) {
            // Guardar nombre de usuario en sesión para 2FA
            $_SESSION['usuario'] = $usuario;

            // Opcional: guardar id si lo quieres usar más adelante
            $usuario_id = $db->getConexion()->lastInsertId();
            $_SESSION['usuario_id'] = $usuario_id;

            header("Location: GenerarSecreto.php");
            exit;

        } else {
            $mensaje = "<div class='alert alert-danger'>❌ Error al registrar el usuario.</div>";
        }
    } else {
        $mensaje = "<div class='alert alert-warning'>⚠️ Todos los campos son obligatorios.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Registro de Usuario</title>
   
    <link rel="stylesheet" href="css/formulario.css" />
</head>
<body class="bg-light">

<div class="container mt-5" style="max-width: 500px;">
    <div class="card">
    <h3>📝 Formulario de Registro</h3>
    
    <!-- Mensaje dinámico -->
    <?= $mensaje ?? '' ?>

    <form method="POST" action="">
    <label for="usuario">Usuario</label>
    <input type="text" id="usuario" name="usuario" required />

    <label for="nombre">Nombre</label>
    <input type="text" id="nombre" name="nombre" required />

    <label for="apellido">Apellido</label>
    <input type="text" id="apellido" name="apellido" required />

    <label for="correo">Correo</label>
    <input type="email" id="correo" name="correo" required />

    <label for="sexo">Sexo</label>
    <select id="sexo" name="sexo" required>
        <option value="0">Masculino</option>
        <option value="1">Femenino</option>
    </select>

    <label for="contrasena">Contraseña</label>
    <input type="password" id="contrasena" name="contrasena" required />

    <button type="submit" class="btn-primary">Registrarse</button>

</form>

</div>
</div>

</body>
</html>
