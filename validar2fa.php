<?php
session_start();
require_once 'vendor/autoload.php';
require_once __DIR__ . '/clases/mysql.inc.php';

use Sonata\GoogleAuthenticator\GoogleAuthenticator;

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

$usuario = $_SESSION['usuario'];
$mensaje = '';
$ga = new GoogleAuthenticator();

if (!isset($_SESSION['intentos_2fa'])) {
    $_SESSION['intentos_2fa'] = 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo = $_POST['codigo'] ?? '';

    if (!preg_match('/^\d{6}$/', $codigo)) {
        $mensaje = "<div class='alert alert-danger mt-3'>Código inválido. Debe contener 6 dígitos.</div>";
    } else {
        $db = new mod_db();
        $sql = "SELECT secret_2fa FROM usuarios WHERE usuario = " . $db->sql_quote($usuario);
        $result = $db->consultar($sql);
        $fila = $result ? $result->fetch(PDO::FETCH_ASSOC) : null;

        if (!$fila || empty($fila['secret_2fa'])) {
            die("No se encontró el código secreto 2FA para el usuario.");
        }

        $secret = $fila['secret_2fa'];

        if ($ga->checkCode($secret, $codigo)) {
            // ✅ Código correcto
            unset($_SESSION['intentos_2fa']);
            $_SESSION['autenticado_2fa'] = true;

            header('Location:formularios/PanelControl.php');
            exit;
        } else {
            $_SESSION['intentos_2fa']++;

            if ($_SESSION['intentos_2fa'] >= 3) {
                unset($_SESSION['usuario']);
                unset($_SESSION['intentos_2fa']);
                unset($_SESSION['autenticado_2fa']);

                header("Location: GenerarSecreto.php?reinicio=1");
                exit;
            } else {
                $mensaje = "<div class='alert alert-warning mt-3'>Código incorrecto. Intento {$_SESSION['intentos_2fa']} de 3.</div>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Validar Código 2FA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center" style="height: 100vh;">

<div class="card p-4 shadow rounded-4" style="width: 360px;">
    <h3 class="mb-3 text-center">Validar Código 2FA</h3>

    <form method="POST" autocomplete="off">
        <div class="mb-3">
            <label for="codigo" class="form-label">Código generado por Google Authenticator:</label>
            <input type="text" class="form-control rounded-pill" id="codigo" name="codigo" required autofocus pattern="\d{6}" title="Ingresa un código de 6 dígitos" />
        </div>
        <button type="submit" class="btn btn-success w-100 rounded-pill">Validar</button>
    </form>

    <?= $mensaje ?>
</div>

</body>
</html>
