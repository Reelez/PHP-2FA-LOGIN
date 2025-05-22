<?php
require 'vendor/autoload.php';
include("clases/mysql.inc.php");
use Sonata\GoogleAuthenticator\GoogleAuthenticator;

$db = new mod_db();

// Solo para pruebas: define manualmente un usuario
$usuario = 'usuario@ejemplo.com';

// Obtener el secreto desde la base de datos, usando la columna correcta "Usuario"
$sql = "SELECT secret_2fa FROM usuarios WHERE Usuario = " . $db->sql_quote($usuario) . " LIMIT 1";
$res = $db->consultar($sql);

if ($res) {
    $data = $db->fetch_array($res);
    if ($data && isset($data['secret_2fa'])) {
        $secret = $data['secret_2fa'];
    } else {
        die("No se encontró el usuario o no tiene 2FA configurado.");
    }
} else {
    die("Error en la consulta SQL.");
}

$mensaje = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['codigo_2fa'])) {
    $codigo_usuario = $_POST['codigo_2fa'];
    $g = new GoogleAuthenticator();

    if ($g->checkCode($secret, $codigo_usuario)) {
        $mensaje = "<div class='alert alert-success text-center'>✅ Código correcto. 2FA validado.</div>";
    } else {
        $mensaje = "<div class='alert alert-danger text-center'>❌ Código incorrecto. Intenta nuevamente.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Prueba 2FA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="secreto.css">
</head>
<body style="background-color: #fcf7d3;">
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card p-4 shadow rounded-4" style="width: 100%; max-width: 400px;">
            <h4 class="text-center mb-4">🔐 Prueba de verificación 2FA</h4>
            <?= $mensaje ?>
            <form method="POST">
                <div class="mb-3">
                    <label for="codigo_2fa" class="form-label">Código de Google Authenticator</label>
                    <input type="text" class="form-control rounded-pill" id="codigo_2fa" name="codigo_2fa" required>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-dark rounded-pill">Verificar</button>
                </div>
            </form>
            <p class="text-center mt-3 small">El usuario usado en esta prueba es:<br><strong><?= htmlspecialchars($usuario) ?></strong></p>
        </div>
    </div>
</body>
</html>

