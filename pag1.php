<?php
$mostrarFormulario = true;
$tipo    = "success";
$mensaje = "";

// Comprobar si se han enviado los datos por POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $mostrarFormulario = false;
    
    require("conecta.php");

    // Recibir y limpiar datos
    $dni       = trim($_POST["dni"] ?? "");
    $nombre    = trim($_POST["nombre"] ?? "");
    $apellido1 = trim($_POST["apellido1"] ?? "");
    $apellido2 = trim($_POST["apellido2"] ?? "");

    // Validación en el servidor
    if ($dni === "" || $nombre === "" || $apellido1 === "") {
        $tipo    = "danger";
        $mensaje = "DNI, nombre y primer apellido son obligatorios.";
    } elseif (mb_strlen($dni) > 9 || mb_strlen($nombre) > 50
           || mb_strlen($apellido1) > 50 || mb_strlen($apellido2) > 50) {
        $tipo    = "danger";
        $mensaje = "Alguno de los datos supera la longitud permitida.";
    } else {
        // Hacer que mysqli lance excepciones en caso de error
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        try {
            $conn = new mysqli($servername, $username, $password, $dbname);
            $conn->set_charset("utf8mb4");

            // Consulta preparada: evita inyección SQL
            $sql  = "INSERT INTO profesor (dni, nombre, apellido1, apellido2) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            // apellido2 vacío se guarda como NULL
            $apellido2 = ($apellido2 === "") ? null : $apellido2;
            $stmt->bind_param("ssss", $dni, $nombre, $apellido1, $apellido2);
            $stmt->execute();

            $mensaje = "El profesor " . htmlspecialchars($nombre . " " . $apellido1) . " se guardó correctamente.";

            $stmt->close();
            $conn->close();
        } catch (mysqli_sql_exception $e) {
            $tipo = "danger";
            if ($e->getCode() === 1062) {
                $mensaje = "Ya existe un profesor con el DNI " . htmlspecialchars($dni) . ".";
            } else {
                $mensaje = "No se pudo guardar el profesor. Revisa la conexión con la base de datos.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $mostrarFormulario ? "Registrar profesor" : "Resultado"; ?></title>
  <link href="estilos.css" rel="stylesheet">
</head>
<body>
  <main>
    <div>
      <div>
        <div>
          <div>
            
            <?php if ($mostrarFormulario): ?>
              <!-- VISTA DEL FORMULARIO -->
              <h1>Registrar profesor.</h1>
              <p>Completa los datos para guardarlo en el colegio.</p>

              <form action="" method="post">
                <div>
                  <label for="dni">DNI.</label>
                  <input type="text" id="dni" name="dni" maxlength="9" required placeholder="12345678A">
                </div>

                <div>
                  <label for="nombre">Nombre.</label>
                  <input type="text" id="nombre" name="nombre" maxlength="50" required>
                </div>

                <div>
                  <div>
                    <label for="apellido1">Primer apellido.</label>
                    <input type="text" id="apellido1" name="apellido1" maxlength="50" required>
                  </div>
                  <div>
                    <label for="apellido2">Segundo apellido.</label>
                    <input type="text" id="apellido2" name="apellido2" maxlength="50">
                  </div>
                </div>

                <div>
                  <button type="submit">Guardar profesor.</button>
                  <button type="reset">Limpiar.</button>
                  <a href="consulta.php">
                    <button type="button">Ver profesores ingresados</button>
                  </a>
                </div>
                <button> <a href="menu.php">Regresar al menu</a></button>
              </form>

            <?php else: ?>
              <!-- VISTA DE RESULTADO -->
              <div class="alerta-<?php echo $tipo; ?>" role="alert">
                <?php echo $mensaje; ?>
              </div>
              <a href="">Registrar otro profesor.</a>
            <?php endif; ?>

          </div>
        </div>
      </div>
    </div>
  </main>
</body>
</html>