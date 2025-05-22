<?php
require 'vendor/autoload.php';
include("clases/mysql.inc.php");
$db = new mod_db();

use Sonata\GoogleAuthenticator\GoogleAuthenticator;


// Procesar formulario
$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo = $_POST['codigo'];
     //$codigo = 763616;
    $g = new GoogleAuthenticator();

    if ($g->checkCode($secret, $codigo)) {
        $mensaje = "<p style='color: green;'>✅ Código válido. 2FA activado correctamente.</p>";
        // Aquí puedes guardar en sesión que está verificado
    } else {
        $mensaje = "<p style='color: red;'>❌ Código incorrecto. Intenta de nuevo.</p>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Activar 2FA</title>
</head>
<body>
    <h2>Autenticación de Dos Factores (2FA)</h2>

    <p>Escanea el siguiente código QR con Google Authenticator:</p>
    <!--<img src="data:image/png;base64,<?= $base64 ?>" />-->

    <p>O ingresa este código manualmente: <strong><?= $secret ?></strong></p>

    <form method="POST">
        <label for="codigo">Código de Google Authenticator:</label>
        <input type="text" name="codigo" required />
        <button type="submit">Verificar</button>
    </form>

    <?= $mensaje ?>
</body>
</html>