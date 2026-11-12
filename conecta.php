<?php
$servername = "localhost";
$username = "root";
$password = "123456789";
$dbname = "colegio";

// Crea la conexion
$conn = mysqli_connect($servername, $username, $password, $dbname);

// revisa la conexion
if (!$conn) {
  die("Coneccion fallida: " . mysqli_connect_error());
}
// echo "Coneccion exitosa con colegio";
?>