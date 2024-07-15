<?php session_start();
$varsession = $_SESSION['id'];
if($varsession == null || $varsession== ''){
    echo "Usted no tiene autorizacion";
    die(); //termina la sesion para que 
}
include 'conexion.php';
$id_persona = $_SESSION['id'];
$registro = mysqli_query($conexion,"select * from personas where id = $id_persona");
$reg = mysqli_fetch_array($registro);
if(isset($_REQUEST['nombre'])){
    $nombre = $_REQUEST['nombre'];
    $apellidos = $_POST['apellidos'];
    $aumero = $_POST['aumero'];
    $direccion = $_POST['direccion'];
    $codigo_postal = $_POST['codigo_postal'];
    $area = $_POST['area'];
    $email = $_POST['email'];
    $estado_region = $_POST['estado_region'];

    mysqli_query($conexion,"update personas set nombre='$nombre',apellidos='$apellidos', aumero ='$aumero', direccion= '$direccion',  codigo_postal='$codigo_postal', area='$area', email='$email', estado_region='$estado_region' where id = '$id_persona'");
    echo "<script> alert('usuario actualizado'); </script>";
    echo "<script> window.location='profile.php'; </script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>perfil</title>
    <link href="css/prof.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">


</head>
<body>
    <div class="container rounded bg-white mt-5 mb-5">
        <div class="row">
            <div class="col-md-3 border-right">
                <div class="d-flex flex-column align-items-center text-center p-3 py-5"><img class="rounded-circle mt-5" width="150px" src="https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg"><span class="font-weight-bold"><?php echo $_SESSION['nombre']; ?></span><span class="text-black-50"><?php echo $_SESSION['email']; ?></span><span> </span></div>
            </div>
            <div class="col-md-5 border-right">
                <div class="p-3 py-5">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="text-right">Configuracion del perfil</h4>
                    </div>
                    <form action="" method="POST">
                    <div class="row mt-2">
                        <div class="col-md-6"><label class="labels">Nombre</label><input id="nombre" name="nombre" type="text" class="form-control" placeholder="Nombre" <?php { echo "value='".$reg['nombre']."' "; }?>></div>
                        <div class="col-md-6"><label class="labels">Apellidos</label><input id="apellidos" name="apellidos" type="text" class="form-control" <?php { echo "value='".$reg['apellidos']."' "; }?> placeholder="Apellidos"></div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12"><label class="labels">Numero</label><input id="aumero" name="aumero" type="text" class="form-control" placeholder="Ingresa tu Número telefónico" <?php echo "value='".(($reg['aumero'] == 5) ? '' : $reg['aumero'])."' "; ?>></div>
                        <div class="col-md-12"><label class="labels">Dirección</label><input id="direccion" name="direccion" type="text" class="form-control" placeholder="Ingresa tu dirección" <?php echo "value='".(($reg['direccion'] == "S/D") ? '' : $reg['direccion'])."' "; ?>></div>
                        <div class="col-md-12"><label class="labels">Código Postal</label><input id="codigo_postal" name="codigo_postal" type="text" class="form-control" placeholder="Ingresa tu código postal" <?php echo "value='".(($reg['codigo_postal'] == "S/D") ? '' : $reg['codigo_postal'])."' "; ?>></div>
                        <div class="col-md-12"><label class="labels">Area</label><input id="area" name="area" type="text" class="form-control" placeholder="Ingresa tu colonia" <?php echo "value='".(($reg['area'] == "S/D") ? '' : $reg['area'])."' "; ?>></div>
                        <div class="col-md-12"><label class="labels">Email</label><input id="email" name="email" type="text" class="form-control" placeholder="Ingresa tu Correo Electronico" <?php echo "value='".(($reg['email'] == "S/D") ? '' : $reg['email'])."' "; ?>></div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6"><label class="labels">Estado/Región</label><input id="estado_region" name="estado_region" type="text" class="form-control" <?php echo "value='".(($reg['estado_region'] == "S/D") ? '' : $reg['estado_region'])."' "; ?> placeholder="Estado"></div>
                    </div>
                    <div class="mt-5 text-center"><button class="btn btn-primary profile-button" type="submit">Save Profile</button></div>
                    </form>
                </div>
            </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
</body>
</html>