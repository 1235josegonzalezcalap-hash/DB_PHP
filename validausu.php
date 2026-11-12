<?php
session_start();
require("conecta.php");

$nombre = $_POST['nombre'];
$email = $_POST['email'];
$password = $_POST['password'];

$consulta = "SELECT * FROM usuarios WHERE email = '$email'";
$resultado = mysqli_query($conn, $consulta);

if (mysqli_num_rows($resultado) > 0) {
    $_SESSION['error'] = "El email ya esta registrado.";
    header("Location: registro.php");
    exit;
}


$sql = "INSERT INTO usuarios (nombre, email, password)
        VALUES ('$nombre', '$email', '$password')";

if(mysqli_query($conn, $sql)) {
    $_SESSION['mensaje'] = "Registro exitoso. Ahora puedes iniciar sesion.";
    header("Location: index.php");
}else{
    $_SESSION['error'] = "Error al registrar: " . mysqli_error($conn);
    header("Location: altalog.php");
}
mysqli_close($conn);
?>