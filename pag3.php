<?php
// Incluimos la conexión a la base de datos (asegúrate de que conecta.php esté en la misma ruta)
require("conecta.php");

// Inicializamos variables para mensajes y datos del profesor
$resultado = "";
$profesor_data = null;

// 1. Si se envió el formulario para ACTUALIZAR los datos
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["accion"]) && $_POST["accion"] == "actualizar") {
    $dni = $_POST["dni"];
    $nombre = $_POST["nombre"];
    $apellido1 = $_POST["apellido1"];
    $apellido2 = $_POST["apellido2"];

    // Sentencia SQL para actualizar los datos
    $sql_update = "UPDATE profesor SET nombre='$nombre', apellido1='$apellido1', apellido2='$apellido2' WHERE dni='$dni'";
    
    if ($conn->query($sql_update) === TRUE) {
        $resultado = "<p style='color: green;'>¡Los datos del profesor se han actualizado correctamente!</p>";
    } else {
        $resultado = "<p style='color: red;'>Error al actualizar el registro: " . $conn->error . "</p>";
    }
}

// 2. Si se seleccionó un DNI para consultar/editar (y no viene de actualizar)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["dni"]) && (!isset($_POST["accion"]) || $_POST["accion"] != "actualizar")) {
    $dni = $_POST["dni"];
    $sql_consulta = "SELECT * FROM profesor WHERE dni='$dni'";
    $result_consulta = $conn->query($sql_consulta);

    if ($result_consulta && $result_consulta->num_rows > 0) {
        // Obtenemos los datos para rellenar el formulario de edición
        $profesor_data = $result_consulta->fetch_assoc();
    } else {
        $resultado = "<p style='color: red;'>No se encontraron resultados para el DNI seleccionado.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Consultar y Actualizar Profesor</title>
</head>
<body>
    <h1>Gestión de Profesores</h1>

    <!-- Mostramos mensajes de éxito o error -->
    <?php echo $resultado; ?>
    
    <!-- Formulario para seleccionar el DNI del profesor -->
    <form action="" method="post" style="margin-bottom: 20px;">
        <label for="dni">Elige el DNI del profesor a consultar/actualizar:</label>
        <select name="dni" id="dni">
            <option value="">-- Selecciona un profesor --</option>
            <?php
            // Consultamos todos los profesores para llenar el select automáticamente
            $sql_select = "SELECT dni, nombre, apellido1 FROM profesor";
            $resultado_select = $conn->query($sql_select);

            if ($resultado_select && $resultado_select->num_rows > 0) {
                while ($profesor = $resultado_select->fetch_assoc()) {
                    // Mantenemos seleccionado el DNI si ya fue enviado previamente
                    $selected = (isset($_POST["dni"]) && $_POST["dni"] == $profesor["dni"]) ? "selected" : "";
                    
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

    <!-- 3. Formulario de Actualización (Se muestra solo si se ha encontrado al profesor) -->
    <?php if ($profesor_data): ?>
    <form action="" method="post">
        <h3>Editar Profesor</h3>
        
        <!-- DNI oculto para identificar el registro a modificar -->
        <input type="hidden" name="dni" value="<?php echo $profesor_data['dni']; ?>">
        <!-- Indicador de que esta petición es para actualizar -->
        <input type="hidden" name="accion" value="actualizar">

        <p><strong>DNI:</strong> <?php echo $profesor_data['dni']; ?></p>

            <label for="nombre">Nombre:</label><br>
            <input type="text" id="nombre" name="nombre" value="<?php echo $profesor_data['nombre']; ?>" required><br>
            <label for="apellido1">Primer Apellido:</label><br>
            <input type="text" id="apellido1" name="apellido1" value="<?php echo $profesor_data['apellido1']; ?>" required><br>
            <label for="apellido2">Segundo Apellido:</label><br>
            <input type="text" id="apellido2" name="apellido2" value="<?php echo $profesor_data['apellido2']; ?>"><br>
    

        <button type="submit" class="btn btn-success">Guardar Cambios</button>
    </form>
    <?php endif; ?>

    <?php 
    // Cerramos la conexión al terminar de usar la base de datos
    $conn->close();
    ?>
</body>
</html>