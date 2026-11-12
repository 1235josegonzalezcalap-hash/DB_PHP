<?php
// Incluimos la conexión a la base de datos (asegúrate de que conecta.php esté en la misma ruta)
require("conecta.php");

// Inicializamos una variable para los resultados o mensajes
$resultado_html = "";

// Verificamos si se envió el formulario para hacer la consulta del DNI seleccionado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["dni"])) {
    $dni = $_POST["dni"];
    // Usamos el DNI seleccionado para buscar al profesor
    $sql_consulta = "SELECT * FROM profesor WHERE dni='$dni'";
    $result_consulta = $conn->query($sql_consulta);

    if ($result_consulta && $result_consulta->num_rows > 0) {
        while($row = $result_consulta->fetch_assoc()) {
            $resultado_html .= "dni: " . $row["dni"] . " - Nombre: " . $row["nombre"] . " " . $row["apellido1"] . " " . $row["apellido2"] . "<br>";
        }
    } else {
        $resultado_html = "No se encontraron resultados para el DNI seleccionado.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Consulta a Profesor</title>
</head>
<body>
    <h1>Consulta a Profesor</h1>
    
    <!-- El formulario se apunta a sí mismo -->
    <form action="" method="post">
        <label for="dni">Elige el dni del profesor a consultar</label>
        <select name="dni" id="dni">
            <option value="">-- Selecciona un profesor --</option>
            <?php
            // Consultamos todos los profesores para llenar el select automáticamente
            $sql_select = "SELECT dni, nombre, apellido1 FROM profesor";
            $resultado_select = $conn->query($sql_select);

            if ($resultado_select && $resultado_select->num_rows > 0) {
                while ($profesor = $resultado_select->fetch_assoc()) {
                    // Verificamos si este DNI fue el que se envió para mantenerlo seleccionado
                    $selected = (isset($_POST["dni"]) && $_POST["dni"] == $profesor["dni"]) ? "selected" : "";
                    
                    // Mostramos el DNI como valor y el nombre completo como texto visible
                    echo '<option value="' . $profesor["dni"] . '" ' . $selected . '>' 
                         . $profesor["dni"] . ' - ' . $profesor["nombre"] . ' ' . $profesor["apellido1"] 
                         . '</option>';
                }
            }
            ?>
        </select>
        <button type="submit" class="btn btn-primary">Consultar</button>
        <button> <a href="menu.php">Regresar al menu</a></button>
    </form>

    <div style="margin-top: 20px;">
        <?php 
        // Mostramos el resultado de la consulta si existe
        if (!empty($resultado_html)) {
            echo $resultado_html; 
        }
        // Cerramos la conexión al terminar de usar la base de datos
        $conn->close();
        ?>
    </div>
</body>
</html>