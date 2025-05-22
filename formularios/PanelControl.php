<?php
session_start();

// Verificar que el usuario ha iniciado sesión y pasó el 2FA
if (!isset($_SESSION['usuario']) || !isset($_SESSION['autenticado_2fa']) || $_SESSION['autenticado_2fa'] !== true) {
    header("Location: login.php");
    exit;
}

$Usuario = $_SESSION['usuario']; // Ya está disponible tras autenticación
$menu08 = " id=\"current\"";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="Description" content="Sistema Central.">
    <meta name="Keywords" content="your, keywords">
    <meta name="Distribution" content="Global">
    <meta name="Author" content="fulano de tal - fulano@gmail.com">
    <meta name="Robots" content="index,follow">
    <link rel="stylesheet" href="../Estilos/Techmania.css" type="text/css">
    <link rel="shortcut icon" href="iconos/gnome.ico">

    <!-- CSS adicionales -->
    <link rel="stylesheet" href="../css/mainModificado.css">
    <link rel="stylesheet" href="../css/shortcodes.css">
    <link rel="stylesheet" type="text/css" href="../css/settings.css" media="screen">
    <link rel="stylesheet" href="../css/color-scheme/turquoise.css">

    <title>Panel de Control</title>
</head>
<body>

<!-- Contenedor principal -->
<div id="wrap">
<?php include("../comunes/cabecera4.php"); ?>
<div id="content-wrap">
    <div id="main">
        <h1>USUARIO: <?php echo strtoupper($Usuario); ?></h1>

        <?php if (isset($_GET['id_mess'])): ?>
            <p><code><font color="#FF0000"><?php echo Mensajes($_GET['id_mess']); ?></font></code></p>
        <?php endif; ?>

        <p><code>
        <?php
            $dia = date("j");
            $mes = date("n");
            $AnioActual = date("Y");

            if ($mes == 12) {
                echo "Dios me los bendiga abundantemente, bendiga su camino, y pasen una Feliz Navidad y un Próspero Año Nuevo, unidos a su amada familia.<br>";
            } else {
                echo "Bendiciones en este día. ";
            }

            echo "<br>";
            $arrayMes1 = [
                1 => "Enero", 2 => "Febrero", 3 => "Marzo", 4 => "Abril", 5 => "Mayo", 6 => "Junio",
                7 => "Julio", 8 => "Agosto", 9 => "Septiembre", 10 => "Octubre", 11 => "Noviembre", 12 => "Diciembre"
            ];

            echo "El día de hoy es $dia de " . $arrayMes1[$mes] . " de $AnioActual.<br>";
        ?>
        </code></p>

        <?php include("../formularios/TableroMenu.php"); ?>
        <br><br><br>
    </div>
</div>
<?php include("../comunes/footer.php"); ?>
</div>
</body>
</html>
