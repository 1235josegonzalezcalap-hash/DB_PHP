<?php
require("conecta.php");

$mensaje = "";

// Manejar la eliminación si se envió el formulario por POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["dni"])) {
    $dni = $_POST["dni"];
    $sql = "DELETE FROM profesor WHERE dni= '$dni'";

    if (mysqli_query($conn, $sql)) {
        $mensaje = "Profesor eliminado con exito";
    } else {
        
        $mensaje = "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

// Obtener la lista actualizada de profesores disponibles desde la base de datos
$query_profesores = "SELECT dni FROM profesor";
$resultado_profesores = mysqli_query($conn, $query_profesores);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Eliminar Profesor</title>
</head>
<body>
    <p>Elimina a un profesor</p>

    <?php if (!empty($mensaje)) { echo "<p><strong>$mensaje</strong></p>"; } ?>

    <form action="" method="post">
        <label for="dni">Elige el dni del profesor a eliminar</label>
        <select name="dni" id="dni">
            <?php
            if ($resultado_profesores && mysqli_num_rows($resultado_profesores) > 0) {
                while ($fila = mysqli_fetch_assoc($resultado_profesores)) {
                    $dni_prof = htmlspecialchars($fila['dni']);
                    echo "<option value=\"$dni_prof\">$dni_prof</option>";
                }
            } else {
                echo "<option value=\"\">No hay profesores disponibles</option>";
            }
            ?>
        </select>
        <button type="submit" class="btn btn-primary">Consultar</button>
        <button> <a href="menu.php">Regresar al menu</a></button>
    </form>
</body>
</html>
<?php
mysqli_close($conn);
?>