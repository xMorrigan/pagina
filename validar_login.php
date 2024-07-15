<?php
include 'conexion.php';
session_start();
$_SESSION['email'] = $_POST['email'];
$_SESSION['password'] = $_POST['password'];

$consulta = "select * from personas where email='$_SESSION[email]' and password='$_SESSION[password]'";
$usuarios = mysqli_query($conexion, $consulta);

$filas = mysqli_num_rows($usuarios);

if ($filas > 0) {
    $datos = mysqli_fetch_array($usuarios);
    $_SESSION['id'] = $datos['id'];
    $_SESSION['nombre'] = $datos['nombre'];
    $_SESSION['apellidos'] = $datos['apellidos'];
    $_SESSION['aumero'] = $datos['aumero'];
    $_SESSION['direccion'] = $datos['direccion'];
    $_SESSION['codigo_postal'] = $datos['codigo_postal'];
    $_SESSION['area'] = $datos['area'];
    $_SESSION['email'] = $datos['email'];
    $_SESSION['password'] = $datos['password'];
    $_SESSION['estado_region'] = $datos['estado_region'];

    if ($filas) {
        $_SESSION['login_message'] = "Inicio de sesión exitoso";
        header("location: index.php");
    } else {
        echo "Error en la consulta: " . mysqli_error($conexion);
    }
} else {
    mysqli_close($conexion);
    echo "<script>alert('Usuario no válido');</script>";
    echo "<script>window.location ='login.php';</script>";
    session_destroy();
}

mysqli_free_result($usuarios);
?>