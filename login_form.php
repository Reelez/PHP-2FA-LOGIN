<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="css/login.css" type="text/css" />
    <script src="jquery/jquery-latest.js"></script>
    <script src="jquery/jquery.validate.js"></script>
    <script>
    $(document).ready(function(){
        $("#deteccionUser").validate({
            rules: {
                usuario: "required",
                contrasena: "required"
            }
        });
    });
    </script>
</head>
<body>
    <div class="login-card">
        <h2>Iniciar sesión</h2>
        <form id="deteccionUser" method="POST" action="procesar_login.php">
            <div class="form-group">
                <label for="usuario">Usuario:</label>
                <input id="usuario" name="usuario" type="text" required />
            </div>

            <div class="form-group">
                <label for="contrasena">Contraseña:</label>
                <input id="contrasena" name="contrasena" type="password" required />
            </div>

            <input type="submit" value="Ingresar" class="btn-primary" />
        </form>

        <a href="registro.php" class="btn-secondary">Registrarse</a>
    </div>
</body>
</html>
