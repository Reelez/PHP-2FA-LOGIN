<?PHP
session_start();  
include("clases/mysql.inc.php");	
$db = new mod_db();

include("clases/SanitizarEntrada.php");
include("comunes/loginfunciones.php");
include("clases/objLoginAdmin.php");

$tolog = false;

if (isset($_POST["tolog"]))
  $tolog = $_POST["tolog"];

if (isset($tolog) && ($tolog == "true") && ($_SERVER['REQUEST_METHOD'] === 'POST')) {
    $Usuario = $_POST['usuario'];
    $ClaveKey = $_POST['contrasena'];
    $ipRemoto = $_SERVER['REMOTE_ADDR'];

    $Logearme = new ValidacionLogin($Usuario, $ClaveKey, $ipRemoto, $db);

    if ($Logearme->logger()) {
        $Logearme->autenticar();

        if ($Logearme->getIntentoLogin()) {
            $_SESSION['Usuario'] = $Logearme->getUsuario();
            $_SESSION['autenticado'] = "PENDIENTE"; // 🚩 Aún no validamos 2FA

            // 🔍 Verificamos si el usuario tiene 2FA habilitado
            $usuarioLimpio = $db->sql_quote($Usuario);
            $sql = "SELECT secret_2fa FROM usuarios WHERE correo = $usuarioLimpio LIMIT 1";
            $resultado = $db->consultar($sql);
            $datos = $db->fetch_array($resultado);

            if ($datos && !empty($datos['secret_2fa'])) {
                // 👁‍🗨 Si tiene 2FA, redirigimos a verificación
                $Logearme->registrarIntentos();
                $tolog = false;
                redireccionar("verificar_2fa.php");
            } else {
                // ✅ Si NO tiene 2FA, login normal
                $_SESSION['autenticado'] = "SI";
                $Logearme->registrarIntentos();
                $tolog = false;
                redireccionar("formularios/PanelControl.php");
            }

        } else {
            $Logearme->registrarIntentos();
            $_SESSION["emsg"] = 1;
            redireccionar("login.php");		
        }

    } else {
        $_SESSION["emsg"] = 1;
        redireccionar("login.php");
    }

} else {
    redireccionar("login.php");
}
?>
