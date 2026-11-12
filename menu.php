<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestion de datos</title>
</head>
<body>
    <h2>Elije que accion quieres realizar</h2>
        <button><a href="pag1.php">Registrar</a></button>
        <button><a href="pag2.php">Consultar</a></button>
        <button><a href="pag3.php">Actualizar</a></button>
        <button><a href="pag4.php">Eliminar</a></button>
        <button> <a href="index.php"> Cerrar sesion</a></button>
</body>
</html>