<?php
session_start();
require_once 'vendor/autoload.php';
require_once __DIR__ . '/clases/mysql.inc.php';

use Sonata\GoogleAuthenticator\GoogleQrUrl;
use Sonata\GoogleAuthenticator\GoogleAuthenticator;

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

$usuario = $_SESSION['usuario'];
$mensaje = '';

if (isset($_GET['reinicio']) && $_GET['reinicio'] == 1) {
    $mensaje = "Has superado el límite de intentos. Por seguridad, debes generar un nuevo código 2FA.";
}

$ga = new GoogleAuthenticator();
$db = new mod_db();

$sql = "SELECT secret_2fa FROM usuarios WHERE usuario = " . $db->sql_quote($usuario);
$result = $db->consultar($sql);
$fila = $result ? $result->fetch(PDO::FETCH_ASSOC) : null;

if (!$fila || empty($fila['secret_2fa']) || (isset($_GET['reinicio']) && $_GET['reinicio'] == 1)) {
    $secret = $ga->generateSecret();
    $data = ['secret_2fa' => $secret];
    $where = "usuario = " . $db->sql_quote($usuario);

    if (!$db->updateSeguro('usuarios', $data, $where)) {
        die("Error al guardar el secreto 2FA.");
    }
} else {
    $secret = $fila['secret_2fa'];
}

$nombre_app = 'MiAppEjemplo';
$qr_url = GoogleQrUrl::generate($usuario, $secret, $nombre_app);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Generar Código 2FA</title>
    <link rel="stylesheet" href="css/secreto.css" />
</head>
<body>
    <div class="card">
        <h3>Configurar Autenticación de Dos Factores (2FA)</h3>

        <?php if ($mensaje): ?>
            <p style="color:red;"><?php echo htmlspecialchars($mensaje); ?></p>
        <?php endif; ?>

        <p>Escanea este código QR con tu aplicación Google Authenticator:</p>
        <img src="<?php echo htmlspecialchars($qr_url); ?>" alt="Código QR 2FA" />

        <p>O ingresa manualmente este código en tu app:</p>
        <p class="fw-bold"><?php echo htmlspecialchars($secret); ?></p>

        <form action="validar_2fa_activacion.php" method="post">
            <label for="codigo">Introduce el código que te genera la app:</label>
            <input type="text" id="codigo" name="codigo" required autofocus pattern="\d{6}" autocomplete="off" title="Ingresa un código de 6 dígitos" />
            <button type="submit" class="btn-primary">Validar Código</button>
        </form>
    </div>
</body>
</html>
