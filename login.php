<?php
session_start();
require_once 'vendor/autoload.php';
require_once 'clases/mysql.inc.php';

$db = new mod_db();
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $contrasena = $_POST['contrasena'] ?? '';

    if ($usuario && $contrasena) {
        $sql = "SELECT * FROM usuarios WHERE usuario = " . $db->sql_quote($usuario);
        $result = $db->consultar($sql);
        $fila = $result ? $result->fetch(PDO::FETCH_ASSOC) : null;

        if ($fila && password_verify($contrasena, $fila['hashMagic'])) {
            $_SESSION['usuario'] = $usuario;
            header("Location: validar2fa.php");
            exit;
        } else {
            $mensaje = "<div class='alert alert-danger'>❌ Usuario o contraseña incorrectos.</div>";
        }
    } else {
        $mensaje = "<div class='alert alert-warning'>⚠️ Ingresa todos los campos.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="login-card">
        <h2>🔐 Iniciar sesión</h2>
        <?= $mensaje ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="usuario">Usuario:</label>
                <input type="text" name="usuario" id="usuario" required />
            </div>

            <div class="form-group">
                <label for="contrasena">Contraseña:</label>
                <input type="password" name="contrasena" id="contrasena" required />
            </div>

            <input type="submit" value="Ingresar" class="btn-primary" />
        </form>

        <a href="registro.php" class="btn-secondary">Registrarse</a>
    </div>
</body>
</html>
