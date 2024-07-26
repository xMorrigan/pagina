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
    $_SESSION['rol'] = $datos['rol'];

    $_SESSION['login_message'] = "Inicio de sesión exitoso";

    if ($datos['rol'] == 'admin') {
        header("Location: dashboard.php");
    } else if ($datos['rol'] == 'user') {
        header("Location: index.php");
    } else {
        echo "No tiene permisos";
    }
} else {
    mysqli_close($conexion);
    echo "<script>alert('Usuario no válido');</script>";
    echo "<script>window.location ='login.php';</script>";
    session_destroy();
}

mysqli_free_result($usuarios);
