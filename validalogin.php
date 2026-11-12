<?php
session_start();
include 'conecta.php';

$email = $_POST['email'];
$password = $_POST['password'];

// Consulta para validar credenciales
$sql = "SELECT * FROM usuarios WHERE email = '$email' AND password = '$password'";
$resultado = mysqli_query($conn, $sql);

if (mysqli_num_rows($resultado) == 1) {
    $usuario = mysqli_fetch_assoc($resultado);

    $_SESSION['id_usuario'] = $usuario['id'];
    $_SESSION['nombre'] = $usuario['nombre'];
    $_SESSION['email'] = $usuario['email'];

    header("Location: menu.php");
} else {
    $_SESSION['mensaje'] = "Email o contraseña incorrectos.";
    header("Location: index.php");
}

mysqli_close($conn);
?>