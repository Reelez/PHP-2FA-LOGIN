<?php
session_start();
require_once __DIR__ . '/clases/mysql.inc.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$usuario    = $_POST['usuario'] ?? '';
$contrasena = $_POST['contrasena'] ?? '';

// Validación básica
if (!$usuario || !$contrasena) {
    $_SESSION['error_login'] = "⚠️ Debe ingresar usuario y contraseña.";
    header('Location: login.php');
    exit;
}

$db = new mod_db();

// Buscar al usuario
$sql = "SELECT Usuario, HashMagic, secret_2fa FROM usuarios WHERE Usuario = " . $db->sql_quote($usuario);
$result = $db->consultar($sql);
$fila = $result ? $result->fetch(PDO::FETCH_ASSOC) : null;

if (!$fila || !password_verify($contrasena, $fila['HashMagic'])) {
    $_SESSION['error_login'] = "❌ Usuario o contraseña incorrectos.";
    header('Location: login.php');
    exit;
}

// Usuario y contraseña correctos
$_SESSION['usuario'] = $usuario;
$_SESSION['intentos_2fa'] = 0;        // Reinicia contador de intentos 2FA
$_SESSION['2fa_activo']   = false;    // Aún no ha sido validado el 2FA

// Redirigir a la validación 2FA (siempre, ya que asumimos que todos los usuarios usan 2FA)
header('Location: validar2fa.php');
exit;
?>
